<?php

/*
 *
 */

/**
 * Description of QueryUtil
 *
 * @author Ahmad
 */

class QueryUtil2 extends ConnectionData {

    private static $connected = false;
    private static $last_used_buffer = null;

    private function QueryUtil2(){
    }

    public static function connect(){

        try {

            if( ! self::$connected ){
                DBAdapter::get_instance()->connect();
                self::$connected = true;
            }
            
        } catch (Exception $e) {
            throw new CustomException( 'Error in : creating connection', $e );//from php 5.3 no need to custum
        }
    }

    public static function close(){

        try {

            if( self::$connected ){
                DBAdapter::get_instance()->close();
                self::$connected = false;
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : creating connection', $e );//from php 5.3 no need to custum
        }
    }

    public static function excute_update($query, $params=null){

        $result = 0;

        try {
            
            self::connect();
            
            $connection = DBAdapter::get_instance();

            //$connection->connect();

            $result = $connection->update_query( $query, $params );

            if( $result > 0 ){
                $lastId = $connection->get_last_insert_id();
                if( $lastId > 0 ){
                    $result = $lastId;
                }
            }

            //$connection->close();

        } catch (Exception $e) {
            throw new CustomException( 'Error in : update query', $e );//from php 5.3 no need to custum
        }

        return $result;
    }

    public static function excute_select($query, $params=null){

        $array = array();

        try {

            self::connect();

            $connection = DBAdapter::get_instance();

            //$connection->connect();

            $array = $connection->select_query( $query, $params );

            self::stripslashes_deep(&$array);
            //stripslashes_deep_func(&$array);
            
            self::$last_used_buffer = $array;

            //$connection->close();

        } catch (Exception $e) {
            throw new Exception( 'Error : ' .  $e->getMessage() . "" );
        }

        return $array;
    }

    public static function excute_sp_select($query, $params=null){

        $array = array();

        try {

            self::connect();

            $connection = DBAdapter::get_instance();

            //$connection->connect();

            $array = $connection->select_query( $query, $params );

            self::stripslashes_deep(&$array);
            //stripslashes_deep_func(&$array);
            
            self::$last_used_buffer = $array;

            //$connection->close();
            
            self::close();

        } catch (Exception $e) {
            throw new Exception( 'Error : ' .  $e->getMessage() . "" );
        }

        return $array;
    }

    public static function excute_select_multi($query, $params=null){

        $array = array();

        try {

            self::connect();
            
            $connection = DBAdapter::get_instance();

            //$connection->connect();

            $array = $connection->select_multi_query( $query, $params );

            self::stripslashes_deep(&$array);
            
            self::$last_used_buffer = $array;

            //$connection->close();

            self::close();

        } catch (Exception $e) {
            throw new Exception( 'Error : ' .  $e->getMessage() . "" );
        }

        return $array;
    }

    public static function stripslashes_deep($value){

        $value = is_array($value) ?
                array_map( array(self, stripslashes_deep), $value ) :
                stripslashes($value);

        //Logger::log( '$value : '.$value, INFO );
        
        return $value;
    }

    public static function get_last_used_buffer(){
        return self::$last_used_buffer;
    }

}

/*
function stripslashes_deep_func($value){

    $value = is_array($value) ?
            array_map('stripslashes_deep', $value) :
            stripslashes($value);

    return $value;
}
*/

?>
