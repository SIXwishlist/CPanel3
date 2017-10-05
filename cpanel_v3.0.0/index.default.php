<?php session_start();

    include_once './pre.php';

    //include_once BASE_DIR.'/mvc/views/functions/view_utils.php';

    try {

        $output_html = '';

        ConstantLoader::load_config_properties();

        Dictionary::define_variables();

//        try{
//            QueryUtil::connect();
//        } catch (Exception $e) {
//            Logger::log( $e->getMessage(), ERROR, true );
//        }

        $request = HttpRequest::get_instance();
        $page    = $request->get_parameter("page");

        $page = ( $page == null ) ? "home" : $page;

        switch( $page ){

            //case "home":
            //    $output_html = HomeDisplay::get_home();
            //    break;

            default:
                $output_html = HomeDisplay::get_home();
                break;

        }

        //HitMngController::add_hit();
        
        echo $output_html;

//        try{
//            QueryUtil::close();
//        } catch (Exception $e) {
//            Logger::log( $e->getMessage(), ERROR );
//        }

    } catch (Exception $e) {
        Logger::log( $e->getTraceAsString(), ERROR );
        echo $e->getMessage();
    }

?>
