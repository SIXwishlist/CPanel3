<?php
/*
 *
 */

/**
 * Description of CartDisplay
 *
 * @author Ahmad
 */

class CartDisplay {

    public static function get_cart(){

        $output_string = '';

        try {

            $request = HttpRequest::get_instance();

            $lang   = Dictionary::get_language();


            QueryUtil::connect();

            $frame_data = FrontUtil::get_default_data_array();

            $layout     = PageFrame::get_front_layout();

            

            $layout->title  = Dictionary::get_text('Cart_lbl') . ' | ' . $layout->title_postfix;
            $layout->tags[] = array( "name" => "keywords"   , "content" => Dictionary::get_text('Cart_lbl') );
            $layout->tags[] = array( "name" => "description", "content" => Dictionary::get_text('Cart_lbl') );

            
            $cart_items  = CartSession::get_cart_items();
            $total_items = CartSession::get_total_items();
            $total_price = CartSession::get_total_price();

            $pids        = self::pick_product_ids($cart_items);

            $product_params = array(
                "pids" => $pids
            );

            $products = ProductDB::search_products($product_params, -1, -1, '`product_id`', 'ASC');

            $cart_products = self::combine_products_with_cart($products, $cart_items);


            $page_data = array(
                "cart_items"    => $cart_items,
                "total_items"   => $total_items,
                "total_price"   => $total_price,
                "cart_products" => $cart_products
            );

            $front_tpl = TplLoader::get_tpl_data('cart.tpl.php', 'mvc/views/pages', $page_data);

            $main_data["lang_ar"] = UrlUtil::get_cart_href("ar");
            $main_data["lang_en"] = UrlUtil::get_cart_href("en");

            $frame_data["frame"] = $layout;
            $frame_data["lang"]  = $lang;

            $frame_data["page_content"] = $front_tpl;

            $output_string = TplLoader::get_tpl_data('frame.tpl.php', 'mvc/views/front', $frame_data);

            QueryUtil::close();

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

    private static function pick_product_ids($cart_items) {
        
        $pids = array();
        
        try{
            
            foreach ($cart_items as $item) {
                $pids[] = $item["pid"];
            }
            
        } catch (Exception $e) {
            throw new CustomException( 'Error in : pick product ids', $e );
        }

        return $pids;
    }

    public static function combine_products_with_cart($products, $cart_items) {
        
        $cart_products = array();
        
        try{
            
            foreach ($products as $product) {

                foreach ($cart_items as $item) {

                    if( $product->product_id == $item["pid"] ){

                        $product->quantity = $item["quantity"];
                        $product->price    = $item["price"];
                        
                    }
                }
                
                $cart_products[] = $product;

            }
            
        } catch (Exception $e) {
            throw new CustomException( 'Error in : pick product ids', $e );
        }

        return $cart_products;
    }

}

?>
