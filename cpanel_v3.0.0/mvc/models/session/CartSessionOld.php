<?php

/*
 *
 */

//include_once BASE_DIR.'/mvc/libraries/http/HttpRequest.php';
//include_once BASE_DIR.'/mvc/libraries/http/HttpSession.php';

//include_once BASE_DIR.'/mvc/libraries/OutputCollector.php';
//include_once BASE_DIR.'/mvc/libraries/FileUtil.php';
//include_once BASE_DIR.'/mvc/libraries/TextUtil.php';

/**
 * Description of CartSession
 *
 * @author Ahmad
 */

class CartSessionOld {

    public static function initCart(){
        $_SESSION["cart_items"] = array();
    }
    
    public static function addToCart($pid, $quantity, $price){

        $status = 0;

        try {

            if ( $pid > 0 ) {
            
                $productObject = array();

                $productObject["quantity"] = $quantity;
                $productObject["price"]    = $price;

                $_SESSION["cart_items"][$pid] = $productObject;

                $status = $pid;
            
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error : \n' .  $e->getMessage() . "\n" );
        }

        return $status;
    }
    
    public static function updateItem($pid, $quantity, $price){

        $status = 0;

        try {

            $productObject = array();

            unset( $_SESSION["cart_items"][$pid] );

            $productObject["quantity"] = $quantity;
            $productObject["price"]    = $price;

            $_SESSION["cart_items"][$pid] = $productObject;

            $status = $pid;

        } catch (Exception $e) {
            throw new CustomException( 'Error : \n' .  $e->getMessage() . "\n" );
        }

        return $status;
    }

    public static function removeItem($pid){

        $status = 0;

        try {

            unset( $_SESSION["cart_items"][$pid] );

            $status = $pid;

        } catch (Exception $e) {
            throw new CustomException( 'Error : \n' .  $e->getMessage() . "\n" );
        }

        return $status;
    }
    
    public static function emptyCart(){

        $status = 0;

        try {

            unset( $_SESSION["cart_items"] );

            $_SESSION["cart_items"] = array();

            $status = 1;

        } catch (Exception $e) {
            throw new CustomException( 'Error : \n' .  $e->getMessage() . "\n" );
        }

        return $status;
    }
    
    public static function getItem($pid){

        $item = null;

        $items = $_SESSION["cart_items"];

        if (  is_array($items)  ) {
            $item = $items[$pid];
        }

        return $item;
    }

    public static function cartItems(){

        $items = array();

        $items = $_SESSION["cart_items"];
        
        //foreach ( $items as $pid => $item ){}
        
        return $items;
    }

        
    public static function getTotalPrice(){

        $totalPrice = 0;
    
        $items = $_SESSION["cart_items"];

        if ( is_array($items) ) {
            foreach ( $items as $item ) {
                $totalPrice += $item["price"] * $item["quantity"];
            }
        }

        return $totalPrice;
    }

    public static function getTotalItems(){
        
        $totalItems = 0;

        $items = $_SESSION["cart_items"];

        if ( is_array($items) ) {
            foreach ( $items as $item ) {
                $totalItems += $item["quantity"];
            }
        }

        return $totalItems;
    }
    

}

?>