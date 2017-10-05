<?php
/*
 *
 */

define("GOOGLE_API_KEY",          'AIzaSyD_87xwBxO8vYi4cKboeMGplUWkS5L6_bI');

define("CERT_FILE_SANDBOX",       'dev.pem');
define("CERT_PASSPHRASE_SANDBOX", 'arak@CERT-4dev');

define("CERT_FILE",               'aps.pem');
define("CERT_PASSPHRASE",         'arak@CERT-4dev');

require_once BASE_DIR.'/mvc/libraries/ApnsPHP/Autoload.php';
#require_once 'ApnsPHP/Autoload.php';

/**
 * Description of PushUtil
 *
 * @author Ahmad
 */

class PushUtil {

    public static function send_ios_push_notification($message, $deviceTokens) {
            
        $status = SERVER_ERROR;

        try {

            $cert_path = BASE_DIR . '/certs/' . CERT_FILE;

            ob_start();
            
            $push = new ApnsPHP_Push(
                        ApnsPHP_Abstract::ENVIRONMENT_PRODUCTION,
                        //ApnsPHP_Abstract::ENVIRONMENT_SANDBOX,
                        $cert_path
                    );

            $push->setProviderCertificatePassphrase(CERT_PASSPHRASE);

            if (count($deviceTokens) >= 1) {

                // do the actual push notification
                $push->connect();

                foreach( $deviceTokens as $token ) {

                    $apnsMessage = new ApnsPHP_Message( $token );

                    //$apnsMessage->setCustomIdentifier("Message-Badge-3");
                    $apnsMessage->setBadge(0);
                    $apnsMessage->setText($message);
                    $apnsMessage->setSound();
                    $apnsMessage->setExpiry(30);

                    $push->add( $apnsMessage );

                }

            }

            $push->send();
            
            $push->disconnect();

            $aErrorQueue = $push->getErrors();

            if (!empty($aErrorQueue)) {

                $status = FAILED;
                //var_dump($aErrorQueue);
                throw new CustomException( print_r($aErrorQueue) );
                
            } else {                
                $status = SUCCESS;
            }

            //$status   = ( empty($aErrorQueue) ) ? SUCCESS : FAILED;

            $log_string = ob_get_contents();

        } catch (Exception $e) {
            Logger::log( $e->getMessage(), ERROR );
        } //finally {    
            //ob_end_clean();
        //}
        ob_end_clean();
        
        return $status;
    }

    public static function send_ios_push_notification_method2($message, $deviceTokens){
        
        $status = SERVER_ERROR;

        try {

            // Provide the Host Information.

            $production = array(
                "local_cert" => CERT_FILE,
                "passphrase" => CERT_PASSPHRASE,
                "host"       => 'gateway.push.apple.com',
            );

            $sandbox = array(
                "local_cert" => CERT_FILE_SANDBOX,
                "passphrase" => CERT_PASSPHRASE_SANDBOX,
                "host"       => 'gateway.sandbox.push.apple.com',
            );


            $env_info = $production;


            $local_cert = BASE_DIR . '/certs/' . $env_info["local_cert"];
            $passphrase = $env_info["passphrase"];

            $host       = $env_info["host"];
            $port       = 2195;


            
            // Create the message content that is to be sent to the device.
            $body = array();
            $body['aps'] = array( 'alert' => $message, 'sound' => 'default');//, 'badge' => 1 );
            $payload = json_encode($body);


            // Create the Socket Stream.
            $context = stream_context_create();
            
            stream_context_set_option($context, 'ssl', 'local_cert', $local_cert);
            stream_context_set_option($context, 'ssl', 'passphrase', $passphrase);

            // Open the Connection to the APNS Server.
            $socket = stream_socket_client('ssl://'.$host.':'.$port, $error, $errstr, 30, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $context);

            // Check if we were able to open a socket.
            if (!$socket){
                throw new Exception("APNS Connection Failed: $error $errstr " . PHP_EOL);
            }

            foreach ($deviceTokens as $token) {

                // Build the Binary Notification.
                $msg = chr (0) . chr (0) . chr (32) . pack ('H*', $token) . pack ('n', strlen ($payload)) . $payload;

                // Send the Notification to the Server.
                $result = fwrite( $socket, $msg, strlen ($msg) );
            
                if( $result > 0 ){                    
                    $results ++;
                }
            
            }

            // Close the Connection to the Server.
            fclose($socket);

            if ( $results == count($deviceTokens) ){
                $status = SUCCESS;
            } else {
                $status = FAILED;
            }

            //$status   = ( $result > 0 ) ? SUCCESS : FAILED;

        } catch (Exception $e) {
            Logger::log( $e->getMessage(), ERROR );
            $status = FAILED;
        }
        
        return $status;
    }

    
    public static function send_android_push_notification($message, $registrationIDs){
        
        $status = SERVER_ERROR;

        try {
            
            // Replace with real BROWSER API key from Google APIs
            $apiKey = GOOGLE_API_KEY;

            // Set POST variables
            $url = 'https://android.googleapis.com/gcm/send';

            $fields = array(
                'registration_ids' => $registrationIDs,
                'data' => array("message" => $message),
            );

            $headers = array(
                'Authorization: key=' . $apiKey,
                'Content-Type: application/json'
            );

            
            // Open connection
            $ch = curl_init();

            // Set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);

            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Disabling SSL Certificate support temporarly
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

            // Execute post
            $result_string = curl_exec($ch);
            
            if ($result_string === FALSE) {
                throw new Exception('Curl failed: ' . curl_error($ch) );
            }

            // Close connection
            curl_close($ch);

            //{
            //  "multicast_id":5595877223857099230,
            //  "success":0,
            //  "failure":1,
            //  "canonical_ids":0,
            //  "results":[
            //      {"error":"InvalidRegistration"}
            //   ]
            //}
            
            $result   = json_decode($result_string);
            
            $status   = $result->success;

            $status   = ( $result->success > 0 ) ? SUCCESS : FAILED;
            
        } catch (Exception $e) {
            Logger::log( $e->getMessage(), ERROR );
        }
        
        return $status;
    }

}
