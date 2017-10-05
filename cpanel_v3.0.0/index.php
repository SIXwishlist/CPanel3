<?php session_start();

    include_once './boot.php';

    try {

        $output_html = '';

        $request = HttpRequest::get_instance();
        $page    = $request->get_parameter("page");

        $page = ( $page == null ) ? "home" : $page;

        switch( $page ){

            case "home":
                $output_html = HomeDisplay::get_home();
                break;
            
            
            case "verify":
                $output_html = VerifyDisplay::get_page();
                break;

            case "reset":
                $output_html = ResetDisplay::get_page();
                break;


            case "category_info":
                $output_html = CategoryDisplay::get_category_info();
                break;

            case "product_info":
                $output_html = ProductDisplay::get_product_info();
                break;


            case "section_info":
                $output_html = SectionDisplay::get_section_info();
                break;

            case "target_info":
                $output_html = TargetDisplay::get_target_info();
                break;

            case "embed_info":
                $output_html = EmbedDisplay::get_embed_info();
                break;

            case "search":
                $output_html = SearchDisplay::search();
                break;

            case "contact_us":
                 $output_html = ErrorDisplay::get_error();
                //$output_html = ContactDisplay::get_contact_form();
                break;

            case "cart":
                 $output_html = CartDisplay::get_cart();
                break;

            case "checkout":
                 $output_html = CheckoutDisplay::get_checkout();
                break;


            case "sitemap":
                $output_html = SiteMapDisplay::get_sitemap();
                break;

            case "sitemap_xml":
                $output_html = SiteMapDisplay::get_sitemap_xml();
                break;


            case "manage":
                $output_html = ManageDisplay::get_manage_page();
                break;

            case "error":
                $output_html = ErrorDisplay::get_error();
                break;

            default:
                $output_html = ErrorDisplay::get_error();
                //$output_html = HomeDisplay::get_home();
                break;

        }

        //HitMngController::add_hit();
        
        echo $output_html;

    } catch (Exception $e) {
        Logger::log( $e->getTraceAsString(), ERROR );
        echo $e->getMessage();
    }

?>
