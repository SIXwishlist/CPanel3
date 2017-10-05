<?php
/*
 *
 */

/**
 * Description of ShotManage
 *
 * @author Ahmad
 */

class ShotManage extends ManageController {

    public static $dir = "shots";

    public static function add_shot(){

        $output_array = array();

        try {
            
            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_ADD);

            if( $permitted ){
                
                QueryUtil::connect();
                
                $shot = self::read_shot_form();

                $status = ShotDB::add_shot($shot);

                if( $status > 0 ){
                    
                    $shot->shot_id = QueryUtil::get_last_insert_id();

                    $shot->icon  = self::get_shot_icon($shot->shot_id, '');
                    $shot->file  = self::get_shot_file($shot->shot_id, $shot->type, '', -1);

                    $status = ShotDB::update_shot($shot);

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

    public static function update_shot(){

        $output_array = array();
        
        try {

            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();
            
            $permitted = self::check_permission(ACTION_EDIT);

            if( $permitted ){

                QueryUtil::connect();
                
                $shot = self::read_shot_form();

                $shot_id  = $request->get_int_parameter("shot_id");

                $old_shot = ShotDB::get_shot($shot_id);
                
                //$shot->icon  = FileUtil::replace_thumb("icon", "icon", ICON_PAGES_WIDTH, ICON_PAGES_HEIGHT, $old_shot->icon, $path);
                $shot->icon = self::get_shot_icon($shot->shot_id, $old_shot->icon);
                $shot->file = self::get_shot_file($shot->shot_id, $shot->type, $old_shot->file, $old_shot->type);
                
                $status = ShotDB::update_shot($shot);

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

    public static function remove_shot(){

        $output_array = array();
        
        try {
            
            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_REMOVE);

            if( $permitted ){

                QueryUtil::connect();
                
                $shot_id = $request->get_int_parameter("shot_id");

                $shot    = ShotDB::get_shot($shot_id);

                $status   = ShotDB::remove_shot($shot);

                if( $status > 0 ){
                    $status = FileUtil::remove_file($path, $shot->icon );
                    $status = self::remove_shot_file($shot->type, $shot->file);
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


    public static function get_shots(){

        $output_array = array();

        try {

            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_VIEW_ALL);
            
            if( $permitted ){

                QueryUtil::connect();
                
                $parent_id = $request->get_int_parameter("parent_id");

                $index = $request->get_int_parameter("index");
                $count = $request->get_int_parameter("count");

                $parent_id = ($parent_id > 0 ) ? $parent_id : -1;

                //if no count this means unlimited
                $count = ( $count == 0 ) ? -1 : $count;

                $shots        = ShotDB::get_shots($parent_id, -1, $index, $count);
                $result_count = ShotDB::get_shots_count($parent_id, -1);

                $shots_array_list = self::get_formated_array($shots);

                $output_array["shots"]       = $shots_array_list;
                $output_array["shots_count"] = $result_count;

                $status = intval($permitted);

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
    
    public static function search_shots(){

        $output_array = array();

        try {

            $request = HttpRequest::get_instance();
            $session = HttpSession::get_instance();

            $shots_array_list = array();
            $result_count        = 0;

            $permitted = self::check_permission(ACTION_VIEW_ALL);

            if( $permitted ){

                QueryUtil::connect();
                
                $shot_params = array();

                $shot_params["shot_id"] = $request->get_int_parameter("shot_id");
                $shot_params["title"]   = $request->get_parameter("title");
                $shot_params["status"]  = $request->get_int_parameter("status");
                $shot_params["level"]   = $request->get_int_parameter("level");

                $index = $request->get_int_parameter("index");
                $count = $request->get_int_parameter("count");

                //if no count this means unlimited
                $count = ( $count == 0 ) ? -1 : $count;

                $shots        = ShotDB::search_shots($shot_params, $index, $count, '`shot_id`', 'ASC');
                $result_count = ShotDB::search_shots_count($shot_params);

                $shots_array_list = self::get_formated_array($shots);
            
                $output_array["shots"]     = $shots_array_list;
                $output_array["result_count"] = $result_count;
                
                $status = intval($permitted);

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

    
    public static function import_shots(){

        $output_array = array();

        try {

            $path = UPLOAD_DIR . self::$dir;

            $session = HttpSession::get_instance();
            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_EDIT);
            //$permitted = self::check_user_permission(ACTION_ADD);

            if( $permitted ){

                $file = FileUtil::save_file("file", "file", $path);

                $shots_array_list = ExcelUtil::import_from_excel( $path . '/' . $file );

                unset( $shots_array_list[0] );

                FileUtil::remove_file($path, $file);

                $shot_records = array();

                foreach ($shots_array_list as $row) {

                    $shot = new stdClass();

                    $shot->name = $row[0];
                    $shot->name = $row[0];
                    $shot->name = $row[0];
                    $shot->name = $row[0];
                    $shot->name = $row[0];
                    $shot->name = $row[0];
                    $shot->name = $row[0];
                    $shot->name = $row[0];
                    $shot->name = $row[0];
                    $shot->name = $row[0];

                    $shot_records[] = $shot;

                }

                $status = ShotDB::add_shots_list($shot_records);

                $affected_rows = QueryUtil::get_affected_rows();

                if( $affected_rows == count($shot_records) ){
                    $status = SUCCESS;
                }

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

    public static function export_shots(){

        $output_array = array();

        try {

            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();
            $session = HttpSession::get_instance();

            $permitted = self::check_permission(ACTION_VIEW_ALL);
            //$permitted = self::check_user_permission(ACTION_VIEW_ALL);

            if( $permitted ){

                $shot_params = array();

                $shot_params["shot_id"]     = $request->get_int_parameter("shot_id");
                $shot_params["name"]        = $request->get_parameter("name");
                $shot_params["all_results"] = true;

                $index = $request->get_int_parameter("index");
                $count = $request->get_int_parameter("count");

                //if no count this means unlimited
                $count = ( $count == 0 ) ? -1 : $count;

                $shots            = ShotDB::search_shots($shot_params, $index, $count, '`shot_id`', 'ASC');

                $shots_array_list = self::get_formated_array($shots);

                $shots_headers = array();
                $shots_data    = array();

                if( count($shots_array_list) > 0 ){

                    $shots_headers = array_keys($shots_array_list[0]);

                    foreach ($shots_array_list as $row) {
                        $shots_data[] = array_values($row);
                    }
                }

                $status = ExcelUtil::export_to_excel( $shots_data, $shots_headers, "shots.xls" );


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

    public static function export_shots_sample(){

        $output_array = array();

        try {

            $path = UPLOAD_DIR . self::$dir;

            $session = HttpSession::get_instance();
            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_VIEW_ALL);
            //$permitted = self::check_user_permission(ACTION_ADD);

            if( $permitted ){

                $shots_headers = array( "title_ar", "title_en", "desc_ar", "desc_en", "content_ar", "content_en", "keys_ar", "keys_en", "icon", "image", "style", "menu", "options", "order", "active", "parent_id" );
                $shots_data    = array();

                $status = ExcelUtil::export_to_excel( $shots_data, $shots_headers, "shots.xls" );

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



    private static function get_shot_icon($id, $old_icon){

        $icon = '';

        try {
            
            $path    = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();

            $uploaded_icon = $request->get_file("icon");

            if(  !empty($uploaded_icon)  ) {
                
                if(  empty($old_icon)  ) {
                    $temp_icon = FileUtil::save_thumb("icon", "icon", ICON_MEDIA_WIDTH, ICON_MEDIA_HEIGHT, $path);
                }  else {
                    $temp_icon = FileUtil::replace_thumb("icon", "icon", ICON_MEDIA_WIDTH, ICON_MEDIA_HEIGHT, $old_icon, $path);
                }

                $ext  = FileUtil::get_file_ext($uploaded_icon->name);
                $icon = "icon_" . $id . $ext;
                FileUtil::rename_file($path, $temp_icon, $icon);

            }

            if( empty($icon) ){
                $icon = $old_icon;
            }
            
        } catch (Exception $e) {
            throw new CustomException( 'Error in : get icon', $e );//from php 5.3 no need to custum
        }
       
        return $icon;
    }

    private static function get_shot_file($id, $type, $old_file, $old_type=-1){

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
    
    private static function remove_shot_file($type, $file){

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


    public static function get_formated_array(array $shots){

        $shots_array_list = array();

        for ( $i=0; $i<count($shots); $i++ ){

            $shot = $shots[$i];

            $shot_object = self::get_formated_object($shot);

            $shots_array_list[] = $shot_object;
        }

        return $shots_array_list;
    }

    public static function get_formated_object($shot){

        $shot_object = array();

        $shot_object["shot_id"]     = $shot->shot_id;
        
        $shot_object["icon"]        = $shot->icon;
        $shot_object["file"]        = $shot->file;
        $shot_object["type"]        = $shot->type;
        $shot_object["order"]       = $shot->order;
        $shot_object["active"]      = $shot->active;
        $shot_object["parent_type"] = $shot->parent_type;
        $shot_object["parent_id"]   = $shot->parent_id;

        return $shot_object;
    }


    private static function read_shot_form(){

        $shot = new stdClass();

        try {

            $shot_arr = array();

            $request = HttpRequest::get_instance();

            $shot_arr['shot_id']     = $request->get_int_parameter("shot_id");
            $shot_arr['icon']         = '';
            $shot_arr['file']         = '';
            $shot_arr['type']         = $request->get_int_parameter("type");
            $shot_arr['order']        = $request->get_int_parameter("order");
            $shot_arr['active']       = $request->get_int_parameter("active");
            $shot_arr['parent_type']  = $request->get_int_parameter("parent_type");
            $shot_arr['parent_id']    = $request->get_int_parameter("parent_id");
            
            $shot = (object) $shot_arr;
            
        } catch (Exception $e) {
            throw new CustomException( 'Error in : read form', $e );//from php 5.3 no need to custum
        }

        return $shot;
    }

}

?>
