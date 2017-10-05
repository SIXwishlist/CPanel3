<?
/*
 * 
 */

/**
 * Description of MySQLiDataBase
 *
 * @author Ahmad
 */
class MySQLiDataBase extends CustomException {

    private $connection;
    private $last_affected_rows;

    private static $instance = null;

    public static function get_instance() {

        if (self::$instance == null) {
            self::$instance = new PdoDataBase();
        }

        return self::$instance;
    }


    public function connect($config, $prefix, $persistent=false) {

        $result = false;

        try {

            //  Database credentials
            $host   = $config[$prefix.".host"];
            $dbname = $config[$prefix.".database"];
            
            $user   = $config[$prefix.".user"];
            $pass   = $config[$prefix.".password"];
            
            //$port   = $config[$prefix.".port"];
            //$socket = $config[$prefix.".socket"];
            //'unix_socket' => '/tmp/mysql.sock’
            
            $dsn = "mysql:host=$host;dbname=$dbname";

            
            //Open a connection
            $this->connection = new mysqli($host, $user, $pass, $dbname);
            
            /* check connection */
            if (mysqli_connect_errno()) {
                throw new CustomException( "Connect failed: %s\n", mysqli_connect_error() );
            }
            
            $result = true;
            
        } catch (Exception $e) {
            $result = false;
            throw new CustomException( 'Error in : creating connection: \n' .  $e->getMessage() . "\n" );
        }

        return $result;
    }


    public function select_query($sql, $parameters=null){

        $result_array = array();
       
        try {

            $this->connection->query("set names 'utf8'");

            if( count($parameters) <= 0 ){

                //$statement = $this->connection->query($sql);
                $result = $this->connection->query($sql);
                
            } else {

                $statement = $this->connection->prepare($sql);
                
                if( ! $statement ){
                    throw new CustomException("Prepare failed: (" . $this->connection->errno . ") " . $this->connection->error);
                }

                self::bind_params($statement, $parameters);

                $executed = $statement->execute();

                if( !$executed ){
                    throw new CustomException("Execution failed: (" . $statement->errno . ") " . $statement->error);
                }
                
                $result = $statement->get_result();

            }
            
            while(  $row = $result->fetch_assoc()  ){
                $result_array[] = $row;
            }
            
            //$result->free();
            
            $statement->free_result();

            $statement->close();

        } catch (Exception $e) {
            throw new CustomException( 'Error in : select query', $e );//from php 5.3 no need to custum
        }
        
        return $result_array;
    }

    public function update_query($sql, $parameters=null){

        $result = 0;

        try {

            $this->connection->query("set names 'utf8'");

            if( count($parameters) <= 0 ){

                $result = $this->connection->query($sql);

            } else {

                $statement = $this->connection->prepare($sql);
                
                self::bind_params($statement, $parameters);

                $result = $statement->execute();

                $this->last_affected_rows = $this->connection->affected_rows;
                //$this->last_affected_rows = $statement->rowCount();

                $statement->free_result();

                $statement->close();

            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : update query', $e );//from php 5.3 no need to custum
        }

        return $result;
    }

    public function multi_select_query($sql, $parameters=null){

        $result_array = array();
       
        try {

            $this->connection->query("set names 'utf8'");

            /* execute multi query */
            
            if ($this->connection->multi_query($sql)) {
                do {
                    /* store first result set */
                    if ( $result = $this->connection->use_result() ) {

                        $array = array();

                        while ($row = $result->fetch_assoc()) {
                            //print_r( $row );
                            $array[] = $row;
                        }

                        //if( count($array) > 0 ){
                        //}
                        $result_array[] = $array;

                        $result->free_result();
                    }
                    /* print divider */
                    if ($this->connection->more_results()) {
                        //printf("-----------------\n");
                    }
                } while ($this->connection->next_result());
            }
            
            
//            
//            if( empty($parameters) || count($parameters) <= 0 ){
//
//                $statement = $this->connection->query($sql);
//
//            } else {
//
//                $statement = $this->connection->prepare($sql);
//
//                self::bind_params($statement, $parameters);
//                
//                $statement->execute();
//
//            }
//
//            do{
//
//                //$rowset = $statement->fetchAll(PDO::FETCH_ASSOC);
//                
//                $statement->fetch(PDO::FETCH_ASSOC);
//                while ( $row = $statement->fetch(PDO::FETCH_BOTH) ) {
//                    $rowset[] = $row;
//                }
//
//                $result_array[] = $rowset;
//
//                //do stuff
//            } while( $statement->nextRowset() );
//
//            $statement->closeCursor();

        } catch (Exception $e) {
            throw new CustomException( 'Error in : multi select query', $e );//from php 5.3 no need to custum
        }
        
        return $result_array;
    }


    private static function bind_params(&$statement, $params) {

        try{

            //foreach ($parameters as $i => $value) {
            //    $statement->bindParam(($i+1), $value, "s");
            //}

            for ($i=0; $i<count($params); $i++) {

                if ( is_numeric( $params[$i] ) ) {
                    if ( is_integer( $params[$i] ) ) {
                        $type = "i";
                    } else if ( is_float( $params[$i] ) || is_double( $params[$i] ) ) {
                        $type = "d";//there is no type for decimal
                    } else {
                        $type = "i";
                    }
                } else {
                    $type = "s";
                }
                
                $statement->bindParam( ($i+1), $params[$i], $type );
                
            }
        
        } catch (Exception $e) {
            throw new CustomException( 'Error in : binding parameters: \n' .  $e->getMessage() . "\n" );
        }

        return $statement;
    }


    public function get_last_insert_id(){
        return $this->connection->insert_id;
    }

    public function get_affected_rows(){
        return $this->last_affected_rows;
    }


    public function begin_transaction(){
       
        try {

            $this->connection->begin_transaction();

        } catch (Exception $e) {
            throw new CustomException( 'Error in : begin transaction', $e );//from php 5.3 no need to custum
        }
        
        return;
    }

    public function commit(){

        $status = false;
        
        try {

            $status = $this->connection->commit();

        } catch (Exception $e) {
            throw new CustomException( 'Error in : begin transaction', $e );//from php 5.3 no need to custum
        }
        
        return $status;
    }

    public function roll_back(){
       
        $status = false;

        try {

            $status = $this->connection->rollback();

        } catch (Exception $e) {
            throw new CustomException( 'Error in : roll back transaction', $e );//from php 5.3 no need to custum
        }
        
        return $status;
    }


    public function connected(){

        $status = false;

        try {
            
            $status = $this->connection->stat();

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get connection status: \n' .  $e->getMessage() . "\n" );
        }

        return $status;
    }


    public function close(){

        $status = false;

        try {

            $status = $this->connection->close();

        } catch (Exception $e) {
            throw new CustomException( 'Error in : close connection: \n' .  $e->getMessage() . "\n" );
        }

        return $status;
    }

}
