<?php
/*
 *
 */

/**
 * Description of EmbedManage
 *
 * @author Ahmad
 */

class EmbedManage extends ManageController {

    public static $dir = "embeds";

    public static function add_embed(){

        $output_array = array();

        try {
            
            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_ADD);

            if( $permitted ){

                QueryUtil::connect();
                
                $request->set_parameter("options", (EDITABLE|REMOVABLE) );
                
                $embed = self::read_embed_form();

                $status = EmbedDB::add_embed($embed);

                if( $status > 0 ){
                    
                    $embed->embed_id = $status;

                    //$embed->icon  = FileUtil::save_thumb("icon", "icon", ICON_MEDIA_WIDTH, ICON_MEDIA_HEIGHT,  $path);
                    $embed->icon  = self::get_embed_icon($embed->embed_id, '');
                    $embed->file  = self::get_embed_file($embed->embed_id, $embed->type, '', -1);

                    $status = EmbedDB::update_embed($embed);

                }
                
                QueryUtil::close();
                
            }else{
                $status = -1;//ERROR_TYPE_UNAUTHORIZED_ACCESS;
            }

            $output_array["status"] = intval($status);

        } catch (Exception $e) {
            $output_array["status"] = SERVER_ERROR;
            Logger::log( $e->getMessage(), ERROR );
        }

