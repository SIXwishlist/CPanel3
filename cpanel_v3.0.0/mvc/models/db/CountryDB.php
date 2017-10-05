<?php
/*
 *
 */

/**
 * Description of CountryDB
 *
 * @author Ahmad
 */

class CountryDB extends DataDB {

    public static function add_country($country){

        $result = 0;

        try {

            $params = array( $country->code, $country->name );

            $query = "INSERT INTO `countries` ( `code`, `name` ) "
                . " VALUES ( ?, ? ) ";

            $result = QueryUtil::excute_update( $query, $params );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : add country', $e );//from php 5.3 no need to custum
        }
        
        return $result;
    }

    public static function update_country($country){

        $result = 0;

        try {

            $params = array( $country->code, $country->name );

            $query = "UPDATE `countries` SET "
                ." `code` = ?, `name` = ? "
                ." WHERE `country_id` = ".$country->country_id;

            $result = QueryUtil::excute_update( $query, $params );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : update country', $e );//from php 5.3 no need to custum
        }
        
        return $result;
    }

    public static function remove_country($country){

        $result = 0;

        try {

            $query = "DELETE FROM `countries` WHERE `country_id` = ".$country->country_id;

            $result = QueryUtil::excute_update( $query );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : remove country', $e );//from php 5.3 no need to custum
        }

        return $result;
    }



    public static function get_countries($start = -1, $count = -1, $order_by = '`country_id`', $arrange = 'ASC'){

        $countries = null;

        try {

            $params = array();

            $query = "SELECT * FROM `countries` WHERE 1 ";

            if( $order_by != '' ){
                $query .= " ORDER BY ".$order_by." ".$arrange;
            }

            if( $start > -1 && $count > -1 ){
                $query .= " LIMIT ".$start.", ".$count."";
            }

            $buffer = QueryUtil::excute_select( $query, $params );

            $countries = self::format_country_objects($buffer);

        } catch (Exception $e) {
             throw new CustomException( 'Error in : get countries', $e );//from php 5.3 no need to custum
        }

        return $countries;
    }

    public static function get_countries_count(){

        $count = 0;

        try {

            $params = array();

            $query = "SELECT COUNT(*) AS `count` FROM `countries` WHERE 1 ";
            
            $buffer = QueryUtil::excute_select( $query, $params );

            if( count($buffer) > 0 ){
                $count = $buffer[0]["count"];
            }

        } catch (Exception $e) {
             throw new CustomException( 'Error in : get countries count', $e );//from php 5.3 no need to custum
        }

        return $count;
    }

    public static function get_country_by_code($code){

        $country = null;

        try {
            
            $params = array();

            $query = "SELECT * FROM `countries` WHERE "
                ." `code` = ? ";

            $params [] = $code;
            
            $buffer = QueryUtil::excute_select( $query, $params );

            $countries = self::format_country_objects($buffer);

            if( count($countries) > 0 ){
                $country = $countries[0];
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get country info', $e );//from php 5.3 no need to custum
        }

        return $country;
    }

    public static function get_country($country_id){

        $country = null;

        try {

            $query = "SELECT * FROM `countries` WHERE "
                ." `country_id` = ".$country_id;

            $buffer = QueryUtil::excute_select( $query );

            $countries = self::format_country_objects($buffer);

            if( count($countries) > 0 ){
                $country = $countries[0];
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get country info', $e );//from php 5.3 no need to custum
        }

        return $country;
    }


    /**
     * Returns array of objects that from format query result set
     * output into array of objects
     * @param buffer 2D array that is result of a query
     * @return objects array
     */
    public static function format_country_objects($buffer){

        $countries = array();

        try {

            for($i=0; $i<count($buffer); $i++){

                $country = (object) $buffer[$i];
                
                $countries[] = $country;
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : format country objects', $e );//from php 5.3 no need to custum
        }

        return $countries;
    }

}

?>
