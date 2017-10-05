<?php

/*
 *
 */

//include_once BASE_DIR.'/mvc/libraries/json/JSONDocument.php';

//include_once BASE_DIR.'/mvc/libraries/QueryUtil.php';
//include_once BASE_DIR.'/mvc/libraries/TextConverter.php';

/**
 * Description of Cache
 *
 * @author Ahmad
 */
class Cache {

    public static $dir = 'cache/';
    public $filename = "cache.json";

    public $array_loaded = false;
    public $cache_array;

    private function Cache(){
    }

    public static function create_instance($file){

        $cache = new Cache();

        $cache->array_loaded = false;

        if( !empty($file) ){
            $cache->filename = $file;
        }
        
        return $cache;
    }

    public function set_cache_file($file){

        if( !empty($file) ){
            $this->filename = $file;
            $this->array_loaded = false;
        }
        
    }

    private function load(){

        try{

            if( ! $this->array_loaded ){

                $path = UPLOAD_DIR . self::$dir;

                $jsonParser = new JSONDocument();

                $jsonParser->load_json_file( $path . $this->filename );

                $this->cache_array = $jsonParser->get_json_array();

                if( ! is_array( $this->cache_array) ){
                    $this->cache_array = array();
                }

                $this->array_loaded = true;
            }
            
        }catch(Exception $e){
            throw new CustomException( 'Error in : load cache.json', $e );//from php 5.3 no need to custum
        }
    }

    private function save_changes(){

        $stat = 0;

        try{

            $path = UPLOAD_DIR . self::$dir;

            $jsonWriter = new JSONDocument($this->cache_array);

            $jsonWriter->set_encoding( DEFAULT_ENCODING );

            $jsonWriter->convert_to_json_string();

            $stat = $jsonWriter->save_json_file($path, $this->filename);

        }catch(Exception $e){
            throw new CustomException( 'Error in : save changes ', $e );//from php 5.3 no need to custum
        }

        return $stat;
    }

    public function set_data($key, $data, $expiration = -1){

        $result = 0;

        try {

            self::load();

            $cache_array = & $this->cache_array["vars"];

            if( $cache_array == null ){
                $cache_array = array();
            }

            $time = time();
            
            $array = array();

            $array["key"]        = $key;
            $array["time"]       = $time;
            $array["expiration"] = $expiration;
            $array["data"]       = $data;

            $cache_array[$key]  = $array;

            $result = self::save_changes();

        } catch (Exception $e) {
            throw new CustomException( 'Error in : set data', $e );//from php 5.3 no need to custum
        }

        return $result;
    }

    public function get_data($key){

        $data = null;

        try {

            self::load();
            
            $array = $this->cache_array["vars"][$key];
            
            if( is_array($array) ){
                
                $current_time = time();
                $data_time    = $array["time"];
                $expiration   = $array["expiration"];

                if( (($current_time-$data_time) > $expiration) && ($expiration > 0 )){
                    self::clear_data($key);
                }else{
                    $data = TextUtil::convert_array_to_object( $array["data"] );
                }

            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get data', $e );//from php 5.3 no need to custum
        }

        return $data;
    }

    public function clear_data($key){

        $result = 0;

        try {

            self::load();
            
            $array = $this->cache_array["vars"][$key];
            
            if( is_array($array) ){
                
                unset( $this->cache_array["vars"][$key] );

                $result = self::save_changes();

            }              

        } catch (Exception $e) {
            throw new CustomException( 'Error in : remove data', $e );//from php 5.3 no need to custum
        }

        return $result;
    }

    public function clear_all(){

        $result = 0;

        try {

            self::load();
            
            $this->cache_array["vars"] = array();

            $result = self::save_changes();

        } catch (Exception $e) {
            throw new CustomException( 'Error in : clear all data', $e );//from php 5.3 no need to custum
        }

        return $result;
    }

    public static function remove_cached_files(){

        $result = 0;

        try {

            $path = UPLOAD_DIR . self::$dir;

            $files = glob($path.'/*'); // get all file names // glob('path/to/temp/*'); // 

            foreach($files as $file){ // iterate files
              if(is_file($file))
                unlink($file); // delete file
            }
            
            $result = 1;

        } catch (Exception $e) {
            throw new CustomException( 'Error in : clear all data', $e );//from php 5.3 no need to custum
        }

        return $result;
    }

}

?>
