<?php
/*
 *
 */

/**
 * Description of ProductManage
 *
 * @author Ahmad
 */

class ProductManage extends ManageController {

    public static $dir = "products";

    public static function add_product(){

        $output_array = array();

        try {

            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_ADD);

            if( $permitted ){
                
                QueryUtil::connect();

                $product  = self::read_product_form();
                
                $product->icon  = FileUtil::save_thumb("icon", "icon", ICON_PAGES_WIDTH, ICON_PAGES_HEIGHT,  $path);
                //$product->icon  = FileUtil::save_file("icon",  "icon",  $path);
                $product->image = FileUtil::save_file("image", "image", $path);
                
                $status = ProductDB::add_product($product);
                
                QueryUtil::close();
                
            }else{
                $status = -1;//ERROR_TYPE_UNAUTHORIZED_ACCESS;
            }

            $output_array["status"] = intval($permitted);

        } catch (Exception $e) {
            $output_array["status"] = SERVER_ERROR;
            Logger::log( $e->getMessage(), ERROR );
        }

        return $output_array;

    }

    public static function update_product(){

        $output_array = array();
        
        try {

            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();
            
            $permitted = self::check_permission(ACTION_EDIT);

            if( $permitted ){

                QueryUtil::connect();
                
                $product = self::read_product_form();

                $product_id  = $request->get_int_parameter("product_id");

                $old_product = ProductDB::get_product($product_id);
                
                $product->icon  = FileUtil::replace_thumb("icon", "icon", ICON_PAGES_WIDTH, ICON_PAGES_HEIGHT, $old_product->icon, $path);
                //$product->icon  = FileUtil::replace_file("icon",  "icon",  $path, $old_product->icon );
                $product->image = FileUtil::replace_file("image", "image", $path, $old_product->image);
                
                $status = ProductDB::update_product($product);

                QueryUtil::close();
                
            }else{
                $status = -1;//ERROR_TYPE_UNAUTHORIZED_ACCESS;
            }

            $output_array["status"] = intval($permitted);

        } catch (Exception $e) {
            $output_array["status"] = SERVER_ERROR;
            Logger::log( $e->getMessage(), ERROR );
        }

        return $output_array;

    }

    public static function remove_product(){

        $output_array = array();
        
        try {
            
            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_REMOVE);

            if( $permitted ){

                QueryUtil::connect();
                
                $product_id = $request->get_int_parameter("product_id");

                $product    = ProductDB::get_product($product_id);

                $status     = ProductDB::remove_product($product);
                
                if( $status > 0 ){
                    FileUtil::remove_file($path, $product->icon );
                    FileUtil::remove_file($path, $product->image);                    
                }

                QueryUtil::close();
                
            }else{
                $status = -1;//ERROR_TYPE_UNAUTHORIZED_ACCESS;
            }

            $output_array["status"] = intval($permitted);

        } catch (Exception $e) {
            $output_array["status"] = SERVER_ERROR;
            Logger::log( $e->getMessage(), ERROR );
        }

        return $output_array;

    }

    
    public static function get_products(){

        $output_array = array();

        try {

            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_VIEW_ALL);
            
            if( $permitted ){

                $category_id = $request->get_int_parameter("category_id");

                $index = $request->get_int_parameter("index");
                $count = $request->get_int_parameter("count");

                $category_id = ($category_id > 0 ) ? $category_id : -1;
                
                //if no count this means unlimited
                $count = ( $count == 0 ) ? -1 : $count;

                $products     = ProductDB::get_products($category_id, $index, $count, '`order`', 'ASC');
                $result_count = ProductDB::get_products_count($category_id);

                $products_array_list = self::get_formated_array($products);

                $output_array["products"]     = $products_array_list;
                $output_array["result_count"] = $result_count;
                
                $status = intval($permitted);

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
    
    public static function search_products(){

        $output_array = array();

        try {

            $request = HttpRequest::get_instance();
            $session = HttpSession::get_instance();

            $products_array_list = array();
            $result_count        = 0;

            $permitted = self::check_permission(ACTION_VIEW_ALL);

            if( $permitted ){

                $product_params = array();

                $product_params["product_id"] = $request->get_int_parameter("product_id");
                $product_params["title"]      = $request->get_parameter("title");
                $product_params["status"]     = $request->get_int_parameter("status");
                $product_params["level"]      = $request->get_int_parameter("level");

                $index = $request->get_int_parameter("index");
                $count = $request->get_int_parameter("count");

                //if no count this means unlimited
                $count = ( $count == 0 ) ? -1 : $count;

                $products     = ProductDB::search_products($product_params, $index, $count, '`product_id`', 'ASC');
                $result_count = ProductDB::search_products_count($product_params);

                $products_array_list = self::get_formated_array($products);
            
                $output_array["products"]     = $products_array_list;
                $output_array["result_count"] = $result_count;
                
                $status = intval($permitted);

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


    public static function import_products(){

        $output_array = array();

        try {

            $path = UPLOAD_DIR . self::$dir;

            $session = HttpSession::get_instance();
            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_EDIT);
            //$permitted = self::check_user_permission(ACTION_ADD);

            if( $permitted ){

                $file = FileUtil::save_file("file", "file", $path);

                $products_array_list = ExcelUtil::import_from_excel( $path . '/' . $file );

                unset( $products_array_list[0] );

                FileUtil::remove_file($path, $file);

                $product_records = array();

                foreach ($products_array_list as $row) {

                    $product = new stdClass();

                    $product->name = $row[0];
                    $product->name = $row[0];
                    $product->name = $row[0];
                    $product->name = $row[0];
                    $product->name = $row[0];
                    $product->name = $row[0];
                    $product->name = $row[0];
                    $product->name = $row[0];
                    $product->name = $row[0];
                    $product->name = $row[0];

                    $product_records[] = $product;

                }

                $status = ProductDB::add_products_list($product_records);

                $affected_rows = QueryUtil::get_affected_rows();

                if( $affected_rows == count($product_records) ){
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

    public static function export_products(){

        $output_array = array();

        try {

            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();
            $session = HttpSession::get_instance();

            $permitted = self::check_permission(ACTION_VIEW_ALL);
            //$permitted = self::check_user_permission(ACTION_VIEW_ALL);

            if( $permitted ){

                $product_params = array();

                $product_params["product_id"]   = $request->get_int_parameter("product_id");
                $product_params["name"]        = $request->get_parameter("name");
                $product_params["all_results"] = true;

                $index = $request->get_int_parameter("index");
                $count = $request->get_int_parameter("count");

                //if no count this means unlimited
                $count = ( $count == 0 ) ? -1 : $count;

                $products            = ProductDB::search_products($product_params, $index, $count, '`product_id`', 'ASC');

                $products_array_list = self::get_formated_array($products);

                $products_headers = array();
                $products_data    = array();

                if( count($products_array_list) > 0 ){

                    $products_headers = array_keys($products_array_list[0]);

                    foreach ($products_array_list as $row) {
                        $products_data[] = array_values($row);
                    }
                }

                $status = ExcelUtil::export_to_excel( $products_data, $products_headers, "products.xls" );


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

    public static function export_products_sample(){

        $output_array = array();

        try {

            $path = UPLOAD_DIR . self::$dir;

            $session = HttpSession::get_instance();
            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_VIEW_ALL);
            //$permitted = self::check_user_permission(ACTION_ADD);

            if( $permitted ){

                $products_headers = array( "title_ar", "title_en", "desc_ar", "desc_en", "content_ar", "content_en", "keys_ar", "keys_en", "icon", "image", "format", "menu", "options", "order", "active", "category_id" );
                $products_data    = array();

                $status = ExcelUtil::export_to_excel( $products_data, $products_headers, "products.xls" );

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

    
    public static function get_formated_array(array $products){

        $products_array_list = array();

        for ( $i=0; $i<count($products); $i++ ){

            $product = $products[$i];

            $product_object = self::get_formated_object($product);

            $products_array_list[] = $product_object;
        }

        return $products_array_list;
    }

    public static function get_formated_object($product){

        $product_object = array();

        $product_object["product_id"]      = $product->product_id;

        $product_object["title_ar"]        = $product->title_ar;
        $product_object["title_en"]        = $product->title_en;
        $product_object["keys_ar"]         = $product->keys_ar;
        $product_object["keys_en"]         = $product->keys_en;
        $product_object["desc_ar"]         = $product->desc_ar;
        $product_object["desc_en"]         = $product->desc_en;
        $product_object["content_ar"]      = $product->content_ar;
        $product_object["content_en"]      = $product->content_en;
        $product_object["icon"]            = $product->icon;
        $product_object["image"]           = $product->image;
        $product_object["format"]          = $product->format;
        $product_object["menu"]            = $product->menu;
        $product_object["number"]          = $product->number;
        $product_object["price"]           = $product->price;
        $product_object["discount"]        = $product->discount;
        $product_object["available"]       = $product->available;
        $product_object["installment1"]    = $product->installment1;
        $product_object["installment2"]    = $product->installment2;
        $product_object["order"]           = $product->order;
        $product_object["active"]          = $product->active;
        $product_object["parent_id"]       = $product->parent_id;

        $menu = intval( $product->menu );

        $options = intval( $product->options );

        $product_object["featured"]  = ( ($options & PRODUCT_FEATURED)  > 0 ) ? 1 : 0;
        $product_object["offer"]     = ( ($options & PRODUCT_OFFER)     > 0 ) ? 1 : 0;
        $product_object["sale"]      = ( ($options & PRODUCT_SALE)      > 0 ) ? 1 : 0;
        $product_object["recent"]    = ( ($options & PRODUCT_RECENT)    > 0 ) ? 1 : 0;

        return $product_object;
    }


    private static function read_product_form(){

        $product = new stdClass();

        try {

            $product_arr = array();

            $request = HttpRequest::get_instance();

            $product_arr['product_id']      = $request->get_int_parameter("product_id");
            $product_arr['title_ar']        = $request->get_parameter("title_ar");
            $product_arr['title_en']        = $request->get_parameter("title_en");
            $product_arr['desc_ar']         = $request->get_parameter("desc_ar");
            $product_arr['desc_en']         = $request->get_parameter("desc_en");
            $product_arr['content_ar']      = TextUtil::fixText( $request->get_parameter("content_ar") );
            $product_arr['content_en']      = TextUtil::fixText( $request->get_parameter("content_en") );
            $product_arr['keys_ar']         = $request->get_parameter("keys_ar");
            $product_arr['keys_en']         = $request->get_parameter("keys_en");
            
            $product_arr['icon']            = '';
            $product_arr['image']           = '';
            
            $product_arr['format']          = $request->get_int_parameter("format");
            $product_arr['menu']            = $request->get_int_parameter("menu");
            $product_arr['options']         = $request->get_int_parameter("options");

            $product_arr['number']          = $request->get_parameter("number");
            $product_arr['price']           = $request->get_double_parameter("price");
            $product_arr['discount']        = $request->get_double_parameter("discount");
            $product_arr['installment1']    = $request->get_double_parameter("installment1");
            $product_arr['installment2']    = $request->get_double_parameter("installment2");
            $product_arr['available']       = $request->get_int_parameter("available");
            
            $product_arr['order']           = $request->get_int_parameter("order");
            $product_arr['active']          = $request->get_int_parameter("active");
            $product_arr['parent_id']       = $request->get_int_parameter("parent_id");
            
            $menu    = $product_arr['menu'];
            $options = $product_arr['options'];
            
            $featured = $request->get_int_parameter("featured");
            $offer    = $request->get_int_parameter("offer");
            $recent   = $request->get_int_parameter("recent");
            $sale     = $request->get_int_parameter("sale");
            
            $options |= ( $featured > 0 ) ? PRODUCT_FEATURED  : 0 ;
            $options |= ( $offer    > 0 ) ? PRODUCT_OFFER     : 0 ;
            $options |= ( $sale     > 0 ) ? PRODUCT_SALE      : 0 ;
            $options |= ( $recent   > 0 ) ? PRODUCT_RECENT    : 0 ;

            $product_arr['menu']    = $menu;
            $product_arr['options'] = $options;

            $product = (object) $product_arr;
            
        } catch (Exception $e) {
            throw new CustomException( 'Error in : read form', $e );//from php 5.3 no need to custum
        }

        return $product;
    }

}

?>