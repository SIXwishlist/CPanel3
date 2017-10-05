<?php
/*
 *
 */


/**
 * Description of AdvertManageController
 *
 * @author Ahmad
 */

class PushNotificationControllerUtil {

    public static $date_format = 'Y-m-d H:i:s';

    public static function send_to_multiple_devices($message, $phones){
        
        $status = -1;

        try {

            $registrationIDs = array();
            $deviceTokens    = array();

            $isAndroid = false;

            $android_devices = DeviceAndroidDB::get_devices_in($phones);
            
            if( ! empty($android_devices) ){
                
                $isAndroid = true;
                
                foreach ($android_devices as $android_device ){
                    $registrationIDs[] = $android_device->reg_id;
                }
                
                $status = PushUtil::send_android_push_notification($message, $registrationIDs);
                
            }

            if( ! $isAndroid ){
                
                $ios_devices = DeviceIOSDB::get_devices_in($phones);

                if( ! empty($ios_devices) ){

                    foreach ($ios_devices as $ios_device ){
                        $deviceTokens[] = $ios_device->device_token;
                    }

                    $status = PushUtil::send_ios_push_notification($message, $deviceTokens);

                }

            }

        } catch (Exception $e) {
            throw new CustomException( $e->getMessage(), ERROR );
        }

        return $status;

    }
    
    public static function send_to_single_device($message, $phone){
        
        $status = -1;

        try {

            $registrationIDs = array();
            $deviceTokens    = array();

            $isAndroid = false;

            $android_device       = DeviceAndroidDB::get_device_by($phone);
            
            if( ! empty($android_device) ){
                
                $isAndroid = true;
                
                $registrationIDs[] = $android_device->reg_id;
                
                $status = PushUtil::send_android_push_notification($message, $registrationIDs);
                
            }

            if( ! $isAndroid ){
                
                $ios_device = DeviceIOSDB::get_device_by($phone);

                if( ! empty($ios_device) ){

                    $deviceTokens[] = $ios_device->device_token;

                    $status = PushUtil::send_ios_push_notification($message, $deviceTokens);

                }

            }

        } catch (Exception $e) {
            throw new CustomException( $e->getMessage(), ERROR );
        }

        return $status;

    }
    
    public static function send_broadcast($message, $app_id=-1){

        $status = -1;

        try {

            $registrationIDs = array();
            $deviceTokens    = array();


            
            $android_devices = DeviceAndroidDB::get_devices($app_id);

            foreach ($android_devices as $android_device){
                $registrationIDs[] = $android_device->reg_id;
            }

            $status = PushUtil::send_android_push_notification($message, $registrationIDs);



            $ios_devices = DeviceIOSDB::get_devices($app_id);

            foreach ($ios_devices as $ios_device){
                $deviceTokens[] = $ios_device->device_token;
            }

            $status = PushUtil::send_ios_push_notification($message, $deviceTokens);


        } catch (Exception $e) {
            throw new CustomException( $e->getMessage(), ERROR );
        }

        return $status;

    }

}

?>