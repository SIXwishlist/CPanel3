<?php
/*
 *
 */

/**
 * Description of ManageDisplay
 *
 * @author Ahmad
 */

class ManageDisplay {

    public static function get_manage_page(){

        $output_string = '';

        try {

            $request = HttpRequest::get_instance();

            $lang    = Dictionary::get_language();

            if( CACHING_ENABLED ){
                $cache  = Cache::create_instance("page.manage.cache.json");
                $output = $cache->get_data($lang);
                if( !empty($output) ){ return $output; }
            }

            QueryUtil::connect();

            $frame_data = array();
            $page_data  = array();
            
            $page_data["page"]  = 'manage';
            $page_data["style"] = '';

            $layout = PageFrame::get_manage_layout();

            
            $layout->title  = ' .:. '.WEBSITE_NAME.' .:. ';

            $layout->tags[] = array( "name" => "keywords"   , "content" => WEBSITE_NAME );
            $layout->tags[] = array( "name" => "description", "content" => WEBSITE_NAME );


            $frame_data["frame"] = $layout;
            $frame_data["lang"]  = $lang;

            $manage_tpl = TplLoader::get_tpl_data('manage.tpl', 'mvc/views/pages', $page_data);

            $frame_data["page_content"] = $manage_tpl;

            $output_string = TplLoader::get_tpl_data('frame.tpl.php', 'mvc/views/manage', $frame_data);         
            
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