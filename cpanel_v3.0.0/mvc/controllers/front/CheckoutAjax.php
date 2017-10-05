<?php
/*
 *
 */

/**
 * Description of CheckoutAjax
 *
 * @author Ahmad
 */

class CheckoutAjax extends FrontAjax {

    public static $date_format  = 'Y-m-d H:i:s';

    public static function checkout(){

        $output_array = array();
        
        try {

            $request = HttpRequest::get_instance();
            $session = HttpSession::get_instance();
            
            $permitted = self::check_user_logged();
            
            if( $permitted > 0 ){

                QueryUtil::connect();
                
                $cart_items       = CartSession::get_cart_items();

                $total_sale_price = CartSession::get_total_sale_price();
                
                $card_number     = $request->get_parameter("card_number");
                $expiration_date = $request->get_parameter("expiration_date");
                $card_code       = $request->get_parameter("card_code");

                $expire_month    = '';
                $expire_year     = '';
                
                $ex_date_array = split("/", $expiration_date);

                if( count($ex_date_array) > 0 ){
                    $expire_month  = $ex_date_array[0];
                    $expire_year   = $ex_date_array[1];
                }else{
                    throw new CustomException("Invalid Date");
                }
                
                $order_info = array();

                $order_info["number"]       = $card_number;
                $order_info["type"]         = self::get_credit_card_type($card_number);//"mastercard";
                $order_info["expire_month"] = $expire_month;
                $order_info["expire_year"]  = $expire_year;
                $order_info["cvv2"]         = $card_code;
                $order_info["first_name"]   = "Joe";
                $order_info["last_name"]    = "Shopper";
                $order_info["total"]        = $total_sale_price;
                $order_info["currency"]     = "USD";

                $json_response = PaypalDirectPaymentUtil::proceed_order($order_info);
                
                $status = $json_response->status;
                
                if( $json_response->state == "approved" && $json_response->status == 1 ){
                    
                    $user_id      = $session->get_int_attribute("user_id");

                    $tnx_id       = $json_response->id;
                    
                    $payment_date = date( self::$date_format );
                    
                    foreach ($cart_items as $item) {
                        
                        $payment = new stdClass();

                        $payment->status     = 2;
                        $payment->amount     = $total_sale_price;
                        $payment->date       = $payment_date;
                        $payment->tnx_id     = $tnx_id;
                        $payment->quantity   = $item["quantity"];
                        $payment->product_id = $item["pid"];
                        $payment->user_id    = $user_id;

                        $payment_records[] = $payment;

                    }

                    $status = PaymentDB::add_payment_list($payment_records);
                    
                    if( $status > 0 ){
                        CartSession::empty_cart();
                    }

                    //@todo
                    //Sending emails
                    
                    QueryUtil::close();

                }

                $output_array["status"] = intval($status);

            } else {
                $output_array["status"] = UNAUTHORIZED_ACCESS;
            }


        } catch (Exception $e) {
            $output_array["status"] = SERVER_ERROR;
            Logger::log( $e->getMessage(), ERROR );
        }

        return $output_array;
    }
    
    public static function get_credit_card_type($account_number){

        //start without knowing the credit card type
        $result = "unknown";

        //first check for MasterCard
        if (  preg_match("/^5[1-5]/", $account_number )  ) {
            $result = "mastercard";
        }

        //then check for Visa
        else if (  preg_match("/^4/", $account_number )) {
            $result = "visa";
        }

        //then check for AmEx
        else if (  preg_match("/^3[47]/", $account_number )) {
            $result = "amex";
        }

        return $result;
    }

}

?>