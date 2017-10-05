<?php
/*
 *
 */

/**
 * Description of ErrorDisplay
 *
 * @author Ahmad
 */

class ErrorDisplay {

    public static function get_error(){

        $output_string = '';

        try {

            $request = HttpRequest::get_instance();

            $active = 1;

            $lang   = Dictionary::get_language();

            if( CACHING_ENABLED){
                $cache  = Cache::create_instance("page.error.cache.json");
                $output = $cache->get_data($lang);
                if( !empty($output) ){ return $output; }
            }

            QueryUtil::connect();

            $frame_data = FrontUtil::get_default_data_array();

            $layout     = PageFrame::get_front_layout();

            $error_title = Dictionary::get_text("PageNotFound_lbl");
            
            $layout->title  = $error_title . ' | ' . $layout->title_postfix;
            $layout->tags[] = array( "name" => "keywords"   , "content" => $error_title . ' - ' . $layout->title_postfix );
            $layout->tags[] = array( "name" => "description", "content" => $error_title . ' - ' . $layout->title_postfix );


            $page_data = array();

            $frame_data["lang_ar"] = BASE_URL."ar";
            $frame_data["lang_en"] = BASE_URL."en";

            $frame_data["frame"]  = $layout;
            $frame_data["lang"]   = $lang;

            $front_tpl = TplLoader::get_tpl_data('error.tpl', 'mvc/views/pages', $page_data);

            $frame_data["page_content"] = $front_tpl;

            $output_string = TplLoader::get_tpl_data('frame.tpl.php', 'mvc/views/front', $frame_data);
            
            QueryUtil::close();
            
            if( CACHING_ENABLED){
                $cache->set_data($lang, $output_string);
            }
            
        } catch (Exception $e) {
            Logger::log( $e->getMessage(), ERROR );
        }
        
        return $output_string;
    }
}

?>