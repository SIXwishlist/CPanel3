<?php
/*
 *
 */

/**
 * Description of NewsManage
 *
 * @author Ahmad
 */

class NewsManage {

    public static $dir = "news_list";

    public static function add_news(){

        $output_array = array();

        try {
            
            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();

            $permitted = AdminManage::check_admin_permission(ACTION_ADD);

            if( $status > 0 ){

                $request->set_parameter("options", (EDITABLE|REMOVABLE) );
                
                $news = self::read_news_form();

                $news->image = FileUtil::save_file("image", "image", $path);

                $status = NewsDB::add_news($news);
                
                //if( $status > 0 ){
                //    $status = SectionTreeJSON::build();
                //}
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

    public static function update_news(){

        $output_array = array();
        
        try {

            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();
            
            $permitted = AdminManage::check_admin_permission(ACTION_EDIT);

            if( $status > 0 ){

                $news = self::read_news_form();

                $news_id  = $request->get_int_parameter("news_id");

                $old_news = NewsDB::get_news($news_id);
                
                $news->image = FileUtil::replace_file("image", "image", $path, $old_news->image);
                
                $status = NewsDB::update_news($news);

                //if( $status > 0 ){
                //    $status = SectionTreeJSON::build();
                //}
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

    public static function remove_news(){

        $output_array = array();
        
        try {
            
            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();

            $permitted = AdminManage::check_admin_permission(ACTION_REMOVE);

            if( $status > 0 ){

                $news_id = $request->get_int_parameter("news_id");

                $news = NewsDB::get_news($news_id);

                $status     = NewsDB::remove_news($news);

                if( $status > 0 ){
                    FileUtil::remove_file($path, $news->image);                    
                }
               
                //if( $status > 0 ){
                //    $status = SectionTreeJSON::build();
                //}
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

    
    public static function get_news_list(){

        $output_array = array();

        try {

            $request = HttpRequest::get_instance();

            $permitted = AdminManage::check_admin_permission(ACTION_ADD);
            $active = ( $status <= 0 ) ? 1 : 0;

            //$parent_id  = $request->get_int_parameter("parent_id");

            $index = $request->get_int_parameter("index");
            $count = $request->get_int_parameter("count");

            //$parent_id  = ( $parent_id  < 0 ) ? -1 : $parent_id;

            //if no count this means unlimited
            $count = ( $count == 0 ) ? -1 : $count;

            $news_list    = NewsDB::get_news_list($active, $index, $count, '`order`', 'ASC');
            $result_count = NewsDB::get_news_list_count($active);

            $news_list_array_list = self::get_formated_array($news_list);

            $output_array["news_list"]    = $news_list_array_list;
            $output_array["result_count"] = $result_count;
            $output_array["index"]        = $index;

        } catch (Exception $e) {
            $output_array["status"] = SERVER_ERROR;
            Logger::log( $e->getMessage(), ERROR );
        }
        
        return $output_array;
    }

    
    public static function get_formated_array(array $news_list){

        $news_list_array_list = array();

        for ( $i=0; $i<count($news_list); $i++ ){

            $news = $news_list[$i];

            $news_object = self::get_formated_object($news);

            $news_list_array_list[] = $news_object;
        }

        return $news_list_array_list;
    }

    public static function get_formated_object($news){

        $news_object = array();

        $news_object["news_id"]      = $news->news_id;

        $news_object["title_ar"]     = $news->title_ar;
        $news_object["title_en"]     = $news->title_en;
        $news_object["desc_ar"]      = $news->desc_ar;
        $news_object["desc_en"]      = $news->desc_en;
        $news_object["link_ar"]      = $news->link_ar;
        $news_object["link_en"]      = $news->link_en;
        $news_object["image"]        = $news->image;
        $news_object["order"]        = $news->order;
        $news_object["active"]       = $news->active;
        
        return $news_object;
    }


    private static function read_news_form(){

        $news = new stdClass();

        try {

            $news_arr = array();

            $request = HttpRequest::get_instance();

            $news_arr['news_id']      = $request->get_int_parameter("news_id");
            $news_arr['title_ar']     = $request->get_parameter("title_ar");
            $news_arr['title_en']     = $request->get_parameter("title_en");
            $news_arr['desc_ar']      = TextUtil::fixText( $request->get_parameter("desc_ar") );
            $news_arr['desc_en']      = TextUtil::fixText( $request->get_parameter("desc_en") );
            
            $link_ar  = $request->get_parameter("link_ar");
            $link_en  = $request->get_parameter("link_en");

            $link_ar = ( mb_detect_encoding($link_ar) === 'ASCII') ? urldecode($link_ar) : $link_ar ;
            $link_en = ( mb_detect_encoding($link_en) === 'ASCII') ? urldecode($link_en) : $link_en ;

            $news_arr['link_ar']     = $link_ar;
            $news_arr['link_en']     = $link_en;
            
            //$news_arr['link_ar']      = $request->get_parameter("link_ar");
            //$news_arr['link_en']      = $request->get_parameter("link_en");
            
            $news_arr['image']        = '';
            $news_arr['order']        = $request->get_int_parameter("order");
            $news_arr['active']       = $request->get_int_parameter("active");
            
            $news = (object) $news_arr;
            
        } catch (Exception $e) {
            throw new CustomException( 'Error in : read form', $e );//from php 5.3 no need to custum
        }

        return $news;
    }

}

?>