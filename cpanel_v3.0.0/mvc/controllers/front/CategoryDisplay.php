<?php
/*
 *
 */

/**
 * Description of CategoryDisplay
 *
 * @author Ahmad
 */

class CategoryDisplay {

    public static function get_category_info(){

        $output_string = '';

        try {

            $request = HttpRequest::get_instance();

            $lang   = Dictionary::get_language();

            $category_id = $request->get_int_parameter("category_id");
            $category_id = ( $category_id > 0 ) ? $category_id : 0;

            if( CACHING_ENABLED ){
                $cache  = Cache::create_instance("category.$category_id.cache.json");
                $output = $cache->get_data($lang);
                if( !empty($output) ){ return $output; }
            }

            QueryUtil::connect();

            $frame_data = FrontUtil::get_default_data_array();

            $layout     = PageFrame::get_front_layout();


            $category_path = CategoryTreeJSON::get_category_path($category_id);

            $sub_category_menu = new stdClass();
            
            if( $category_id > 0 ){

                $category = CategoryDB::get_category($category_id);

                $parent_category = CategoryTreeJSON::get_category($category_id);
                $childs          = $parent_category->childs;

                $sub_category_menu->title  = Dictionary::get_text_by_lang($category, "title");
                $sub_category_menu->href   = UrlUtil::get_category_href($category);
                $sub_category_menu->childs = $childs;

            }else{

                $childs    = CategoryTreeJSON::get_tree();
                
                $sub_category_menu->title  = Dictionary::get_text("Categories_lbl");
                $sub_category_menu->href   = UrlUtil::get_category_root_href();
                $sub_category_menu->childs = $childs;
            }


            $cat_ids  = self::get_category_sub_ids($category_id);
                        
            $products = ProductDB::get_products_in($cat_ids, array(), -1, -1, 'RAND()', '');

            self::extract_product_options($products);
            
            $result_count = count($products);

            
            if( $category != null ){
                $layout->title  = Dictionary::get_text_by_lang($category, "title") . ' | ' . $layout->title_postfix;
                $layout->tags[] = array( "name" => "keywords"   , "content" => Dictionary::get_text_by_lang($category, "keys") );
                $layout->tags[] = array( "name" => "description", "content" => Dictionary::get_text_by_lang($category, "desc") );
            }else{
                $layout->title  = Dictionary::get_text('Categories_lbl') . ' | ' . $layout->title_postfix;
                $layout->tags[] = array( "name" => "keywords"   , "content" => Dictionary::get_text('Categories_lbl') );
                $layout->tags[] = array( "name" => "description", "content" => Dictionary::get_text('Categories_lbl') );
            }
            
            
            $page_data = array(
                "category_path" => $category_path,
                "category"      => $category,
                "products"      => $products,
                "result_count"  => $result_count
            );

            $front_tpl = TplLoader::get_tpl_data('category_info.tpl.php', 'mvc/views/pages', $page_data);


            $frame_data["wide_main"]         = '';
            $frame_data["sub_category_menu"] = $sub_category_menu;

            if( $category_id > 0 ){
                $category->child_type = 1;
                $main_data["lang_ar"] = UrlUtil::get_category_child_href($category, "ar");
                $main_data["lang_en"] = UrlUtil::get_category_child_href($category, "en");
            }else{
                $main_data["lang_ar"] = UrlUtil::get_category_root_href('ar');
                $main_data["lang_en"] = UrlUtil::get_category_root_href('en');
            }

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
    
    public static function get_category_sub_ids($category_id){
        
        $cat_ids = array();

        try{

            $cat_ids[]   = $category_id;

            $cat_array   = CategoryDB::get_sub_categories($category_id);

            foreach ($cat_array as $cat_object) {
                $cat_ids[] = $cat_object->category_id;
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get category sub ids', $e );
        }
        
        return $cat_ids;
    }
    
    
    public static function extract_product_options(&$products){
        
        try {

            foreach ($products as $product) {
                
                $options = $product->options;
                
                $product->featured = ( ($options & PRODUCT_FEATURED) > 0 ) ? 1 : 0;
                $product->offer    = ( ($options & PRODUCT_OFFER)    > 0 ) ? 1 : 0;
                $product->sale     = ( ($options & PRODUCT_SALE)     > 0 ) ? 1 : 0;
                $product->recent   = ( ($options & PRODUCT_RECENT)   > 0 ) ? 1 : 0;

            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : extract product options', $e );//from php 5.3 no need to custum
        }

    }
    
}

?>
