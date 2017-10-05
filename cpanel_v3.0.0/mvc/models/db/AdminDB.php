<?php
/*
 *
 */

/**
 * Description of AdminDB
 *
 * @author Ahmad
 */
class AdminDB extends DataDB {

    public static function add_admin($admin){

        $result = 0;

        try {

            $params = array( $admin->name, $admin->password, $admin->email, $admin->rule_id );

            $query = "INSERT INTO `admins` ( `name`, `password`, `email`, `rule_id` ) "
                . " VALUES ( ?, ?, ?, ? ) ";

            $result = QueryUtil::excute_update( $query, $params );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : add admin', $e );//from php 5.3 no need to custum
        }

        return $result;
    }

    public static function update_admin($admin){

        $result = 0;

        try {

            $params = array( $admin->name, $admin->password, $admin->email, $admin->rule_id );

            $query = "UPDATE `admins` SET "
                ." `name` = ?, `password` = ?, `email` = ?, `rule_id` = ? "
                ." WHERE `admin_id` = ".$admin->admin_id;

            $result = QueryUtil::excute_update( $query, $params );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : update admin', $e );//from php 5.3 no need to custum
        }

        return $result;
    }

    public static function remove_admin($admin){

        $result = 0;

        try {

            $query = "DELETE FROM `admins` WHERE `admin_id` = ".$admin->admin_id;

            $result = QueryUtil::excute_update( $query );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : remove admin', $e );//from php 5.3 no need to custum
        }
        return $result;
    }


    public static function get_admins($start = -1, $count = -1){

        $admins = null;

        try {

            $query = "SELECT * FROM `admins` WHERE 1 ";

            if( $start > -1 && $count > -1 ){
                $query .= " LIMIT ".$start.", ".$count."";
            }

            $buffer = QueryUtil::excute_select( $query );

            $admins = self::format_objects($buffer);

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get admins', $e );//from php 5.3 no need to custum
        }

        return $admins;
    }

    public static function get_admins_count(){

        $count = 0;

        try {

            $query = "SELECT COUNT(*) AS `count` FROM `admins` WHERE 1 ";

            $buffer = QueryUtil::excute_select( $query );

            if( count($buffer) > 0 ) {
                $count = $buffer[0]["count"];
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get admins count', $e );//from php 5.3 no need to custum
        }

        return $count;
    }


    public static function search_admins($options = array(), $start = -1, $count = -1, $order_by = '`admin_id`', $arrange = 'ASC'){

        $admins = array();

        try {
            
            extract( $options );

            $all_empty = true;
            
            $params = array();            
            
            $query = "SELECT * FROM `admins` WHERE 1 ";
            
            if( !empty($admin_id) ){
                $params [] = $admin_id;
                $query    .= " AND `admin_id` = ? ";
                $all_empty = false;
            }
            
            if( !empty($name) ){
                $params [] = $name;
                $query    .= " AND `name` = ? ";
                $all_empty = false;
            }

            if( !empty($email) ){
                $params [] = $email;
                $query .= " AND `email` = ? ";
                $all_empty = false;
            }

            if( !empty($rule_id) ){
                $params [] = $rule_id;
                $query     .= " AND `rule_id` = ? ";
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

            $admins = self::format_objects($buffer);

        } catch (Exception $e) {
            throw new CustomException( 'Error in : search admins', $e );//from php 5.3 no need to custum
        }

        return $admins;
    }

    public static function search_admins_count( $options = array() ){

        $count = -1;

        try {
            
            extract( $options );
            
            $all_empty = true;
            
            $params = array();            
            
            $query = "SELECT COUNT(*) AS `count` FROM `admins` WHERE 1 ";
            
            if( !empty($admin_id) ){
                $params [] = $admin_id;
                $query    .= " AND `admin_id` = ? ";
                $all_empty = false;
            }
            
            if( !empty($name) ){
                $params [] = $name;
                $query    .= " AND `name` = ? ";
                $all_empty = false;
            }

            if( !empty($email) ){
                $params [] = $email;
                $query    .= " AND `email` = ? ";
                $all_empty = false;
            }

            if( !empty($rule_id) ){
                $params [] = $rule_id;
                $query    .= " AND `rule_id` = ? ";
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
            throw new CustomException( 'Error in : get admin search result count', $e );//from php 5.3 no need to custum
        }

        return $count;
    }


    public static function get_admin($admin_id){

        $admin = null;

        try {

            $query = "SELECT * FROM `admins` WHERE "
                ." `admin_id` = ".$admin_id;

            $buffer = QueryUtil::excute_select( $query );

            $admins = self::format_objects($buffer);

            if( count($admins) > 0 ){
                $admin = $admins[0];
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get admin info', $e );//from php 5.3 no need to custum
        }

        return $admin;
    }

    public static function check_admin($name, $password){

        $admin = null;

        try {

            $params = array( $name, $password );

            $query = "SELECT * FROM `admins` "
                ." WHERE `name` = ? AND `password` = ? ";

            $buffer = QueryUtil::excute_select( $query, $params );

            $admins = self::format_objects($buffer);

            if( count($admins) > 0 ){
                $admin = $admins[0];
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : check admin', $e );//from php 5.3 no need to custum
        }

        return $admin;
    }

    /**
     * Returns array of objects that from format query result set
     * output into array of objects
     * @param buffer 2D array that is result of a query
     * @return objects array
     */
    public static function format_objects($buffer){

        $admins = array();

        try {

            for($i=0; $i<count($buffer); $i++){
            
                $admin = (object) $buffer[$i];

                $admins[] = $admin;
            
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : format admin objects', $e );//from php 5.3 no need to custum
        }

        return $admins;
    }

}
?>
