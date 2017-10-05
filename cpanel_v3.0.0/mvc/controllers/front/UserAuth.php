<?php
/*
 *
 */

/**
 * Description of UserAuth
 *
 * @author Ahmad
 */

class UserAuth extends FrontAjax {

    public static $date_format  = 'Y-m-d H:i:s';


    public static function check_login(){

        $output_array = array();
        
        try {

            $session = HttpSession::get_instance();
            
            $status = self::check_user_logged();
            
            if( $status <= 0 ){

                $user_id = $session->get_cookie("user_id");

                if( $user_id > 0 ){

                    QueryUtil::connect();

                    $user = UserDB::get_user_join_dial($user_id);

                    QueryUtil::close();

                    if( !empty($user) ){

                        //$session->set_attribute( "user", $user );

                        $session->set_attribute( "user_id",     $user->user_id  );
                        $session->set_attribute( "rule_id",     $user->rule_id  );
                        $session->set_attribute( "name",        $user->name     );
                        $session->set_attribute( "user_status", $user->status   );

                        $status = SUCCESS;

                    } else {
                        $status = USER_NOT_EXIST;
                    }

                } else {
                    $status = FAILED;                    
                }
            }

            if( $status > 0 ){

                $user_id     = $session->get_int_attribute("user_id");
                $rule_id     = $session->get_int_attribute("rule_id");
                $name        = $session->get_attribute("name");
                $user_status = $session->get_int_attribute("user_status");
               

                $output_array["user_id"]     = intval($user_id);
                $output_array["rule_id"]     = intval($rule_id);
                $output_array["name"]        = $name;
                $output_array["user_status"] = intval($user->status);

            }

            $output_array["status"]  = intval($status);


        } catch (Exception $e) {
            $output_array["status"] = SERVER_ERROR;
            Logger::log( $e->getMessage(), ERROR );
        }

        return $output_array;
    }

    public static function authenticate(){

        $output_array = array();
        
        try {

            $status  = 0;

            $user_id = self::check_user_logged();

            $request = HttpRequest::get_instance();
            $session = HttpSession::get_instance();

            $status = self::check_captcha("login", CAPATCHA_TRIALS);

            if( $status > 0 ){

                QueryUtil::connect();

                if( $user_id <= 0 ){

                    $username = $request->get_parameter("username");
                    $password = $request->get_parameter("password");

                    if( !empty($username) && !empty($password) ){

                        $user = UserDB::check_user($username, $password);

                        if( $user != null ){
                            $user_id = $user->user_id;
                        }

                    }
                }

                if( $user_id > 0 ){

                    $user = UserDB::get_user($user_id);

                } else {
                     $status = USER_NOT_EXIST;
                }

                
                if( $status > 0 ){

                    //$session->set_attribute( "user", $user );

                    $session->set_attribute( "user_id",     $user->user_id );
                    $session->set_attribute( "rule_id",     $user->rule_id );
                    $session->set_attribute( "name",        $user->name    );
                    $session->set_attribute( "user_status", $user->status  );

                    $remember = $request->get_int_parameter("remember");

                    if( $remember ){
                        $session->set_cookie("user_id", $user_id);
                    }

                    $user_id     = $user->user_id;
                    $rule_id     = $user->rule_id;
                    $name        = $user->name;
                    $user_status = $user->status;

                    $output_array["user_id"]         = intval($user_id);
                    $output_array["rule_id"]         = intval($rule_id);
                    $output_array["name"]            = $name;
                    $output_array["user_status"]     = $user_status;

                }

                QueryUtil::close();

            }

            $output_array["status"]  = intval($status);
            
        } catch (Exception $e) {
            $output_array["status"]  = SERVER_ERROR;
            Logger::log( $e->getMessage(), ERROR );
        }

        return $output_array;
    }

    public static function logout(){

        $output_array = array();

        try {

            //$status  = 0;

            $session = HttpSession::get_instance();

            $user_id = $session->get_attribute("user_id");
            
            $session->remove_attribute("user");

            $session->remove_attribute( "user_id" );
            $session->remove_attribute( "rule_id" );
            $session->remove_attribute( "name" );
            $session->remove_attribute( "user_status" );

            $session->remove_cookie("user_id");

            $status = $user_id;

            $output_array["status"] = intval($status);

        } catch (Exception $e) {
            $output_array["status"] = SERVER_ERROR;
            Logger::log( $e->getMessage(), ERROR );
        }

        return $output_array;
    }


    public static function check_exist(){

        $output_array = array();
        
        try {

            $status = 0;
            
            $request = HttpRequest::get_instance();

            QueryUtil::connect();
            
            $entry = $request->get_parameter("entry");

            $user_options = array();
                
            $user_options["email"]    = $entry;
            $user_options["mobile"]   = $entry;
            //$user_options["active"] = 1;
            
            $user  = UserDB::get_user_with($user_options);
            
            if( $user != null && $user->user_id > 0 ){

                $status = 1;
                
            }

            QueryUtil::close();
            
            $output_array["status"] = intval($status);

        } catch (Exception $e) {
            $output_array["status"] = SERVER_ERROR;
            Logger::log( $e->getMessage(), ERROR );
        }

        return $output_array;

    }

