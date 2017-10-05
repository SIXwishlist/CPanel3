<?php
/*
 *
 */

/**
 * Description of ProductDisplay
 *
 * @author Ahmad
 */

class ProductDisplay {

    public static function get_product_info(){

        $output_string = '';

        try {

            $request = HttpRequest::get_instance();

            $lang   = Dictionary::get_language();

            $product_id = $request->get_int_parameter("product_id");
            $product_id = ( $product_id > 0 ) ? $product_id : 0;

            if( CACHING_ENABLED ){
                $cache  = Cache::create_instance("product.$product_id.cache.json");
                $output = $cache->get_data($lang);
                if( !empty($output) ){ return $output; }
            }

            QueryUtil::connect();

            $frame_data = FrontUtil::get_default_data_array();

            $layout     = PageFrame::get_front_layout();

            $active = 1;
            
            $product = ProductDB::get_product($product_id);
            $shots   = ShotDB::get_shots($product_id, $active, -1, -1);

            $shots_count = count($shots);
            
            
            $product_path   = CategoryTreeJSON::get_category_path($product->parent_id);
            $product_path[] = $product;
            
            
            $other_products = ProductDB::get_similar_products($product->parent_id, $product->product_id);

            if( isset($product) ){
                $layout->title  = Dictionary::get_text_by_lang($product, "title") . ' | ' . $layout->title_postfix;
                $layout->tags[] = array( "name" => "keywords"   , "content" => Dictionary::get_text_by_lang($product, "keys") );
                $layout->tags[] = array( "name" => "description", "content" => Dictionary::get_text_by_lang($product, "desc") );
            }

            $page_data = array(
                "product_path"   => $product_path,
                "product"        => $product,
                "other_products" => $other_products,
                "shots"          => $shots,
                "shots_count"    => $shots_count
            );

            $front_tpl = TplLoader::get_tpl_data('product_info.tpl.php', 'mvc/views/pages', $page_data);


            $product->child_type = 2;
            
            $main_data["lang_ar"]= UrlUtil::get_category_child_href($product, "ar");
            $main_data["lang_en"]= UrlUtil::get_category_child_href($product, "en");

            $frame_data["frame"] = $layout;
            $frame_data["lang"]  = $lang;

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
