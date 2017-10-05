<?php
/*
 *
 */


/**
 * Description of PopupManage
 *
 * @author Ahmad
 */

class PopupManage {

    public static $dir = "popup";
    public static $dateFormat = 'Y-m-d H:i:s';

    public static function update_popup(){

        $output_array = array();
        
        try {

            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();
            
            $permitted = AdminManage::check_admin_permission(ACTION_EDIT);

            if( $status > 0 ){

                $popup = self::read_popup_form();

                $status = PopupJSON::update_popup($popup);

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

    public static function get_popup(){

        $output_array = array();

        try {

            $request = HttpRequest::get_instance();

            $popup   = PopupJSON::get_popup();

            $output_array["popup"] = $popup;

        } catch (Exception $e) {
            $output_array["status"] = SERVER_ERROR;
            Logger::log( $e->getMessage(), ERROR );
        }
        
        return $output_array;
    }

    private static function read_popup_form(){

        $popup = new stdClass();

        try {

            $popup_arr = array();
            
            $request = HttpRequest::get_instance();
            
            $popup_arr['content'] = TextUtil::fixText( $request->get_parameter("content") );
            $popup_arr['active']  = $request->get_int_parameter("active");

            $popup = (object) $popup_arr;
            
        } catch (Exception $e) {
            throw new CustomException( 'Error in : read form', $e );//from php 5.3 no need to custum
        }

        return $popup;
    }

}

?>