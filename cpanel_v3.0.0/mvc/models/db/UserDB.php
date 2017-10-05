<?php
/*
 *
 */

/**
 * Description of UserDB
 *
 * @author Ahmad
 */
class UserDB extends DataDB {

    public static function add_user($user){

        $result = 0;

        try {

            $user->birth_date   = ( $user->birth_date   == '' ) ? null : $user->birth_date;
            $user->suspend_date = ( $user->suspend_date == '' ) ? null : $user->suspend_date;

            $params = array( $user->name, $user->password, $user->email, $user->phone, $user->key, $user->code, $user->icon, $user->birth_date, $user->gender, $user->country, $user->created, $user->updated, $user->suspend_date, $user->status, $user->rule_id, $user->options );

            $query = "INSERT INTO `users` ( `name`, `password`, `email`, `phone`, `key`, `code`, `icon`, `birth_date`, `gender`, `country`, `created`, `updated`, `suspend_date`, `status`, `rule_id`, `options` ) "
                . " VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? ) ";

            $result = QueryUtil::excute_update( $query, $params );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : add user', $e );//from php 5.3 no need to custum
        }

        return $result;
    }

    public static function update_user($user){

        $result = 0;

        try {
            
            $user->birth_date   = ( $user->birth_date   == '' ) ? null : $user->birth_date;
            $user->suspend_date = ( $user->suspend_date == '' ) ? null : $user->suspend_date;

            $params = array( $user->name, $user->password, $user->email, $user->phone, $user->key, $user->code, $user->icon, $user->birth_date, $user->gender, $user->country, $user->created, $user->updated, $user->suspend_date, $user->status, $user->rule_id, $user->options );
            
            $query = "UPDATE `users` SET "
                ." `name` = ?, `password` = ?, `email` = ?, `phone` = ?, `key` = ?, `code` = ?, `icon` = ?, `birth_date` = ?, `gender` = ?, `country` = ?, `created` = ?, `updated` = ?, `suspend_date` = ?, `status` = ?, `rule_id` = ?, `options` = ? "
                ." WHERE `user_id` = ".$user->user_id;

            $result = QueryUtil::excute_update( $query, $params );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : update user', $e );//from php 5.3 no need to custum
        }
        
        return $result;
    }

    public static function remove_user($user){

        $result = 0;

        try {

            $query = "DELETE FROM `users` WHERE `user_id` = ".$user->user_id;

            $result = QueryUtil::excute_update( $query );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : remove user', $e );//from php 5.3 no need to custum
        }
        
        return $result;
    }


    public static function get_users($start = -1, $count = -1, $order_by = '`user_id`', $arrange = 'DESC'){

        $users = null;

        try {

            $query = "SELECT * FROM `users` WHERE 1 ";

            if( $order_by != '' ){
                $query .= " ORDER BY ".$order_by." ".$arrange;
            }

            if( $start > -1 && $count > -1 ){
                $query .= " LIMIT ".$start.", ".$count."";
            }

            $buffer = QueryUtil::excute_select( $query );

            $users  = self::format_objects($buffer);

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get users', $e );//from php 5.3 no need to custum
        }

        return $users;
    }

