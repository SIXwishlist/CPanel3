<?php
/*
 *
 */

/**
 * Description of UserManage
 *
 * @author Ahmad
 */

class UserManage extends ManageController {

    public static $dir = "users";
    public static $date_format = 'Y-m-d H:i:s';

    public static function add_user(){

        $output_array = array();

        try {
            
            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_ADD);

            if( $permitted ){

                QueryUtil::connect();
                
                $user = self::read_user_form();

                //$user->icon  = FileUtil::save_thumb("icon", "icon", ICON_PAGES_WIDTH, ICON_PAGES_HEIGHT,  $path);
                $user->icon  = FileUtil::save_file("icon",  "icon",  $path);

                $status = UserDB::add_user($user);
                
                QueryUtil::close();
                
            }else{
                $status = -1;//ERROR_TYPE_UNAUTHORIZED_ACCESS;
            }

            $output_array["status"] = intval($status);

        } catch (Exception $e) {
            $output_array["status"] = SERVER_ERROR;
            Logger::log( $e->getMessage(), ERROR );
        }

        return $output_array;

    }

    public static function update_user(){

        $output_array = array();
        
        try {

            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();
            
            $permitted = self::check_permission(ACTION_EDIT);

            if( $permitted ){
                
                QueryUtil::connect();

                $user = self::read_user_form();

                $user_id  = $request->get_int_parameter("user_id");

                $old_user = UserDB::get_user($user_id);
                
                //$user->icon  = FileUtil::replace_thumb("icon", "icon", ICON_PAGES_WIDTH, ICON_PAGES_HEIGHT, $old_user->icon, $path);
                $user->icon  = FileUtil::replace_file("icon",  "icon",  $path, $old_user->icon );
                
                $status = UserDB::update_user($user);
                
                QueryUtil::close();

            }else{
                $status = -1;//ERROR_TYPE_UNAUTHORIZED_ACCESS;
            }

            $output_array["status"] = intval($status);

        } catch (Exception $e) {
            $output_array["status"] = SERVER_ERROR;
            Logger::log( $e->getMessage(), ERROR );
        }

        return $output_array;

    }

    public static function remove_user(){

        $output_array = array();
        
        try {
            
            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_REMOVE);

            if( $permitted ){

                QueryUtil::connect();
                
                $user_id = $request->get_int_parameter("user_id");

                $user    = UserDB::get_user($user_id);

                $status  = UserDB::remove_user($user);

                if( $status > 0 ){
                    FileUtil::remove_file($path, $user->icon );
                }
                
                QueryUtil::close();
               
            }else{
                $status = -1;//ERROR_TYPE_UNAUTHORIZED_ACCESS;
            }

            $output_array["status"] = intval($status);

        } catch (Exception $e) {
            $output_array["status"] = SERVER_ERROR;
            Logger::log( $e->getMessage(), ERROR );
        }

        return $output_array;

    }


    public static function suspend_user(){

        $output_array = array();
        
        try {

            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();
            
            $permitted = self::check_permission(ACTION_EDIT);

            if( $permitted ){
                
                QueryUtil::connect();

                $user_id      = $request->get_int_parameter("user_id");
                $suspend_date = $request->get_parameter("date");

                $user     = UserDB::get_user($user_id);
                
                $user->rule_id      = USER_RULE_SUSPENDED;
                $user->suspend_date = $suspend_date;

                $status   = UserDB::update_user($user);

                QueryUtil::close();
                
            }else{
                $status = -1;//ERROR_TYPE_UNAUTHORIZED_ACCESS;
            }

            $output_array["status"] = intval($status);

        } catch (Exception $e) {
            $output_array["status"] = SERVER_ERROR;
            Logger::log( $e->getMessage(), ERROR );
        }

        return $output_array;

    }
    
    public static function forbid_user(){

        $output_array = array();
        
        try {

            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();
            
            $permitted = self::check_permission(ACTION_EDIT);

            if( $permitted ){

                QueryUtil::connect();
                
                $user_id  = $request->get_int_parameter("user_id");

                $user     = UserDB::get_user($user_id);

                $user->suspend_date = null;
                $user->rule_id      = USER_RULE_BLOCKED;

                $status   = UserDB::update_user($user);
                
                QueryUtil::close();

            }else{
                $status = -1;//ERROR_TYPE_UNAUTHORIZED_ACCESS;
            }

            $output_array["status"] = intval($status);

        } catch (Exception $e) {
            $output_array["status"] = SERVER_ERROR;
            Logger::log( $e->getMessage(), ERROR );
        }

        return $output_array;

    }
    
    public static function unsuspend_user(){

        $output_array = array();
        
        try {

            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();
            
            $permitted = self::check_permission(ACTION_EDIT);

            if( $permitted ){
                
                QueryUtil::connect();

                $user_id  = $request->get_int_parameter("user_id");

                $user     = UserDB::get_user($user_id);
                
                $user->suspend_date = null;
                $user->rule_id      = USER_RULE_NORMAL;

                $status   = UserDB::update_user($user);
                
                QueryUtil::close();

            }else{
                $status = -1;//ERROR_TYPE_UNAUTHORIZED_ACCESS;
            }

            $output_array["status"] = intval($status);

        } catch (Exception $e) {
            $output_array["status"] = SERVER_ERROR;
            Logger::log( $e->getMessage(), ERROR );
        }

        return $output_array;

    }


    public static function get_users(){

        $output_array = array();

        try {

            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_VIEW_ALL);

            if( $permitted ){
                
                QueryUtil::connect();

                $parent_id   = $request->get_int_parameter("parent_id");

                $index = $request->get_int_parameter("index");
                $count = $request->get_int_parameter("count");
                
                //if no count this means unlimited
                $count = ( $count == 0 ) ? -1 : $count;

                $users       = UserDB::get_users($index, $count, '`created`', 'DESC');
                $users_count = UserDB::get_users_count();

                $users_array_list = self::get_formated_array($users);

                $output_array["users"]       = $users_array_list;
                $output_array["users_count"] = $users_count;

                $status = SUCCESS;
                
                QueryUtil::close();

            }else{
                $status = -1;//ERROR_TYPE_UNAUTHORIZED_ACCESS;
            }

            $output_array["status"] = intval($status);

        } catch (Exception $e) {
            $output_array["status"] = SERVER_ERROR;
            Logger::log( $e->getMessage(), ERROR );
        }
        
        return $output_array;
    }

    public static function get_suspended_users(){

        $output_array = array();

        try {

            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_VIEW_ALL);

            if( $permitted ){

                QueryUtil::connect();
                
                $index = $request->get_int_parameter("index");
                $count = $request->get_int_parameter("count");

                //if no count this means unlimited
                $count = ( $count == 0 ) ? -1 : $count;

                $options = array( "rule_list" => array( USER_RULE_BLOCKED, USER_RULE_SUSPENDED ) );

                $users       = UserDB::search_users($options, $index, $count, '`created`', 'DESC');
                $users_count = UserDB::search_users_count($options);

                $users_array_list = self::get_formated_array($users);

                $output_array["users"]       = $users_array_list;
                $output_array["users_count"] = $users_count;

                $status = SUCCESS;
                
                QueryUtil::close();

            }else{
                $status = -1;//ERROR_TYPE_UNAUTHORIZED_ACCESS;
            }

            $output_array["status"] = intval($status);

        } catch (Exception $e) {
            $output_array["status"] = SERVER_ERROR;
            Logger::log( $e->getMessage(), ERROR );
        }
        
        return $output_array;
    }


    public static function search_users(){

        $output_array = array();

        try {

            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_VIEW_ALL);

            if( $permitted ){

                QueryUtil::connect();

                $options = array(

                    "user_id" => $request->get_int_parameter("user_id"), 

                    "name"    => $request->get_parameter("name"), 
                    "email"   => $request->get_parameter("email"), 
                    "phone"   => $request->get_parameter("phone"), 
                    "country" => $request->get_parameter("country"), 

                    "status"  => $request->get_int_parameter("status"), 
                    "rule_id" => $request->get_int_parameter("rule_id"), 

                );

                $index = $request->get_int_parameter("index");
                $count = $request->get_int_parameter("count");
                
                //if no count this means unlimited
                $count = ( $count == 0 ) ? -1 : $count;

                $users       = UserDB::search_users($options, $index, $count);
                $users_count = UserDB::search_users_count($options);

                $users_array_list = self::get_formated_array($users);

                $output_array["users"]       = $users_array_list;
                $output_array["users_count"] = $users_count;

                $status = SUCCESS;
                
                QueryUtil::close();

            }else{
                $status = -1;//ERROR_TYPE_UNAUTHORIZED_ACCESS;
            }

            $output_array["status"] = intval($status);

        } catch (Exception $e) {
            $output_array["status"] = SERVER_ERROR;
            Logger::log( $e->getMessage(), ERROR );
        }
        
        return $output_array;
    }


    private static function read_user_form(){

        $user = new stdClass();

        try {

            $user_arr = array();

            $request = HttpRequest::get_instance();

            //user_id 	
            //username 	password 	email 	phone 	key 	code 	
            //fname 	lname 	icon 	birth_date 	gender 	country
            // 	created 	updated     suspend_date    status 	rule_id
            
            $user_arr['user_id']    = $request->get_int_parameter("user_id");
            
            $user_arr['name']       = $request->get_parameter("name");
            $user_arr['password']   = $request->get_parameter("password");
            $user_arr['email']      = $request->get_parameter("email");
            $user_arr['phone']      = $request->get_parameter("phone");
            $user_arr['key']        = $request->get_parameter("key");
            $user_arr['code']       = $request->get_parameter("code");
            
            $user_arr['fname']      = $request->get_parameter("fname");
            $user_arr['lname']      = $request->get_parameter("lname");
            $user_arr['icon']       = '';
            $user_arr['birth_date'] = $request->get_parameter("birth_date");
            $user_arr['gender']     = $request->get_parameter("gender");
            $user_arr['country']    = $request->get_parameter("country");
            
            $user_arr['created']    = $request->get_parameter("created");
            $user_arr['updated']    = $request->get_parameter("updated");

            $user_arr['suspend_date'] = null;

            $user_arr['status']     = $request->get_int_parameter("status");
            $user_arr['rule_id']    = $request->get_int_parameter("rule_id");

            $real_estate = $request->get_int_parameter("real_estate");

            $options = 0;

            $options |= ( $real_estate > 0 ) ? REAL_ESTATE : 0;

            $user_arr['options']    = $options;

            $user = (object) $user_arr;
            
        } catch (Exception $e) {
            $output_array["status"] = SERVER_ERROR;
            throw new CustomException( 'Error in : read form', $e );//from php 5.3 no need to custum
        }

        return $user;
    }


    public static function get_formated_array(array $users){

        $users_array_list = array();

        for ( $i=0; $i<count($users); $i++ ){

            $user = $users[$i];

            $userObject = self::get_formated_object($user);

            $users_array_list[] = $userObject;
        }

        return $users_array_list;
    }

    private static function get_formated_object($user){

        $user_object = array();

        $user_object['user_id']    = $user->user_id;

        //user_id 	
        //username 	password 	email 	phone 	key 	code 	
        //fname 	lname 	icon 	birth_date 	gender 	country
        // 	created 	updated 	status 	rule_id

        $user_object['name']       = $user->name;
        $user_object['password']   = $user->password;
        $user_object['email']      = $user->email;
        $user_object['phone']      = $user->phone;
        $user_object['key']        = $user->key;
        $user_object['code']       = $user->code;

        $user_object['name']       = $user->name;
        $user_object['icon']       = $user->icon;
        $user_object['birth_date'] = $user->birth_date;
        $user_object['gender']     = $user->gender;
        $user_object['country']    = $user->country;

        $user_object['created']    = $user->created;
        $user_object['updated']    = $user->updated;

        $user_object['suspend_date'] = $user->suspend_date;

        $user_object['status']      = $user->status;
        $user_object['rule_id']     = $user->rule_id;

        $user_object['options']     = $user->options;

        return $user_object;
    }


    private static function check_password_matching($user){

        $status = 0;

        try {

            $password         = $user->password;
            $password_confirm = $user->password_confirm;

            if( $password == $password_confirm ){
                $status = 1;
            }else{
                $status = -1;
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : check password matching', $e );//from php 5.3 no need to custum
        }

        return $status;
    }

}

?>