<?php
/*
 *
 */

/**
 * Description of AdManage
 *
 * @author Ahmad
 */

class AdManage extends ManageController {

    public static $dir = "ads";

    public static function add_ad(){

        $output_array = array();

        try {
            
            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_ADD);

            if( $permitted ){

                QueryUtil::connect();
                
                $ad = self::read_ad_form();

                $ad->file = self::get_ad_file($ad->type, '');

                $status = AdDB::add_ad($ad);
                
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

    public static function update_ad(){

        $output_array = array();
        
        try {

            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();
            
            $permitted = self::check_permission(ACTION_EDIT);

            if( $permitted ){
                
                QueryUtil::connect();

                $ad = self::read_ad_form();

                $ad_id  = $request->get_int_parameter("ad_id");

                $old_ad = AdDB::get_ad($ad_id);
                
                $ad->file = self::get_ad_file($ad->type, $old_ad->file, $old_ad->type);
                                
                $status = AdDB::update_ad($ad);
                
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

    public static function remove_ad(){

        $output_array = array();
        
        try {
            
            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_REMOVE);

            if( $permitted ){
                
                QueryUtil::connect();

                $ad_id  = $request->get_int_parameter("ad_id");

                $ad     = AdDB::get_ad($ad_id);

                $status = AdDB::remove_ad($ad);

                if( $status > 0 ){
                    $status = self::remove_ad_file($ad->type, $ad->file);
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

    public static function get_ads(){

        $output_array = array();

        try {

            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_VIEW_ALL);

            if( $permitted ){

                QueryUtil::connect();
                
                $index = $request->get_int_parameter("index");
                $count = $request->get_int_parameter("count");

                //if no count this means unlimited
                $count = ( $count == 0 ) ? -1 : $count;

                $ads       = AdDB::get_ads(-1, $index, $count);
                $ads_count = AdDB::get_ads_count(-1);

                $ads_array_list = self::get_formated_array($ads);

                $output_array["ads"]       = $ads_array_list;
                $output_array["ads_count"] = $ads_count;
    
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

    public static function search_ads(){

        $output_array = array();

        try {

            $request = HttpRequest::get_instance();
            $session = HttpSession::get_instance();

            $ads_array_list = array();
            $result_count    = 0;

            $permitted = self::check_permission(ACTION_VIEW_ALL);

            if( $permitted ){

                QueryUtil::connect();

                $ad_params = array();

                $ad_params["ad_id"] = $request->get_int_parameter("ad_id");

                $index = $request->get_int_parameter("index");
                $count = $request->get_int_parameter("count");

                //if no count this means unlimited
                $count = ( $count == 0 ) ? -1 : $count;

                $ads       = AdDB::search_ads($ad_params, $index, $count, '`ad_id`', 'ASC');
                $ads_count = AdDB::search_ads_count($ad_params);

                $ads_array_list = self::get_formated_array($ads);

                $output_array["ads"]       = $ads_array_list;
                $output_array["ads_count"] = $ads_count;

                QueryUtil::close();

            }

            $output_array["status"]       = $permitted;

        } catch (Exception $e) {
            $output_array["status"] = SERVER_ERROR;
            Logger::log( $e->getMessage(), ERROR );
        }
        
        return $output_array;
    }

    
    private static function get_ad_file($type, $old_file, $old_type=-1){

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

                case FILE_TYPE_EMBED_CODE:
                    $file  = $request->get_parameter("file");
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

    private static function remove_ad_file($type, $file){

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
    
    private static function get_formated_array(array $ads){

        $ads_array_list = array();

        for ( $i=0; $i<count($ads); $i++ ){

            $ad = $ads[$i];

            $ad_object = self::get_formated_object($ad);

            $ads_array_list[] = $ad_object;
        }

        return $ads_array_list;
    }

    private static function get_formated_object($ad){

        $ad_object = array();

        $ad_object["ad_id"]  = $ad->ad_id;

        $ad_object["file"]   = $ad->file;
        $ad_object["type"]   = $ad->type;
        $ad_object["link"]   = $ad->link;
        $ad_object["width"]  = $ad->width;
        $ad_object["height"] = $ad->height;
        $ad_object["active"] = $ad->active;
        $ad_object["order"]  = $ad->order;

        return $ad_object;
    }


    private static function read_ad_form(){

        $ad = new stdClass();

        try {

            $ad_arr = array();

            $request = HttpRequest::get_instance();
            
            $ad_arr['ad_id']    = $request->get_int_parameter("ad_id");
            $ad_arr['file']     = '';
            $ad_arr['type']     = $request->get_int_parameter("type");
            
            $link  = $request->get_parameter("link");
            $link  = ( mb_detect_encoding($link) === 'ASCII') ? urldecode($link) : $link ;
            $ad_arr['link']     = $link;
            
            //$ad_arr['link']     = $request->get_parameter("link");
            
            $ad_arr['width']    = $request->get_int_parameter("width");
            $ad_arr['height']   = $request->get_int_parameter("height");
            $ad_arr['active']   = $request->get_int_parameter("active");
            $ad_arr['order']    = $request->get_int_parameter("order");
            
            $ad = (object) $ad_arr;

        } catch (Exception $e) {
            throw new CustomException( 'Error in : read form', $e );//from php 5.3 no need to custum
        }

        return $ad;
    }

}

?>