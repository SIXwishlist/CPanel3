<?php
/*
 *
 */

/**
 * Description of CategoryChildManage
 *
 * @author Ahmad
 */

class CategoryChildManage extends ManageController {

    public static $dir = "categories";

    public static function get_category_path(){

        $output_array = array();

        try {

            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_VIEW);

            if( $permitted ){

                QueryUtil::connect();
                
                $category_id = $request->get_int_parameter("category_id");

                $category_id = ( $category_id > -1 ) ? $category_id : -1;

                $categories   = CategoryChildDB::get_category_path($category_id);

                $categories_array_list = self::get_formated_path_array($categories);

                $output_array["categories"] = $categories_array_list;

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
    
    public static function get_category_childs(){

        $output_array = array();

        try {

            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_VIEW);

            if( $permitted ){

                QueryUtil::connect();
                
                $parent_id  = $request->get_int_parameter("parent_id");

                $index = $request->get_int_parameter("index");
                $count = $request->get_int_parameter("count");

                $parent_id  = ( $parent_id > -1 ) ? $parent_id : -1;

                //if no count this means unlimited
                $count = ( $count == 0 ) ? -1 : $count;

                $childs_array_list = CategoryChildDB::get_category_childs($parent_id);
                $result_count      = count($childs_array_list);

                if( $count > -1 ){
                    $childs_array_list = array_slice($childs_array_list, $index, $count);
                }

                $output_array["category_childs"] = $childs_array_list;
                $output_array["result_count"]   = $result_count;

                $status = SUCCESS;

                QueryUtil::close();

            }else{
                $status = -1;//ERROR_TYPE_UNAUTHORIZED_ACCESS;
            }

            $output_array["status"] = $status;

        } catch (Exception $e) {
            $output_array["status"] = SERVER_ERROR;
            Logger::log( $e->getMessage(), ERROR );
        }
                
        return $output_array;
    }


    public static function get_formated_path_array(array $categories){

        $categories_array_list = array();

        for ( $i=0; $i<count($categories); $i++ ){

            $category = $categories[$i];

            $category_object = array();

            $category_object["category_id"] = $category->category_id;
            $category_object["title_ar"]   = $category->title_ar;
            $category_object["title_en"]   = $category->title_en;
            $category_object["parent_id"]  = $category->parent_id;

            $categories_array_list[] = $category_object;
        }

        return $categories_array_list;
    }

    //dont delete me i am compare function
    public static function cmp_items_func($a, $b){

        if (intval($a['order']) == intval($b['order']))
            return 0;
        if (intval($a['order']) >  intval($b['order']))
            return 1;

        return -1;
    }

}

?>