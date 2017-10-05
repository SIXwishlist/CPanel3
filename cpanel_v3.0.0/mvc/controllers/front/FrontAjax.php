<?php
/*
 *
 */

/**
 * Description of FrontAjax
 *
 * @author Ahmad
 */

class FrontAjax {
    
    public static function check_captcha($name, $max_trials=4){

        $status = 0;

        try {

            $request = HttpRequest::get_instance();
            $session = HttpSession::get_instance();

            $times      = $session->get_int_attribute($name."_times");
            $last_trial = $session->get_int_attribute($name."_last_trial");

            $wait_time = time() - $last_trial;//$last_request_trial;

            if( $wait_time < 20 ){
                $times++;
                $session->set_attribute($name."_times", $times);
            }

            if( $wait_time > 60 ){
                $times = 0;
                $session->set_attribute($name."_times", $times);
            }

            if( $times > $max_trials ){

                $captcha_text = $request->get_parameter("captcha_text");
                $user_key     = md5( $captcha_text );
                $key          = $session->get_attribute("captcha_key");

                if( $user_key != $key ){
                    $status = CAPATCHA_REQUIRED;
                }else{
                    $status = SUCCESS;
                }

            }else{
                $status = SUCCESS;
            }

            $last_trial = time();

            $session->set_attribute($name."_last_trial", $last_trial);

        } catch (Exception $e) {
            throw new CustomException( 'Error in : check request sending times', $e );//from php 5.3 no need to custum
        }

        return $status;
    }

    public static function check_user_logged(){

        $status = 0;

        try {

            $session = HttpSession::get_instance();

            $user_id = $session->get_attribute("user_id");

            if( $user_id != null && $user_id > 0 ){
                $status = $user_id;
            }else{
                $status = -1;//ERROR_TYPE_ADMIN_NOT_LOGGED
            }

        } catch (Exception $e) {
            $output_array["status"] = SERVER_ERROR;
            Logger::log( $e->getMessage(), ERROR );
        }

        return $status;
    }

    public static function check_permission($action = 0){

        $permitted = false;

        $session = HttpSession::get_instance();
        $request = HttpRequest::get_instance();
        
        $user_id = $session->get_attribute("user_id");

        if( $user_id != null && $user_id > 0 ){
            $status = $user_id;
        }else{
            $status = -1;
        }
        
        $permitted = ( $status > 0 ) ? true : false;

        return $permitted;

    }

}

?>
