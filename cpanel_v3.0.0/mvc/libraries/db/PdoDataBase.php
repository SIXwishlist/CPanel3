<?
/*
 * 
 */

/**
 * Description of PdoDataBase
 *
 * @author Ahmad
 */
class PdoDataBase extends CustomException {

    // JDBC driver name and database URL

    private $connection;
    private $last_affected_rows;

    private static $error_info;
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
            
            $dsn = "mysql:host=$host;dbname=$dbname";

            //'unix_socket' => '/tmp/mysql.sock’,
            
            //Open a connection
            $this->connection = new PDO($dsn, $user, $pass);
            
            //for persistent connection
            if( $persistent ){
                $this->connection->setAttribute(PDO::ATTR_PERSISTENT, TRUE);
            }

            //$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //$this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            //$this->connection->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);

            //$this->connection->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);

            $result = true;
            
        } catch (PDOException $e) {
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

                $statement = $this->connection->query($sql);
                
            } else {

                $statement = $this->connection->prepare($sql);

                self::bind_params($statement, $parameters);
                
                $statement->execute();

            }

            if( !$statement ) {
                throw new CustomException( 'SQL syntax error' );
            }

            $error = $statement->errorInfo();

            if( $error[1] >= 0 ) {
                $result_array = $statement->fetchAll(PDO::FETCH_ASSOC);
            }

            $statement->closeCursor();

            
            if( $error[1] > 0 ) {

                self::$error_info = $error;

                throw new CustomException( 'Query error : '.$error[2] );//echo($errors[2]);

            }
            
            //$result_array = $statement->fetchAll(PDO::FETCH_ASSOC);
            //$statement->fetch()
            //$statement->fetch(PDO::FETCH_ASSOC)
            //while ( $row = $statement->fetch(PDO::FETCH_BOTH) ) {
            //    $resultArray[] = $row;
            //}

        } catch (PDOException $e) {
            throw new CustomException( 'Error in : select query', $e );//from php 5.3 no need to custum
        }
        
        return $result_array;
    }

    public function update_query($sql, $parameters=null){

        $result = 0;

        try {

            $this->connection->query("set names 'utf8'");

            if( count($parameters) <= 0 ){

                $result = $this->connection->exec($sql);

            } else {

                $statement = $this->connection->prepare($sql);
                
                self::bind_params($statement, $parameters);

                $result = $statement->execute();

                if( !$statement ) {
                    throw new CustomException( 'SQL syntax error' );
                }

                $this->last_affected_rows = $statement->rowCount();

                $statement->closeCursor();

                $error = $statement->errorInfo();

                if( $error[1] > 0 ) {

                    self::$error_info = $error;

                    throw new CustomException( 'Query error : '.$error[2] );//echo($errors[2]);
                }

            }

        } catch (PDOException $e) {
            throw new CustomException( 'Error in : update query', $e );//from php 5.3 no need to custum
        }

        return $result;
    }

    public function multi_select_query($sql, $parameters=null){

        $result_array = array();
       
        try {

            $this->connection->query("set names 'utf8'");

            if( empty($parameters) || count($parameters) <= 0 ){

                $statement = $this->connection->query($sql);

            } else {

                $statement = $this->connection->prepare($sql);

                self::bind_params($statement, $parameters);
                
                $statement->execute();

            }

            if( !$statement ) {
                throw new CustomException( 'SQL syntax error' );
            }
            
                        
            $error = $statement->errorInfo();

            //if( $statement->errorCode() == 0 ) {
            if( $error[1] >= 0 ) {

                //do fetch
                do{

                    $rowset = $statement->fetchAll(PDO::FETCH_ASSOC);

                    $result_array[] = $rowset;

                    //do stuff
                } while( $statement->nextRowset() );

            }

            $statement->closeCursor();
            
            if( $error[1] > 0 ) {

                self::$error_info = $error;

                throw new CustomException( 'Query error : '.$error[2] );//echo($errors[2]);

            }

        } catch (PDOException $e) {
            throw new CustomException( 'Error in : multi select query', $e );//from php 5.3 no need to custum
        }
        
        return $result_array;
    }


    private static function bind_params(&$statement, $params) {

        try{

            //foreach ($parameters as $i => $value) {
            //    $statement->bindParam(($i+1), $value, PDO::PARAM_STR);
            //}

            for ($i=0; $i<count($params); $i++) {

                if ( is_numeric( $params[$i] ) ) {
                    if ( is_integer( $params[$i] ) ) {
                        $type = PDO::PARAM_INT;
                    } else if ( is_float( $params[$i] ) || is_double( $params[$i] ) ) {
                        $type = PDO::PARAM_STR;//there is no type for decimal
                    } else {
                        $type = PDO::PARAM_INT;
                    }
                } else {
                    $type = PDO::PARAM_STR;
                }
                
                $statement->bindParam( ($i+1), $params[$i], $type );
                
            }
        
        } catch (PDOException $e) {
            throw new CustomException( 'Error in : binding parameters: \n' .  $e->getMessage() . "\n" );
        }

        return $statement;
    }


    public static function get_error(){
        
        $error_array = self::$error_info;

        $error = new stdClass();

        $error->code    = $error_array[1];
        $error->message = $error_array[2];
        
        return $error;
    }

    public function get_last_insert_id(){
        return $this->connection->lastInsertId();
    }

    public function get_affected_rows(){
        return $this->last_affected_rows;
    }


    public function begin_transaction(){
       
        try {

            $this->connection->beginTransaction();

        } catch (PDOException $e) {
            throw new CustomException( 'Error in : begin transaction', $e );//from php 5.3 no need to custum
        }
        
        return;
    }

    public function commit(){
       
        try {

            $this->connection->commit();

        } catch (PDOException $e) {
            throw new CustomException( 'Error in : begin transaction', $e );//from php 5.3 no need to custum
        }
        
        return;
    }

    public function roll_back(){
       
        try {

            $this->connection->rollBack();

        } catch (PDOException $e) {
            throw new CustomException( 'Error in : roll back transaction', $e );//from php 5.3 no need to custum
        }
        
        return;
    }


    public function connected(){

        $status = false;

        try {
            
            $status = $this->connection->getAttribute(PDO::ATTR_CONNECTION_STATUS);

        } catch (PDOException $e) {
            throw new CustomException( 'Error in : get connection status: \n' .  $e->getMessage() . "\n" );
        }

        return $status;
    }


    public function close(){

        $success = false;

        try {
            
            $this->connection = null;

            $success = true;

        } catch (PDOException $e) {
            throw new CustomException( 'Error in : close connection: \n' .  $e->getMessage() . "\n" );
        }

        return $success;
    }

}
