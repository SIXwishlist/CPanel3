<?php
/*
 *
 */

define ('ZAIN_SUCCESS_CODE', 'I01');

/**
 * Description of MobileUtil
 *
 * @author Ahmad
 */

class MobileUtil {

    public static function send_sms($phone, $msg){
        
        $status = SERVER_ERROR;

        try {

            $status   = self::send_jormall_sms($phone, $msg);
            
        } catch (Exception $e) {
            throw new CustomException( 'Error in: send sms ', $e );//from php 5.3 no need to custum
        }
        
        return $status;
    }

    public static function send_jormall_sms($phone, $msg){
        
        $status = SERVER_ERROR;

        try {

            $sender_id = urlencode(SENDER_ID);//'Arak%20tech';
            $acc_name  = ACC_NAME;//'araktech';;
            $acc_pass  = ACC_PASS;//'araktech123';
            
            //$path_template = 'http://josmsservice.com/smsonline/msgservicejo.cfm?numbers=%s&senderid=%s&AccName=%s&AccPass=%s&msg=%s';
            $path_template = PATH_TEMPLATE;
            
            $path   = sprintf($path_template, $phone, $sender_id, $acc_name, $acc_pass, $msg);

            $output = file_get_contents($path);

            $pos    = strpos($output, 'MsgID');
            $pos    = strpos($output, '=', $pos);
            $length = strlen($output);
            
            $msg_id  = trim(  substr(  $output , $pos+1, ($length-($pos+2))  )  );
            
            //Logger::log( $output, INFO );

            $status   = ( $msg_id > 0 ) ? SUCCESS : FAILED;
            
        } catch (Exception $e) {
            throw new CustomException( 'Error in: send jormall sms ', $e );//from php 5.3 no need to custum
        }
        
        return $status;
    }

    public static function send_zain_sms($phone, $msg){
        
        $status = SERVER_ERROR;

        try {

            $sender_id = SENDER_ID;//'Arak%20tech';
            $acc_name  = ACC_NAME;//'araktech';;
            $acc_pass  = ACC_PASS;//'araktech123';
            
            //$path_template = 'http://josmsservice.com/smsonline/msgservicejo.cfm?numbers=%s&senderid=%s&AccName=%s&AccPass=%s&msg=%s';
            $path_template = PATH_TEMPLATE;
            
            $path   = sprintf($path_template, $acc_name, $acc_pass, $msg, $phone, $sender_id);

            $output = file_get_contents($path);

            if ( strpos($output, ZAIN_SUCCESS_CODE) !== false) {

                $status = SUCCESS;

            }else{

                $status = FAILED;

            }
            
            //Logger::log( $output, INFO );

            //I01-Job 1 queued for processing.

            //E01-Invalid USERNAME or PASSWORD.
            //E02-Account Expired.
            //E03-Account Inactive.
            //E04-Empty SMS message.
            //E05-Invalid mobile number.
            //E06-SMS balance already expired.
            //E07-SMS balance already consumed.
            //E08-Database error.
            //E09-One of the following parameters missing, USERNAME, PASSWORD, MESSAGE TEXT OR MOBILE NUMBER.
            //E010-Invalid delivery date.
            //E011-Date and time for scheduled messages should be greater than the current date and time.
            //E015-SMS message exceeded the max size for the selected language.
            //E016-Invalid sender ID, sender ID must be in English chars and less than or equal 11 in length, space and special characters not allowed.
            //E0223-Invalid sender ID, please contacts your account Admin to register your sender ID.
            
        } catch (Exception $e) {
            throw new CustomException( 'Error in: send zain sms ', $e );//from php 5.3 no need to custum
        }
        
        return $status;
    }

//    public static function send_sms($phone, $msg){
//        
//        $status = SERVER_ERROR;
//
//        try {
//
//            $sender_id = SENDER_ID;//'Arak%20tech';
//            $acc_name  = ACC_NAME;//'araktech';;
//            $acc_pass  = ACC_PASS;//'araktech123';
//            
//            //$path_template = 'http://josmsservice.com/smsonline/msgservicejo.cfm?numbers=%s&senderid=%s&AccName=%s&AccPass=%s&msg=%s';
//            $path_template = PATH_TEMPLATE;
//            
//            $path   = sprintf($path_template, $phone, $sender_id, $acc_name, $acc_pass, $msg);
//
//            $output = file_get_contents($path);
//
//            $pos    = strpos($output, 'MsgID');
//            $pos    = strpos($output, '=', $pos);
//            $length = strlen($output);
//            
//            $msg_id  = trim(  substr(  $output , $pos+1, ($length-($pos+2))  )  );
//            
//            //Logger::log( $output, INFO );
//
//            $status   = ( $msg_id > 0 ) ? SUCCESS : FAILED;
//            
//        } catch (Exception $e) {
//            Logger::log( $e->getMessage(), ERROR );
//        }
//        
//        return $status;
//    }

    public static function fix_mobile_input($phone){
        
        try {
            
            //$phone = self::fix_arabic_characters($phone);

            $phone = self::remove_dial_code($phone);
            
            return $phone;

        } catch (Exception $e) {
            throw new CustomException( 'Error in: send zain sms ', $e );//from php 5.3 no need to custum
        }
        
    }

    //public static function fix_arabic_characters($string) {
    //
    //    $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    //    $arabic  = ['٩', '٨', '٧', '٦', '٥', '٤', '٣', '٢', '١','٠'];
    //
    //    $num = range(0, 9);
    //
    //    $converted_persian_nums = mb_str_replace($persian, $num, $string);
    //    $english_numbers_only   = mb_str_replace($arabic,  $num, $converted_persian_nums);
    //
    //    return $english_numbers_only;
    //
    //}

    public static function add_dial_code($phone) {

        if( empty( $phone ) ){ return $phone; }
        
        if ( !defined("COUNTRY_DIAL") ) {
            define("COUNTRY_DIAL", "962");
        }
        
        $country_dial = COUNTRY_DIAL;

        $phone = ( (substr($phone, 0, strlen($country_dial)) === $country_dial) ) ? $phone : $country_dial.$phone;
        
        return $phone;

    }

    public static function remove_dial_code($phone) {
        
        if ( !defined("COUNTRY_DIAL") ) {
            define("COUNTRY_DIAL", "962");
        }

        $country_dial = COUNTRY_DIAL;


        $plus_dial = "+".$country_dial;

        if( ( substr($phone, 0, strlen($plus_dial)) === $plus_dial ) ) {
            $phone = substr($phone, strlen($plus_dial), strlen($phone));
        }
        
        
        $dial = $country_dial;

        if( ( substr($phone, 0, strlen($dial)) === $dial ) ) {
            $phone = substr($phone, strlen($dial), strlen($phone));
        }


        $zero = "0";

        if( ( substr($phone, 0, strlen($zero)) === $zero ) ) {
            $phone = substr($phone, strlen($zero), strlen($phone));
        }

        return $phone;

    }

    public static function get_student_location($phone){
        
        $position = new stdClass();

        try {

            //31.9565778,35.9456951,15z
            $position->lat = 31.9565778;
            $position->lng = 35.9456951;
            
        } catch (Exception $e) {
            Logger::log( $e->getMessage(), ERROR );
        }
        
        return $position;
    }

    public static function get_bus_location($gps){
        
        $position = new stdClass();

        try {

            //31.9565778,35.9456951,15z
            $position->lat = 31.9565778;
            $position->lng = 35.9456951;
            
        } catch (Exception $e) {
            Logger::log( $e->getMessage(), ERROR );
        }
        
        return $position;
    }

}
