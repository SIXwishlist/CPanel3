<?php
/*
 *
 */

/**
 * Description of SlideManage
 *
 * @author Ahmad
 */

class SlideManage extends ManageController {

    public static $dir = "slides";
    public static $dateFormat = 'Y-m-d H:i:s';

    public static function add_slide(){

        $output_array = array();

        try {

            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_ADD);

            if( $permitted ){
                
                QueryUtil::connect();

                $slide = self::read_slide_form();
                
                $slide->icon = FileUtil::save_file("icon",  "icon",  $path);

                $slide->file = self::save_slide_file($slide->type, '');

                $status = SlideDB::add_slide($slide);
                
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

    public static function update_slide(){

        $output_array = array();
        
        try {

            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();
            
            $permitted = self::check_permission(ACTION_EDIT);

            if( $permitted ){
                
                QueryUtil::connect();

                $slide = self::read_slide_form();

                $slide_id  = $request->get_int_parameter("slide_id");

                $old_slide = SlideDB::get_slide($slide_id);

                $slide->file = self::save_slide_file($slide->type, $old_slide->file, $old_slide->type);

                $status = SlideDB::update_slide($slide);

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

    public static function remove_slide(){

        $output_array = array();
        
        try {
            
            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_REMOVE);

            if( $permitted ){
                
                QueryUtil::connect();

                $slide_id = $request->get_int_parameter("slide_id");

                $slide    = SlideDB::get_slide($slide_id);

                $status   = SlideDB::remove_slide($slide);

                if( $status > 0 ){
                    $status = FileUtil::remove_file($path, $slide->icon );
                    $status = self::remove_slide_file($slide->type, $slide->file);
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


    public static function get_slides(){

        $output_array = array();

        try {

            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_ADD);

            if( $permitted ){

                QueryUtil::connect();
                
                $parent_type = $request->get_int_parameter("parent_type");
                $parent_id   = $request->get_int_parameter("parent_id");

                $index = $request->get_int_parameter("index");
                $count = $request->get_int_parameter("count");

                $parent_type = ( $parent_type <= 0 ) ? -1 : $parent_type;
                $parent_id   = ( $parent_id   <= 0 ) ? -1 : $parent_id;

                $active = -1;
                
                //if no count this means unlimited
                $count = ( $count == 0 ) ? -1 : $count;

                $slides       = SlideDB::get_slides($parent_type, $parent_id, $active, $index, $count);
                $slides_count = SlideDB::get_slides_count($parent_type, $parent_id, $active);

                $slides_array_list = self::get_formated_array($slides);

                $output_array["slides"]       = $slides_array_list;
                $output_array["slides_count"] = $slides_count;
                $output_array["index"]        = $index;

                $status = SUCCESS;
                
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

    public static function search_slides(){

        $output_array = array();

        try {

            $request = HttpRequest::get_instance();
            $session = HttpSession::get_instance();

            $slides_array_list = array();
            $result_count    = 0;

            $permitted = self::check_permission(ACTION_VIEW_ALL);

            if( $permitted ){

                QueryUtil::connect();

                $slide_params = array();

                $slide_params["slide_id"] = $request->get_int_parameter("slide_id");
                $slide_params["title"]    = $request->get_parameter("title");

                $index = $request->get_int_parameter("index");
                $count = $request->get_int_parameter("count");

                //if no count this means unlimited
                $count = ( $count == 0 ) ? -1 : $count;

                $slides       = SlideDB::search_slides($slide_params, $index, $count, '`slide_id`', 'ASC');
                $result_count = SlideDB::search_slides_count($slide_params);

                $slides_array_list = self::get_formated_array($slides);

                $output_array["slides"]       = $slides_array_list;
                $output_array["result_count"] = $result_count;

                QueryUtil::close();

            }

            $output_array["status"]       = $permitted;

        } catch (Exception $e) {
            $output_array["status"] = SERVER_ERROR;
            Logger::log( $e->getMessage(), ERROR );
        }
        
        return $output_array;
    }


    private static function save_slide_file($type, $old_file, $old_type=-1){

        $file = '';

        try {
            
            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();

            switch( $type ){

                case FILE_TYPE_IMAGE:
                case FILE_TYPE_FLASH:
                    $file = FileUtil::save_file("file", "file", $path);
                    break;

                case FILE_TYPE_VIDEO:
                    $postfix = date("U") . "_" . mt_rand(0, 1000);
                    $file    = "file_" . $postfix;

                    $file1 = FileUtil::save_file("file1", $file.".mp4",  $path, false);
                    $file2 = FileUtil::save_file("file2", $file.".ogv",  $path, false);
                    $file3 = FileUtil::save_file("file3", $file.".webm", $path, false);
                    break;

                case FILE_TYPE_YOUTUBE:
                    $url   = $request->get_parameter("file");
                    $file  = FileUtil::get_youtube_id($url);
                    break;

                case FILE_TYPE_VIMEO:
                    $url   = $request->get_parameter("file");
                    $file  = FileUtil::get_vimeo_id($url);
                    break;

            }
            

            $file_changed = false;
            
            if( $type == FILE_TYPE_VIDEO ){
                if(  !empty($file1) || !empty($file2) || !empty($file3)  ) {
                    $file_changed = true;
                }
            }else{
                if(  !empty($file)  ) {
                    $file_changed = true;
                }
            }
            
            if( $file_changed ){

                switch( $old_type ){

                    case FILE_TYPE_IMAGE:
                    case FILE_TYPE_FLASH:
                        FileUtil::remove_file($path, $old_file);
                        break;

                    case FILE_TYPE_VIDEO:
                        FileUtil::remove_file($path, $old_file.".mp4" );
                        FileUtil::remove_file($path, $old_file.".ogv" );
                        FileUtil::remove_file($path, $old_file.".webm");
                        break;
                }

            } else {
                $file = $old_file;
            }
            
        } catch (Exception $e) {
            throw new CustomException( 'Error in : get file', $e );//from php 5.3 no need to custum
        }
       
        return $file;
    }
    
    private static function remove_slide_file($type, $file){

        $status = 0;

        try {
            
            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();

            switch( $type ){

                case FILE_TYPE_IMAGE:
                case FILE_TYPE_FLASH:
                    $status = FileUtil::remove_file($path, $file);
                    $status = 1;
                    break;

                case FILE_TYPE_VIDEO:
                    $status = FileUtil::remove_file($path, $file.".mp4" );
                    $status = FileUtil::remove_file($path, $file.".ogv" );
                    $status = FileUtil::remove_file($path, $file.".webm");
                    $status = 1;
                    break;
            }

            
        } catch (Exception $e) {
            throw new CustomException( 'Error in : removing file', $e );//from php 5.3 no need to custum
        }
       
        return $status;
    }


    private static function get_formated_array(array $slides){

        $slides_array_list = array();

        for ( $i=0; $i<count($slides); $i++ ){

            $slide = $slides[$i];

            $slide_object = self::get_formated_object($slide);

            $slides_array_list[] = $slide_object;
        }

        return $slides_array_list;
    }

    private static function get_formated_object($slide){

        $slide_object = array();
                
        $slide_object["slide_id"]   = $slide->slide_id;

        $slide_object['title_ar']    = $slide->title_ar;
        $slide_object['title_en']    = $slide->title_en;
        $slide_object['desc_ar']     = $slide->desc_ar;
        $slide_object['desc_en']     = $slide->desc_en;
        $slide_object["icon"]        = $slide->icon;
        $slide_object["file"]        = $slide->file;
        $slide_object["type"]        = $slide->type;
        $slide_object["link_ar"]     = $slide->link_ar;
        $slide_object["link_en"]     = $slide->link_en;
        $slide_object["active"]      = $slide->active;
        $slide_object["order"]       = $slide->order;
        $slide_object["parent_type"] = $slide->parent_type;
        $slide_object["parent_id"]   = $slide->parent_id;

        return $slide_object;
    }


    private static function read_slide_form(){

        $slide = new stdClass();

        try {

            $slide_arr = array();
            
            $request = HttpRequest::get_instance();
            
            $slide_arr['slide_id']   = $request->get_int_parameter("slide_id");
            $slide_arr['title_ar']    = $request->get_parameter("title_ar");
            $slide_arr['title_en']    = $request->get_parameter("title_en");
            $slide_arr['desc_ar']     = TextUtil::fixText( $request->get_parameter("desc_ar") );
            $slide_arr['desc_en']     = TextUtil::fixText( $request->get_parameter("desc_en") );
            $slide_arr['file']        = '';
            $slide_arr['type']        = $request->get_int_parameter("type");
            
            $link_ar  = $request->get_parameter("link_ar");
            $link_en  = $request->get_parameter("link_en");

            $link_ar = ( mb_detect_encoding($link_ar) === 'ASCII') ? urldecode($link_ar) : $link_ar ;
            $link_en = ( mb_detect_encoding($link_en) === 'ASCII') ? urldecode($link_en) : $link_en ;

            $slide_arr['link_ar']     = $link_ar;
            $slide_arr['link_en']     = $link_en;
            
            $slide_arr['active']      = $request->get_int_parameter("active");
            $slide_arr['order']       = $request->get_int_parameter("order");
            $slide_arr['parent_type'] = $request->get_int_parameter("parent_type");
            $slide_arr['parent_id']   = $request->get_int_parameter("parent_id");

            $slide = (object) $slide_arr;
            
        } catch (Exception $e) {
            throw new CustomException( 'Error in : read form', $e );//from php 5.3 no need to custum
        }

        return $slide;
    }

}

?>