    public static function get_users_count(){

        $count = 0;

        try {

            $query = "SELECT COUNT(*) AS `count` FROM `users` WHERE 1 ";

            $buffer = QueryUtil::excute_select( $query );

            if( count($buffer) > 0 ) {
                $count = $buffer[0]["count"];
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get users count', $e );//from php 5.3 no need to custum
        }

        return $count;
    }


    public static function quick_search_users($name, $order_by = '`user_id`', $arrange = 'DESC'){

        $users = array();

        try {

            $params = array( "%". $name ."%" );
            
            $query = "SELECT * FROM `users` 
                      WHERE 1 
                          AND `name` LIKE ? ";

            if( $order_by != '' ){
                $query .= " ORDER BY ".$order_by." ".$arrange;
            }
            
            $buffer = QueryUtil::excute_select( $query, $params );

            $users  = self::format_objects($buffer);
            
        } catch (Exception $e) {
            throw new CustomException( 'Error in : get search users', $e );//from php 5.3 no need to custum
        }

        return $users;
    }


    public static function search_users($options = array(), $start = -1, $count = -1, $order_by = '`user_id`', $arrange = 'DESC'){

        $users = array();

        try {
            
            extract( $options );
            
            $all_empty = true;
            
            $params = array();
            
            $query = "SELECT * FROM `users` 
                      WHERE 1 ";
            
            if( !empty($user_id) ){
                $params [] = $user_id;
                $query    .= " AND `user_id` = ? ";
                $all_empty = false;
            }

            if( !empty($name) ){
                $params [] = $name;
                $query    .= " AND `name`  = ? ";
                $all_empty = false;
            }
            
            if( !empty($email) ){
                $params [] = $email;
                $query    .= " AND `email` = ? ";
                $all_empty = false;
            }
            
            if( !empty($phone) ){
                $params [] = $phone;
                $query    .= " AND `phone` = ? ";
                $all_empty = false;
            }
            
            if( !empty($country) ){
                $params [] = $country;
                $query    .= " AND `country` = ? ";
                $all_empty = false;
            }
            
            if( !empty($status) ){
                $params [] = $status;
                $query    .= " AND `status` = ? ";
                $all_empty = false;
            }
            
            if( !empty($status_list) ){

                $qmarks        = array_fill(0, count($status_list), "?");
                $qmarks_string = implode(", ", $qmarks);
                
                $params = array_merge($params, $status_list);
                $query .= " AND `status` IN ( ".$qmarks_string." ) ";

                $all_empty = false;
            }
            
            if( !empty($rule_id) ){
                $params [] = $rule_id;
                $query    .= " AND `rule_id` = ? ";
                $all_empty = false;
            }
            
            if( !empty($rule_list) ){

                $qmarks        = array_fill(0, count($rule_list), "?");
                $qmarks_string = implode(", ", $qmarks);
                
                $params = array_merge($params, $rule_list);
                $query .= " AND `rule_id` IN ( ".$qmarks_string." ) ";

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

            $users  = self::format_objects($buffer);
            
        } catch (Exception $e) {
            throw new CustomException( 'Error in : get search users', $e );//from php 5.3 no need to custum
        }

        return $users;
    }

    public static function search_users_count($options = array()){

        $count = -1;

        try {
            
            extract( $options );
            
            $all_empty = true;
            
            $params = array();
            
            $query = "SELECT COUNT(*) AS `count` FROM `users`
                      WHERE 1 ";
            
            if( !empty($user_id) ){
                $params [] = $user_id;
                $query    .= " AND `user_id` = ? ";
                $all_empty = false;
            }

            if( !empty($name) ){
                $params [] = $name;
                $query    .= " AND `name`  = ? ";
                $all_empty = false;
            }
            
            if( !empty($email) ){
                $params [] = $email;
                $query    .= " AND `email` = ? ";
                $all_empty = false;
            }
            
            if( !empty($phone) ){
                $params [] = $phone;
                $query    .= " AND `phone` = ? ";
                $all_empty = false;
            }
            
            if( !empty($country) ){
                $params [] = $country;
                $query    .= " AND `country` = ? ";
                $all_empty = false;
            }
            
            if( !empty($status) ){
                $params [] = $status;
                $query    .= " AND `status` = ? ";
                $all_empty = false;
            }
            
            if( !empty($status_list) ){

                $qmarks        = array_fill(0, count($status_list), "?");
                $qmarks_string = implode(", ", $qmarks);
                
                $params = array_merge($params, $status_list);
                $query .= " AND `status` IN ( ".$qmarks_string." ) ";

                $all_empty = false;
            }
            
            if( !empty($rule_id) ){
                $params [] = $rule_id;
                $query    .= " AND `rule_id` = ? ";
                $all_empty = false;
            }
            
            if( !empty($rule_list) ){

                $qmarks        = array_fill(0, count($rule_list), "?");
                $qmarks_string = implode(", ", $qmarks);
                
                $params = array_merge($params, $rule_list);
                $query .= " AND `rule_id` IN ( ".$qmarks_string." ) ";

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
            throw new CustomException( 'Error in : get user search result count', $e );//from php 5.3 no need to custum
        }

        return $count;
    }


    public static function get_product_phone($product_id){

        $user = null;

        try {

            $params = array( $product_id );

            $query = "SELECT `t1`.`advertiser_phone`, 
                             `t2`.`phone` AS `user_phone`

                        FROM      `products` AS `t1` 
                        LEFT JOIN `users`    AS `t2` ON `t1`.`user_id` = `t2`.`user_id`

                    WHERE 1 

                       AND `t1`.`product_id` = ? ";

            $buffer = QueryUtil::excute_select( $query, $params );

            $users = self::format_objects($buffer);

            if( count($users) > 0 ){
                $user = $users[0];
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get user info', $e );//from php 5.3 no need to custum
        }

        return $user;
    }


    public static function get_user($user_id){

        $user = null;

        try {

            $params = array( $user_id );

            $query = "SELECT * FROM `users`  
                       WHERE `user_id` =  ? ";

            $buffer = QueryUtil::excute_select( $query, $params );

            $users = self::format_objects($buffer);

            if( count($users) > 0 ){
                $user = $users[0];
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get user info', $e );//from php 5.3 no need to custum
        }

        return $user;
    }
    
    public static function get_user_join_dial($user_id){

        $user = null;

        try {

            $params = array( $user_id );

            $query = "SELECT `t1`.*, `t2`.`dial`
                        FROM      `users`     AS `t1` 
                        LEFT JOIN `countries` AS `t2` ON `t2`.`code`= `t1`.`country`
                    WHERE `t1`.`user_id` =  ? ";

            $buffer = QueryUtil::excute_select( $query, $params );

            $users = self::format_objects($buffer);

            if( count($users) > 0 ){
                $user = $users[0];
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get user join dial', $e );//from php 5.3 no need to custum
        }

        return $user;
    }

    public static function get_user_with($options){

        $user = null;

        try {
            
            extract( $options );

            $all_empty = true;
            
            $params = array();            
            
            $query = "SELECT * FROM `users` WHERE 0 ";
            
            if( !empty($name) ){
                $params [] = $name;
                $query .= " OR `name` = ? ";
                //$all_empty = false;
            }

            if( !empty($email) ){
                $params [] = $email;
                $query .= " OR `email` = ? ";
                //$all_empty = false;
            }

            if( !empty($phone) ){
                $params [] = $phone;
                $query .= " OR `phone` = ? ";
                //$all_empty = false;
            }
            
            $buffer = QueryUtil::excute_select( $query, $params );

            $users = self::format_objects($buffer);
            
            if( count($users) > 0 ){
                $user = $users[0];
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get user with options', $e );//from php 5.3 no need to custum
        }
        
        return $user;
    }

    public static function check_user($email_or_phone, $password){

        $user = null;

        try {

            $params = array( $email_or_phone, $email_or_phone, $password );

            $query = " SELECT * FROM `users` 
                       WHERE 1 
                           AND ( `email` = ? OR `phone` = ? ) 
                           AND `password` = ? ";

            $buffer = QueryUtil::excute_select( $query, $params );

            $users = self::format_objects($buffer);

            if( count($users) > 0 ){
                $user = $users[0];
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : check user', $e );//from php 5.3 no need to custum
        }

        return $user;
    }

    /* overrides function */
    public static function format_objects($buffer){

        $objects = array();

        try {

            for($i=0; $i<count($buffer); $i++){

                $object = (object) $buffer[$i];

                $object->real_estate = ( ($object->options & REAL_ESTATE) > 0 ) ? 1 : 0;

                $objects[] = $object;
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : format objects', $e );//from php 5.3 no need to custum
        }

        return $objects;
    }
    
}

?>
