<?php
/*
 *
 */

/**
 * Description of PaymentDB
 *
 * @author Ahmad
 */
class PaymentDB extends DataDB {

    public static function add_payment($payment){

        $result = 0;

        try {

            $params = array( $payment->amount, $payment->status, $payment->date, $payment->tnx_id, $payment->quantity, $payment->product_id, $payment->user_id );

            $query = "INSERT INTO `payments` ( `amount`, `status`, `date`, `tnx_id`, `quantity`, `product_id`, `user_id` ) "
                . " VALUES ( ?, ?, ?, ?, ?, ?, ? ) ";

            $result = QueryUtil::excute_update( $query, $params );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : add payment', $e );//from php 5.3 no need to custum
        }

        return $result;
    }

    public static function update_payment($payment){

        $result = 0;

        try {

            $params = array( $payment->amount, $payment->status, $payment->date, $payment->tnx_id, $payment->quantity, $payment->product_id, $payment->user_id );

            $query = "UPDATE `payments` SET "
                ." `amount` = ?, `status` = ?, `date` = ?, `tnx_id` = ?, `quantity` = ?, `product_id` = ?, `user_id` = ? "
                ." WHERE `payment_id` = ".$payment->payment_id;

            $result = QueryUtil::excute_update( $query, $params );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : update payment', $e );//from php 5.3 no need to custum
        }
        return $result;
    }

    public static function remove_payment($payment){

        $result = 0;

        try {

            $query = "DELETE FROM `payments` WHERE `payment_id` = ".$payment->payment_id;

            $result = QueryUtil::excute_update( $query );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : remove payment', $e );//from php 5.3 no need to custum
        }
        return $result;
    }

       
    public static function add_payment_list($payment_records){

        $result = 0;

        try {

            $params      = array();
            $ques_marks  = array();
            
            $query = "INSERT INTO `payments` ( `amount`, `status`, `date`, `tnx_id`, `quantity`, `product_id`, `user_id`  ) VALUES ";
            
            foreach ($payment_records as $payment) {

                $params = array_merge( $params, array( $payment->amount, $payment->status, $payment->date, $payment->tnx_id, $payment->quantity, $payment->product_id, $payment->user_id ) );
                
                $ques_marks [] = " ( ?, ?, ?, ?, ?, ?, ? ) ";
                
            }
            
            $query  .= implode(", ", $ques_marks);

            $result  = QueryUtil::excute_update( $query, $params );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : add payments list', $e );//from php 5.3 no need to custum
        }
        
        return $result;
    }


    public static function get_payments($user_id = -1, $start = -1, $count = -1, $order_by = '`payment_id`', $arrange = 'DESC'){

        $payments = null;

        try {

            $params = array();
            
            $query = "SELECT `t1`.*,
                             `t2`.`name`     AS `user`,
                             `t3`.`title_ar` AS `product_ar`, `t3`.`title_en` AS `product_en` 

                        FROM `payments` AS `t1`

                        LEFT JOIN `users`    AS `t2` ON `t2`.`user_id`    = `t1`.`user_id` 
                        LEFT JOIN `products` AS `t3` ON `t3`.`product_id` = `t1`.`product_id` 

                        WHERE 1 ";

            if( $user_id > 0 ){
                $params[] = $user_id; 
                $query .= " AND `t1`.`user_id` = ? ";
            }

            if( $order_by != '' ){
                $query .= " ORDER BY `t1`.".$order_by." ".$arrange;
            }

            if( $start > -1 && $count > -1 ){
                $query .= " LIMIT ".$start.", ".$count."";
            }

            $buffer = QueryUtil::excute_select( $query, $params );

            $payments = self::format_objects($buffer);

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get payments', $e );//from php 5.3 no need to custum
        }

        return $payments;
    }

    public static function get_payments_count($user_id = -1){

        $count = 0;

        try {
            
            $params = array();

            $query = "SELECT COUNT(*) AS `count` FROM `payments` WHERE 1 ";


            if( $user_id > 0 ){
                $params[] = $user_id; 
                $query .= " AND `user_id` = ? ";
            }

            $buffer = QueryUtil::excute_select( $query, $params );

            if( count($buffer) > 0 ) {
                $count = $buffer[0]["count"];
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get payments count', $e );//from php 5.3 no need to custum
        }

        return $count;
    }


    public static function search_payments($options = array(), $start = -1, $count = -1, $order_by = '`payment_id`', $arrange = 'DESC'){

        $payments = array();

        try {

            extract( $options );

            $all_empty = true;

            $params = array();

            $query = "SELECT `t1`.*,
                             `t2`.`name`     AS `user`,
                             `t3`.`title_ar` AS `product_ar`, `t3`.`title_en` AS `product_en` 

                        FROM `payments` AS `t1`

                        LEFT JOIN `users`    AS `t2` ON `t2`.`user_id`    = `t1`.`user_id` 
                        LEFT JOIN `products` AS `t3` ON `t3`.`product_id` = `t1`.`product_id` 

                        WHERE 1 ";
    
            if( !empty($payment_id) ){
                $params [] = $payment_id;
                $query    .= " AND `t1`.`payment_id` = ? ";
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
                $query .= " ORDER BY `t1`.".$order_by." ".$arrange;
            }

            if( $start > -1 && $count > -1 ){
                $query .= " LIMIT ".$start.", ".$count."";
            }

            $buffer = QueryUtil::excute_select( $query, $params );

            $payments  = self::format_objects($buffer);

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get search payments', $e );//from php 5.3 no need to custum
        }

        return $payments;
    }

    public static function search_payments_count($options = array()){

        $count = -1;

        try {

            extract( $options );

            $all_empty = true;

            $params = array();

            $query = "SELECT COUNT(*) AS `count` FROM `payments` WHERE 1 ";

            if( !empty($payment_id) ){
                $params [] = $payment_id;
                $query    .= " AND `payment_id` = ? ";
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
            throw new CustomException( 'Error in : get payment search result count', $e );//from php 5.3 no need to custum
        }

        return $count;
    }


    public static function get_payment($payment_id){

        $payment = null;

        try {

            $params = array( $payment_id );

            $query = "SELECT * FROM `payments`
                       WHERE `payment_id` =  ? ";

            $buffer = QueryUtil::excute_select( $query, $params );

            $payments = self::format_objects($buffer);

            if( count($payments) > 0 ){
                $payment = $payments[0];
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get payment info', $e );//from php 5.3 no need to custum
        }

        return $payment;
    }

}

?>
