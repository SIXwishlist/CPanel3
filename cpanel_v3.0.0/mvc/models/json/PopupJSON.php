<?php
/*
 *
 */

/**
 * Description of PopupJSON
 *
 * @author Ahmad
 */
class PopupJSON {

    public static $dir = 'json/';
    public static $filename = "popup.json";

    public static $popup;

    private static function load(){

        try{

            $path = UPLOAD_DIR . self::$dir;

            $jsonParser = new JSONDocument();

            $jsonParser->load_json_file( $path . self::$filename );

            self::$popup = $jsonParser->get_json_array();

        }catch(Exception $e){
            throw new Exception( 'Error : load popup.json failed \n',  $e->getMessage(), "\n" );
        }
    }
    private static function save_changes(){

        $stat = 0;

        try{

            $path = UPLOAD_DIR . self::$dir;

            $jsonWriter = new JSONDocument(self::$popup);

            $jsonWriter->set_encoding( DEFAULT_ENCODING );

            $jsonWriter->convert_to_json_string();

            $stat = $jsonWriter->save_json_file($path, self::$filename);

        }catch(Exception $e){
            throw new Exception( 'Error : save changes to popup.json failed \n',  $e->getMessage(), "\n" );
        }

        return $stat;
    }

    public static function update_popup($pPopup){

        $result = 0;

        try {

            $request = HttpRequest::get_instance();

            self::load();

            $old_popup = self::$popup;

            $popup = array();

            $popup["content"] = $pPopup->content;
            $popup["active"]  = $pPopup->active;

            //unset( self::$popup );

            self::$popup = $popup;
            
            
            $result = self::save_changes();

        } catch (Exception $e) {
            throw new Exception( 'Error : update popup failed \n',  $e->getMessage(), "\n" );
        }

        return $result;
    }
    
    public static function get_popup(){

        $popup = null;

        try{

            self::load();

            $popup_array  = self::$popup;

            $popup = (object) $popup_array;

        } catch (Exception $e) {
            throw new Exception( 'Error : can not get popup array \n',  $e->getMessage(), "\n" );
        }

        return $popup;
    }

}

?>
