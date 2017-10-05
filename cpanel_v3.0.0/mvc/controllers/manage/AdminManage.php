<?php
/*
 *
 */

/**
 * Description of AdminManage
 *
 * @author Ahmad
 */

class AdminManage extends ManageAjax {

    public static $dir = "admins";
    public static $date_format = 'Y-m-d H:i:s';


    public static function add_admin(){

        $output_array = array();

        try {

            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_ADD);

            if( $permitted ){

                QueryUtil::connect();
                
                $admin = self::read_admin_form();
                                
                //$admin->image = FileUtil::save_file("image", "image", $path);

                $status = AdminDB::add_admin($admin);
                
                QueryUtil::close();
                
            }else{
                $status = -1;//ERROR_TYPE_UNAUTHORIZED_ACCESS;
            }

            $output_array["status"] = intval($status);

        } catch (Exception $e) {
            Logger::log( $e->getMessage(), ERROR );
        }

        return $output_array;

    }

    public static function update_admin(){

        $output_array = array();
        
        try {

            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();
            $session = HttpSession::get_instance();

            $permitted = self::check_permission(ACTION_EDIT);

            if( $permitted ){
                
                QueryUtil::connect();

                $admin = self::read_admin_form();

                //$admin_id  = $request->get_int_parameter("admin_id");
                
                //$old_admin = AdminDB::get_admin($admin_id);

                //$admin->image = FileUtil::replace_file("image", "image", $path, $old_admin->image);

                $status = AdminDB::update_admin($admin);
                
                QueryUtil::close();
                
            }else{
                $status = -1;//ERROR_TYPE_UNAUTHORIZED_ACCESS;
            }

            $output_array["status"] = intval($status);

        } catch (Exception $e) {
            Logger::log( $e->getMessage(), ERROR );
        }

        return $output_array;

    }

    public static function remove_admin(){

        $output_array = array();
        
        try {
            
            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_REMOVE);

            if( $permitted ){

                QueryUtil::connect();

                $admin_id = $request->get_int_parameter("admin_id");

                $admin    = AdminDB::get_admin($admin_id);

                $status   = AdminDB::remove_admin($admin);

                //if( $status > 0 ){
                //    FileUtil::remove_file($path, $admin->image);
                //}
                
                QueryUtil::close();

            }else{
                $status = -1;//ERROR_TYPE_UNAUTHORIZED_ACCESS;
            }

            $output_array["status"] = intval($status);

        } catch (Exception $e) {
            Logger::log( $e->getMessage(), ERROR );
        }

        return $output_array;

    }


    public static function get_admins(){

        $output_array = array();

        try {

            $request = HttpRequest::get_instance();

            $admins_array_list = array();
            $result_count             = 0;

            $permitted = self::check_permission(ACTION_VIEW_ALL);

            if( $permitted ){
                
                QueryUtil::connect();
                
                $index = $request->get_int_parameter("index");
                $count = $request->get_int_parameter("count");

                //if no count this means unlimited
                $count = ( $count == 0 ) ? -1 : $count;

                $admins       = AdminDB::get_admins($index, $count);
                $admins_count = AdminDB::get_admins_count();

                $admins_array_list = self::get_formated_array($admins);

                $output_array["admins"]       = $admins_array_list;
                $output_array["admins_count"] = $admins_count;
                
                QueryUtil::close();
                
            }

            $output_array["status"] = $permitted;

        } catch (Exception $e) {
            Logger::log( $e->getMessage(), ERROR );
        }
        
        return $output_array;
    }

    public static function search_admins(){

        $output_array = array();

        try {

            $request = HttpRequest::get_instance();
            $session = HttpSession::get_instance();

            $admins_array_list = array();

            $permitted = self::check_permission(ACTION_VIEW_ALL);

            if( $permitted ){

                QueryUtil::connect();

                $options = array();

                $options["name"]    = $request->get_parameter("name");
                $options["rule_id"] = $request->get_int_parameter("rule_id");

                $index = $request->get_int_parameter("index");
                $count = $request->get_int_parameter("count");

                //if no count this means unlimited
                $count = ( $count == 0 ) ? -1 : $count;

                $admins       = AdminDB::search_admins($options, $index, $count, '`admin_id`', 'ASC');
                $admins_count = AdminDB::search_admins_count($options);

                $admins_array_list = self::get_formated_array($admins);

                $output_array["admins"]       = $admins_array_list;
                $output_array["admins_count"] = $admins_count;

                QueryUtil::close();

            }

            $output_array["status"]        = $permitted;

        } catch (Exception $e) {
            Logger::log( $e->getMessage(), ERROR );
        }
        
        return $output_array;
    }


    public static function get_formated_array(array $admins){

        $admins_array_list = array();

        for ( $i=0; $i<count($admins); $i++ ){

            $admin = $admins[$i];

            $admin_object = self::get_formated_object($admin);

            $admins_array_list[] = $admin_object;
        }

        return $admins_array_list;
    }

    private static function get_formated_object($admin){

        $admin_object = array();

        $admin_object["admin_id"]  = $admin->admin_id;
        $admin_object["name"]      = $admin->name;
        $admin_object["password"]  = $admin->password;
        $admin_object["email"]     = $admin->email;
        $admin_object["rule_id"]   = $admin->rule_id;

        return $admin_object;
    }


    private static function read_admin_form(){

        $admin = new stdClass();

        try {

            $admin_arr = array();

            $request = HttpRequest::get_instance();

            $admin_arr['admin_id'] = $request->get_int_parameter("admin_id");
            $admin_arr['name']     = $request->get_parameter("name");
            $admin_arr['password'] = $request->get_parameter("password");
            $admin_arr['email']    = $request->get_parameter("email");
            $admin_arr['rule_id']  = $request->get_int_parameter("rule_id");

            $admin = (object) $admin_arr;

        } catch (Exception $e) {
            throw new CustomException( 'Error in : read form', $e );//from php 5.3 no need to custum
        }

        return $admin;
    }


    public static function check_permission($action){
        
        $permitted = parent::check_permission($action);
        
        if( !$permitted ){
            return $permitted;
        }
        
        $session = HttpSession::get_instance();
        $request = HttpRequest::get_instance();

        $passed_admin_id = $request->get_int_parameter("admin_id");
        
        $user_id     = $session->get_int_attribute("user_id");
        $rule_id     = $session->get_int_attribute("rule_id");
        $admin_id      = $session->get_int_attribute("admin_id");

        switch ($rule_id) {

            case USER_TYPE_MASTER:
                $permitted = true;
                break;

            case USER_TYPE_ORG:
                if( $admin_id == $passed_admin_id && ( $action == ACTION_EDIT || $action == ACTION_VIEW )){
                    $permitted = true;
                }
                break;

            case USER_TYPE_CHECKER:
                if( $admin_id == $passed_admin_id && ( $action == ACTION_VIEW )){
                    $permitted = true;
                }
                break;

            case USER_TYPE_ENTRY:
                if( $admin_id == $passed_admin_id && ( $action == ACTION_VIEW )){
                    $permitted = true;
                }
                break;

            default:
                break;
        }
    
        return $permitted;
    }
    

}

?>