    public static function register(){

        $output_array = array();

        try {

            $request = HttpRequest::get_instance();

            QueryUtil::connect();
            
            $status = self::check_captcha("register", CAPATCHA_TRIALS);
            
            $password         = $request->get_parameter("password");
            $password_confirm = $request->get_parameter("password_confirm");

            if( $status > 0 ){

                if( $password == $password_confirm ){
                    $status = 1;
                }else{
                    $status = -1;
                }

            }
            
            if( $status > 0){
                
                $user_arr = array();

                $user_key = md5( KEY_GEN . '' . time() );

                //$user_arr['name']       = $request->get_parameter("name");
                $user_arr['name']       = $request->get_parameter("username");
                $user_arr['password']   = $password;
                $user_arr['email']      = $request->get_parameter("email");
                $user_arr['phone']      = MobileUtil::remove_dial_code( $request->get_parameter("phone") );
                $user_arr['country']    = $request->get_parameter("country");
                $user_arr['key']        = $user_key;
                $user_arr['code']       = '';
                $user_arr['icon']       = '';
                $user_arr['birth_date'] = $request->get_parameter("birth_date");
                $user_arr['gender']     = $request->get_parameter("gender");
                //$user_arr['gender']     = $request->get_parameter("gender");

                $create_date = date(self::$date_format);

                $user_arr['created']      = $create_date;
                $user_arr['updated']      = $create_date;
                $user_arr['suspend_date'] = null;
                
                $user_arr['status']     = USER_STATUS_NOT_VERIFIED;
                $user_arr['rule_id']    = USER_RULE_NORMAL;
                $user_arr['options']    = 0;

                $user = (object) $user_arr;
                
                $status = UserDB::add_user($user);

            }

            if( $status > 0){

                $lang = Dictionary::get_language();

                $dir  = ($lang == "ar") ? "rtl" : "ltr";

                $subject     = Dictionary::get_text('RegisterVerify_Subject_lbl');
                $email       = $request->get_parameter("email");
                
                $user_id     = QueryUtil::get_last_insert_id();
                
                $verify_link = UrlUtil::get_verify_href($user_id, $user_key);

                $page_data = array(
                    "dir"          => $dir,
                    "verify_link"  => $verify_link
                );

                $message = TplLoader::get_tpl_data('verify_email.tpl', 'mvc/views/emails', $page_data);

                
                $attachments = array(
                    //'/uploads/'.self::$dir.'/' . $card_form->attachment
                );

                $status = MailSender::send_google_mail(SENDER_MAIL, SENDER_PASS, SENDER_NAME, $email, $subject, $message, $attachments);

            }

            QueryUtil::close();
            
            $output_array["status"] = intval($status);

        } catch (Exception $e) {
            $output_array["status"] = SERVER_ERROR;

            if( QueryUtil::get_error()->code == SQL_DUPLICATE_USER ){
                $output_array["status"] = USER_ALREADY_EXIST;
            }

            Logger::log( $e->getMessage(), ERROR );
        }

        return $output_array;
    }


    public static function forget_password() {

        $output_array = array();

        try {

            $request = HttpRequest::get_instance();

            QueryUtil::connect();
            
            $status = self::check_captcha("forget", CAPATCHA_TRIALS);
            
            $username = $request->get_parameter("username");
            
            if( $status > 0){
                
                $options = array();
                
                $options["email"]  = $username;
                $options["phone"]  = $username;
                //$options["name"]   = $username;
                //$options["active"] = 1;

                $user = UserDB::get_user_with($options);

                if( !empty($user) ){
                    
                    $lang = Dictionary::get_language();

                    $dir  = ($lang == "ar") ? "rtl" : "ltr";

                    $subject     = Dictionary::get_text('ResetEmail_Subject_lbl');
                    $email       = $user->email;

                    $user_key    = $user->key;
                    $user_id     = $user->user_id;

                    $reset_link = UrlUtil::get_reset_href($user_id, $user_key);

                    $page_data = array(
                        "dir"        => $dir,
                        "reset_link" => $reset_link
                    );

                    $message = TplLoader::get_tpl_data('reset_email.tpl', 'mvc/views/emails', $page_data);


                    $attachments = array(
                        //'/uploads/'.self::$dir.'/' . $card_form->attachment
                    );

                    $status = MailSender::send_google_mail(SENDER_MAIL, SENDER_PASS, SENDER_NAME, $email, $subject, $message, $attachments);

                } else {
                    $status = USER_NOT_EXIST;
                }

            }

            QueryUtil::close();
            
            $output_array["status"] = intval($status);

        } catch (Exception $e) {
            $output_array["status"] = SERVER_ERROR;
            Logger::log( $e->getMessage(), ERROR );
        }

        return $output_array;
    }

    public static function reset_password() {

        $output_array = array();

        try {

            $request = HttpRequest::get_instance();

            QueryUtil::connect();
            
            $status = self::check_captcha("reset", CAPATCHA_TRIALS);
            
            $password         = $request->get_parameter("new_password");
            $password_confirm = $request->get_parameter("new_password_confirm");
            
            if( !empty($password) && $password == $password_confirm ){
                
                $user_id  = $request->get_int_parameter("user_id");
                $user_key = $request->get_parameter("user_key");
                
                $user = UserDB::get_user($user_id);

                if( !empty($user) ){

                    if( $user_key == $user->key){

                        $user->password = $password;
                        $status = UserDB::update_user($user);

                    }else{
                        $status = USER_KEY_INCORRECT;
                    }

                }else{
                    $status = USER_NOT_EXIST;
                }

            } else {
                $status = PASSWORD_NOT_MATCH;
            }

            QueryUtil::close();
            
            $output_array["status"] = intval($status);

        } catch (Exception $e) {
            $output_array["status"] = SERVER_ERROR;
            Logger::log( $e->getMessage(), ERROR );
        }

        return $output_array;
    }


    private static function is_account_expired($from_date, $to_date){
        
        $expired = true;
        
        $current_time = strtotime(date("Y-m-d H:i:s"));

        $from_time = strtotime( $from_date ." 00:00:00" );
        $to_time   = strtotime( $to_date   ." 23:59:59" );

        if ( $current_time >= $from_time && $current_time <= $to_time ) {
            $expired = false;
        } else {
            $expired = true;
        }
        
        return $expired;
    }

}

?>