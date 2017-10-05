<?php
/*
 *
 */

/**
 * Description of DBConnect
 *
 * @author Ahmad
 */
class DBConnect extends CustomException {

    private $host     = null;
    private $database = null;
    private $user     = null;
    private $password = null;
    private $connection = null;
    private $persistent = false;

    private function DBConnect(){
    }

    public static function create_instance($database, $host=null, $user=null, $password=null, $is_persistent = false ){

        $db_mngr = new DBConnect();

        $db_mngr->database = $database;
        $db_mngr->host     = $host;
        $db_mngr->user     = $user;
        $db_mngr->password = $password;

        $db_mngr->persistent = $is_persistent;

        $db_mngr->connect();

        return $db_mngr;

    }

    private function connect(){

        $success = false;

        if($this->persistent){
            $this->connection = mysql_pconnect($this->host, $this->user, $this->password);
        }else{
            $this->connection = mysql_connect($this->host, $this->user, $this->password);
        }

        if($this->connection == false){
            throw new CustomException('Cannot connect to mysql server');
        }

        $success = mysql_select_db($this->database, $this->connection);

        if($success == false){
            throw new CustomException('Cannot find database, or database not exist');
        }

        return $success;
    }

    public function update_query($query, $parameters = null){

        $result = 0;

        try {

            mysql_query("set names 'utf8'", $this->connection);

            if($parameters != null){
                // '?', '%s'
                $query = str_replace( '?', '\'%s\'', $query );

                foreach($parameters as &$param){
                    $param = mysql_escape_string($param);
                }

                $paramsArray = $parameters;
                array_unshift( $paramsArray, $query );

                $query = call_user_func_array( "sprintf", $paramsArray);

            }

            $result = mysql_query($query, $this->connection);

        } catch (Exception $e) {
            throw new CustomException( $e->getMessage()."\n" );
        }
        return $result;
    }

    public function select_query($query, $parameters = null){

        $resultArray = array();

        try {

            mysql_query("set names 'utf8'", $this->connection);
            
            if($parameters != null){

                // '?', '%s'
                $query = str_replace( '?', '\'%s\'', $query );

                foreach($parameters as &$param){
                    $param = mysql_escape_string($param);
                }

                $paramsArray = $parameters;
                array_unshift( $paramsArray, $query );

                $query = call_user_func_array( "sprintf", $paramsArray);
            }

            $result = mysql_query($query, $this->connection);

            while($array = mysql_fetch_assoc($result)) {
                $resultArray[] = $array;
            }

        } catch (Exception $e) {
            throw new CustomException( $e->getMessage()."\n" );
        }
        return $resultArray;
    }

    public function get_last_insert_id(){
        return mysql_insert_id();
    }

    public function close(){

        $success = false;

        try {

           $success = mysql_close($this->connection);

           if($success == false){
             throw new CustomException('Cannot close connection');
           }
        } catch (Exception $e) {
            throw new CustomException( $e->getMessage()."\n" );
        }

        return $success;
    }

    public function free(){

        try {

            $this->connection = null;

        } catch (Exception $e) {
            throw new CustomException( $e->getMessage()."\n" );
        }

    }

}
?>