        return $output_array;

    }

    public static function update_embed(){

        $output_array = array();
        
        try {

            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();
            
            $permitted = self::check_permission(ACTION_EDIT);

            if( $permitted ){

                QueryUtil::connect();
                
                $embed = self::read_embed_form();

                $embed_id  = $request->get_int_parameter("embed_id");

                $old_embed = EmbedDB::get_embed($embed_id);
                
                //$embed->icon  = FileUtil::replace_thumb("icon", "icon", ICON_PAGES_WIDTH, ICON_PAGES_HEIGHT, $old_embed->icon, $path);
                $embed->icon = self::get_embed_icon($embed->embed_id, $old_embed->icon);
                $embed->file = self::get_embed_file($embed->embed_id, $embed->type, $old_embed->file, $old_embed->type);
                
                $status = EmbedDB::update_embed($embed);
                
                QueryUtil::close();

            }else{
                $status = -1;//ERROR_TYPE_UNAUTHORIZED_ACCESS;
            }

            $output_array["status"] = intval($status);

        } catch (Exception $e) {
            $output_array["status"] = SERVER_ERROR;
            Logger::log( $e->getMessage(), ERROR );
        }

        return $output_array;

    }

    public static function remove_embed(){

        $output_array = array();
        
        try {
            
            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_REMOVE);

            if( $permitted ){

                QueryUtil::connect();
                
                $embed_id = $request->get_int_parameter("embed_id");

                $embed    = EmbedDB::get_embed($embed_id);

                $status   = EmbedDB::remove_embed($embed);

                if( $status > 0 ){
                    $status = FileUtil::remove_file($path, $embed->icon );
                    $status = self::remove_embed_file($embed->type, $embed->file);
                }
                
                QueryUtil::close();
               
            }else{
                $status = -1;//ERROR_TYPE_UNAUTHORIZED_ACCESS;
            }

            $output_array["status"] = intval($status);

        } catch (Exception $e) {
            $output_array["status"] = SERVER_ERROR;
            Logger::log( $e->getMessage(), ERROR );
        }

        return $output_array;

    }


    private static function get_embed_icon($id, $old_icon){

        $icon = '';

        try {
            
            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();

            $uploaded_icon = $request->get_file("icon");

            if(  !empty($uploaded_icon)  ) {
                
                if(  empty($old_icon)  ) {
                    $tempIcon = FileUtil::save_thumb("icon",  "icon", ICON_MEDIA_WIDTH, ICON_MEDIA_HEIGHT, $path);
                }  else {
                    $tempIcon = FileUtil::replace_thumb("icon",  "icon", ICON_MEDIA_WIDTH, ICON_MEDIA_HEIGHT, $old_icon, $path);
                }

                $ext  = FileUtil::get_file_ext($uploaded_icon->name);
                $icon = "icon_" . $id . $ext;
                FileUtil::rename_file($path, $tempIcon, $icon);

            }

            if( empty($icon) ){
                $icon = $old_icon;
            }
            
        } catch (Exception $e) {
            throw new CustomException( 'Error in : get icon', $e );//from php 5.3 no need to custum
        }
       
        return $icon;
    }

    private static function get_embed_file($id, $type, $old_file, $old_type=-1){

        $file = '';

        try {
            
            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();

            switch( $type ){

                  case FILE_TYPE_DOWNLOAD:
                  case FILE_TYPE_IMAGE:
                  case FILE_TYPE_FLASH:
                      $uploaded_file = $request->get_file("file");
                      if( !empty($uploaded_file) ){
                          $ext  = FileUtil::get_file_ext($uploaded_file->name);
                          $file = "file_" . $id . $ext;
                          $file = FileUtil::save_file("file", $file,  $path, false);
                      }
                      break;
                  
                  case FILE_TYPE_SOUND:
                      $file  = "file_" . $id;
                      $uploaded_file1 = $request->get_file("file1");
                      $uploaded_file2 = $request->get_file("file2");
                      if( !empty( $uploaded_file1 ) ){
                          $file1 = FileUtil::save_file("file1", $file.".mp3",  $path, false);
                      }
                      if( !empty( $uploaded_file2 ) ){
                          $file2 = FileUtil::save_file("file2", $file.".ogg",  $path, false);
                      }
                      break;
                  
                  case FILE_TYPE_VIDEO:
                      $file  = "file_" . $id;
                      $uploaded_file1 = $request->get_file("file1");
                      $uploaded_file2 = $request->get_file("file2");
                      $uploaded_file3 = $request->get_file("file3");
                      if( !empty( $uploaded_file1 ) ){
                          $file1 = FileUtil::save_file("file1", $file.".mp4",  $path, false);
                      }
                      if( !empty( $uploaded_file2 ) ){
                          $file2 = FileUtil::save_file("file2", $file.".ogv",  $path, false);
                      }
                      if( !empty( $uploaded_file3 ) ){
                          $file3 = FileUtil::save_file("file3", $file.".webm", $path, false);
                      }
                      break;
                  
                  case FILE_TYPE_YOUTUBE:
                      $url   = $request->get_parameter("file");
                      if( !empty( $url ) ){
                          $file  = FileUtil::get_youtube_id($url);
                      }
                      break;
                  
                  case FILE_TYPE_VIMEO:
                      $url   = $request->get_parameter("file");
                      if( !empty( $url ) ){
                          $file  = FileUtil::get_vimeo_id($url);
                      }
                      break;
                  
                  case FILE_TYPE_SOUND_CLOUD:
                      $file = $request->get_parameter("file");
                      //if( !empty( $file ) ){
                      //    $file  = FileUtil::get_vimeo_id($file);
                      //}
                      break;
                  
                  case FILE_TYPE_EMBED_CODE:
                      $file  = $request->get_parameter("file");
                      break;
                  
            }
            
            if( $type != $old_type ){

                switch( $old_type ){

                    case FILE_TYPE_DOWNLOAD:
                    case FILE_TYPE_IMAGE:
                    case FILE_TYPE_FLASH:
                        FileUtil::remove_file($path, $old_file);
                        break;

                    case FILE_TYPE_SOUND:
                        FileUtil::remove_file($path, $old_file.".mp3" );
                        FileUtil::remove_file($path, $old_file.".ogg" );
                        break;

                    case FILE_TYPE_VIDEO:
                        FileUtil::remove_file($path, $old_file.".mp4" );
                        FileUtil::remove_file($path, $old_file.".ogv" );
                        FileUtil::remove_file($path, $old_file.".webm");
                        break;
                }
            }
            
            if( empty($file) ){
                 $file = $old_file;
            }
            
        } catch (Exception $e) {
            throw new CustomException( 'Error in : get file', $e );//from php 5.3 no need to custum
        }
       
        return $file;
    }
    
    
    private static function remove_embed_file($type, $file){

        $status = 0;

        try {
            
            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();

            switch( $type ){

                case FILE_TYPE_DOWNLOAD:
                case FILE_TYPE_IMAGE:
                case FILE_TYPE_FLASH:
                    $status = FileUtil::remove_file($path, $file);
                    $status = 1;
                    break;

                case FILE_TYPE_SOUND:
                    $status = FileUtil::remove_file($path, $file.".mp3" );
                    $status = FileUtil::remove_file($path, $file.".ogg" );
                    $status = 1;
                    break;

                case FILE_TYPE_VIDEO:
                    $status = FileUtil::remove_file($path, $file.".mp4" );
                    $status = FileUtil::remove_file($path, $file.".ogv" );
                    $status = FileUtil::remove_file($path, $file.".webm");
                    $status = 1;
                    break;
                
                default:
                    $status = 1;
                    break;
            }

            
        } catch (Exception $e) {
            throw new CustomException( 'Error in : removing file', $e );//from php 5.3 no need to custum
        }
       
        return $status;
    }
    
    public static function get_formated_array(array $embeds){

        $embeds_array_list = array();

        for ( $i=0; $i<count($embeds); $i++ ){

            $embed = $embeds[$i];

            $embed_object = self::get_formated_object($embed);

            $embeds_array_list[] = $embed_object;
        }

        return $embeds_array_list;
    }

    public static function get_formated_object($embed){

        $embed_object = array();

        $embed_object["embed_id"]    = $embed->embed_id;

        $embed_object["title_ar"]    = $embed->title_ar;
        $embed_object["title_en"]    = $embed->title_en;
        $embed_object["desc_ar"]     = $embed->desc_ar;
        $embed_object["desc_en"]     = $embed->desc_en;
        $embed_object["icon"]        = $embed->icon;
        $embed_object["file"]        = $embed->file;
        $embed_object["type"]        = $embed->type;
        $embed_object["order"]       = $embed->order;
        $embed_object["active"]      = $embed->active;
        $embed_object["parent_type"] = $embed->parent_type;
        $embed_object["parent_id"]   = $embed->parent_id;

        return $embed_object;
    }


    private static function read_embed_form(){

        $embed = new stdClass();

        try {

            $embed_arr = array();

            $request = HttpRequest::get_instance();

            $embed_arr['embed_id']     = $request->get_int_parameter("embed_id");
            $embed_arr['title_ar']     = $request->get_parameter("title_ar");
            $embed_arr['title_en']     = $request->get_parameter("title_en");
            $embed_arr['desc_ar']      = TextUtil::fixText( $request->get_parameter("desc_ar") );
            $embed_arr['desc_en']      = TextUtil::fixText( $request->get_parameter("desc_en") );
            $embed_arr['icon']         = '';
            $embed_arr['file']         = '';
            $embed_arr['type']         = $request->get_int_parameter("type");
            $embed_arr['order']        = $request->get_int_parameter("order");
            $embed_arr['active']       = $request->get_int_parameter("active");
            $embed_arr['parent_type']  = $request->get_int_parameter("parent_type");
            $embed_arr['parent_id']    = $request->get_int_parameter("parent_id");
            
            $embed = (object) $embed_arr;
            
        } catch (Exception $e) {
            throw new CustomException( 'Error in : read form', $e );//from php 5.3 no need to custum
        }

        return $embed;
    }
}

?>