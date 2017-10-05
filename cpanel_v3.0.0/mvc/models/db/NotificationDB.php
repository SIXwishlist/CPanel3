<?php
/*
 *
 */

/**
 * Description of NotificationDB
 *
 * @author Ahmad
 */
class NotificationDB extends DataDB {

    public static function add_notification($notification){

        $result = 0;

        try {

            $params = array( $notification->action, $notification->desc, $notification->time, $notification->status, $notification->user_id, $notification->target_id );

            $query = "INSERT INTO `notifications` ( `action`, `desc`, `time`, `status`, `user_id`, `target_id` ) "
                . " VALUES ( ?, ?, ?, ?, ?, ? ) ";

            $result = QueryUtil::excute_update( $query, $params );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : add notification', $e );//from php 5.3 no need to custum
        }

        return $result;
    }

    public static function update_notification($notification){

        $result = 0;

        try {

            $params = array( $notification->action, $notification->desc, $notification->time, $notification->status, $notification->user_id, $notification->target_id );

            $query = "UPDATE `notifications` SET "
                ." `action` = ?, `desc` = ?, `time` = ?, `status` = ?, `user_id` = ?, `target_id` = ? "
                ." WHERE `not_id` = ".$notification->not_id;

            $result = QueryUtil::excute_update( $query, $params );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : update notification', $e );//from php 5.3 no need to custum
        }

        return $result;
    }

    public static function remove_notification($notification){

        $result = 0;

        try {

            $query = "DELETE FROM `notifications` WHERE `not_id` = ".$notification->not_id;

            $result = QueryUtil::excute_update( $query );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : remove notification', $e );//from php 5.3 no need to custum
        }

        return $result;
    }


    public static function get_notifications($options = array(), $start = -1, $count = -1, $order_by = '`not_id`', $arrange = 'ASC'){

        $notifications = null;

        try {

            extract( $options );

            $params = array();

            $query = "SELECT `t1`.*, `t2`.`name` AS `organization`, `t3`.`name` AS `user`  

                        FROM      `notifications` AS `t1` 
                        LEFT JOIN `organizations` AS `t2` ON `t2`.`org_id`    = `t1`.`target_id` 
                        LEFT JOIN `users`         AS `t3` ON `t3`.`user_id`   = `t1`.`user_id` 

                      WHERE 1 ";

            if( !empty($target_id) ){
                $params[] = $target_id;
                $query   .= " AND `t1`.`target_id` = ? ";
            }

            if( !empty($user_id) ){
                $params[] = $user_id;
                $query   .= " AND `t1`.`user_id` = ? ";
            }

            if( $order_by != '' ){
                $query .= " ORDER BY `t1`.".$order_by." ".$arrange;
            }

            if( $start > -1 && $count > -1 ){
                $query .= " LIMIT ".$start.", ".$count."";
            }

            $buffer = QueryUtil::excute_select( $query, $params );

            $notifications = self::format_objects($buffer);

        } catch (Exception $e) {
             throw new CustomException( 'Error in : get notifications', $e );//from php 5.3 no need to custum
        }

        return $notifications;
    }

    public static function get_notifications_count($options = array()){

        $count = 0;

        try {
            
            extract( $options );

            $params = array();

            $query = "SELECT COUNT(*) AS `count` FROM `notifications` WHERE 1 ";

            if( !empty($target_id) ){
                $params[] = $target_id;
                $query   .= " AND `target_id` = ? ";
            }

            if( !empty($user_id) ){
                $params[] = $user_id;
                $query   .= " AND `user_id` = ? ";
            }
            

            $buffer = QueryUtil::excute_select( $query, $params );

            if( count($buffer) > 0 ){
                $count = $buffer[0]["count"];                
            }

        } catch (Exception $e) {
             throw new CustomException( 'Error in : get notifications count', $e );//from php 5.3 no need to custum
        }

        return $count;
    }


    public static function search_notifications($options = array(), $start = -1, $count = -1, $order_by = '`not_id`', $arrange = 'ASC'){

        $notifications = null;

        try {

            extract( $options );

            $all_empty = true;

            $params = array();

            $query = "SELECT * FROM `notifications` WHERE 1 ";
            
            if( !empty($target_id) ){
                $params[] = $target_id;
                $query   .= " AND `target_id` = ? ";
                $all_empty = false;
            }

            if( !empty($user_id) ){
                $params[] = $user_id;
                $query   .= " AND `user_id` = ? ";
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

            $notifications = self::format_objects($buffer);

        } catch (Exception $e) {
             throw new CustomException( 'Error in : get notifications', $e );//from php 5.3 no need to custum
        }

        return $notifications;
    }

    public static function search_notifications_count($options = array()){

        $count = 0;

        try {

            extract( $options );

            $all_empty = true;

            $params = array();

            $query = "SELECT COUNT(*) AS `count` FROM `notifications` WHERE 1 ";

            if( !empty($target_id) ){
                $params[] = $target_id;
                $query   .= " AND `target_id` = ? ";
                $all_empty = false;
            }

            if( !empty($user_id) ){
                $params[] = $user_id;
                $query   .= " AND `user_id` = ? ";
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
             throw new CustomException( 'Error in : get notifications count', $e );//from php 5.3 no need to custum
        }

        return $count;
    }


    public static function get_notification($not_id){

        $notification = null;

        try {

            $query = "SELECT * FROM `notifications` WHERE "
                ." `not_id` = ".$not_id;

            $buffer = QueryUtil::excute_select( $query );

            $notifications = self::format_objects($buffer);

            if( count($notifications) > 0 ){
                $notification = $notifications[0];
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get notification info', $e );//from php 5.3 no need to custum
        }

        return $notification;
    }

}
?>
