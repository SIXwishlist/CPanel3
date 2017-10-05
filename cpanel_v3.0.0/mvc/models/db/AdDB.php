<?php
/*
 *
 */

/**
 * Description of AdDB
 *
 * @author Ahmad
 */
class AdDB extends DataDB {

    public static function add_ad($ad){

        $result = 0;

        try {

            $params = array( $ad->file, $ad->type, $ad->link, $ad->width, $ad->height, $ad->active, $ad->order  );

            $query = "INSERT INTO `ads` ( `file`, `type`, `link`, `width`, `height`, `active`, `order` ) "
                . " VALUES ( ?, ?, ?, ?, ?, ?, ? ) ";

            $result = QueryUtil::excute_update( $query, $params );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : add ad', $e );//from php 5.3 no need to custum
        }
        return $result;
    }

    public static function update_ad($ad){

        $result = 0;

        try {

            $params = array( $ad->file, $ad->type, $ad->link, $ad->width, $ad->height, $ad->active, $ad->order  );

            $query = "UPDATE `ads` SET "
                ." `file` = ?, `type` = ?, `link` = ?, `width` = ?, `height` = ?, `active` = ?, `order` = ? "
                ." WHERE `ad_id` = ".$ad->ad_id;

            $result = QueryUtil::excute_update( $query, $params );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : update ad', $e );//from php 5.3 no need to custum
        }
        return $result;
    }

    public static function remove_ad($ad){

        $result = 0;

        try {

            $query = "DELETE FROM `ads` WHERE `ad_id` = ".$ad->ad_id;

            $result = QueryUtil::excute_update( $query );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : remove ad', $e );//from php 5.3 no need to custum
        }
        return $result;
    }


    public static function get_ads($active = -1, $start = -1, $count = -1, $order_by_1 = '`order`', $arrange1 = 'ASC', $order_by_2 = '`ad_id`', $arrange2 = 'ASC'){

        $ads = null;

        try {

            $params = array();

            $query = "SELECT * FROM `ads` WHERE 1 ";

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

            $ads = self::format_objects($buffer);

        } catch (Exception $e) {
             throw new CustomException( 'Error in : get ads', $e );//from php 5.3 no need to custum
        }

        return $ads;
    }

    public static function get_ads_count($active = -1){

        $count = 0;

        try {

            $params = array();

            $query = "SELECT COUNT(*) AS `count` FROM `ads` WHERE 1 ";

            if( $active > 0 ){
                $query .= " AND `active` = 1 ";
            }

            
            $buffer = QueryUtil::excute_select( $query, $params );

            $count = $buffer[0]["count"];

        } catch (Exception $e) {
             throw new CustomException( 'Error in : get ads count', $e );//from php 5.3 no need to custum
        }

        return $count;
    }


    public static function search_ads($ad_params, $start = -1, $count = -1, $order_by = '`ad_id`', $arrange = 'ASC'){

        $ads = array();

        try {
            
            extract( $ad_params );

            $all_empty = true;
            
            $params = array();            
            
            $query = "SELECT * FROM `ads` WHERE 1 ";
            
            if( !empty($ad_id) ){
                $params [] = $ad_id;
                $query    .= " AND `ad_id` = ? ";
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

            $ads = self::format_objects($buffer);

        } catch (Exception $e) {
            throw new CustomException( 'Error in : search ads', $e );//from php 5.3 no need to custum
        }

        return $ads;
    }
    
    public static function search_ads_count( $ad_params ){

        $count = -1;

        try {
            
            extract( $ad_params );
            
            $all_empty = true;
            
            $params = array();            
            
            $query = "SELECT COUNT(*) AS `count` FROM `ads` WHERE 1 ";
            
            if( !empty($ad_id) ){
                $params [] = $ad_id;
                $query    .= " AND `ad_id` = ? ";
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
            throw new CustomException( 'Error in : get ad search result count', $e );//from php 5.3 no need to custum
        }

        return $count;
    }


    public static function get_ad($ad_id){

        $ad = null;

        try {

            $query = "SELECT * FROM `ads` WHERE "
                ." `ad_id` = ".$ad_id;

            
            $buffer = QueryUtil::excute_select( $query );

            $ads = self::format_objects($buffer);

            $ad = $ads[0];

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get ad info', $e );//from php 5.3 no need to custum
        }

        return $ad;
    }

}
?>
