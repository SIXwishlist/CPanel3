<?php
/*
 *
 */

/**
 * Description of SectionManage
 *
 * @author Ahmad
 */

class SectionManage extends ManageController {

    public static $dir = "sections";

    public static function add_section(){

        $output_array = array();

        try {
            
            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_ADD);

            if( $permitted ){

                QueryUtil::connect();
                
                $request->set_parameter("options", (EDITABLE|REMOVABLE) );
                
                $section = self::read_section_form();

                $section->icon  = FileUtil::save_thumb("icon", "icon", ICON_PAGES_WIDTH, ICON_PAGES_HEIGHT,  $path);
                //$section->icon  = FileUtil::save_file("icon",  "icon",  $path);
                $section->image = FileUtil::save_file("image", "image", $path);

                $status = SectionDB::add_section($section);
                
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

    public static function update_section(){

        $output_array = array();
        
        try {

            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();
            
            $permitted = self::check_permission(ACTION_EDIT);

            if( $permitted ){
                
                QueryUtil::connect();

                $section = self::read_section_form();

                $section_id  = $request->get_int_parameter("section_id");

                $old_section = SectionDB::get_section($section_id);
                
                $section->icon  = FileUtil::replace_thumb("icon", "icon", ICON_PAGES_WIDTH, ICON_PAGES_HEIGHT, $old_section->icon, $path);
                //$section->icon  = FileUtil::replace_file("icon",  "icon",  $path, $old_section->icon );
                $section->image = FileUtil::replace_file("image", "image", $path, $old_section->image);
                
                $status = SectionDB::update_section($section);
                
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

    public static function remove_section(){

        $output_array = array();
        
        try {
            
            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_REMOVE);

            if( $permitted ){

                QueryUtil::connect();
                
                $section_id = $request->get_int_parameter("section_id");

                $section    = SectionDB::get_section($section_id);

                $status     = SectionDB::remove_section($section);

                if( $status > 0 ){
                    FileUtil::remove_file($path, $section->icon );
                    FileUtil::remove_file($path, $section->image);                    
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


    private static function read_section_form(){

        $section = new stdClass();

        try {

            $section_arr = array();

            $request = HttpRequest::get_instance();

            $section_arr['section_id']   = $request->get_int_parameter("section_id");
            $section_arr['title_ar']     = $request->get_parameter("title_ar");
            $section_arr['title_en']     = $request->get_parameter("title_en");
            $section_arr['desc_ar']      = TextUtil::fixText( $request->get_parameter("desc_ar") );
            $section_arr['desc_en']      = TextUtil::fixText( $request->get_parameter("desc_en") );
            $section_arr['content_ar']   = TextUtil::fixText( $request->get_parameter("content_ar") );
            $section_arr['content_en']   = TextUtil::fixText( $request->get_parameter("content_en") );
            $section_arr['keys_ar']      = $request->get_parameter("keys_ar");
            $section_arr['keys_en']      = $request->get_parameter("keys_en");
            $section_arr['icon']         = '';
            $section_arr['image']        = '';
            $section_arr['format']       = $request->get_int_parameter("format");
            $section_arr['menu']         = $request->get_int_parameter("menu");
            $section_arr['options']      = $request->get_int_parameter("options");
            $section_arr['order']        = $request->get_int_parameter("order");
            $section_arr['active']       = $request->get_int_parameter("active");
            $section_arr['parent_id']    = $request->get_int_parameter("parent_id");

            $menu    = $section_arr['menu'];
            $options = $section_arr['options'];
            
            $top_menu  = $request->get_int_parameter("top_menu");
            $main_menu = $request->get_int_parameter("main_menu");
            $side_menu = $request->get_int_parameter("side_menu");
            $foot_menu = $request->get_int_parameter("foot_menu");

            $editable  = $request->get_int_parameter("editable");
            $removable = $request->get_int_parameter("removable");
            $show_menu = $request->get_int_parameter("show_menu");
            $show_text = $request->get_int_parameter("show_text");

            $sitemap_exclude = $request->get_int_parameter("sitemap_exclude");
            $special         = $request->get_int_parameter("special");
            
            $menu |= ( $top_menu  > 0 ) ? TOP_MENU  : 0 ;
            $menu |= ( $main_menu > 0 ) ? MAIN_MENU : 0 ;
            $menu |= ( $side_menu > 0 ) ? SIDE_MENU : 0 ;
            $menu |= ( $foot_menu > 0 ) ? FOOT_MENU : 0 ;

            $options |= ( $editable   > 0 ) ? EDITABLE  : 0 ;
            $options |= ( $removable  > 0 ) ? REMOVABLE : 0 ;
            $options |= ( $show_menu  > 0 ) ? SHOW_MENU : 0 ;
            $options |= ( $show_text  > 0 ) ? SHOW_TEXT : 0 ;

            $options |= ( $sitemap_exclude > 0 ) ? SITEMAP_EXCLUDE : 0 ;
            $options |= ( $special         > 0 ) ? SPECIAL         : 0 ;

            $section_arr['menu']    = $menu;
            $section_arr['options'] = $options;
            
            $section = (object) $section_arr;
            
        } catch (Exception $e) {
            $output_array["status"] = SERVER_ERROR;
            throw new CustomException( 'Error in : read form', $e );//from php 5.3 no need to custum
        }

        return $section;
    }


    public static function get_formated_array(array $sections){

        $sections_array_list = array();

        for ( $i=0; $i<count($sections); $i++ ){

            $section = $sections[$i];

            $sectionObject = self::get_formated_object($section);

            $sections_array_list[] = $sectionObject;
        }

        return $sections_array_list;
    }

    private static function get_formated_object($section){

        $section_object = array();

        $section_object["section_id"] = $section->section_id;

        $section_object["title_ar"]   = $section->title_ar;
        $section_object["title_en"]   = $section->title_en;
        $section_object["keys_ar"]    = $section->keys_ar;
        $section_object["keys_en"]    = $section->keys_en;
        $section_object["desc_ar"]    = $section->desc_ar;
        $section_object["desc_en"]    = $section->desc_en;
        $section_object["content_ar"] = $section->content_ar;
        $section_object["content_en"] = $section->content_en;
        $section_object["icon"]       = $section->icon;
        $section_object["image"]      = $section->image;
        $section_object["format"]     = $section->format;
        $section_object["menu"]       = $section->menu;
        $section_object["order"]      = $section->order;
        $section_object["active"]     = $section->active;
        $section_object["parent_id"]  = $section->parent_id;

        $menu = intval( $section->menu );

        $section_object["top_menu"]  = ( ($menu & TOP_MENU)  > 0 ) ? 1 : 0;
        $section_object["side_menu"] = ( ($menu & SIDE_MENU) > 0 ) ? 1 : 0;
        $section_object["main_menu"] = ( ($menu & MAIN_MENU) > 0 ) ? 1 : 0;
        $section_object["foot_menu"] = ( ($menu & FOOT_MENU) > 0 ) ? 1 : 0;

        $options = intval( $section->options );

        $section_object["show_menu"] = ( ($options & SHOW_MENU) > 0 ) ? 1 : 0;
        $section_object["show_text"] = ( ($options & SHOW_TEXT) > 0 ) ? 1 : 0;

        $section_object["sitemap_exclude"] = ( ($options & SITEMAP_EXCLUDE) > 0 ) ? 1 : 0;
        $section_object["special"]         = ( ($options & SPECIAL        ) > 0 ) ? 1 : 0;

        return $section_object;
    }

}

?>