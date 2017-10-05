<?php
/*
 *
 */

/**
 * Description of SectionChildDB
 *
 * @author Ahmad
 */
class SectionChildDB extends DataDB {

    public static function get_section_path($section_id){

        $sections = null;

        try {

            $params = array( $section_id );
            
            $query = "CALL get_section_path( ? );" ;

            $buffer = QueryUtil::excute_select( $query, $params );
            
            //$query = "call get_section_path( ".$section_id." )" ;

            //$buffer = QueryUtil::excute_sp_select( $query );
            //$buffer = QueryUtil::excute_select( $query );

            $sections = self::format_section_objects($buffer);
            
            $sections = array_reverse($sections);

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get section path', $e );//from php 5.3 no need to custum
        }

        return $sections;
    }


    public static function get_section_childs($section_id){

        $section_childs = null;

        try {

            $query  = "call get_section_childs( ".$section_id." )";

            $buffer = QueryUtil::excute_select_multi( $query );

            $section_childs  = self::get_formated_section_child_objects($buffer);
            
            usort($section_childs, array(self, cmp_childs_func));

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get section childs', $e );//from php 5.3 no need to custum
        }

        return $section_childs;
    }

    public static function get_section_childs_list($section_id){

        $childs = null;

        try {

            $query = "call get_section_childs_list( ".$section_id." )" ;

            $buffer = QueryUtil::excute_select( $query );

            $childs = self::format_section_objects($buffer);
        
        } catch (Exception $e) {
            throw new CustomException( 'Error in : get section childs list', $e );//from php 5.3 no need to custum
        }

        return $childs;
    }


    public static function search_section_childs($search_item){

        $childs = null;

        try {

            $query  = "call search_section_childs( '%".$search_item."%' )";
            
            $buffer = QueryUtil::excute_select_multi( $query );

            $childs  = self::get_formated_section_child_objects($buffer);
            
            usort($childs, array(self, cmp_childs_func));

        } catch (Exception $e) {
            throw new CustomException( 'Error in : search section childs', $e );//from php 5.3 no need to custum
        }

        return $childs;
    }

    public static function search_section_childs_list($search_item){

        $childs = null;

        try {

            $query = "call search_section_childs_list('%".$search_item."%', -1, -1)";

            $buffer = QueryUtil::excute_select( $query );

            $childs = self::format_objects($buffer);
        
        } catch (Exception $e) {
            throw new CustomException( 'Error in : search section childs list', $e );//from php 5.3 no need to custum
        }

        return $childs;
    }
        

    public static function get_formated_section_child_objects($buffer){

        $childs = array();
        
        $index = 1;

        try {

            for($i=0; $i<count($buffer); $i++){

                for($j=0; $j<count($buffer[$i]); $j++){

                    $child = (object) $buffer[$i][$j];

                    $child = self::get_formated_section_child( $child );
                    
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

    public static function get_formated_section_child($object){
        
        $menu    = intval( $object->menu );
        $options = intval( $object->options );

        $object->top_menu  = ( ($menu & TOP_MENU)  > 0 ) ? 1 : 0;
        $object->side_menu = ( ($menu & SIDE_MENU) > 0 ) ? 1 : 0;
        $object->main_menu = ( ($menu & MAIN_MENU) > 0 ) ? 1 : 0;
        $object->foot_menu = ( ($menu & FOOT_MENU) > 0 ) ? 1 : 0;
        
        $object->editable  = ( ($options & EDITABLE ) > 0 ) ? 1 : 0;
        $object->removable = ( ($options & REMOVABLE) > 0 ) ? 1 : 0;
        
        switch ( $object->child_type ){

            case CHILD_TYPE_SECTION:

                $object->child_id  = $object->section_id;

                $object->show_menu = ( ($options & SHOW_MENU) > 0 ) ? 1 : 0;
                $object->show_text = ( ($options & SHOW_TEXT ) > 0 ) ? 1 : 0;
                
                $object->sitemap_exclude = ( ($options & SITEMAP_EXCLUDE) > 0 ) ? 1 : 0;
                $object->special         = ( ($options & SPECIAL        ) > 0 ) ? 1 : 0;

                break;
            
            case CHILD_TYPE_TARGET:

                $object->child_id  = $object->target_id;
                $object->sticky    = ( ($options & STICKY) > 0 ) ? 1 : 0;

                break;

            case CHILD_TYPE_EMBED:
                
                $object->child_id  = $object->embed_id;
                break;

            case CHILD_TYPE_LINK:
                
                $object->child_id   = $object->link_id;
                $object->new_window = ( ($options & NEW_WINDOW) > 0 ) ? 1 : 0;
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


    public static function format_section_objects($buffer){

        $sections = array();

        try {

            for($i=0; $i<count($buffer); $i++){

                $section = (object) $buffer[$i];
                
                $section = self::get_formated_section_child( $section );
                
                $sections[] = $section;
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : format embed objects', $e );//from php 5.3 no need to custum
        }

        return $sections;
    }

//usort($items,  array('SectionChildDB','cmp_childs_func'));
// not used //usort($items, "SectionChildDB::cmp_childs_func");
//usort($items,  array(self, cmp_childs_func));

}
?>
