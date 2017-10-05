<?php
/*
 *
 */

/**
 * Description of SectionChildManage
 *
 * @author Ahmad
 */

class SectionChildManage extends ManageController {

    public static $dir = "sections";

    public static function get_section_path(){

        $output_array = array();

        try {

            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_VIEW);

            if( $permitted ){

                QueryUtil::connect();
                
                $section_id = $request->get_int_parameter("section_id");

                $section_id = ( $section_id > -1 ) ? $section_id : -1;

                $sections   = SectionChildDB::get_section_path($section_id);

                $sections_array_list = self::get_formated_path_array($sections);

                $output_array["sections"] = $sections_array_list;

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
    
    public static function get_section_childs(){

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

                $childs_array_list = SectionChildDB::get_section_childs($parent_id);
                $result_count      = count($childs_array_list);

                if( $count > -1 ){
                    $childs_array_list = array_slice($childs_array_list, $index, $count);
                }

                $output_array["section_childs"] = $childs_array_list;
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


    public static function get_formated_path_array(array $sections){

        $sections_array_list = array();

        for ( $i=0; $i<count($sections); $i++ ){

            $section = $sections[$i];

            $section_object = array();

            $section_object["section_id"] = $section->section_id;
            $section_object["title_ar"]   = $section->title_ar;
            $section_object["title_en"]   = $section->title_en;
            $section_object["parent_id"]  = $section->parent_id;

            $sections_array_list[] = $section_object;
        }

        return $sections_array_list;
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