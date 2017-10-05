<?php session_start();

    include_once './boot.php';

    try {

        $output_html = '';

        $request = HttpRequest::get_instance();
        $page    = $request->get_parameter("page");

        $page = ( $page == null ) ? "home" : $page;

        switch( $page ){

            case "home":
            case "manage":
                $output_html = ManageDisplay::get_manage_page();
                break;

            default:
                $output_html = ManageDisplay::get_manage_page();
                break;

        }

        //HitMngController::add_hit();
        
        echo $output_html;

    } catch (Exception $e) {
        Logger::log( $e->getTraceAsString(), ERROR );
        echo $e->getMessage();
    }

?>
