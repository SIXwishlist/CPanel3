<?
/*
 * 
 */

/**
 * Description of DataBase
 *
 * @author Ahmad
 */
class DataBase extends CustomException {

    // JDBC driver name and database URL

    private $connection;
    private $last_affected_rows;

    private static $instance = null;


    public static function get_instance() {

        if (self::$instance == null) {
            self::$instance = new DataBase();
        }

        return self::$instance;
    }

    public function connect($config, $prefix) {

        $result = 0;

        try {

            //  Database credentials
            $host    = $config[$prefix.".host"];
            $db_name = $config[$prefix.".database"];
            
            $user    = $config[$prefix.".user"];
            $pass    = $config[$prefix.".password"];
            
            //Open a connection
            //$this->connection = mysqli_connect($host, $user, $pass, $db_name);
            $this->connection = new mysqli($host, $user, $pass, $db_name);

            if($this->connection == false){
                throw new CustomException('Cannot connect to mysql server, or database not exist');
            }

            if (mysqli_connect_errno()) {
                throw new CustomException("Cannot connect to mysql server: ".mysqli_connect_error()."\n");
            }

            if ($this->connection->connect_errno) {
                throw new CustomException("Failed to connect to MySQL: (" . $this->connection->connect_errno . ") " . $this->connection->connect_error);
            }

            $result = 1;
            
        } catch (Exception $e) {
            throw new CustomException( 'Error in : creating connection: \n' .  $e->getMessage() . "\n" );
        }

        return $result;
    }

        
    public function select_query($sql, $parameters=null){

        $resultArray = array();
       
        try {

            $this->connection->query("set names 'utf8'");

            if( count($parameters) <= 0 ){

                $result = $this->connection->query($sql);
       
                if( $result ){
                    while ( $row = $result->fetch_assoc() ){
                        $resultArray[] = $row;
                    }
                } else {
                    throw new CustomException( 'no result, sql error', $e );//from php 5.3 no need to custum
                }

                $result->free_result();

                $result->close();

            }else{

                if(  !( $stmt = $this->connection->prepare($sql) )  ){
                    throw new CustomException("Prepare failed: (" . $this->connection->errno . ") " . $this->connection->error);
                }

                $parameters = self::prepend_params($parameters);

                if( ! ( call_user_func_array(array($stmt, "bind_param"), self::get_ref_array($parameters) ) ) ){
                    throw new CustomException("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
                }

                if ( !$stmt->execute() ) {
                    throw new CustomException("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
                }

                if ( !$stmt->store_result() ) {
                    throw new CustomException("Store result failed: (" . $stmt->errno . ") " . $stmt->error);
                }

                $row     = array() ; // Array that accepts the data.
                $results = array() ; // Parameter array passed to 'bind_result()' 

                $metadata = $stmt->result_metadata();

                while ($field = $metadata->fetch_field()) {
                    $col       = $field->name;
                    $results[] = & $row[$col]; 
                }

                if( ! ( call_user_func_array(array($stmt, "bind_result"), $results) ) ){
                    throw new CustomException("Binding results failed: (" . $stmt->errno . ") " . $stmt->error);
                }

                $index = 0;
                while ( $stmt->fetch() ) {
                    foreach ($row as $key => $val) {
                        $resultArray[$index][$key] = $val;
                    }
                    $index++;
                }

                $stmt->free_result();

                $stmt->close();
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : mysqli select query', $e );//from php 5.3 no need to custum
        }
        
        return $resultArray;
    }

    public function update_query($sql, $parameters=null){

        $result = 0;

        try {

            $this->connection->query("set names 'utf8'");

            if( count($parameters) <= 0 ){

                $result = $this->connection->query($sql);

            }else{

                $stmt = $this->connection->prepare($sql);

                $parameters = self::prepend_params($parameters);

                if( ! ( call_user_func_array(array($stmt, "bind_param"), self::get_ref_array($parameters) ) ) ){
                    throw new CustomException("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
                }

                if ( !$stmt->execute() ) {
                    throw new CustomException( "Execute failed: (" . $stmt->errno . ") " . $stmt->error);
                }
                
                $this->last_affected_rows = $this->connection->affected_rows;
                
                $stmt->free_result();

                $stmt->close();

            }

            $result = 1;

        } catch (Exception $e) {
            throw new CustomException( 'Error in : mysqli update query', $e );//from php 5.3 no need to custum
        }

        return $result;
    }

    public function select_multi_query($sql, $parameters=null){

        $resultArray = array();
       
        try {

            $resource = ConnectionResource::get_instance()->pick_resource();
            $resource = json_decode( RESOURCE );

            $mysqli   = mysqli_connect($resource->host, $resource->user, $resource->password, $resource->database);

            /* check connection */
            if (mysqli_connect_errno()) {
                printf("Connect failed: %s\n", mysqli_connect_error());
            }

            $mysqli->query("set names 'utf8'");
            
            /* execute multi query */
            
            if ($mysqli->multi_query($sql)) {
                do {
                    /* store first result set */
                    if ( $result = $mysqli->use_result() ) {

                        $array = array();

                        while ($row = $result->fetch_assoc()) {
                            //print_r( $row );
                            $array[] = $row;
                        }

                        //if( count($array) > 0 ){
                        //}
                        $resultArray[] = $array;

                        $result->free_result();
                    }
                    /* print divider */
                    if ($mysqli->more_results()) {
                        //printf("-----------------\n");
                    }
                } while ($mysqli->next_result());
            }

            /* close connection */
            $mysqli->close();

        } catch (Exception $e) {
            throw new CustomException( 'Error in : mysqli multi query', $e );//from php 5.3 no need to custum
        }
        
        return $resultArray;
    }
   
    public function get_last_insert_id(){
        return $this->connection->insert_id;
    }
   
    public function get_affected_rows(){
        return $this->last_affected_rows;
    }


    #takes in array of parameters and add type in front of array
    private static function prepend_params($params) {

        //$types = str_repeat("s", count($params));
        $types = "";
        for ($i=0; $i<count($params); $i++) {
            if ( is_numeric( $params[$i] ) ) {
                if ( is_integer( $params[$i] ) ) {
                    $types.="i";
                } else if ( is_float( $params[$i] ) || is_double( $params[$i] ) ) {
                    $types.="d";
                } else {
                    $types.="i";
                }
            } else {
                $types.="s";
            }
        }
        
        array_unshift($params, $types);

        //$params   = array_reverse($params);
        //$params[] = $types;
        //$params   = array_reverse($params);

        return $params;
    }

    private static function get_ref_array($arr) {

        if (strnatcmp(phpversion(),'5.3')>=0) {

            $ret = array();

            foreach($arr as $key => $val) {
                $ret[$key] = &$arr[$key];
            }

            return $ret;
        }

        return $arr;
    } 

    
    public function close(){

        $success = false;

        try {

           $success = $this->connection->close();
           
           if($success == false){
             throw new CustomException('Cannot close mysqli');
           }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : close connection: \n' .  $e->getMessage() . "\n" );
        }

        return $success;
    }

}
