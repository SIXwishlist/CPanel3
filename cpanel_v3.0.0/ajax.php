<?php session_start();

    include_once './boot.php';

    try {

        $request = HttpRequest::get_instance();
        $action  = $request->get_parameter("action");

        $action = ( $action == null ) ? "error" : $action;

        $output_array = array();

        switch ($action) {

            /******************************************************************/
            //disctionary
            case "dictionary":
                $output_array = DictionaryManage::get_frontend_dictionary();
                break;

            /******************************************************************/
            //qsearch
            case "qsearch":
                $output_array = SearchDisplay::quick_search();
                break;

            /******************************************************************/

            //check_login
            case "check_login":
                $output_array = UserAuth::check_login();
                break;

            //authenticate/login
            case "authenticate":
            case "login":
            case "signin":
                $output_array = UserAuth::authenticate();
                break;

            //logout
            case "logout":
                $output_array = UserAuth::logout();
                break;

            //check_exist
            case "check_exist":
                $output_array = UserAuth::check_exist();
                break;

            //forget_password
            case "forget_password":
                $output_array = UserAuth::forget_password();
                break;

            //reset_password
            case "reset_password":
                $output_array = UserAuth::reset_password();
                break;

            //signup
            case "signup":
            case "register":
                $output_array = UserAuth::register();
                break;

            /******************************************************************/

            //add_item
            case "add_item":
                $output_array = CartAjax::add_item();
                break;

            //update_item
            case "update_item":
                $output_array = CartAjax::update_item();
                break;

            //remove_item
            case "remove_item":
                $output_array = CartAjax::remove_item();
                break;

            //empty_cart
            case "empty_cart":
                $output_array = CartAjax::empty_cart();
                break;

            //cart
            case "cart":
                $output_array = CartAjax::get_cart();
                break;

            /******************************************************************/

            //checkout
            case "checkout":
                $output_array = CheckoutAjax::checkout();
                break;

            /******************************************************************/


            //main_background_requests
            case "main_background_requests":
                $output_array = BackgroundManage::get_main();
                break;
            
            /******************************************************************/
            
            //add_admin
            case "add_admin":
                $output_array = AdminManage::add_admin();
                break;

            //update_admin
            case "update_admin":
                $output_array = AdminManage::update_admin();
                break;

            //remove_admin
            case "remove_admin":
                $output_array = AdminManage::remove_admin();
                break;

            //admins
            case "admins":
                $output_array = AdminManage::get_admins();
                break;

            //search_admins
            case "search_admins":
                $output_array = AdminManage::search_admins();
                break;

            /******************************************************************/
            
            //notifications
            case "notifications":
                $output_array = NotificationManage::get_notifications();
                break;

            /******************************************************************/

            //countries
            case "countries":
                $output_array = CountryManage::get_countries();
                break;

            default:
                break;
        }

        $json_output = json_encode($output_array);

        echo $json_output;

    } catch (Exception $e) {
        Logger::log( $e->getTraceAsString(), ERROR );
        
        $output_array["status"] = -1;
        $json_output = json_encode($output_array);
        echo $json_output;
    }
    
?>