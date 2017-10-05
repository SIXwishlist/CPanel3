<?php
/*
 *
 */

/**
 * Description of SlideDB
 *
 * @author Ahmad
 */
class SlideDB extends DataDB {

    public static function add_slide($slide){

        $result = 0;

        try {

            $params = array( $slide->title_ar, $slide->title_en, $slide->desc_ar, $slide->desc_en, $slide->file, $slide->type, $slide->link_ar, $slide->link_en, $slide->active, $slide->order, $slide->parent_type, $slide->parent_id );

            $query = "INSERT INTO `slides` ( `title_ar`, `title_en`, `desc_ar`, `desc_en`, `file`, `type`, `link_ar`, `link_en`, `active`, `order`, `parent_type`, `parent_id` ) "
                . " VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? ) ";

            $result = QueryUtil::excute_update( $query, $params );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : add slide', $e );//from php 5.3 no need to custum
        }

        return $result;
    }

    public static function update_slide($slide){

        $result = 0;

        try {

            $params = array( $slide->title_ar, $slide->title_en, $slide->desc_ar, $slide->desc_en, $slide->file, $slide->type, $slide->link_ar, $slide->link_en, $slide->active, $slide->order, $slide->parent_type, $slide->parent_id );

            $query = "UPDATE `slides` SET "
                ." `title_ar` = ?, `title_en` = ?, `desc_ar` = ?, `desc_en` = ?, `file` = ?, `type` = ?, `link_ar` = ?, `link_en` = ?, `active` = ?, `order` = ?, `parent_type` = ?, `parent_id` = ? "
                ." WHERE `slide_id` = ".$slide->slide_id;

            $result = QueryUtil::excute_update( $query, $params );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : update slide', $e );//from php 5.3 no need to custum
        }
        return $result;
    }

    public static function remove_slide($slide){

        $result = 0;

        try {

            $query = "DELETE FROM `slides` WHERE `slide_id` = ".$slide->slide_id;

            $result = QueryUtil::excute_update( $query );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : remove slide', $e );//from php 5.3 no need to custum
        }

        return $result;
    }


    public static function get_slides($parent_type = -1, $parent_id = -1, $active = -1, $start = -1, $count = -1, $order_by_1 = '`order`', $arrange1 = 'ASC', $order_by_2 = '`slide_id`', $arrange2 = 'ASC'){

        $slides = null;

        try {

            $params = array();

            $query = "SELECT * FROM `slides` WHERE 1 ";

            if( $parent_type > -1 ){
                $params [] = $parent_type;
                $query .= " AND `parent_type`= ? ";
            }

            if( $parent_id > -1 ){
                $params [] = $parent_id;
                $query .= " AND `parent_id`= ? ";
            }

            if( $active > 0 ){
                $query .= " AND `active`= 1 ";
            }

            if( $order_by_1 != '' ){
                $query .= " ORDER BY ".$order_by_1." ".$arrange1;
                if( $order_by_2 != '' ){
                    $query .= " ,  ".$order_by_2." ".$arrange2;
                }
            }

            if( $start > -1 && $count > -1 ){
                $query .= " LIMIT ".$start.", ".$count."";
            }

            
            $buffer = QueryUtil::excute_select( $query, $params );

            $slides = self::format_slide_objects($buffer);
            
        } catch (Exception $e) {
             throw new CustomException( 'Error in : get slides', $e );//from php 5.3 no need to custum
        }

        return $slides;
    }

    public static function get_slides_count($parent_type = -1, $parent_id = -1, $active = -1){

        $count = 0;

        try {

            $params = array();

            $query = "SELECT COUNT(*) AS `count` FROM `slides` WHERE 1 ";

            if( $parent_type > -1 ){
                $params [] = $parent_type;
                $query .= " AND `parent_type`= ? ";
            }
            if( $parent_id > -1 ){
                $params [] = $parent_id;
                $query .= " AND `parent_id`= ? ";
            }

            if( $active > 0 ){
                $query .= " AND `active` = 1 ";
            }

            
            $buffer = QueryUtil::excute_select( $query, $params );

            $count = $buffer[0]["count"];

        } catch (Exception $e) {
             throw new CustomException( 'Error in : get slides count', $e );//from php 5.3 no need to custum
        }

        return $count;
    }

    
    public static function search_slides($slide_params, $start = -1, $count = -1, $order_by = '`slide_id`', $arrange = 'ASC'){

        $slides = array();

        try {
            
            extract( $slide_params );

            $all_empty = true;
            
            $params = array();            
            
            $query = "SELECT * FROM `slides` WHERE 1 ";
            
            if( !empty($slide_id) ){
                $params [] = $slide_id;
                $query    .= " AND `slide_id` = ? ";
                $all_empty = false;
            }
            
            if( !empty($title) ){
                $params [] = $title;
                $params [] = $title;
                $query    .= " AND ( `title_ar` LIKE %?% OR `title_en` LIKE %?% ";
                $all_empty = false;
            }

            if( $all_empty && !$all_results ){                
                $query .= " AND 0 ";
            }            
            
            if( $order_by != '' ){
                $query .= " ORDER BY ".$order_by." ".$arrange;
            }

            if( $start > -1 && $count > -1 ){
                $query .= " LIMIT ".$start.", ".$count."";
            }
            
            $buffer = QueryUtil::excute_select( $query, $params );

            $slides = self::format_objects($buffer);

        } catch (Exception $e) {
            throw new CustomException( 'Error in : search slides', $e );//from php 5.3 no need to custum
        }

        return $slides;
    }
    
    public static function search_slides_count( $slide_params ){

        $count = -1;

        try {
            
            extract( $slide_params );
            
            $all_empty = true;
            
            $params = array();            
            
            $query = "SELECT COUNT(*) AS `count` FROM `slides` WHERE 1 ";
            
            if( !empty($slide_id) ){
                $params [] = $slide_id;
                $query    .= " AND `slide_id` = ? ";
                $all_empty = false;
            }
            
            if( !empty($title) ){
                $params [] = $title;
                $params [] = $title;
                $query    .= " AND ( `title_ar` LIKE %?% OR `title_en` LIKE %?% ";
                $all_empty = false;
            }

            if( $all_empty && !$all_results ){
                $query .= " AND 0 ";
            }
            

            $buffer = QueryUtil::excute_select( $query, $params );

            if( count($buffer) > 0 ){
                $count = $buffer[0]["count"];
            }
            
        } catch (Exception $e) {
            throw new CustomException( 'Error in : get slide search result count', $e );//from php 5.3 no need to custum
        }

        return $count;
    }


    public static function get_slide($slide_id){

        $slide = null;

        try {

            $query = "SELECT * FROM `slides` WHERE "
                ." `slide_id` = ".$slide_id;

            
            $buffer = QueryUtil::excute_select( $query );

            $slides = self::format_slide_objects($buffer);

            $slide = $slides[0];

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get slide info', $e );//from php 5.3 no need to custum
        }

        return $slide;
    }


    /**
     * Returns array of objects that from format query result set
     * output into array of objects
     * @param buffer 2D array that is result of a query
     * @return objects array
     */
    public static function format_slide_objects($buffer){

        $slides = array();

        try {

            for($i=0; $i<count($buffer); $i++){

                $slide = (object) $buffer[$i];

                $slides[] = $slide;
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : format slide objects', $e );//from php 5.3 no need to custum
        }

        return $slides;
    }

}
?>
