<?php
/*
 *
 */

/**
 * Description of ShotDB
 *
 * @author Ahmad
 */
class ShotDB extends DataDB {

    public static function add_shot($shot){

        $result = 0;

        try {

            $params = array( $shot->icon, $shot->file, $shot->type, $shot->order, $shot->active, $shot->parent_type, $shot->parent_id );
            
            $query = "INSERT INTO `shots` ( `icon`, `file`, `type`, `order`, `active`, `parent_type`, `parent_id` ) "
                . " VALUES ( ?, ?, ?, ?, ?, ?, ? ) ";

            $result = QueryUtil::excute_update( $query, $params );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : add shot', $e );//from php 5.3 no need to custum
        }
        return $result;
    }

    public static function update_shot($shot){

        $result = 0;

        try {

            $params = array( $shot->icon, $shot->file, $shot->type, $shot->order, $shot->active, $shot->parent_type, $shot->parent_id );

            $query = "UPDATE `shots` SET "
                ." `icon` = ?, `file` = ?, `type` = ?, `order` = ?, `active` = ?, `parent_type` = ?, `parent_id` = ? "
                ." WHERE `shot_id` = ".$shot->shot_id;

            $result = QueryUtil::excute_update( $query, $params );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : update shot', $e );//from php 5.3 no need to custum
        }
        return $result;
    }

    public static function remove_shot($shot){

        $result = 0;

        try {

            $query = "DELETE FROM `shots` WHERE `shot_id` = ".$shot->shot_id;

            $result = QueryUtil::excute_update( $query );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : remove shot', $e );//from php 5.3 no need to custum
        }

        return $result;
    }



    public static function get_shots($parent_id = -1, $active = -1, $start = -1, $count = -1, $order_by = '`shot_id`', $arrange = 'ASC'){

        $shots = null;

        try {

            $params = array();

            $query = "SELECT * FROM `shots` WHERE 1 ";

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

            $shots = self::format_shot_objects($buffer);

        } catch (Exception $e) {
             throw new CustomException( 'Error in : get shots', $e );//from php 5.3 no need to custum
        }

        return $shots;
    }

    public static function get_shots_count($parent_id = -1, $active = -1){

        $count = 0;

        try {

            $params = array();

            $query = "SELECT COUNT(*) AS `count` FROM `shots` WHERE 1 ";

            if( $parent_id > -1 ){
                $params [] = $parent_id;
                $query .= " AND `parent_id` = ? ";
            }
            if( $active > 0 ){
                $query .= " AND `active` = 1 ";
            }

            
            $buffer = QueryUtil::excute_select( $query, $params );

            if(count($buffer) > 0 ){
                $count = $buffer[0]["count"];
            }

        } catch (Exception $e) {
             throw new CustomException( 'Error in : get shots count', $e );//from php 5.3 no need to custum
        }

        return $count;
    }


    public static function search_shots($word = '', $active = -1, $start = -1, $count = -1){

        $shots = null;

        try {

            $params = array( "%".$word."%", "%".$word."%" );

            $query = "SELECT * FROM `shots` WHERE 1 "
                ." AND ( `title_ar` LIKE ? OR `title_en` LIKE ? ) ";

            if( $active > 0 ){
                $query .= " AND `active`= ".$active." ";
            }

            if( $start > -1 && $count > -1 ){
                $query .= " LIMIT ".$start.", ".$count."";
            }

            
            $buffer = QueryUtil::excute_select( $query, $params );

            $shots = self::format_shot_objects($buffer);

        } catch (Exception $e) {
            throw new CustomException( 'Error in : search shots', $e );//from php 5.3 no need to custum
        }

        return $shots;
    }

    public static function search_shots_count($word = '', $active = -1){

        $count = 0;

        try {

            $params = array( "%".$word."%", "%".$word."%" );

            $query = "SELECT COUNT(*) AS `count` FROM `shots` WHERE 1 "
                ." AND ( `title_ar` LIKE ? OR `title_en` LIKE ? ) ";

            if( $active > 0 ){
                $query .= " AND `active`= 1 ";
            }

            
            $buffer = QueryUtil::excute_select( $query, $params );

            $count = $buffer[0]["count"];

        } catch (Exception $e) {
            throw new CustomException( 'Error in : search shots count', $e );//from php 5.3 no need to custum
        }

        return $count;
    }


    public static function get_shot($shot_id){

        $shot = null;

        try {

            $query = "SELECT * FROM `shots` WHERE "
                ." `shot_id` = ".$shot_id;

            $buffer = QueryUtil::excute_select( $query );

            $shots = self::format_shot_objects($buffer);

            if( count($shots) > 0 ){
                $shot = $shots[0];
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get shot info', $e );//from php 5.3 no need to custum
        }

        return $shot;
    }


    /**
     * Returns array of objects that from format query result set
     * output into array of objects
     * @param buffer 2D array that is result of a query
     * @return objects array
     */
    public static function format_shot_objects($buffer){

        $shots = array();

        try {

            for($i=0; $i<count($buffer); $i++){

                $shot = (object) $buffer[$i];

                $shots[] = $shot;
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : format shot objects', $e );//from php 5.3 no need to custum
        }

        return $shots;
    }

}
?>
