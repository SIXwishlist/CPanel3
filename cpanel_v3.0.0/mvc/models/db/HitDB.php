<?php
/*
 *
 */

/**
 * Description of HitDB
 *
 * @author Ahmad
 */
class HitDB extends DataDB {

    public static function add_hit($hit){

        $result = 0;

        try {

            $params = array( $hit->source, $hit->id, $hit->count );

            $query = "INSERT INTO `hits` ( `source`, `id`, `count` ) "
                . " VALUES ( ?, ?, ? ) ";

            $result = QueryUtil::excute_update( $query, $params );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : add hit', $e );//from php 5.3 no need to custum
        }
        
        return $result;
    }

    public static function update_hit($hit){

        $result = 0;

        try {

            $params = array( $hit->source, $hit->id, $hit->count );

            $query = "UPDATE `hits` SET "
                . " `source` = ?, `id` = ?, `count` = ? "
                ." WHERE `hit_id` = ".$hit->hit_id;

            $result = QueryUtil::excute_update( $query, $params );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : update hit', $e );//from php 5.3 no need to custum
        }
        return $result;
    }

    public static function remove_hit($hit){

        $result = 0;

        try {

            $query = "DELETE FROM `hits` WHERE `hit_id` = ".$hit->hit_id;

            $result = QueryUtil::excute_update( $query );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : remove hit', $e );//from php 5.3 no need to custum
        }

        return $result;
    }


    public static function get_hits($source = -1, $id = -1, $start = -1, $count = -1, $order_by = '`hit_id`', $arrange = 'ASC'){

        $hits = null;

        try {

            $params = array();

            $query = "SELECT * FROM `hits` WHERE 1 ";

            if( $source > -1 ){
                $query .= " AND `source` = ? ";
                $params[] = $id;
            }

            if( $id > -1 ){
                $query .= " AND `id` = ? ";
                $params[] = $id;
            }
            
            if( $order_by != '' ){
                $query .= " ORDER BY ".$order_by." ".$arrange;
            }

            if( $start > -1 && $count > -1 ){
                $query .= " LIMIT ".$start.", ".$count."";
            }


            $buffer = QueryUtil::excute_select( $query, $params );

            $hits = self::format_hit_objects($buffer);

        } catch (Exception $e) {
             throw new CustomException( 'Error in : get hits', $e );//from php 5.3 no need to custum
        }

        return $hits;
    }

    public static function get_hits_count($source = -1, $id = -1){

        $count = 0;

        try {

            $params = array();

            $query = "SELECT COUNT(*) AS `count` FROM `hits` WHERE 1 ";

            if( $source > -1 ){
                $query .= " AND `source` = ? ";
                $params[] = $source;
            }

            if( $id > -1 ){
                $query .= " AND `id` = ? ";
                $params[] = $id;
            }
            

            $buffer = QueryUtil::excute_select( $query, $params );

            if( count($buffer) > 0 ) {
                $count = $buffer[0]["count"];
            }

        } catch (Exception $e) {
             throw new CustomException( 'Error in : get hits count', $e );//from php 5.3 no need to custum
        }

        return $count;
    }


    public static function search_hits($hit_params, $start = -1, $count = -1, $order_by = '`hit_id`', $arrange = 'ASC'){

        $hits = array();

        try {
            
            extract( $hit_params );

            $all_empty = true;
            
            $params = array();            

            $query = "SELECT * FROM `hits` "
                    . " WHERE 1 ";

            if( !empty($hit_id) ){
                $params [] = $hit_id;
                $query    .= " AND `hit_id` = ? ";
                $all_empty = false;
            }
            
            if( !empty($source) ){
                $params [] = $source;
                $query    .= " AND `source` = ? ";
                $all_empty = false;
            }

            if( !empty($id) ){
                $params [] = $id;
                $query    .= " AND `id` = ? ";
                $all_empty = false;
            }
            
            if( $all_empty && !$all_results ){
                $query .= " AND 0 ";
            }            
            
            $query .= " GROUP BY `hit_id` ";
            
            if( $order_by != '' ){
                $query .= " ORDER BY ".$order_by." ".$arrange;
            }

            if( $start > -1 && $count > -1 ){
                $query .= " LIMIT ".$start.", ".$count."";
            }
            
            $buffer = QueryUtil::excute_select( $query, $params );

            $hits = self::format_objects($buffer);

        } catch (Exception $e) {
            throw new CustomException( 'Error in : search hits', $e );//from php 5.3 no need to custum
        }

        return $hits;
    }

    public static function search_hits_count( $hit_params ){

        $count = -1;

        try {
            
            extract( $hit_params );
            
            $all_empty = true;
            
            $params = array();
            
            $query = "SELECT COUNT(*) AS `count` FROM `hits` "
                  . " WHERE 1 ";
            
            if( !empty($hit_id) ){
                $params [] = $hit_id;
                $query    .= " AND `hit_id` = ? ";
                $all_empty = false;
            }

            
            if( !empty($source) ){
                $params [] = $source;
                $query    .= " AND `source` = ? ";
                $all_empty = false;
            }

            if( !empty($id) ){
                $params [] = $id;
                $query    .= " AND `id` = ? ";
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
            throw new CustomException( 'Error in : get hit search result count', $e );//from php 5.3 no need to custum
        }

        return $count;
    }

    
    public static function add_hits_list($hit_records){

        $result = 0;

        try {

            $params      = array();
            $ques_marks  = array();
            
            $query = "INSERT INTO `hits` ( `source`, `id`, `count` ) VALUES ";
            
            foreach ($hit_records as $hit) {
                
                $params [] = $hit->source;
                $params [] = $hit->id;
                $params [] = $hit->count;
                
                $ques_marks [] = " ( ?, ?, ? ) ";
                
            }
            
            $query  .= implode(", ", $ques_marks);

            $result  = QueryUtil::excute_update( $query, $params );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : add hits list', $e );//from php 5.3 no need to custum
        }
        
        return $result;
    }


    public static function get_hit($hit_id){

        $hit = null;

        try {

            $query = "SELECT * FROM `hits` WHERE "
                ." `hit_id` = ".$hit_id;


            $buffer = QueryUtil::excute_select( $query );

            $hits = self::format_hit_objects($buffer);

            if( count($hits) > 0 ) {
                $hit = $hits[0];
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get hit info', $e );//from php 5.3 no need to custum
        }

        return $hit;
    }


    /**
     * Returns array of objects that from format query result set
     * output into array of objects
     * @param buffer 2D array that is result of a query
     * @return objects array
     */
    public static function format_hit_objects($buffer){

        $hits = array();

        try {

            for($i=0; $i<count($buffer); $i++){

                $hit = (object) $buffer[$i];

                $hits[] = $hit;
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : format hit objects', $e );//from php 5.3 no need to custum
        }

        return $hits;
    }

}
?>
