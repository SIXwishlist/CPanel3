<?php
/*
 *
 */

/**
 * Description of WishedItemManage
 *
 * @author Ahmad
 */

class WishedItemManage extends ManageController {

    public static $dir = "wished_items";
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

                $status = WishedItemDB::add_item($item);

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

                $old_item = WishedItemDB::get_item($item_id);

                //$item->icon  = FileUtil::replace_thumb("icon", "icon", ICON_PAGES_WIDTH, ICON_PAGES_HEIGHT, $old_item->icon, $path);
                //$item->icon  = FileUtil::replace_file("icon",  "icon",  $path, $old_item->icon );

                $status = WishedItemDB::update_item($item);

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

                $item    = WishedItemDB::get_item($item_id);

                $status  = WishedItemDB::remove_item($item);

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

                $user_id = $request->get_int_parameter("user_id");

                $index = $request->get_int_parameter("index");
                $count = $request->get_int_parameter("count");

                //if no count this means unlimited
                $count = ( $count == 0 ) ? -1 : $count;

                $wished_items       = WishedItemDB::get_items($user_id, $index, $count, '`item_id`', 'DESC');
                $wished_items_count = WishedItemDB::get_items_count($user_id);

                $wished_items_array_list = self::get_formated_array($wished_items);

                $output_array["wished_items"]       = $wished_items_array_list;
                $output_array["wished_items_count"] = $wished_items_count;

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

                    "item_id"    => $request->get_int_parameter("item_id"),

                    "date"       => $request->get_parameter("date"),
                    "count"      => $request->get_int_parameter("count"),
                    "product_id" => $request->get_int_parameter("product_id"),
                    "user_id"    => $request->get_int_parameter("user_id")

                );

                $index = $request->get_int_parameter("index");
                $count = $request->get_int_parameter("count");

                //if no count this means unlimited
                $count = ( $count == 0 ) ? -1 : $count;

                $wished_items       = WishedItemDB::search_items($options, $index, $count);
                $wished_items_count = WishedItemDB::search_items_count($options);

                $wished_items_array_list = self::get_formated_array($wished_items);

                $output_array["wished_items"]       = $wished_items_array_list;
                $output_array["wished_items_count"] = $wished_items_count;

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
            
            $item_arr['item_id']    = $request->get_int_parameter("item_id");
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


    public static function get_formated_array(array $wished_items){

        $wished_items_array_list = array();

        for ( $i=0; $i<count($wished_items); $i++ ){

            $item = $wished_items[$i];

            $itemObject = self::get_formated_object($item);

            $wished_items_array_list[] = $itemObject;
        }

        return $wished_items_array_list;
    }

    private static function get_formated_object($item){

        $item_object = array();

        $item_object['item_id']    = $item->item_id;
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
