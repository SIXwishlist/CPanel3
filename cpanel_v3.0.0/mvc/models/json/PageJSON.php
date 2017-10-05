<?php

/*
 *
 */

//include_once BASE_DIR.'/mvc/libraries/json/JSONDocument.php';

//include_once BASE_DIR.'/mvc/libraries/QueryUtil.php';
//include_once BASE_DIR.'/mvc/libraries/TextConverter.php';

/**
 * Description of PageJSON
 *
 * @author Ahmad
 */
class PageJSON {

    public static $dir = 'json/';
    public static $filename = "pages.json";

    public static $page_array;

    private static function load(){

        try{

            $path = UPLOAD_DIR . self::$dir;

            $jsonParser = new JSONDocument();

            $jsonParser->load_json_file( $path . self::$filename );

            self::$page_array = $jsonParser->get_json_array();

        }catch(Exception $e){
            throw new Exception( 'Error : load banner.json failed \n',  $e->getMessage(), "\n" );
        }
    }
    private static function save_changes(){

        $stat = 0;

        try{

            $path = UPLOAD_DIR . self::$dir;

            $jsonWriter = new JSONDocument(self::$page_array);

            $jsonWriter->set_encoding( DEFAULT_ENCODING );

            $jsonWriter->convert_to_json_string();

            $stat = $jsonWriter->save_json_file($path, self::$filename);

        }catch(Exception $e){
            throw new Exception( 'Error : save changes to banner.json failed \n',  $e->getMessage(), "\n" );
        }

        return $stat;
    }

    public static function add_page($pPage){

        $result = 0;

        try {

            $request = HttpRequest::get_instance();

            self::load();

            $page_array = self::$page_array["pages"];

            if( $page_array == null ){
                $page_array = array();
            }

            $page = array();

            $page_id = count($page_array)+1;

            $page["page_id"]  = $page_id;
            $page["name"]     = $pPage->name;
            $page["title_ar"] = $pPage->title_ar;
            $page["title_en"] = $pPage->title_en;
            $page["text_ar"]  = $pPage->text_ar;
            $page["text_en"]  = $pPage->text_en;
            $page["image"]    = $pPage->image;
            $page["menu"]     = $pPage->menu;
            $page["active"]   = $pPage->active;

            $page_array [] = $page;
            self::$page_array["pages"] = array_values($page_array);

            $result = self::save_changes();

        } catch (Exception $e) {
            throw new Exception( 'Error : new page failed \n',  $e->getMessage(), "\n" );
        }

        return $result;
    }
    public static function update_page($pPage){

        $result = 0;

        try {

            $request = HttpRequest::get_instance();

            self::load();

            $page_array = self::$page_array["pages"];

            $page_id = $pPage->page_id;

            $page_index = self::get_index( self::$page_array["pages"], "page_id", $page_id );

            $page = array();

            $page["page_id"]  = $page_id;
            $page["name"]     = $pPage->name;
            $page["title_ar"] = $pPage->title_ar;
            $page["title_en"] = $pPage->title_en;
            $page["text_ar"]  = $pPage->text_ar;
            $page["text_en"]  = $pPage->text_en;
            $page["image"]    = $pPage->image;
            $page["menu"]     = $pPage->menu;
            $page["active"]   = $pPage->active;

            if( $page_index > -1 ){
                unset( $page_array[$page_index] );
                $page_array[$page_index] = $page;
            }

            self::$page_array["pages"] = array_values($page_array);
            
            
            $result = self::save_changes();

        } catch (Exception $e) {
            throw new Exception( 'Error : update page failed \n',  $e->getMessage(), "\n" );
        }

        return $result;
    }
    public static function remove_page($page){

        $result = 0;

        try {

            $request = HttpRequest::get_instance();

            self::load();

            $page_index = self::get_index( self::$page_array["pages"], "page_id", $page->page_id );

            $page_array = self::$page_array["pages"];
            if( $page_index > -1 ){
                unset( $page_array[$page_index] );
            }

            self::$page_array["pages"] = array_values($page_array);


            $result = self::save_changes();

        } catch (Exception $e) {
            throw new Exception( 'Error : update page failed \n',  $e->getMessage(), "\n" );
        }

        return $result;
    }
    
    public static function get_page($page_id){

        $page_object = null;

        try{

            self::load();

            $page_array  = self::get_element( self::$page_array["pages"], "page_id", $page_id );
            $page_object = (object) $page_array;

        } catch (Exception $e) {
            throw new Exception( 'Error : can not get page array \n',  $e->getMessage(), "\n" );
        }

        return $page_object;
    }
    public static function get_page_by_name($name){

        $page_object = array();

        try{

            self::load();

            $page_object = self::get_element( self::$page_array["pages"], "name", $name );

        } catch (Exception $e) {
            throw new Exception( 'Error : can not get page array \n',  $e->getMessage(), "\n" );
        }

        return $page_object;
    }

    public static function get_pages($index=-1, $count=-1){

        $page_array = array();

        try{

            self::load();

            $page_array = self::$page_array["pages"];

            ksort( &$page_array );

            if ( $index > -1 && $count > -1 ) {
                $page_array = array_slice($page_array, $index, $count);
            }

            for ( $i=0; $i<count($page_array); $i++ ) {
                $page_array[$i] = (object) $page_array[$i];
            }

        } catch (Exception $e) {
            throw new Exception( 'Error : can not get banner array \n',  $e->getMessage(), "\n" );
        }

        return $page_array;
    }
    public static function get_pages_count(){

        $result = 0;

        try{

            self::load();

            $page_array = self::$page_array["pages"];

            $result = count( &$page_array );

        } catch (Exception $e) {
            throw new Exception( 'Error : can not get banner array \n',  $e->getMessage(), "\n" );
        }

        return $result;
    }

   
    private static function &get_element($array, $label, $val){

        for($i=0; $i<count($array); $i++){
            if( $array[$i][$label] == $val ){
                return $array[$i];
            }
        }

        return "";
    }
    private static function get_index($array, $label, $val){

        for($i=0; $i<count($array); $i++){
            if( $array[$i][$label] == $val ){
                return $i;
            }
        }

        return -1;
    }

}

?>
