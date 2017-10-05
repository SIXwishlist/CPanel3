<?php
/*
 *
 */

/**
 * Description of SiteMapDisplay
 *
 * @author Ahmad
 */

class SiteMapDisplay {

    public static function get_sitemap(){

        $output_string = '';

        try {

            $request = HttpRequest::get_instance();

            $active = 1;

            $lang   = Dictionary::get_language();
            
            if( CACHING_ENABLED ){
                $cache  = Cache::create_instance("sitemap.cache.json");
                $output = $cache->get_data($lang);
                if( !empty($output) ){ return $output; }
            }

            QueryUtil::connect();


            $frame_data = FrontUtil::get_default_data_array();

            $layout     = PageFrame::get_front_layout();

            $sitemap_title = Dictionary::get_text("Sitemap_lbl");
            
            $layout->title  = $sitemap_title . ' | ' . $layout->title_postfix;
            $layout->tags[] = array( "name" => "keywords"   , "content" => $sitemap_title . ' - ' . $layout->title_postfix );
            $layout->tags[] = array( "name" => "description", "content" => $sitemap_title . ' - ' . $layout->title_postfix );
            
            $section_tree  = SectionTreeJSON::get_tree();
            $category_tree = CategoryTreeJSON::get_tree();

            $page_data = array(
                "lang"          => $lang,
                "section_tree"  => $section_tree,
                "category_tree" => $category_tree
            );

            $frame_data["lang_ar"] = UrlUtil::get_sitemap_href('ar');
            $frame_data["lang_en"] = UrlUtil::get_sitemap_href('en');
            
            $frame_data["frame"] = $layout;
            $frame_data["lang"]  = $lang;

            $front_tpl = TplLoader::get_tpl_data('sitemap.tpl.php', 'mvc/views/pages', $page_data);

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

    public static function get_sitemap_xml(){

        $output_string = '';

        try {

            header('Content-type: text/xml');

            $request = HttpRequest::get_instance();

            //$status = AdminHandler::check_admin_logged();
            $active = 1;//$active = ( $status <= 0 ) ? 1 : 0;

            $lang   = Dictionary::get_language();
            
            if( CACHING_ENABLED ){
                $cache  = Cache::create_instance("sitemap.xml.cache.json");
                $output = $cache->get_data($lang);
                if( !empty($output) ){ return $output; }
            }

            
            $section_tree  = SectionTreeJSON::get_tree();
            $category_tree = CategoryTreeJSON::get_tree();

            $page_data = array(
                "lang"          => $lang,
                "section_tree"  => $section_tree,
                "category_tree" => $category_tree
            );

            $sitemap_tpl = TplLoader::get_tpl_data('sitemap_xml.tpl.php', 'mvc/views/pages', $page_data);

            $output_string =  $sitemap_tpl;

            if( CACHING_ENABLED){
                $cache->set_data($lang, $output_string);
            }


        } catch (Exception $e) {
            Logger::log( $e->getMessage(), ERROR );
        }
        
        return $output_string;
    }

    public static function get_video_sitemap_xml(){

        $output_string = '';

        try {

            header('Content-type: text/xml');

            $request = HttpRequest::get_instance();

            //$status = AdminHandler::check_admin_logged();
            $active = 1;//$active = ( $status <= 0 ) ? 1 : 0;
            
            $lang   = Dictionary::get_language();
            
            if( CACHING_ENABLED ){
                $cache  = Cache::create_instance("sitemap.video.xml.cache.json");
                $output = $cache->get_data($lang);
                if( !empty($output) ){ return $output; }
            }

            $items = SectionTreeJSON::get_tree();

            $page_data = array(
                "lang"  => $lang,
                "items" => $items
            );

            $video_sitemap_tpl = TplLoader::get_tpl_data('video_sitemap_xml.tpl', 'mvc/views/pages', $page_data);

            $output_string = $video_sitemap_tpl;

            if( CACHING_ENABLED){
                $cache->set_data($lang, $output_string);
            }

        } catch (Exception $e) {
            Logger::log( $e->getMessage(), ERROR );
        }
        
        return $output_string;
    }

}
