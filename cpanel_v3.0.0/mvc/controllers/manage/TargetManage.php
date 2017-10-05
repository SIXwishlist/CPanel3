<?php
/*
 *
 */

/**
 * Description of TargetManage
 *
 * @author Ahmad
 */

class TargetManage extends ManageController {

    public static $dir = "targets";

    public static function add_target(){

        $output_array = array();

        try {
            
            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_ADD);

            if( $permitted ){
                
                QueryUtil::connect();

                $request->set_parameter("options", (EDITABLE|REMOVABLE) );
                
                $target = self::read_target_form();

                $target->icon  = FileUtil::save_thumb("icon", "icon", ICON_PAGES_WIDTH, ICON_PAGES_HEIGHT,  $path);
                //$target->icon  = FileUtil::save_file("icon",  "icon",  $path);
                $target->image = FileUtil::save_file("image", "image", $path);

                $status = TargetDB::add_target($target);
                
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

    public static function update_target(){

        $output_array = array();
        
        try {

            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();
            
            $permitted = self::check_permission(ACTION_EDIT);

            if( $permitted ){

                QueryUtil::connect();
                
                $target = self::read_target_form();

                $target_id  = $request->get_int_parameter("target_id");

                $old_target = TargetDB::get_target($target_id);
                
                $target->icon  = FileUtil::replace_thumb("icon", "icon", ICON_PAGES_WIDTH, ICON_PAGES_HEIGHT, $old_target->icon, $path);
                //$target->icon  = FileUtil::replace_file("icon",  "icon",  $path, $old_target->icon );
                $target->image = FileUtil::replace_file("image", "image", $path, $old_target->image);
                
                $status = TargetDB::update_target($target);
                
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

    public static function remove_target(){

        $output_array = array();
        
        try {
            
            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_REMOVE);

            if( $permitted ){

                QueryUtil::connect();
                
                $target_id = $request->get_int_parameter("target_id");

                $target    = TargetDB::get_target($target_id);

                $status     = TargetDB::remove_target($target);

                if( $status > 0 ){
                    FileUtil::remove_file($path, $target->icon );
                    FileUtil::remove_file($path, $target->image);                    
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


    public static function get_formated_array(array $targets){

        $targets_array_list = array();

        for ( $i=0; $i<count($targets); $i++ ){

            $target = $targets[$i];

            $target_object = self::get_formated_object($target);

            $targets_array_list[] = $target_object;
        }

        return $targets_array_list;
    }

    public static function get_formated_object($target){

        $target_object = array();

        $target_object["target_id"]    = $target->target_id;

        $target_object["title_ar"]     = $target->title_ar;
        $target_object["title_en"]     = $target->title_en;
        $target_object["keys_ar"]      = $target->keys_ar;
        $target_object["keys_en"]      = $target->keys_en;
        $target_object["desc_ar"]      = $target->desc_ar;
        $target_object["desc_en"]      = $target->desc_en;
        $target_object["content_ar"]   = $target->content_ar;
        $target_object["content_en"]   = $target->content_en;
        $target_object["icon"]         = $target->icon;
        $target_object["image"]        = $target->image;
        $target_object["format"]       = $target->format;
        $target_object["menu"]         = $target->menu;
        $target_object["order"]        = $target->order;
        $target_object["active"]       = $target->active;
        $target_object["parent_id"]    = $target->parent_id;

        $menu    = intval( $target->menu );

        $target_object["top_menu"]  = ( ($menu & TOP_MENU)  > 0 ) ? 1 : 0;
        $target_object["main_menu"] = ( ($menu & MAIN_MENU) > 0 ) ? 1 : 0;
        $target_object["side_menu"] = ( ($menu & SIDE_MENU) > 0 ) ? 1 : 0;
        $target_object["foot_menu"] = ( ($menu & FOOT_MENU) > 0 ) ? 1 : 0;
        
        $options = intval( $target->options );

        $target_object["sticky"]  = ( ($options & STICKY)  > 0 ) ? 1 : 0;
        
        return $target_object;
    }


    private static function read_target_form(){

        $target = new stdClass();

        try {

            $target_arr = array();

            $request = HttpRequest::get_instance();

            $target_arr['target_id']    = $request->get_int_parameter("target_id");
            $target_arr['title_ar']     = $request->get_parameter("title_ar");
            $target_arr['title_en']     = $request->get_parameter("title_en");
            $target_arr['desc_ar']      = TextUtil::fixText( $request->get_parameter("desc_ar") );
            $target_arr['desc_en']      = TextUtil::fixText( $request->get_parameter("desc_en") );
            $target_arr['content_ar']   = TextUtil::fixText( $request->get_parameter("content_ar") );
            $target_arr['content_en']   = TextUtil::fixText( $request->get_parameter("content_en") );
            $target_arr['keys_ar']      = $request->get_parameter("keys_ar");
            $target_arr['keys_en']      = $request->get_parameter("keys_en");
            $target_arr['icon']         = '';
            $target_arr['image']        = '';
            $target_arr['format']       = $request->get_int_parameter("format");
            $target_arr['menu']         = $request->get_int_parameter("menu");
            $target_arr['options']      = $request->get_int_parameter("options");
            $target_arr['order']        = $request->get_int_parameter("order");
            $target_arr['active']       = $request->get_int_parameter("active");
            $target_arr['parent_id']    = $request->get_int_parameter("parent_id");
            
            $menu    = $target_arr['menu'];
            $options = $target_arr['options'];
            
            $top_menu  = $request->get_int_parameter("top_menu");
            $main_menu = $request->get_int_parameter("main_menu");
            $side_menu = $request->get_int_parameter("side_menu");
            $foot_menu = $request->get_int_parameter("foot_menu");

            $editable  = $request->get_int_parameter("editable");
            $removable = $request->get_int_parameter("removable");

            $sticky    = $request->get_int_parameter("sticky");
            
            $menu |= ( $top_menu  > 0 ) ? TOP_MENU  : 0 ;
            $menu |= ( $main_menu > 0 ) ? MAIN_MENU : 0 ;
            $menu |= ( $side_menu > 0 ) ? SIDE_MENU : 0 ;
            $menu |= ( $foot_menu > 0 ) ? FOOT_MENU : 0 ;

            $options |= ( $editable   > 0 ) ? EDITABLE  : 0 ;
            $options |= ( $removable  > 0 ) ? REMOVABLE : 0 ;
            
            $options |= ( $sticky  > 0 ) ? STICKY : 0 ;

            $target_arr['menu']    = $menu;
            $target_arr['options'] = $options;
            
            $target = (object) $target_arr;
            
        } catch (Exception $e) {
            throw new CustomException( 'Error in : read form', $e );//from php 5.3 no need to custum
        }

        return $target;
    }

}

?>