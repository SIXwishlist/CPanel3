<?php session_start();

    include_once './boot.php';

    $request = HttpRequest::get_instance();
    
    $action  = $request->get_parameter("action");
    
    $action  = ( $action == null ) ? "error" : $action;

    $output_array = array();

    try{
        
        switch ($action) {

            /************/
            /* Register */
            /************/

            //login
            case "login":
                $output_array = MobileController::login();
                break;

            //parent_register
            case "register":
                $output_array = MobileController::register();
                break;

            //verify
            case "verify":
                $output_array = MobileController::verify();
                break;

            //clone_register
            case "clone_register":
                $output_array = MobileController::clone_register();
                break;

            /*************************/
            /* Notifications */
            /*************************/
            //notifications
            //case "notifications":
            //    $output_array = MobileController::get_notifications();
            //    break;


            /****************/
            /* Maintenance */
            /****************/

            //cars
            case "cars":
                $output_array = MobileController::get_cars();
                break;

            /****************/
            /* Cars */
            /****************/

            //maintenance_form_data
            case "maintenance_form_data":
                $output_array = MobileController::get_maintenance_form_data();
                break;

            //maintenances
            case "maintenances":
                $output_array = MobileController::get_maintenances();
                break;

            //add_maintenance
            case "add_maintenance":
                $output_array = MobileController::add_maintenance();
                break;


            /**********************/
            /* Push Notifications */
            /**********************/
            //save_ios_device_token
            case "save_ios_device_token":
                $output_array = MobileController::save_ios_device_token();
                break;

            //save_android_reg_id
            case "save_android_reg_id":
                $output_array = MobileController::save_android_reg_id();
                break;

            //send_ios_push_notification
            case "send_ios_push_notification":
                $output_array = MobileController::send_ios_push_notification();
                break;

            //send_android_push_notification
            case "send_android_push_notification":
                $output_array = MobileController::send_android_push_notification();
                break;

            //send_ios_push_notification_broadcast
            case "send_ios_push_notification_broadcast":
                $output_array = MobileController::send_ios_push_notification_broadcast();
                break;

            //send_android_push_notification_broadcast
            case "send_android_push_notification_broadcast":
                $output_array = MobileController::send_android_push_notification_broadcast();
                break;

            /*********/
            /* Error */
            /*********/
            //error
            case "error":
                $output_array = MobileController::error();
                break;

            default:
                break;
        }

    } catch (Exception $e) {
        $output_array["status"] = SERVER_ERROR;
    }

    $json_output = json_encode($output_array);

    header('Content-Type: application/json');
    
    echo $json_output;

    //$output_array = HitMngController::add_hit();
    //CounterControl::addPageCounter();
    //HitControl::addPageHit();

?>