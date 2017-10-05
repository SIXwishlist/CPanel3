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
                $output_array = DictionaryManage::get_backend_dictionary();
                break;

            /******************************************************************/

            //check_login
            case "check_login":
                $output_array = AdminAuth::check_login();
                break;

            //authenticate/login
            case "authenticate":
            case "login":
            case "signin":
                $output_array = AdminAuth::authenticate();
                break;

            //logout
            case "logout":
                $output_array = AdminAuth::logout();
                break;

            //check_exist
            case "check_exist":
                $output_array = AdminAuth::check_exist();
                break;

            //forget_password
            case "forget_password":
                $output_array = AdminAuth::forget_password();
                break;

            //reset_password
            case "reset_password":
                $output_array = AdminAuth::reset_password();
                break;

            //signup
            case "signup":
            case "register":
                $output_array = AdminAuth::register();
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

            //cache
            case "rebuild_category_tree":
                $output_array = CacheManage::rebuild_category_tree();
                break;

            case "rebuild_section_tree":
                $output_array = CacheManage::rebuild_section_tree();
                break;

            case "clear_cache":
                $output_array = CacheManage::clear_cache();
                break;

            /******************************************************************/

            //sections
            case "section_path":
                $output_array = SectionChildManage::get_section_path();
                break;

            //sections
            case "section_childs":
                $output_array = SectionChildManage::get_section_childs();
                break;
            
            /******************************************************************/

            //add_section
            case "add_section":
                $output_array = SectionManage::add_section();
                break;

            //update_section
            case "update_section":
                $output_array = SectionManage::update_section();
                break;

            //remove_section
            case "remove_section":
                $output_array = SectionManage::remove_section();
                break;
            
            /******************************************************************/

            //add_target
            case "add_target":
                $output_array = TargetManage::add_target();
                break;

            //update_target
            case "update_target":
                $output_array = TargetManage::update_target();
                break;

            //remove_target
            case "remove_target":
                $output_array = TargetManage::remove_target();
                break;
            
            /******************************************************************/

            //add_embed
            case "add_embed":
                $output_array = EmbedManage::add_embed();
                break;

            //update_embed
            case "update_embed":
                $output_array = EmbedManage::update_embed();
                break;

            //remove_embed
            case "remove_embed":
                $output_array = EmbedManage::remove_embed();
                break;

            /******************************************************************/

            //add_link
            case "add_link":
                $output_array = LinkManage::add_link();
                break;

            //update_link
            case "update_link":
                $output_array = LinkManage::update_link();
                break;

            //remove_link
            case "remove_link":
                $output_array = LinkManage::remove_link();
                break;

            /******************************************************************/

            //categories
            case "category_path":
                $output_array = CategoryChildManage::get_category_path();
                break;

            //categories
            case "category_childs":
                $output_array = CategoryChildManage::get_category_childs();
                break;

            /******************************************************************/

            //add_category
            case "add_category":
                $output_array = CategoryManage::add_category();
                break;

            //update_category
            case "update_category":
                $output_array = CategoryManage::update_category();
                break;

            //remove_category
            case "remove_category":
                $output_array = CategoryManage::remove_category();
                break;

            //category_info
            case "category_info":
                $output_array = CategoryManage::get_category_info();
                break;

            /******************************************************************/

            //add_product
            case "add_product":
                $output_array = ProductManage::add_product();
                break;

            //update_product
            case "update_product":
                $output_array = ProductManage::update_product();
                break;

            //remove_product
            case "remove_product":
                $output_array = ProductManage::remove_product();
                break;

            /******************************************************************/

            //add_shot
            case "add_shot":
                $output_array = ShotManage::add_shot();
                break;

            //update_shot
            case "update_shot":
                $output_array = ShotManage::update_shot();
                break;

            //remove_shot
            case "remove_shot":
                $output_array = ShotManage::remove_shot();
                break;

            //shots
            case "shots":
                $output_array = ShotManage::get_shots();
                break;

            /******************************************************************/

            //add_slide
            case "add_slide":
                $output_array = SlideManage::add_slide();
                break;

            //update_slide
            case "update_slide":
                $output_array = SlideManage::update_slide();
                break;

            //remove_slide
            case "remove_slide":
                $output_array = SlideManage::remove_slide();
                break;

            //slides
            case "slides":
                $output_array = SlideManage::get_slides();
                break;
            
            //search_slides
            case "search_slides":
                $output_array = SlideManage::search_slides();
                break;
  
            /******************************************************************/
                        
            //add_ad
            case "add_ad":
                $output_array = AdManage::add_ad();
                break;

            //update_ad
            case "update_ad":
                $output_array = AdManage::update_ad();
                break;

            //remove_ad
            case "remove_ad":
                $output_array = AdManage::remove_ad();
                break;

            //ads
            case "ads":
                $output_array = AdManage::get_ads();
                break;
            
            //search_ads
            case "search_ads":
                $output_array = AdManage::search_ads();
                break;
            
            /******************************************************************/
                        
            //add_user
            case "add_user":
                $output_array = UserManage::add_user();
                break;

            //update_user
            case "update_user":
                $output_array = UserManage::update_user();
                break;

            //remove_user
            case "remove_user":
                $output_array = UserManage::remove_user();
                break;

            //users
            case "users":
                $output_array = UserManage::get_users();
                break;

            //search_users
            case "search_users":
                $output_array = UserManage::search_users();
                break;

            /******************************************************************/

            //add_payment
            case "add_payment":
                $output_array = PaymentManage::add_payment();
                break;

            //update_payment
            case "update_payment":
                $output_array = PaymentManage::update_payment();
                break;

            //remove_payment
            case "remove_payment":
                $output_array = PaymentManage::remove_payment();
                break;

            //payments
            case "payments":
                $output_array = PaymentManage::get_payments();
                break;

            //search_payments
            case "search_payments":
                $output_array = PaymentManage::search_payments();
                break;

            /******************************************************************/

            //add_wished_item
            case "add_wished_item":
                $output_array = WishedItemManage::add_item();
                break;

            //update_wished_item
            case "update_wished_item":
                $output_array = WishedItemManage::update_item();
                break;

            //remove_wished_item
            case "remove_wished_item":
                $output_array = WishedItemManage::remove_item();
                break;

            //wished_items
            case "wished_items":
                $output_array = WishedItemManage::get_items();
                break;

            //search_wish_items
            case "search_wish_items":
                $output_array = WishedItemManage::search_items();
                break;

            /******************************************************************/

            ////////////////////////////////////////////////////////////////
            //android device
            case "add_android_device":
                $output_array = DeviceAndroidManage::add_device();
                break;
            case "update_android_device":
                $output_array = DeviceAndroidManage::update_device();
                break;
            case "remove_android_device":
                $output_array = DeviceAndroidManage::remove_device();
                break;

            case "android_devices":
                $output_array = DeviceAndroidManage::get_devices();
                break;

            ////////////////////////////////////////////////////////////////
            //ios device
            case "add_ios_device":
                $output_array = DeviceIOSManage::add_device();
                break;
            case "update_ios_device":
                $output_array = DeviceIOSManage::update_device();
                break;
            case "remove_ios_device":
                $output_array = DeviceIOSManage::remove_device();
                break;

            case "ios_devices":
                $output_array = DeviceIOSManage::get_devices();
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


        if( AUTO_CACHING_CMS ){

            $status = $output_array['status'];

            if( $status > 0 ){

                $no_front_update_array = array(
                    "add_admin",  "update_admin", "remove_admin", 
                    "dictionary", 
                    //"directory_content",  "create_folder", "upload_file", "remove_item", 
                    "check_admin_login", "admin_login", "admin_auth", "admin_logout",

                    "dictionary",
                    "check_login", "authenticate",
                    "login", "signin", "logout", 
                    "forget_password", "reset_password", 
                    "signup", "register", "check_exist", 
                    "main_background_requests"
                );

                $section_update_array = array(
                    "add_section", "update_section", "remove_section", 
                    "add_target",  "update_target",  "remove_target", 
                    "add_target",  "update_target",  "remove_target", 
                    "add_embed",   "update_embed",   "remove_embed", 
                    "add_link",    "update_link",    "remove_link"
                );

                $category_update_array = array(
                    "add_category", "update_category", "remove_category", 
                    "add_product",  "update_product",  "remove_product"
                );


                if (  in_array($action, $section_update_array)  ) {
                    $status = SectionTreeJSON::build();
                    $output_array['status'] = $status;
                }

                else if (  in_array($action, $category_update_array)  ) {
                    $status = CategoryTreeJSON::build();
                    $output_array['status'] = $status;
                }


                if( $status > 0 ){
                    if ( ! in_array($action, $no_front_update_array)  ) {
                        $status = Cache::remove_cached_files();
                        $output_array['status'] = $status;
                    }
                }
            }

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