<?php session_start();

    include_once './bootstrap.php';

    $request = HttpRequest::get_instance();
    
    $action  = $request->get_parameter("action");
    
    $action  = ( $action == null ) ? "error" : $action;

    $output_array = array();

    switch ($action) {

        /**********************/
        /* Push Notifications */
        /**********************/
        //save_ios_device_token
        case "save_ios_device_token":
            $output_array = NotificationMobileController::save_ios_device_token();
            break;

        //save_android_reg_id
        case "save_android_reg_id":
            $output_array = NotificationMobileController::save_android_reg_id();
            break;

        //send_ios_push_notification
        case "send_ios_push_notification":
            $output_array = NotificationMobileController::send_ios_push_notification();
            break;

        //send_android_push_notification
        case "send_android_push_notification":
            $output_array = NotificationMobileController::send_android_push_notification();
            break;

        //send_ios_push_notification_broadcast
        case "send_ios_push_notification_broadcast":
            $output_array = NotificationMobileController::send_ios_push_notification_broadcast();
            break;

        //send_android_push_notification_broadcast
        case "send_android_push_notification_broadcast":
            $output_array = NotificationMobileController::send_android_push_notification_broadcast();
            break;

        default:
            break;
    }

    $json_output = json_encode($output_array);

    header('Content-Type: application/json');
    
    echo $json_output;

    //$output_array = HitMngController::add_hit();
    //CounterControl::addPageCounter();
    //HitControl::addPageHit();

?>