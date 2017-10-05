<?php
/*
 *
 */

/**
 * Description of BackgroundManage
 *
 * @author Ahmad
 */

class BackgroundManage extends ManageAjax {

    public static function get_main(){

        $output_array = array();

        try {

            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_VIEW_ALL);

            if( $permitted ){
                
                QueryUtil::connect();

                $countries = CountryDB::get_countries(-1, -1);

                $countries = json_decode( json_encode($countries),   true );

                QueryUtil::close();
            }

            $output_array["status"]    = $permitted;

            $output_array["countries"] = $countries;

        } catch (Exception $e) {
            Logger::log( $e->getMessage(), ERROR );
        }
        
        return $output_array;
    }

//    public static function check_permission($action){
//
//        $permitted = false;
//
//        $session = HttpSession::get_instance();
//        $request = HttpRequest::get_instance();
//
//        $user_id = $session->get_int_attribute("user_id");
//        $rule_id  = $session->get_int_attribute("rule_id");
//
//        if( $user_id == null || $user_id <= 0 ){
//            $permitted = false;
//        } else {
//            $permitted = true;
//        }
//
//        return $permitted;
//    }
//    
}

?>