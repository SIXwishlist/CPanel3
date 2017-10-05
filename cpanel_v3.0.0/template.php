<?php session_start();

    include_once './boot.php';

    try {

        $request = HttpRequest::get_instance();
        $tpl     = $request->get_parameter("tpl");

        $tpl     = ( $tpl == null ) ? "unknown" : $tpl;
        
        $data    = array();

        $output_string = "";

        switch ($tpl) {

            case "forget_password":
                $output_string = TplLoader::get_tpl_data('admin_forget_password.tpl', 'mvc/views/tpl/js/forms', $data);
                break;

            case "reset_password":
                $output_string = TplLoader::get_tpl_data('admin_reset_password.tpl', 'mvc/views/tpl/js/forms', $data);
                break;

            case "admin_login":
                $output_string = TplLoader::get_tpl_data('admin_login.tpl', 'mvc/views/tpl/js/forms', $data);
                break;

            case "import_form":
                $output_string = TplLoader::get_tpl_data('import_form.tpl', 'mvc/views/tpl/js/forms', $data);
                break;

            case "editor_popup":
                $output_string = TplLoader::get_tpl_data('editor_popup.tpl', 'mvc/views/tpl/js/forms', $data);
                break;

            case "popup":
                $output_string = TplLoader::get_tpl_data('popup.tpl', 'mvc/views/tpl/js/popup', $data);
                break;

            default:
                break;

        }

        echo $output_string;


    } catch (Exception $e) {
        Logger::log( $e->getTraceAsString(), ERROR );
        echo $e->getMessage();
    }
    
?>