<?php
/*
 *
 */

/**
 * Description of LinkManage
 *
 * @author Ahmad
 */

class LinkManage extends ManageController {

    public static $dir = "links";

    public static function add_link(){

        $output_array = array();

        try {
            
            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_ADD);

            if( $permitted ){
                
                QueryUtil::connect();

                $request->set_parameter("options", (EDITABLE|REMOVABLE) );
                
                $link = self::read_link_form();

                $link->icon  = FileUtil::save_thumb("icon", "icon", ICON_PAGES_WIDTH, ICON_PAGES_HEIGHT,  $path);
                //$link->icon  = FileUtil::save_file("icon",  "icon",  $path);

                $status = LinkDB::add_link($link);
                
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

    public static function update_link(){

        $output_array = array();
        
        try {

            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();
            
            $permitted = self::check_permission(ACTION_EDIT);

            if( $permitted ){
                
                QueryUtil::connect();

                $link = self::read_link_form();

                $link_id  = $request->get_int_parameter("link_id");

                $old_link = LinkDB::get_link($link_id);
                
                $link->icon  = FileUtil::replace_thumb("icon", "icon", ICON_PAGES_WIDTH, ICON_PAGES_HEIGHT, $old_link->icon, $path);
                //$link->icon  = FileUtil::replace_file("icon",  "icon",  $path, $old_link->icon );
                
                $status = LinkDB::update_link($link);
                
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

    public static function remove_link(){

        $output_array = array();
        
        try {
            
            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_REMOVE);

            if( $permitted ){

                QueryUtil::connect();
                
                $link_id = $request->get_int_parameter("link_id");

                $link    = LinkDB::get_link($link_id);

                $status  = LinkDB::remove_link($link);

                if( $status > 0 ){
                    FileUtil::remove_file($path, $link->icon );
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

   
    public static function get_formated_array(array $links){

        $links_array_list = array();

        for ( $i=0; $i<count($links); $i++ ){

            $link = $links[$i];

            $link_object = self::get_formated_object($link);

            $links_array_list[] = $link_object;
        }

        return $links_array_list;
    }

    public static function get_formated_object($link){

        $link_object = array();

        $link_object["link_id"]      = $link->link_id;

        $link_object["title_ar"]     = $link->title_ar;
        $link_object["title_en"]     = $link->title_en;
        $link_object["desc_ar"]      = $link->desc_ar;
        $link_object["desc_en"]      = $link->desc_en;
        $link_object["icon"]         = $link->icon;
        $link_object["url_ar"]       = $link->url_ar;
        $link_object["url_en"]       = $link->url_en;
        $link_object["menu"]         = $link->menu;
        $link_object["new_window"]   = $link->new_window;
        $link_object["order"]        = $link->order;
        $link_object["active"]       = $link->active;
        $link_object["parent_id"]    = $link->parent_id;

        $menu = intval( $link->menu );

        $link_object["top_menu"]  = ( ($menu & TOP_MENU)  > 0 ) ? 1 : 0;
        $link_object["main_menu"] = ( ($menu & MAIN_MENU) > 0 ) ? 1 : 0;
        $link_object["side_menu"] = ( ($menu & SIDE_MENU) > 0 ) ? 1 : 0;
        $link_object["foot_menu"] = ( ($menu & FOOT_MENU) > 0 ) ? 1 : 0;
        
        $options = intval( $link->options );

        $link_object["new_window"] = ( ($options & NEW_WINDOW)  > 0 ) ? 1 : 0;
        
        return $link_object;
    }


    private static function read_link_form(){

        $link = new stdClass();

        try {

            $link_arr = array();

            $request = HttpRequest::get_instance();

            $link_arr['link_id']    = $request->get_int_parameter("link_id");
            $link_arr['title_ar']   = $request->get_parameter("title_ar");
            $link_arr['title_en']   = $request->get_parameter("title_en");
            $link_arr['desc_ar']    = TextUtil::fixText( $request->get_parameter("desc_ar") );
            $link_arr['desc_en']    = TextUtil::fixText( $request->get_parameter("desc_en") );
            $link_arr['icon']       = '';
            
            //$link_arr['url_ar']     = $request->get_parameter("url_ar");
            //$link_arr['url_en']     = $request->get_parameter("url_en");
            
            $url_ar  = $request->get_parameter("url_ar");
            $url_en  = $request->get_parameter("url_en");

            $url_ar = ( mb_detect_encoding($url_ar) === 'ASCII') ? urldecode($url_ar) : $url_ar ;
            $url_en = ( mb_detect_encoding($url_en) === 'ASCII') ? urldecode($url_en) : $url_en ;

            $link_arr['url_ar']     = $url_ar;
            $link_arr['url_en']     = $url_en;
            
            $link_arr['menu']       = $request->get_int_parameter("menu");
            $link_arr['options']    = $request->get_int_parameter("options");
            $link_arr['new_window'] = $request->get_int_parameter("new_window");
            $link_arr['order']      = $request->get_int_parameter("order");
            $link_arr['active']     = $request->get_int_parameter("active");
            $link_arr['parent_id']  = $request->get_int_parameter("parent_id");

            $menu    = $link_arr['menu'];
            $options = $link_arr['options'];
            
            $top_menu  = $request->get_int_parameter("top_menu");
            $main_menu = $request->get_int_parameter("main_menu");
            $side_menu = $request->get_int_parameter("side_menu");
            $foot_menu = $request->get_int_parameter("foot_menu");

            $editable  = $request->get_int_parameter("editable");
            $removable = $request->get_int_parameter("removable");
            $new_window = $request->get_int_parameter("new_window");
            
            $menu |= ( $top_menu  > 0 ) ? TOP_MENU  : 0 ;
            $menu |= ( $main_menu > 0 ) ? MAIN_MENU : 0 ;
            $menu |= ( $side_menu > 0 ) ? SIDE_MENU : 0 ;
            $menu |= ( $foot_menu > 0 ) ? FOOT_MENU : 0 ;

            $options |= ( $editable   > 0 ) ? EDITABLE  : 0 ;
            $options |= ( $removable  > 0 ) ? REMOVABLE : 0 ;
            $options |= ( $new_window > 0 ) ? NEW_WINDOW : 0 ;

            $link_arr['menu']    = $menu;
            $link_arr['options'] = $options;
            
            $link = (object) $link_arr;
            
//            $menu    = $link_arr['menu'];
//            $options = $link_arr['options'];
//
//            $top_menu  = $request->get_int_parameter("top_menu");
//            $side_menu = $request->get_int_parameter("side_menu");
//            $foot_menu = $request->get_int_parameter("foot_menu");
//
//            $menu |= ( $top_menu  > 0 ) ? TOP_MENU  : 0 ;
//            $menu |= ( $side_menu > 0 ) ? SIDE_MENU : 0 ;
//            $menu |= ( $foot_menu > 0 ) ? FOOT_MENU : 0 ;
//
//
//            $new_window = $request->get_int_parameter("new_window");
//
//            $options    = ( $new_window > 0 ) ? ($options | NEW_WINDOW) : ($options ^ NEW_WINDOW);
//
//            $link_arr['menu']    = $menu;
//            $link_arr['options'] = $options;
//            
//            $link = (object) $link_arr;
            
        } catch (Exception $e) {
            throw new CustomException( 'Error in : read form', $e );//from php 5.3 no need to custum
        }

        return $link;
    }

}

?>