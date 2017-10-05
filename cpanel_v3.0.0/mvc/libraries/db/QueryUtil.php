<?

/*
 *
 */

define("CONNECT_MODE_MANUAL", 0);
define("CONNECT_MODE_AUTO",   1);

/**
 *
 * @author ahmad
 */
class QueryUtil {
    
    public static $last_affected_rows = 0;
    public static $last_insert_id     = -1;
    public static $error_info         = null;
    public static $last_used_buffer   = null;
    
    public static $connect_mode = CONNECT_MODE_AUTO;
    
    public static $db_prefix = "default.db";
    
    public static function connect($db_prefix=null) {

        $result = 0;

        try {

            if( self::$connect_mode == CONNECT_MODE_AUTO ){

                self::$db_prefix = ($db_prefix == null) ? self::$db_prefix : $db_prefix;

                $config = self::get_db_resource();

                $db_object = PdoDataBase::get_instance();

                $result = $db_object->connect($config, self::$db_prefix);
                
            }
            
        } catch (Exception $ex) {
            $result = -1;
            throw new CustomException( "Can't create connection", $ex);
        }

        return $result;
    }
    
    public static function close() {

        $result = 0;

        try {
            
            if( self::$connect_mode == CONNECT_MODE_AUTO ){

                $db_object = PdoDataBase::get_instance();

                $result = $db_object->close();

            }
            
        } catch (Exception $ex) {
            $result = -1;
            throw new CustomException( "Can't close connection", $ex);
        }

        return $result;
    }

    
    public static function set_db($db_prefix) {

        self::$db_prefix = $db_prefix;
        
    }


    public static function excute_select($sql, $params=null) {

        $array = array();

        try {

            $db_object = null;
            
            if( self::$connect_mode == CONNECT_MODE_MANUAL ){
                
                $config = self::get_db_resource();
                
                $db_object = new PdoDataBase();
            
                $db_object->connect($config, self::$db_prefix);
                
            }else{
                
                $db_object = PdoDataBase::get_instance();
                
                if( ! $db_object->connected() ){

                    $config = self::get_db_resource();

                    $db_object->connect($config, self::$db_prefix);
                }
                
            }
            
            $array = $db_object->select_query($sql, $params);
            
            self::stripslashes_deep($array);
            //stripslashes_deep_func(&$array);
            
            self::$last_used_buffer = $array;
            
            if( self::$connect_mode == CONNECT_MODE_MANUAL ){
                $db_object->close();
            }
            
        } catch (Exception $ex) {
            throw new CustomException( "Can't excute select statement", $ex);
        }

        return $array;
    }

    public static function excute_update($sql, $params=null) {

        $result = 0;

        try {

            $db_object = null;
            
            if( self::$connect_mode == CONNECT_MODE_MANUAL ){
                
                $config = self::get_db_resource();
                
                $db_object = new PdoDataBase();
            
                $db_object->connect($config, self::$db_prefix);
                
            }else{
                
                $db_object = PdoDataBase::get_instance();

                if( ! $db_object->connected() ){

                    $config = self::get_db_resource();

                    $db_object->connect($config, self::$db_prefix);
                }
            }
            
            $result = $db_object->update_query($sql, $params);
            
            self::$last_insert_id     = $db_object->get_last_insert_id();

            self::$last_affected_rows = $db_object->get_affected_rows();
            
            if( self::$connect_mode == CONNECT_MODE_MANUAL ){
                $db_object->close();
            }
            
        } catch (Exception $ex) {
            self::$error_info = PdoDataBase::get_error();
            throw new CustomException( "Can't excute update statement", $ex);
        }

        return $result;
    }

    public static function excute_select_multi($sql, $params=null) {

        $array = array();

        try {

            $db_object = null;
            
            if( self::$connect_mode == CONNECT_MODE_MANUAL ){
                
                $config = self::get_db_resource();
                
                $db_object = new PdoDataBase();
            
                $db_object->connect($config, self::$db_prefix);
                
            }else{
                
                $db_object = PdoDataBase::get_instance();

                if( ! $db_object->connected() ){

                    $config = self::get_db_resource();

                    $db_object->connect($config, self::$db_prefix);
                }
            }
            
            $array = $db_object->multi_select_query($sql, $params);
            
            self::stripslashes_deep($array);
            //stripslashes_deep_func(&$array);
            
            self::$last_used_buffer = $array;
            
            if( self::$connect_mode == CONNECT_MODE_MANUAL ){
                $db_object->close();
            }
            
        } catch (Exception $ex) {
            throw new CustomException( "Can't excute select statement", $ex);
        }

        return $array;
    }


    private static function get_db_resource(){
        
        $resource = DBResource::get_instance()->get_db_config();
        
        return $resource;
    }

    
    public static function stripslashes_deep(&$value){

        $value = is_array($value) ?
                array_map( array(self, stripslashes_deep), $value ) :
                stripslashes($value);

        //Logger::log( '$value : '.$value, INFO );
        
        return $value;
    }
    
    public static function get_last_used_buffer(){
        return self::$last_used_buffer;
    }
    
    public static function get_last_insert_id(){
        return self::$last_insert_id;
    }
    
    public static function get_affected_rows(){
        return self::$last_affected_rows;
    }
    
    public static function get_error(){
        return self::$error_info;
    }

    
}
