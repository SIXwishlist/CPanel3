<?php
/*
 *
 */

/**
 * Description of CartAjax
 *
 * @author Ahmad
 */

class CartAjax extends FrontAjax {

    public static $date_format  = 'Y-m-d H:i:s';

    public static function add_item(){

        $output_array = array();
        
        try {

            $request = HttpRequest::get_instance();
            $session = HttpSession::get_instance();

            $pid      = $request->get_int_parameter("pid");
            $quantity = $request->get_int_parameter("quantity");
            //$price    = $request->get_double_parameter("price");
            
            $product = CategoryTreeJSON::get_product($pid);
            
            $discount = $product->discount;
            $price    = $product->price;

            if( !empty($product) ){
                $status = CartSession::add_item($pid, $quantity, $price, $discount);
            }else{
                $status = PRODUCT_NOT_EXIST;
            }

            $output_array["status"]  = intval($status);

        } catch (Exception $e) {
            $output_array["status"] = SERVER_ERROR;
            Logger::log( $e->getMessage(), ERROR );
        }

        return $output_array;
    }

    public static function update_item(){

        $output_array = array();
        
        try {

            $request = HttpRequest::get_instance();
            $session = HttpSession::get_instance();

            $pid      = $request->get_int_parameter("pid");
            $quantity = $request->get_int_parameter("quantity");
            //$price    = $request->get_double_parameter("price");
            
            $product = CategoryTreeJSON::get_product($pid);
            
            $discount = $product->discount;
            $price    = $product->price;
            
            if( !empty($product) ){
                $status = CartSession::update_item($pid, $quantity, $price, $discount);
            }else{
                $status = PRODUCT_NOT_EXIST;
            }
            
            $output_array["status"]  = intval($status);

        } catch (Exception $e) {
            $output_array["status"] = SERVER_ERROR;
            Logger::log( $e->getMessage(), ERROR );
        }

        return $output_array;
    }

    public static function remove_item(){

        $output_array = array();
        
        try {

            $request = HttpRequest::get_instance();
            $session = HttpSession::get_instance();

            $pid     = $request->get_int_parameter("pid");
            
            $status  = CartSession::remove_item($pid);
            
            $output_array["status"]  = intval($status);

        } catch (Exception $e) {
            $output_array["status"] = SERVER_ERROR;
            Logger::log( $e->getMessage(), ERROR );
        }

        return $output_array;
    }

    public static function empty_cart(){

        $output_array = array();
        
        try {

            $request = HttpRequest::get_instance();
            $session = HttpSession::get_instance();
            
            $status  = CartSession::empty_cart();
            
            $output_array["status"]  = intval($status);

        } catch (Exception $e) {
            $output_array["status"] = SERVER_ERROR;
            Logger::log( $e->getMessage(), ERROR );
        }

        return $output_array;
    }

    public static function get_cart(){

        $output_array = array();
        
        try {

            $session = HttpSession::get_instance();

            $cart_items       = CartSession::get_cart_items();
            $total_items      = CartSession::get_total_items();
            $total_price      = CartSession::get_total_price();
            $total_sale_price = CartSession::get_total_sale_price();

            $output_array["cart_items"]  = $cart_items;
            $output_array["total_items"] = $total_items;
            $output_array["total_price"] = $total_price;
            $output_array["total_sale"]  = $total_sale_price;
            
            $status = SUCCESS;

            $output_array["status"]  = intval($status);

        } catch (Exception $e) {
            $output_array["status"] = SERVER_ERROR;
            Logger::log( $e->getMessage(), ERROR );
        }

        return $output_array;
    }

}

?>