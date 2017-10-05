<?php
/*
 *
 */

/**
 * Description of PaymentManage
 *
 * @author Ahmad
 */

class PaymentManage extends ManageController {

    public static $dir = "payments";
    public static $date_format = 'Y-m-d H:i:s';

    public static function add_payment(){

        $output_array = array();

        try {

            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_ADD);

            if( $permitted ){

                QueryUtil::connect();

                $payment = self::read_payment_form();

                //$payment->icon  = FileUtil::save_thumb("icon", "icon", ICON_PAGES_WIDTH, ICON_PAGES_HEIGHT,  $path);
                //$payment->icon  = FileUtil::save_file("icon",  "icon",  $path);

                $status = PaymentDB::add_payment($payment);

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

    public static function update_payment(){

        $output_array = array();

        try {

            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_EDIT);

            if( $permitted ){

                QueryUtil::connect();

                $payment = self::read_payment_form();

                $payment_id  = $request->get_int_parameter("payment_id");

                $old_payment = PaymentDB::get_payment($payment_id);

                //$payment->icon  = FileUtil::replace_thumb("icon", "icon", ICON_PAGES_WIDTH, ICON_PAGES_HEIGHT, $old_payment->icon, $path);
                //$payment->icon  = FileUtil::replace_file("icon",  "icon",  $path, $old_payment->icon );

                $status = PaymentDB::update_payment($payment);

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

    public static function remove_payment(){

        $output_array = array();

        try {

            $path = UPLOAD_DIR . self::$dir;

            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_REMOVE);

            if( $permitted ){

                QueryUtil::connect();

                $payment_id = $request->get_int_parameter("payment_id");

                $payment    = PaymentDB::get_payment($payment_id);

                $status  = PaymentDB::remove_payment($payment);

                if( $status > 0 ){
                    //FileUtil::remove_file($path, $payment->icon );
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


    public static function get_payments(){

        $output_array = array();

        try {

            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_VIEW_ALL);

            if( $permitted ){

                QueryUtil::connect();

                $user_id   = $request->get_int_parameter("user_id");

                $index = $request->get_int_parameter("index");
                $count = $request->get_int_parameter("count");

                //if no count this means unlimited
                $count = ( $count == 0 ) ? -1 : $count;

                $payments       = PaymentDB::get_payments($user_id, $index, $count, '`date`', 'DESC');
                $payments_count = PaymentDB::get_payments_count($user_id);

                $payments_array_list = self::get_formated_array($payments);

                $output_array["payments"]       = $payments_array_list;
                $output_array["payments_count"] = $payments_count;

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


    public static function search_payments(){

        $output_array = array();

        try {

            $request = HttpRequest::get_instance();

            $permitted = self::check_permission(ACTION_VIEW_ALL);

            if( $permitted ){

                QueryUtil::connect();

                $options = array(

                    "payment_id" => $request->get_int_parameter("payment_id"),

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

                $payments       = PaymentDB::search_payments($options, $index, $count);
                $payments_count = PaymentDB::search_payments_count($options);

                $payments_array_list = self::get_formated_array($payments);

                $output_array["payments"]       = $payments_array_list;
                $output_array["payments_count"] = $payments_count;

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

    private static function read_payment_form(){

        $payment = new stdClass();

        try {

            $payment_arr = array();

            $request = HttpRequest::get_instance();

            $payment_arr['payment_id'] = $request->get_int_parameter("payment_id");
            $payment_arr['amount']     = $request->get_double_parameter("amount");
            $payment_arr['status']     = $request->get_int_parameter("status");
            $payment_arr['date']       = $request->get_parameter("date");
            $payment_arr['tnx_id']     = $request->get_parameter("tnx_id");
            $payment_arr['product_id'] = $request->get_int_parameter("product_id");
            $payment_arr['user_id']    = $request->get_int_parameter("user_id");

            $payment = (object) $payment_arr;

        } catch (Exception $e) {
            $output_array["status"] = SERVER_ERROR;
            throw new CustomException( 'Error in : read form', $e );//from php 5.3 no need to custum
        }

        return $payment;
    }


    public static function get_formated_array(array $payments){

        $payments_array_list = array();

        for ( $i=0; $i<count($payments); $i++ ){

            $payment = $payments[$i];

            $paymentObject = self::get_formated_object($payment);

            $payments_array_list[] = $paymentObject;
        }

        return $payments_array_list;
    }

    private static function get_formated_object($payment){

        $payment_object = array();
        
        $payment_object['payment_id'] = $payment->payment_id;
        $payment_object['amount']     = $payment->amount;
        $payment_object['status']     = $payment->status;
        $payment_object['date']       = $payment->date;
        $payment_object['tnx_id']     = $payment->tnx_id;
        $payment_object['product_id'] = $payment->product_id;
        $payment_object['user_id']    = $payment->user_id;
        
        $payment_object['user']       = $payment->user;
        $payment_object['product_ar'] = $payment->product_ar;
        $payment_object['product_en'] = $payment->product_en;

        return $payment_object;
    }

}

?>
