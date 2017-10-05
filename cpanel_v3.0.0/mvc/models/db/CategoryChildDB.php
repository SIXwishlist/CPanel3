<?php
/*
 *
 */

/**
 * Description of CategoryChildDB
 *
 * @author Ahmad
 */
class CategoryChildDB extends DataDB {

    public static function get_category_path($category_id){

        $categories = null;

        try {

            $query = "call get_category_path( ".$category_id." )" ;

            //$buffer = QueryUtil::excute_sp_select( $query );
            $buffer = QueryUtil::excute_select( $query );

            $categories = self::format_category_objects($buffer);
            
            $categories = array_reverse($categories);

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get category path', $e );//from php 5.3 no need to custum
        }

        return $categories;
    }


    public static function get_category_childs($category_id){

        $category_childs = null;

        try {

            $query  = "call get_category_childs( ".$category_id." )";

            $buffer = QueryUtil::excute_select_multi( $query );

            $category_childs  = self::get_formated_category_child_objects($buffer);
            
            usort($category_childs, array(self, cmp_childs_func));

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get category childs', $e );//from php 5.3 no need to custum
        }

        return $category_childs;
    }

    public static function get_category_childs_list($category_id){

        $childs = null;

        try {

            $query = "call get_category_childs_list( ".$category_id." )" ;

            $buffer = QueryUtil::excute_select( $query );

            $childs = self::format_category_objects($buffer);
        
        } catch (Exception $e) {
            throw new CustomException( 'Error in : get category childs list', $e );//from php 5.3 no need to custum
        }

        return $childs;
    }


    public static function search_category_childs($search_item){

        $childs = null;

        try {

            $query  = "call search_category_childs( '%".$search_item."%' )";
            
            $buffer = QueryUtil::excute_select_multi( $query );

            $childs  = self::get_formated_category_child_objects($buffer);
            
            usort($childs, array(self, cmp_childs_func));

        } catch (Exception $e) {
            throw new CustomException( 'Error in : search category childs', $e );//from php 5.3 no need to custum
        }

        return $childs;
    }

    public static function search_category_childs_list($search_item){

        $childs = null;

        try {

            $query = "call search_category_childs_list('%".$search_item."%', -1, -1)";

            $buffer = QueryUtil::excute_select( $query );

            $childs = self::format_objects($buffer);
        
        } catch (Exception $e) {
            throw new CustomException( 'Error in : search category childs list', $e );//from php 5.3 no need to custum
        }

        return $childs;
    }
        

    public static function get_formated_category_child_objects($buffer){

        $childs = array();
        
        $index = 1;

        try {

            for($i=0; $i<count($buffer); $i++){

                for($j=0; $j<count($buffer[$i]); $j++){

                    $child = (object) $buffer[$i][$j];

                    $child = self::get_formated_category_child( $child );
                    
                    $child->child_index = $index;

                    $childs[] = $child;
                    
                    $index++;
                }

            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : format child objects', $e );//from php 5.3 no need to custum
        }

        return $childs;
    }

    public static function get_formated_category_child($object){
        
        $menu    = intval( $object->menu );
        $options = intval( $object->options );
        
        $object->editable  = ( ($options & EDITABLE ) > 0 ) ? 1 : 0;
        $object->removable = ( ($options & REMOVABLE) > 0 ) ? 1 : 0;
        
        switch ( $object->child_type ){

            case CHILD_TYPE_CATEGORY:

                $object->child_id  = $object->category_id;
                
                $object->top_menu  = ( ($menu & TOP_MENU)  > 0 ) ? 1 : 0;
                $object->side_menu = ( ($menu & SIDE_MENU) > 0 ) ? 1 : 0;
                $object->foot_menu = ( ($menu & FOOT_MENU) > 0 ) ? 1 : 0;

                $object->show_menu  = ( ($options & SHOW_MENU) > 0 ) ? 1 : 0;
                $object->show_text  = ( ($options & SHOW_TEXT) > 0 ) ? 1 : 0;

                break;
            
            case CHILD_TYPE_PRODUCT:

                $object->child_id  = $object->product_id;
                
                $object->featured = ( ($options & PRODUCT_FEATURED) > 0 ) ? 1 : 0;
                $object->offer    = ( ($options & PRODUCT_OFFER)    > 0 ) ? 1 : 0;
                $object->sale     = ( ($options & PRODUCT_SALE)     > 0 ) ? 1 : 0;
                $object->recent   = ( ($options & PRODUCT_RECENT)   > 0 ) ? 1 : 0;

                break;

        }

        return $object;
    }


    public static function cmp_childs_func($a, $b){

        if (intval($a->order) == intval($b->order))
            return 0;
        if (intval($a->order) >  intval($b->order))
            return 1;

        return -1;
    }

    public static function format_category_objects($buffer){

        $categories = array();

        try {

            for($i=0; $i<count($buffer); $i++){

                $category = (object) $buffer[$i];
                
                $category = self::get_formated_category_child( $category );
                
                $categories[] = $category;
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : format embed objects', $e );//from php 5.3 no need to custum
        }

        return $categories;
    }

//usort($items,  array('CategoryChildDB','cmp_childs_func'));
// not used //usort($items, "CategoryChildDB::cmp_childs_func");
//usort($items,  array(self, cmp_childs_func));

}
?>
