<?php
/*
 *
 */

define("HOME_MENU", 1);

/**
 * Description of HomeDisplay
 *
 * @author Ahmad
 */

class HomeDisplay {

    public static function get_home(){

        $output_string = '';

        try {

            $request = HttpRequest::get_instance();

            $active = 1;

            $lang   = Dictionary::get_language();

            if( CACHING_ENABLED){
                $cache  = Cache::create_instance("page.home.cache.json");
                $output = $cache->get_data($lang);
                if( !empty($output) ){ return $output; }
            }

            QueryUtil::connect();

            $frame_data = FrontUtil::get_default_data_array();

            $layout     = PageFrame::get_front_layout();

            $target     = TargetDB::get_target(HOME_PAGE);

            if( $target != null ){
                $layout->title  = Dictionary::get_text_by_lang($target, "title") . ' | ' . $layout->title_postfix;
                $layout->tags[] = array( "name" => "keywords"   , "content" => Dictionary::get_text_by_lang($target, "keys") );
                $layout->tags[] = array( "name" => "description", "content" => Dictionary::get_text_by_lang($target, "desc") );
            }
            
            $product_params = array(
                "featured" => 1,
                "offer"    => 0,
                "sale"     => 0,
                "recent"   => 0
            );
            
            $featured_params = array("featured" => 1, "offer" => 0, "sale" => 0, "recent" => 0);
            $offer_params    = array("featured" => 0, "offer" => 1, "sale" => 0, "recent" => 0);
            $sale_params     = array("featured" => 0, "offer" => 0, "sale" => 1, "recent" => 0);
            $recent_params   = array("featured" => 0, "offer" => 0, "sale" => 0, "recent" => 1);
            
            $featured_products = ProductDB::search_products($featured_params, -1, -1, 'RAND()', '');
            $offer_products    = ProductDB::search_products($offer_params,    -1, -1, 'RAND()', '');
            $sale_products     = ProductDB::search_products($sale_params,     -1, -1, 'RAND()', '');
            $recent_products   = ProductDB::search_products($recent_params,   -1, -1, 'RAND()', '');
            
            $page_data = array(
                "target"            => $target,
                "featured_products" => $featured_products,
                "offer_products"    => $offer_products,
                "sale_products"     => $sale_products,
                "recent_products"   => $recent_products
            );


            
            $frame_data["lang_ar"] = BASE_URL."ar";
            $frame_data["lang_en"] = BASE_URL."en";

            $frame_data["slide_mode"] = true;
            $frame_data["wide_main"]  = 'wide_main';
            
            $frame_data["frame"] = $layout;
            $frame_data["lang"]  = $lang;

            $front_tpl = TplLoader::get_tpl_data('home.tpl.php', 'mvc/views/pages', $page_data);

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