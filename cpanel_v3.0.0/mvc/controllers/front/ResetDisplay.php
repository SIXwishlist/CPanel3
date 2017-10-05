<?php
/*
 *
 */

/**
 * Description of ResetDisplay
 *
 * @author Ahmad
 */

class ResetDisplay {
    
    public static $date_format = 'Y-m-d';

    public static function get_page(){

        $output_string = '';

        try {

            $request = HttpRequest::get_instance();
            $session = HttpSession::get_instance();

            $lang   = Dictionary::get_language();

            QueryUtil::connect();

            $frame_data = FrontUtil::get_default_data_array();

            $layout     = PageFrame::get_front_layout();

            $target     = TargetDB::get_target(HOME_PAGE);

            if( $target != null ){
                $layout->title  = Dictionary::get_text_by_lang($target, "title") . ' | ' . $layout->title_postfix;
                $layout->tags[] = array( "name" => "keywords"   , "content" => Dictionary::get_text_by_lang($target, "keys") );
                $layout->tags[] = array( "name" => "description", "content" => Dictionary::get_text_by_lang($target, "desc") );
            }

            $user_id = $request->get_int_parameter("user_id");
            $ukey    = $request->get_parameter("ukey");

            $page_data = array(
                "user_id"  => $user_id,
                "user_key" => $ukey
            );
            

            $main_data["lang_ar"] = UrlUtil::get_reset_href($user_id, $ukey, "ar");
            $main_data["lang_en"] = UrlUtil::get_reset_href($user_id, $ukey, "en");

            $frame_data["slide_mode"] = false;
            $frame_data["wide_main"]  = 'wide_main';
            
            $frame_data["frame"] = $layout;
            $frame_data["lang"]  = $lang;

            $front_tpl = TplLoader::get_tpl_data('reset.tpl.php', 'mvc/views/pages', $page_data);

            $frame_data["page_content"] = $front_tpl;

            $output_string = TplLoader::get_tpl_data('frame.tpl.php', 'mvc/views/front', $frame_data);         
            
            QueryUtil::close();
            
            if( CACHING_ENABLED ){
                $cache->set_data($lang, $output_string);
            }
            
        } catch (Exception $e) {
            Logger::log( $e->getMessage(), ERROR );
        }
        
        return $output_string;
    }

}

?>