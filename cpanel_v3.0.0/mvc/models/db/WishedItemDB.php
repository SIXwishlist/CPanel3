<?php
/*
 *
 */

/**
 * Description of WishedItemDB
 *
 * @author Ahmad
 */
class WishedItemDB extends DataDB {

    public static function add_item($item){

        $result = 0;

        try {

            $params = array( $item->date, $item->count, $item->product_id, $item->user_id );

            $query = "INSERT INTO `wished_items` ( `date`, `count`, `product_id`, `user_id` ) "
                . " VALUES ( ?, ?, ?, ? ) ";

            $result = QueryUtil::excute_update( $query, $params );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : add item', $e );//from php 5.3 no need to custum
        }

        return $result;
    }

    public static function update_item($item){

        $result = 0;

        try {

            $params = array( $item->date, $item->count, $item->product_id, $item->user_id );

            $query = "UPDATE `wished_items` SET "
                ." `date` = ?, `count` = ?, `product_id` = ?, `user_id` = ? "
                ." WHERE `item_id` = ".$item->item_id;

            $result = QueryUtil::excute_update( $query, $params );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : update item', $e );//from php 5.3 no need to custum
        }
        
        return $result;
    }

    public static function remove_item($item){

        $result = 0;

        try {

            $query = "DELETE FROM `wished_items` WHERE `item_id` = ".$item->item_id;

            $result = QueryUtil::excute_update( $query );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : remove item', $e );//from php 5.3 no need to custum
        }
        
        return $result;
    }


    public static function get_items($user_id = -1, $start = -1, $count = -1, $order_by = '`t1`.`item_id`', $arrange = 'DESC'){

        $items = null;

        try {

            $params = array();
            
            $query = "SELECT `t1`.*,
                             `t2`.`name`     AS `user`,
                             `t3`.`title_ar` AS `product_ar`, `t3`.`title_en` AS `product_en`

                        FROM `wished_items` AS `t1`

                        LEFT JOIN `users`    AS `t2` ON `t2`.`user_id`    = `t1`.`user_id`
                        LEFT JOIN `products` AS `t3` ON `t3`.`product_id` = `t1`.`product_id`

                      WHERE 1 ";

            if( $user_id > 0 ){
                $params[] = $user_id; 
                $query .= " AND `t1`.`user_id` = ? ";
            }

            if( $order_by != '' ){
                $query .= " ORDER BY ".$order_by." ".$arrange;
            }

            if( $start > -1 && $count > -1 ){
                $query .= " LIMIT ".$start.", ".$count."";
            }

            $buffer = QueryUtil::excute_select( $query, $params );

            $items  = self::format_objects($buffer);

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get items', $e );//from php 5.3 no need to custum
        }

        return $items;
    }

    public static function get_items_count($user_id = -1){

        $count = 0;

        try {

            $params = array();

            $query = "SELECT COUNT(*) AS `count` FROM `wished_items` WHERE 1 ";

            if( $user_id > 0 ){
                $params[] = $user_id; 
                $query .= " AND `user_id` = ? ";
            }

            $buffer = QueryUtil::excute_select( $query, $params );

            if( count($buffer) > 0 ) {
                $count = $buffer[0]["count"];
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get items count', $e );//from php 5.3 no need to custum
        }

        return $count;
    }


    public static function search_items($options = array(), $start = -1, $count = -1, $order_by = '`t1`.`item_id`', $arrange = 'DESC'){

        $items = array();

        try {

            extract( $options );

            $all_empty = true;

            $params = array();

            $query = "SELECT `t1`.*,
                             `t2`.`name`     AS `user`,
                             `t3`.`title_ar` AS `product_ar`, `t3`.`title_en` AS `product_en`

                        FROM `wished_items` AS `t1`

                        LEFT JOIN `users`    AS `t2` ON `t2`.`user_id`    = `t1`.`user_id`
                        LEFT JOIN `products` AS `t3` ON `t3`.`product_id` = `t1`.`product_id`

                        WHERE 1 ";

            if( !empty($item_id) ){
                $params [] = $item_id;
                $query    .= " AND `t1`.`item_id` = ? ";
                $all_empty = false;
            }

            if( !empty($amount) ){
                $params [] = $amount;
                $query    .= " AND `t1`.`amount`  = ? ";
                $all_empty = false;
            }

            if( !empty($status) ){
                $params [] = $status;
                $query    .= " AND `t1`.`status`  = ? ";
                $all_empty = false;
            }

            if( !empty($date) ){
                $params [] = $date;
                $query    .= " AND `t1`.`date` = ? ";
                $all_empty = false;
            }

            if( !empty($tnx_id) ){
                $params [] = $tnx_id;
                $query    .= " AND `t1`.`tnx_id` = ? ";
                $all_empty = false;
            }

            if( !empty($product_id) ){
                $params [] = $product_id;
                $query    .= " AND `t1`.`product_id` = ? ";
                $all_empty = false;
            }

            if( !empty($user_id) ){
                $params [] = $user_id;
                $query    .= " AND `t1`.`user_id` = ? ";
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

            $items  = self::format_objects($buffer);

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get search items', $e );//from php 5.3 no need to custum
        }

        return $items;
    }

    public static function search_items_count($options = array()){

        $count = -1;

        try {

            extract( $options );

            $all_empty = true;

            $params = array();

            $query = "SELECT COUNT(*) AS `count` FROM `wished_items` WHERE 1 ";

            if( !empty($item_id) ){
                $params [] = $item_id;
                $query    .= " AND `item_id` = ? ";
                $all_empty = false;
            }

            if( !empty($amount) ){
                $params [] = $amount;
                $query    .= " AND `t1`.`amount`  = ? ";
                $all_empty = false;
            }

            if( !empty($status) ){
                $params [] = $status;
                $query    .= " AND `status`  = ? ";
                $all_empty = false;
            }

            if( !empty($date) ){
                $params [] = $date;
                $query    .= " AND `date` = ? ";
                $all_empty = false;
            }

            if( !empty($tnx_id) ){
                $params [] = $tnx_id;
                $query    .= " AND `tnx_id` = ? ";
                $all_empty = false;
            }

            if( !empty($product_id) ){
                $params [] = $product_id;
                $query    .= " AND `product_id` = ? ";
                $all_empty = false;
            }

            if( !empty($user_id) ){
                $params [] = $user_id;
                $query    .= " AND `user_id` = ? ";
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
            throw new CustomException( 'Error in : get item search result count', $e );//from php 5.3 no need to custum
        }

        return $count;
    }


    public static function get_item($item_id){

        $item = null;

        try {

            $params = array( $item_id );

            $query = "SELECT * FROM `wished_items`
                       WHERE `item_id` =  ? ";

            $buffer = QueryUtil::excute_select( $query, $params );

            $items = self::format_objects($buffer);

            if( count($items) > 0 ){
                $item = $items[0];
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get item info', $e );//from php 5.3 no need to custum
        }

        return $item;
    }

}

?>
