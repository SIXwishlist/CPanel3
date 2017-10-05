<?php
/*
 *
 */

/**
 * Description of CategoryManage
 *
 * @author Ahmad
 */

class CategoryManage extends ManageController {

    public static $dir = "categories";

    public static function add_category(){

        $output_array = array();

        try {
            
            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_ADD);

            if( $permitted ){
                
                QueryUtil::connect();

                $request->set_parameter("options", (EDITABLE|REMOVABLE) );
                
                $category = self::read_category_form();

                $category->icon  = FileUtil::save_thumb("icon", "icon", ICON_PAGES_WIDTH, ICON_PAGES_HEIGHT,  $path);
                //$category->icon  = FileUtil::save_file("icon",  "icon",  $path);
                $category->image = FileUtil::save_file("image", "image", $path);

                $status = CategoryDB::add_category($category);
                
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

    public static function update_category(){

        $output_array = array();
        
        try {

            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();
            
            $permitted = self::check_permission(ACTION_EDIT);

            if( $permitted ){

                QueryUtil::connect();
                
                $category = self::read_category_form();

                $category_id  = $request->get_int_parameter("category_id");

                $old_category = CategoryDB::get_category($category_id);
                
                $category->icon  = FileUtil::replace_thumb("icon", "icon", ICON_PAGES_WIDTH, ICON_PAGES_HEIGHT, $old_category->icon, $path);
                //$category->icon  = FileUtil::replace_file("icon",  "icon",  $path, $old_category->icon );
                $category->image = FileUtil::replace_file("image", "image", $path, $old_category->image);
                
                $status = CategoryDB::update_category($category);
                
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

    public static function remove_category(){

        $output_array = array();
        
        try {
            
            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_REMOVE);

            if( $permitted ){

                QueryUtil::connect();
                
                $category_id = $request->get_int_parameter("category_id");

                $category    = CategoryDB::get_category($category_id);

                $status      = CategoryDB::remove_category($category);

                if( $status > 0 ){
                    FileUtil::remove_file($path, $category->icon );
                    FileUtil::remove_file($path, $category->image);                    
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


    private static function read_category_form(){

        $category = new stdClass();

        try {

            $category_arr = array();

            $request = HttpRequest::get_instance();

            $category_arr['category_id']   = $request->get_int_parameter("category_id");
            $category_arr['title_ar']     = $request->get_parameter("title_ar");
            $category_arr['title_en']     = $request->get_parameter("title_en");
            $category_arr['desc_ar']      = TextUtil::fixText( $request->get_parameter("desc_ar") );
            $category_arr['desc_en']      = TextUtil::fixText( $request->get_parameter("desc_en") );
            $category_arr['content_ar']   = TextUtil::fixText( $request->get_parameter("content_ar") );
            $category_arr['content_en']   = TextUtil::fixText( $request->get_parameter("content_en") );
            $category_arr['keys_ar']      = $request->get_parameter("keys_ar");
            $category_arr['keys_en']      = $request->get_parameter("keys_en");
            $category_arr['icon']         = '';
            $category_arr['image']        = '';
            $category_arr['format']       = $request->get_int_parameter("format");
            $category_arr['menu']         = $request->get_int_parameter("menu");
            $category_arr['options']      = $request->get_int_parameter("options");
            $category_arr['order']        = $request->get_int_parameter("order");
            $category_arr['active']       = $request->get_int_parameter("active");
            $category_arr['parent_id']    = $request->get_int_parameter("parent_id");

            $menu    = $category_arr['menu'];
            $options = $category_arr['options'];
            
            $top_menu  = $request->get_int_parameter("top_menu");
            $main_menu = $request->get_int_parameter("main_menu");
            //$side_menu = $request->get_int_parameter("side_menu");
            $foot_menu = $request->get_int_parameter("foot_menu");

            $show_menu = $request->get_int_parameter("show_menu");
            $show_text = $request->get_int_parameter("show_text");
            
            $menu |= ( $top_menu  > 0 ) ? TOP_MENU  : 0 ;
            //$menu |= ( $side_menu > 0 ) ? SIDE_MENU : 0 ;
            $menu |= ( $main_menu > 0 ) ? MAIN_MENU : 0 ;
            $menu |= ( $foot_menu > 0 ) ? FOOT_MENU : 0 ;

            $options |= ( $show_menu  > 0 ) ? SHOW_MENU : 0 ;
            $options |= ( $show_text  > 0 ) ? SHOW_TEXT : 0 ;

            $category_arr['menu']    = $menu;
            $category_arr['options'] = $options;
            
            $category = (object) $category_arr;
            
        } catch (Exception $e) {
            $output_array["status"] = SERVER_ERROR;
            throw new CustomException( 'Error in : read form', $e );//from php 5.3 no need to custum
        }

        return $category;
    }


    public static function get_formated_array(array $categories){

        $categories_array_list = array();

        for ( $i=0; $i<count($categories); $i++ ){

            $category = $categories[$i];

            $categoryObject = self::get_formated_object($category);

            $categories_array_list[] = $categoryObject;
        }

        return $categories_array_list;
    }

    private static function get_formated_object($category){

        $category_object = array();

        $category_object["category_id"] = $category->category_id;

        $category_object["title_ar"]   = $category->title_ar;
        $category_object["title_en"]   = $category->title_en;
        $category_object["keys_ar"]    = $category->keys_ar;
        $category_object["keys_en"]    = $category->keys_en;
        $category_object["desc_ar"]    = $category->desc_ar;
        $category_object["desc_en"]    = $category->desc_en;
        $category_object["content_ar"] = $category->content_ar;
        $category_object["content_en"] = $category->content_en;
        $category_object["icon"]       = $category->icon;
        $category_object["image"]      = $category->image;
        $category_object["format"]     = $category->format;
        $category_object["menu"]       = $category->menu;
        $category_object["order"]      = $category->order;
        $category_object["active"]     = $category->active;
        $category_object["parent_id"]  = $category->parent_id;

        $menu = intval( $category->menu );

        $category_object["top_menu"]  = ( ($menu & TOP_MENU)  > 0 ) ? 1 : 0;
        //$category_object["side_menu"] = ( ($menu & SIDE_MENU) > 0 ) ? 1 : 0;
        $category_object["main_menu"] = ( ($menu & MAIN_MENU) > 0 ) ? 1 : 0;
        $category_object["foot_menu"] = ( ($menu & FOOT_MENU) > 0 ) ? 1 : 0;

        $options = intval( $category->options );

        $category_object["show_menu"] = ( ($options & SHOW_MENU) > 0 ) ? 1 : 0;
        $category_object["show_text"] = ( ($options & SHOW_TEXT)  > 0 ) ? 1 : 0;

        return $category_object;
    }

}

?>