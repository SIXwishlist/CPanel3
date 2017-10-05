<?php
/*
 *
 */

/**
 * Description of UserRuleDB
 *
 * @author Ahmad
 */
class UserRuleDB extends DataDB {

    public static function add_user_rule($user_rule){

        $result = 0;

        try {

            $params = array( $user_rule->name );

            $query = "INSERT INTO `user_rules` ( `name` ) "
                . " VALUES ( ? ) ";

            $result = QueryUtil::excute_update( $query, $params );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : add user', $e );//from php 5.3 no need to custum
        }
        return $result;
    }

    public static function update_user_rule($user_rule){

        $result = 0;

        try {

            $params = array( $user_rule->name );

            $query = "UPDATE `user_rules` SET "
                ." `name` = ? "
                ." WHERE `rule_id` = ".$user_rule->rule_id;

            $result = QueryUtil::excute_update( $query, $params );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : update user', $e );//from php 5.3 no need to custum
        }
        return $result;
    }

    public static function remove_user_rule($user_rule){

        $result = 0;

        try {

            $query = "DELETE FROM `user_rules` WHERE `rule_id` = ".$user_rule->rule_id;

            $result = QueryUtil::excute_update( $query );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : remove user', $e );//from php 5.3 no need to custum
        }
        return $result;
    }

    public static function get_user_rules($start = -1, $count = -1){

        $user_rules = null;

        try {

            $query = "SELECT * FROM `user_rules` WHERE 1 ";

            if( $start > -1 && $count > -1 ){
                $query .= " LIMIT ".$start.", ".$count."";
            }


            $buffer = QueryUtil::excute_select( $query );

            $user_rules = self::format_user_rule_objects($buffer);

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get user rules', $e );//from php 5.3 no need to custum
        }

        return $user_rules;
    }

    public static function get_user_rules_count(){

        $count = 0;

        try {

            $query = "SELECT COUNT(*) AS `count` FROM `user_rules` WHERE 1 ";


            $buffer = QueryUtil::excute_select( $query );

            if( count($buffer) > 0 ) {
                $count = $buffer[0]["count"];
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get user rules count', $e );//from php 5.3 no need to custum
        }

        return $count;
    }

    public static function get_user_rule($rule_id){

        $user_rule = null;

        try {
            
            $query = " SELECT * FROM `user_rules`"
                    . " WHERE `rule_id` = ".$rule_id;
            

            $buffer = QueryUtil::excute_select( $query );

            $user_rules = self::format_user_rule_objects($buffer);

            $user_rule = $user_rules[0];

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get user rule info', $e );//from php 5.3 no need to custum
        }

        return $user_rule;
    }

    /**
     * Returns array of objects that from format query result set
     * output into array of objects
     * @param buffer 2D array that is result of a query
     * @return objects array
     */
    public static function format_user_rule_objects($buffer){

        $user_rules = array();

        try {

            for($i=0; $i<count($buffer); $i++){
            
                $user_rule = (object) $buffer[$i];

                $user_rules[] = $user_rule;
            
            }


        } catch (Exception $e) {
            throw new CustomException( 'Error in : format user rule objects', $e );//from php 5.3 no need to custum
        }
        
        return $user_rules;
    }

}

?>
