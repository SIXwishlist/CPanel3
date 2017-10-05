<?php

/*
 *
 */

/**
 * Description of PaypalDirectPaymentUtil
 *
 * @author Ahmad
 */

class PaypalDirectPaymentUtil {

    public static function proceed_order($order_info){
        
        $response = null;

        try {
            
            ob_start();

            try {

                //You can use the below php code for the credit card payment :

                $number       = $order_info["number"];
                $type         = $order_info["type"];
                $expire_month = $order_info["expire_month"];
                $expire_year  = $order_info["expire_year"];
                $cvv2         = $order_info["cvv2"];
                $first_name   = $order_info["first_name"];
                $last_name    = $order_info["last_name"];
                $total        = $order_info["total"];
                $currency     = $order_info["currency"];

                //open connection to getting the token
                $ch = curl_init();

                if( PAYPAL_MODE == PAYPAL_MODE_SANDBOX ){

                    $client      = PAYPAL_SANDBOX_CLIENT;
                    $secret      = PAYPAL_SANDBOX_SECRET;

                    $auth_url    = PAYPAL_SANDBOX_AUTH_URL;
                    $payment_url = PAYPAL_SANDBOX_PAYMENT_URL;

                }else{                    

                    $client      = PAYPAL_LIVE_CLIENT;
                    $secret      = PAYPAL_LIVE_SECRET;

                    $auth_url    = PAYPAL_LIVE_AUTH_URL;
                    $payment_url = PAYPAL_LIVE_PAYMENT_URL;

                }


                //curl_setopt($ch, CURLOPT_URL, "https://api.paypal.com/v1/oauth2/token");
                //curl_setopt($ch, CURLOPT_URL, "https://api.sandbox.paypal.com/v1/oauth2/token");

                curl_setopt($ch, CURLOPT_URL, $auth_url);
                curl_setopt($ch, CURLOPT_HEADER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
                curl_setopt($ch, CURLOPT_USERPWD, $client.":".$secret);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");

                $result = curl_exec($ch);

                if( empty($result) ){
                    throw new CustomException("Error: No response From Paypal.");
                } else {
                    $json = json_decode($result);
                    //print_r($json->access_token);
                }

                $access_token = $json->access_token;

                // Now doing txn after getting the token 
                $ch = curl_init();

                $data = '{
                  "intent":"sale",
                  "redirect_urls":{
                    "return_url":"http://<return URL here>",
                    "cancel_url":"http://<cancel URL here>"
                  },
                  "payer": {
                    "payment_method": "credit_card",
                    "funding_instruments": [
                      {
                        "credit_card": {
                          "number": "'      .$number.       '",
                          "type": "'        .$type.         '",
                          "expire_month": ' .$expire_month. ',
                          "expire_year": '  .$expire_year.  ',
                          "cvv2": '         .$cvv2.         ',
                          "first_name": "'  .$first_name.   '",
                          "last_name": "'   .$last_name.    '"
                        }
                      }
                    ]
                  },
                  "transactions":[
                    {
                      "amount":{
                        "total":"'    .$total.    '",
                        "currency":"' .$currency. '"
                      },
                      "description":"This is the payment transaction description."
                    }
                  ]
                }
                ';

                //curl_setopt($ch, CURLOPT_URL, "https://api.paypal.com/v1/payments/payment");
                //curl_setopt($ch, CURLOPT_URL, "https://api.sandbox.paypal.com/v1/payments/payment");

                curl_setopt($ch, CURLOPT_URL, $payment_url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json","Authorization: Bearer ".$access_token));

                $result = curl_exec($ch);


                if( empty($result) ){
                    throw new CustomException("Error: No response From Paypal.");
                } else {
                    $json = json_decode($result);
                    //print_r($json);
                }

                $response_string = ob_get_contents();

                $response = json_decode($response_string);
                
                $response->status = $json;

            } catch (Exception $e) {
                throw new CustomException("Error in Proceeding Order, Order Not Completed.", $e);
            }
        
            ob_end_clean();

        } catch (Exception $e) {
            throw new CustomException("Error while Proceeding Order, Order May Not Completed.", $e);
        }

        return $response;
    }

}
