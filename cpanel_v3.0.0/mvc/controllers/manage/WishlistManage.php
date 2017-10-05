<?php
/*
 *
 */

/**
 * Description of WishlistManage
 *
 * @author Ahmad
 */

class WishlistManage extends ManageController {

    public static $dir = "wish_items";
    public static $date_format = 'Y-m-d H:i:s';

    public static function add_item(){

        $output_array = array();

        try {

            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_ADD);

            if( $permitted ){

                QueryUtil::connect();

                $item = self::read_item_form();

                //$item->icon  = FileUtil::save_thumb("icon", "icon", ICON_PAGES_WIDTH, ICON_PAGES_HEIGHT,  $path);
                //$item->icon  = FileUtil::save_file("icon",  "icon",  $path);

                $status = WishlistDB::add_item($item);

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

    public static function update_item(){

        $output_array = array();

        try {

            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_EDIT);

            if( $permitted ){

                QueryUtil::connect();

                $item = self::read_item_form();

                $item_id  = $request->get_int_parameter("payment_id");

                $old_item = WishlistDB::get_item($item_id);

                //$item->icon  = FileUtil::replace_thumb("icon", "icon", ICON_PAGES_WIDTH, ICON_PAGES_HEIGHT, $old_item->icon, $path);
                //$item->icon  = FileUtil::replace_file("icon",  "icon",  $path, $old_item->icon );

                $status = WishlistDB::update_item($item);

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

    public static function remove_item(){

        $output_array = array();

        try {

            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_REMOVE);

            if( $permitted ){

                QueryUtil::connect();

                $item_id = $request->get_int_parameter("payment_id");

                $item    = WishlistDB::get_item($item_id);

                $status  = WishlistDB::remove_item($item);

                if( $status > 0 ){
                    //FileUtil::remove_file($path, $item->icon );
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


    public static function get_items(){

        $output_array = array();

        try {

            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_VIEW_ALL);

            if( $permitted ){

                QueryUtil::connect();

                $parent_id   = $request->get_int_parameter("parent_id");

                $index = $request->get_int_parameter("index");
                $count = $request->get_int_parameter("count");

                //if no count this means unlimited
                $count = ( $count == 0 ) ? -1 : $count;

                $wish_items       = WishlistDB::get_wish_items($index, $count, '`created`', 'DESC');
                $wish_items_count = WishlistDB::get_wish_items_count();

                $wish_items_array_list = self::get_formated_array($wish_items);

                $output_array["wish_items"]       = $wish_items_array_list;
                $output_array["wish_items_count"] = $wish_items_count;

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

    public static function search_items(){

        $output_array = array();

        try {

            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_VIEW_ALL);

            if( $permitted ){

                QueryUtil::connect();

                $options = array(

                    "payment_id" => $request->get_int_parameter("payment_id"),

                    "date"       => $request->get_parameter("date"),
                    "count"      => $request->get_int_parameter("count"),
                    "product_id" => $request->get_int_parameter("product_id"),
                    "user_id"    => $request->get_int_parameter("user_id")

                );

                $index = $request->get_int_parameter("index");
                $count = $request->get_int_parameter("count");

                //if no count this means unlimited
                $count = ( $count == 0 ) ? -1 : $count;

                $wish_items       = WishlistDB::search_items($options, $index, $count);
                $wish_items_count = WishlistDB::search_items_count($options);

                $wish_items_array_list = self::get_formated_array($wish_items);

                $output_array["wish_items"]       = $wish_items_array_list;
                $output_array["wish_items_count"] = $wish_items_count;

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


    private static function read_item_form(){

        $item = new stdClass();

        try {

            $item_arr = array();

            $request = HttpRequest::get_instance();
            
            $item_arr['payment_id'] = $request->get_int_parameter("payment_id");
            $item_arr['date']       = $request->get_parameter("date");
            $item_arr['count']      = $request->get_int_parameter("count");
            $item_arr['product_id'] = $request->get_int_parameter("product_id");
            $item_arr['user_id']    = $request->get_int_parameter("user_id");

            $item = (object) $item_arr;

        } catch (Exception $e) {
            $output_array["status"] = SERVER_ERROR;
            throw new CustomException( 'Error in : read form', $e );//from php 5.3 no need to custum
        }

        return $item;
    }


    public static function get_formated_array(array $wish_items){

        $wish_items_array_list = array();

        for ( $i=0; $i<count($wish_items); $i++ ){

            $item = $wish_items[$i];

            $itemObject = self::get_formated_object($item);

            $wish_items_array_list[] = $itemObject;
        }

        return $wish_items_array_list;
    }

    private static function get_formated_object($item){

        $item_object = array();

        $item_object['payment_id'] = $item->payment_id;
        $item_object['date']       = $item->date;
        $item_object['count']      = $item->count;
        $item_object['product_id'] = $item->product_id;
        $item_object['user_id']    = $item->user_id;

        $item_object['user']       = $item->user;
        $item_object['product_ar'] = $item->product_ar;
        $item_object['product_en'] = $item->product_en;

        return $item_object;
    }

}

?>
