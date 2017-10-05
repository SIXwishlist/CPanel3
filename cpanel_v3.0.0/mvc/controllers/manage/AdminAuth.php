<?php
/*
 *
 */

/**
 * Description of AdminAuth
 *
 * @author Ahmad
 */

class AdminAuth extends ManageAjax {

    public static $date_format  = 'Y-m-d H:i:s';
    

    public static function check_login(){

        $output_array = array();
        
        try {

            $session = HttpSession::get_instance();
            
            $status = self::check_admin_logged();
            
            if( $status <= 0 ){

                $admin_id = $session->get_cookie("admin_id");

                if( $admin_id > 0 ){

                    QueryUtil::connect();

                    $user = AdminDB::get_user_join_organization($admin_id);

                    QueryUtil::close();

                    if( !empty($user) ){

                        //$session->set_attribute( "user", $user );

                        $session->set_attribute( "admin_id",     $user->admin_id     );
                        $session->set_attribute( "rule_id",     $user->rule_id     );
                        $session->set_attribute( "name",        $user->name        );
                        $session->set_attribute( "status",      $user->org_status  );
                        $session->set_attribute( "org_id",      $user->org_id      );
                        
                        $session->set_attribute( "organization_name", $user->organization_name );
                        $session->set_attribute( "creation_date",     $user->creation_date     );
                        $session->set_attribute( "expiration_date",   $user->expiration_date   );
                        $session->set_attribute( "org_status",        $user->org_status        );

                        $status = SUCCESS;

                    } else {
                        $status = USER_NOT_EXIST;
                    }

                } else {
                    $status = FAILED;                    
                }
            }
            
            //if( $status > 0 ){
            //
            //    QueryUtil::connect();
            //
            //    $admin_id = $session->get_int_attribute("admin_id");
            //
            //    $options = array(
            //        "admin_id" => $admin_id,
            //        "status"  => 0
            //    );
            //
            //    $notifications       = NotificationDB::search_notifications($options, 0, 5, '`time`', 'DESC');
            //    $notifications_count = NotificationDB::search_notifications_count($options);
            //
            //    QueryUtil::close();
            //
            //    $notifications_array = json_decode( json_encode($notifications), true );
            //
            //    $output_array["notifications"]       = $notifications_array;
            //    $output_array["notifications_count"] = $notifications_count;
            //
            //}

            if( $status > 0 ){

                $admin_id = $session->get_attribute("admin_id");
                $rule_id = $session->get_attribute("rule_id");
                $name    = $session->get_attribute("name");
                $org_id  = $session->get_attribute("org_id");

                $organization_name = $session->get_attribute("organization_name");
                $creation_date     = $session->get_attribute("creation_date");
                $expiration_date   = $session->get_attribute("expiration_date");
                $org_status        = $session->get_attribute("org_status");

                $output_array["admin_id"] = intval($admin_id);
                $output_array["rule_id"] = intval($rule_id);
                $output_array["name"]    = $name;
                $output_array["org_id"]  = intval($org_id);

                $output_array["organization_name"] = $organization_name;
                $output_array["creation_date"]     = $creation_date;
                $output_array["expiration_date"]   = $expiration_date;
                $output_array["org_status"]        = $org_status;

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

            $admin_id = self::check_admin_logged();

            $request = HttpRequest::get_instance();
            $session = HttpSession::get_instance();

            $status = self::check_captcha("login", CAPATCHA_TRIALS);

            if( $status > 0 ){

                QueryUtil::connect();

                if( $admin_id <= 0 ){

                    $username = $request->get_parameter("username");
                    $password = $request->get_parameter("password");

                    if( !empty($username) && !empty($password) ){

                        $admin = AdminDB::check_admin($username, $password);

                        if( $admin != null ){
                            $admin_id = $admin->admin_id;
                        }

                    }
                }

                if( $admin_id > 0 ){

                    $admin = AdminDB::get_admin($admin_id);

                } else {
                     $status = USER_NOT_EXIST;
                }

                
                if( $status > 0 ){

                    //$session->set_attribute( "user", $admin );

                    $session->set_attribute( "admin_id", $admin->admin_id );
                    $session->set_attribute( "rule_id",  $admin->rule_id  );
                    $session->set_attribute( "name",     $admin->name     );

                    $remember = $request->get_int_parameter("remember");

                    if( $remember ){
                        $session->set_cookie("admin_id", $admin_id);
                    }

                    $admin_id = $admin->admin_id;
                    $rule_id  = $admin->rule_id;
                    $name     = $admin->name;

                    $output_array["admin_id"] = intval($admin_id);
                    $output_array["rule_id"]  = intval($rule_id);
                    $output_array["name"]     = $name;

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

            $admin_id = $session->get_attribute("admin_id");
            
            $session->remove_attribute("user");

            $session->remove_attribute( "admin_id" );
            $session->remove_attribute( "rule_id"  );
            $session->remove_attribute( "name"     );

            $session->remove_cookie("admin_id");

            $status = $admin_id;

            $output_array["status"] = intval($status);

        } catch (Exception $e) {
            $output_array["status"] = SERVER_ERROR;
            Logger::log( $e->getMessage(), ERROR );
        }

        return $output_array;
    }


    public static function get_user_notifications(){

        $output_array = array();

        try {

            $request = HttpRequest::get_instance();
            $session = HttpSession::get_instance();
            
            $permitted = self::check_permission(ACTION_VIEW_ALL);
            
            if( $permitted ){

                $admin_id = $session->get_int_attribute("admin_id");

                QueryUtil::connect();
                
                $options = array(
                    "admin_id" => $admin_id,
                    "status"  => -1
                );

                $notifications      = NotificationDB::search_notifications($options, -1, -1, '`time`', 'DESC');
                $notification_count = NotificationDB::search_notifications_count($options);

                $notification_array_list = array();

                foreach ($notifications as $notification) {

                    $notification_object = array();

                    // not_id 	type 	time 	target_type 	status 	admin_id 	target_id

                    $notification_object["not_id"]      = $notification->not_id;
                    $notification_object["action"]      = $notification->action;
                    $notification_object["time"]        = $notification->time;
                    $notification_object["status"]      = $notification->status;
                    $notification_object["actor_id"]    = $notification->actor_id;
                    $notification_object["target_id"]   = $notification->target_id;

                    $notification_object["actor"]       = $notification->actor;
                    $notification_object["owner"]       = $notification->owner;
                    $notification_object["product"]     = $notification->product;

                    $notification_array_list[] = $notification_object;
                    
                }

                $output_array["user_notifications"]       = $notification_array_list;
                $output_array["user_notifications_count"] = $notification_count;

                $status = SUCCESS;
                
                QueryUtil::close();

            } else {
                $status = UNAUTHORIZED_ACCESS;
            }

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
            
            $user  = AdminDB::get_user_with_one_of($user_options);
            
            if( $user != null && $user->admin_id > 0 ){

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

    public static function register_action(){

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

                //$user_arr['fname']      = $request->get_parameter("fname");
                //$user_arr['lname']      = $request->get_parameter("lname");
                $user_arr['name']       = $request->get_parameter("name");
                $user_arr['password']   = $password;
                $user_arr['email']      = $request->get_parameter("email");
                $user_arr['phone']      = MobileUtil::remove_dial_code( $request->get_parameter("phone") );
                $user_arr['country']    = $request->get_parameter("country");
                $user_arr['key']        = $user_key;
                $user_arr['code']       = '';
                $user_arr['icon']       = '';
                $user_arr['birth_date'] = $request->get_parameter("birth_date");
                $user_arr['gender']     = $request->get_parameter("gender");

                $create_date = date(self::$date_format);

                $user_arr['created']      = $create_date;
                $user_arr['updated']      = $create_date;
                $user_arr['suspend_date'] = null;
                
                $user_arr['status']     = USER_STATUS_NOT_VERIFIED;
                $user_arr['rule_id']    = USER_RULE_NORMAL;
                $user_arr['options']    = 0;

                $user = (object) $user_arr;
                
                $status = AdminDB::add_user($user);

            }

            if( $status > 0){

                $lang = Dictionary::get_language();

                $dir  = ($lang == "ar") ? "rtl" : "ltr";

                $subject     = Dictionary::get_text('RegisterVerify_Subject_lbl');
                $email       = $request->get_parameter("email");
                
                $admin_id     = QueryUtil::get_last_insert_id();
                
                $verify_link = UrlUtil::get_verify_href($admin_id, $user_key);

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
            Logger::log( $e->getMessage(), ERROR );
        }

        return $output_array;
    }

    
    public static function forget_password_action() {

        $output_array = array();

        try {

            $request = HttpRequest::get_instance();

            QueryUtil::connect();
            
            $status = self::check_captcha("forget", CAPATCHA_TRIALS);
            
            $username = $request->get_parameter("username");
            
            if( $status > 0){
                
                $options = array(
                    "email" => $username,
                    "name"  => $username
                );

                $user = AdminDB::search_admins($options);

                if( !empty($user) ){
                    
                    $lang = Dictionary::get_language();

                    $dir  = ($lang == "ar") ? "rtl" : "ltr";

                    $subject     = Dictionary::get_text('ResetEmail_Subject_lbl');
                    $email       = $user->email;

                    $user_key    = $user->key;
                    $admin_id    = $user->admin_id;

                    $reset_link = UrlUtil::get_reset_href($admin_id, $user_key);

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
    
    public static function reset_password_action() {

        $output_array = array();

        try {

            $request = HttpRequest::get_instance();

            QueryUtil::connect();
            
            $status = self::check_captcha("reset", CAPATCHA_TRIALS);
            
            $password         = $request->get_parameter("new_password");
            $password_confirm = $request->get_parameter("new_password_confirm");
            
            if( !empty($password) && $password == $password_confirm ){
                
                $admin_id  = $request->get_int_parameter("admin_id");
                $user_key = $request->get_parameter("user_key");
                
                $user = AdminDB::get_user($admin_id);

                if( !empty($user) ){

                    if( $user_key == $user->key){

                        $user->password = $password;
                        $status = AdminDB::update_user($user);

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
    
    private static function is_organization_expired($from_date, $to_date){
        
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