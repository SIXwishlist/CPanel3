<?php
/*
 *
 */

/**
 * Description of DeviceAndroidDB
 *
 * @author Ahmad
 */
class DeviceAndroidDB extends DataDB {

    public static function add_device($device){

        $result = 0;

        try {

            $params = array( $device->reg_id, $device->phone, $device->created, $device->app_id );
            
            $query = "INSERT INTO `devices_android` ( `reg_id`, `phone`, `created`, `app_id` ) "
                . " VALUES ( ?, ?, ?, ? ) ";

            $result = QueryUtil::excute_update( $query, $params );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : add device', $e );//from php 5.3 no need to custum
        }
        
        return $result;
    }

    public static function update_device($device){

        $result = 0;

        try {

            $params = array( $device->reg_id, $device->phone, $device->created, $device->app_id );

            $query = "UPDATE `devices_android` SET "
                ." `reg_id` = ?, `phone` = ?, `created` = ?, `app_id` = ? "
                ." WHERE `dev_id` = ".$device->dev_id;

            $result = QueryUtil::excute_update( $query, $params );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : update device', $e );//from php 5.3 no need to custum
        }
        
        return $result;
    }

    public static function remove_device($device){

        $result = 0;

        try {

            $query = "DELETE FROM `devices_android` WHERE `dev_id` = ".$device->dev_id;

            $result = QueryUtil::excute_update( $query );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : remove device', $e );//from php 5.3 no need to custum
        }

        return $result;
    }

    
    public static function get_devices($app_id = -1, $start = -1, $count = -1, $order_by = '`dev_id`', $arrange = 'ASC'){

        $devices = null;

        try {

            $params = array();

            $query = "SELECT * FROM `devices_android` WHERE 1 ";

            if( $app_id > 0 ){
                $params[] = $app_id;
                $query .= " AND `app_id` = ? ";
            }

            if( $order_by != '' ){
                $query .= " ORDER BY ".$order_by." ".$arrange;
            }

            if( $start > -1 && $count > -1 ){
                $query .= " LIMIT ".$start.", ".$count."";
            }


            $buffer = QueryUtil::excute_select( $query, $params );

            $devices = self::format_device_objects($buffer);

        } catch (Exception $e) {
             throw new CustomException( 'Error in : get devices', $e );//from php 5.3 no need to custum
        }

        return $devices;
    }

    public static function get_devices_count($app_id = -1){

        $count = 0;

        try {

            $params = array();

            $query = "SELECT COUNT(*) AS `count` FROM `devices_android` WHERE 1 ";

            if( $app_id > 0 ){
                $params[] = $app_id;
                $query .= " AND `app_id` = ? ";
            }

            $buffer = QueryUtil::excute_select( $query, $params );

            if( count($buffer) > 0 ) {
                $count = $buffer[0]["count"];
            }

        } catch (Exception $e) {
             throw new CustomException( 'Error in : get devices count', $e );//from php 5.3 no need to custum
        }

        return $count;
    }

    
    public static function add_or_update_device($device){

        $result = 0;

        try {
            
            $params = array(
                $device->reg_id, $device->phone, $device->created,
                $device->reg_id, $device->phone, $device->created
            );
            
            $query = "INSERT INTO `devices_android` ( `reg_id`, `phone`, `created` ) "
                        . " VALUES( ?, ?, ? ) "
                    
                   . " ON DUPLICATE KEY "
                    
                   . " UPDATE `reg_id` = ?, `phone` = ?, `created` = ? ";

            $result = QueryUtil::excute_update( $query, $params );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : add or update device', $e );//from php 5.3 no need to custum
        }
        
        return $result;
    }

    
    public static function get_devices_in($phones){

        $devices = array();

        try {

            $params = array();
            
            $query = "SELECT * FROM `devices_android` WHERE 1 ";
                        
            if( !empty($phones) && count($phones) > 0 ){
                
                $qmarks = array_fill(0, count($phones), "?");
                
                $qmarks_string = implode(", ", $qmarks);
                
                $params = array_merge($params, $phones);
                $query .= " AND `phone` IN ( ".$qmarks_string." ) ";

            }
            

            $buffer = QueryUtil::excute_select( $query, $params );

            $devices = self::format_device_objects($buffer);

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get device info', $e );//from php 5.3 no need to custum
        }

        return $devices;
    }
    
    
    public static function get_device_by($phone){

        $device = null;

        try {

            $params = array();
            
            $query = "SELECT * FROM `devices_android` WHERE "
                ." `phone` = ? ";
            
            $params[] = $phone;
            

            $buffer = QueryUtil::excute_select( $query, $params );

            $devices = self::format_device_objects($buffer);

            $device = $devices[0];

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get device info', $e );//from php 5.3 no need to custum
        }

        return $device;
    }
    
    public static function get_device($dev_id){

        $device = null;

        try {

            $query = "SELECT * FROM `devices_android` WHERE "
                ." `dev_id` = ".$dev_id;


            $buffer = QueryUtil::excute_select( $query );

            $devices = self::format_device_objects($buffer);

            $device = $devices[0];

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get device info', $e );//from php 5.3 no need to custum
        }

        return $device;
    }


    /**
     * Returns array of objects that from format query result set
     * output into array of objects
     * @param buffer 2D array that is result of a query
     * @return objects array
     */
    public static function format_device_objects($buffer){

        $devices = array();

        try {

            for($i=0; $i<count($buffer); $i++){

                $device = (object) $buffer[$i];

                $devices[] = $device;
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : format device objects', $e );//from php 5.3 no need to custum
        }

        return $devices;
    }

}
?>
