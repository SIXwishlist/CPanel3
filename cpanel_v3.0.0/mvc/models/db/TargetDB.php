<?php
/*
 *
 */

/**
 * Description of targetDB
 *
 * @author Ahmad
 */
class TargetDB extends DataDB {

    public static function add_target($target){

        $result = 0;

        try {

            $params = array( $target->title_ar, $target->title_en, $target->desc_ar, $target->desc_en, $target->content_ar, $target->content_en, $target->keys_ar, $target->keys_en, $target->icon, $target->image, $target->format, $target->menu, $target->options, $target->order, $target->active, $target->parent_id );

            $query = "INSERT INTO `targets` ( `title_ar`, `title_en`, `desc_ar`, `desc_en`, `content_ar`, `content_en`, `keys_ar`, `keys_en`, `icon`, `image`, `format`, `menu`, `options`, `order`, `active`, `parent_id` ) "
                . " VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? ) ";

            $result = QueryUtil::excute_update( $query, $params );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : add target', $e );//from php 5.3 no need to custum
        }
        return $result;
    }

    public static function update_target($target){

        $result = 0;

        try {

            $params = array( $target->title_ar, $target->title_en, $target->desc_ar, $target->desc_en, $target->content_ar, $target->content_en, $target->keys_ar, $target->keys_en, $target->icon, $target->image, $target->format, $target->menu, $target->options, $target->order, $target->active, $target->parent_id );

            $query = "UPDATE `targets` SET "
                ."  `title_ar` = ?, `title_en` = ?, `desc_ar` = ?, `desc_en` = ?, `content_ar` = ?, `content_en` = ?, `keys_ar` = ?, `keys_en` = ?, `icon` = ?, `image` = ?, `format` = ?, `menu` = ?, `options` = ?, `order` = ?, `active` = ?, `parent_id` = ? "
                ." WHERE `target_id` = ".$target->target_id;

            $result = QueryUtil::excute_update( $query, $params );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : update target', $e );//from php 5.3 no need to custum
        }
        return $result;
    }

    public static function remove_target($target){

        $result = 0;

        try {

            $query = "DELETE FROM `targets` WHERE `target_id` = ".$target->target_id;

            $result = QueryUtil::excute_update( $query );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : remove target', $e );//from php 5.3 no need to custum
        }

        return $result;
    }



    public static function get_targets_with($parent_id = -1, $options = -1, $active = -1, $start = -1, $count = -1, $order_by = '`target_id`', $arrange = 'ASC'){

        $targets = null;

        try {

            $params = array();

            $query = "SELECT * FROM `targets` WHERE 1 ";

            if( $options > -1 ){
                $params [] = $options;
                $query .= " AND `options` = ? ";
            }
            if( $parent_id > -1 ){
                $params [] = $parent_id;
                $query .= " AND `parent_id` = ? ";
            }
            if( $active > 0 ){
                $query .= " AND `active` = 1 ";
            }

            if( $order_by != '' ){
                $query .= " ORDER BY ".$order_by." ".$arrange;
            }

            if( $start > -1 && $count > -1 ){
                $query .= " LIMIT ".$start.", ".$count."";
            }

            $buffer = QueryUtil::excute_select( $query, $params );

            $targets = self::format_objects($buffer);

        } catch (Exception $e) {
             throw new CustomException( 'Error in : get targets', $e );//from php 5.3 no need to custum
        }

        return $targets;
    }

    
    public static function get_targets($parent_id = -1, $active = -1, $start = -1, $count = -1, $order_by = '`target_id`', $arrange = 'ASC'){

        $targets = null;

        try {

            $params = array();

            $query = "SELECT * FROM `targets` WHERE 1 ";

            if( $parent_id > -1 ){
                $params [] = $parent_id;
                $query .= " AND `parent_id` = ? ";
            }
            if( $active > 0 ){
                $query .= " AND `active` = 1 ";
            }

            if( $order_by != '' ){
                $query .= " ORDER BY ".$order_by." ".$arrange;
            }

            if( $start > -1 && $count > -1 ){
                $query .= " LIMIT ".$start.", ".$count."";
            }

            
            $buffer = QueryUtil::excute_select( $query, $params );

            $targets = self::format_objects($buffer);

        } catch (Exception $e) {
             throw new CustomException( 'Error in : get targets', $e );//from php 5.3 no need to custum
        }

        return $targets;
    }

    public static function get_targets_count($parent_id = -1, $active = -1){

        $count = 0;

        try {

            $params = array();

            $query = "SELECT COUNT(*) AS `count` FROM `targets` WHERE 1 ";

            if( $parent_id > -1 ){
                $params [] = $parent_id;
                $query .= " AND `parent_id` = ? ";
            }
            if( $active > 0 ){
                $query .= " AND `active` = 1 ";
            }

            
            $buffer = QueryUtil::excute_select( $query, $params );

            if( count($buffer) > 0 ){
                $count = $buffer[0]["count"];
            }

        } catch (Exception $e) {
             throw new CustomException( 'Error in : get targets count', $e );//from php 5.3 no need to custum
        }

        return $count;
    }


    public static function search_targets($word = '', $start = -1, $count = -1, $active = -1){

        $targets = null;

        try {

            $params = array( "%".$word."%", "%".$word."%" );

            $query = "SELECT * FROM `targets` WHERE 1 "
                ." AND ( `title_ar` LIKE ? OR `title_en` LIKE ?  ) ";

            if( $active > 0 ){
                $query .= " AND `active`= ".$active." ";
            }

            if( $start > -1 && $count > -1 ){
                $query .= " LIMIT ".$start.", ".$count."";
            }

            
            $buffer = QueryUtil::excute_select( $query, $params );

            $targets = self::format_objects($buffer);

        } catch (Exception $e) {
            throw new CustomException( 'Error in : search targets', $e );//from php 5.3 no need to custum
        }

        return $targets;
    }

    public static function search_targets_count($word = '', $active = -1){

        $count = 0;

        try {

            $params = array( "%".$word."%", "%".$word."%" );

            $query = "SELECT COUNT(*) AS `count` FROM `targets` WHERE 1 "
                ." AND ( `title_ar` LIKE ? OR `title_en` LIKE ? ) ";

            if( $active > 0 ){
                $query .= " AND `active`= 1 ";
            }

            
            $buffer = QueryUtil::excute_select( $query, $params );

            if( count($buffer) > 0 ){
                $count = $buffer[0]["count"];
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : search targets count', $e );//from php 5.3 no need to custum
        }

        return $count;
    }


    public static function get_target($target_id){

        $target = null;

        try {

            $query = "SELECT * FROM `targets` WHERE "
                ." `target_id` = ".$target_id;

            
            $buffer = QueryUtil::excute_select( $query );

            $targets = self::format_objects($buffer);

            if( count($targets) > 0 ){
                $target = $targets[0];
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get target info', $e );//from php 5.3 no need to custum
        }

        return $target;
    }


    /**
     * Returns array of objects that from format query result set
     * output into array of objects
     * @param buffer 2D array that is result of a query
     * @return objects array
     */
    public static function format_objects($buffer){

        $targets = array();

        try {

            for($i=0; $i<count($buffer); $i++){

                $target = (object) $buffer[$i];
                
                $target->child_type = CHILD_TYPE_TARGET;

                $target = SectionChildDB::get_formated_section_child($target);
                
                $targets[] = $target;
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : format target objects', $e );//from php 5.3 no need to custum
        }

        return $targets;
    }

}
?>
