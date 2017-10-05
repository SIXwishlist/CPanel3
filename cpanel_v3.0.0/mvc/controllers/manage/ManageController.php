<?php
/*
 *
 */

/**
 * Description of ManageController
 *
 * @author Ahmad
 */

class ManageController {

    public static function check_permission($action){

        $permitted = false;

        $session = HttpSession::get_instance();
        $request = HttpRequest::get_instance();
        
        $admin_id = $session->get_attribute("admin_id");

        if( $admin_id != null && $admin_id > 0 ){
            $status = $admin_id;
        }else{
            $status = -1;//ERROR_TYPE_ADMIN_NOT_LOGGED
        }
        
        $permitted = ( $status > 0 ) ? true : false;;
        
        return $permitted;
        
        //
        //switch ($rule_id) {
        //
        //    case USER_TYPE_MASTER:
        //        if( $action == ACTION_VIEW_ALL ){
        //            $permitted = true;
        //        }
        //        $permitted = true;
        //        break;
        //
        //    default:
        //        break;
        //}
        //
        
    }

}

?>
