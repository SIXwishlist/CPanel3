<?php
/*
 *
 */

/**
 * Description of ProductDB
 *
 * @author Ahmad
 */
class ProductDB extends DataDB {

    public static function add_product($product){

        $result = 0;

        try {
            
            $params = array( $product->title_ar, $product->title_en, $product->desc_ar, $product->desc_en, $product->content_ar, $product->content_en, $product->keys_ar, $product->keys_en, $product->icon, $product->image, $product->format, $product->menu, $product->options, $product->price, $product->discount, $product->available, $product->order, $product->active, $product->parent_id );

            $query = "INSERT INTO `products` ( `title_ar`, `title_en`, `desc_ar`, `desc_en`, `content_ar`, `content_en`, `keys_ar`, `keys_en`, `icon`, `image`, `format`, `menu`, `options`, `price`, `discount`, `available`, `order`, `active`, `parent_id` ) " 
                . " VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? ) ";
            
            $result = QueryUtil::excute_update( $query, $params );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : add product', $e );//from php 5.3 no need to custum
        }
        
        return $result;
    }

    public static function update_product($product){

        $result = 0;

        try {

            $params = array( $product->title_ar, $product->title_en, $product->desc_ar, $product->desc_en, $product->content_ar, $product->content_en, $product->keys_ar, $product->keys_en, $product->icon, $product->image, $product->format, $product->menu, $product->options, $product->price, $product->discount, $product->available, $product->order, $product->active, $product->parent_id );

            $query = "UPDATE `products` SET "
                . " `title_ar` = ?, `title_en` = ?, `desc_ar` = ?, `desc_en` = ?, `content_ar` = ?, `content_en` = ?, `keys_ar` = ?, `keys_en` = ?, `icon` = ?, `image` = ?, `format` = ?, `menu` = ?, `options` = ?, `price` = ?, `discount` = ?, `available` = ?, `order` = ?, `active` = ?, `parent_id` = ? "
                ." WHERE `product_id` = ".$product->product_id;

            $result = QueryUtil::excute_update( $query, $params );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : update product', $e );//from php 5.3 no need to custum
        }
        return $result;
    }

    public static function remove_product($product){

        $result = 0;

        try {

            $query = "DELETE FROM `products` WHERE `product_id` = ".$product->product_id;

            $result = QueryUtil::excute_update( $query );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : remove product', $e );//from php 5.3 no need to custum
        }

        return $result;
    }
    

    public static function get_products($parent_id = -1, $start = -1, $count = -1, $order_by = '`product_id`', $arrange = 'ASC'){

        $products = null;

        try {

            $params = array();

            $query = "SELECT * FROM `products` WHERE 1 ";

            if( $parent_id > 0 ){
                $query .= " AND `parent_id` = ? ";
                $params[] = $parent_id;
            }
            
            if( $order_by != '' ){
                $query .= " ORDER BY ".$order_by." ".$arrange;
            }

            if( $start > -1 && $count > -1 ){
                $query .= " LIMIT ".$start.", ".$count."";
            }


            $buffer = QueryUtil::excute_select( $query, $params );

            $products = self::format_product_objects($buffer);

        } catch (Exception $e) {
             throw new CustomException( 'Error in : get products', $e );//from php 5.3 no need to custum
        }

        return $products;
    }

    public static function get_products_count($parent_id = -1){

        $count = 0;

        try {

            $params = array();

            $query = "SELECT COUNT(*) AS `count` FROM `products` WHERE 1 ";

            if( $parent_id > 0 ){
                $query .= " AND `parent_id` = ? ";
                $params[] = $parent_id;
            }
            

            $buffer = QueryUtil::excute_select( $query, $params );

            if( count($buffer) > 0 ) {
                $count = $buffer[0]["count"];
            }

        } catch (Exception $e) {
             throw new CustomException( 'Error in : get products count', $e );//from php 5.3 no need to custum
        }

        return $count;
    }

    
    public static function get_products_in($cat_ids = array(), $options = array(), $start = -1, $count = -1, $order_by = '`product_id`', $arrange = 'ASC'){

        $products = null;

        try {
            
            extract( $options );

            $all_empty = true;
            
            $params = array();

            $query = "SELECT * FROM `products` WHERE 1 ";

            if( !empty($cat_ids) ){
                $ques_marks = implode(',', array_fill(0, count($cat_ids), '?'));
                $params     = array_merge($params, $cat_ids);
                $query     .= " AND `parent_id` IN ( ".$ques_marks." ) ";
                $all_empty  = false;
            }

            if( $featured > 0 ){
                $params [] = PRODUCT_FEATURED;
                $query .= " AND ( `options` & ? ) > 0  ";
                $all_empty = false;
            }

            if( $offer > 0 ){
                $params [] = PRODUCT_OFFER;
                $query .= " AND ( `options` & ? ) > 0  ";
                $all_empty = false;
            }

            if( $sale > 0 ){
                $params [] = PRODUCT_SALE;
                $query .= " AND ( `options` & ? ) > 0  ";
                $all_empty = false;
            }

            if( $recent > 0 ){
                $params [] = PRODUCT_RECENT;
                $query .= " AND ( `options` & ? ) > 0  ";
                $all_empty = false;
            }

            if( $active > 0 ){
                $params [] = $active;
                $query .= " AND `active` = ? ";
                $all_empty = false;
            }
            
            if( $all_empty ){                
                $query .= " AND 0 ";
            }            
            
            if( $order_by != '' ){
                $query .= " ORDER BY ".$order_by." ".$arrange;
            }

            if( $start > -1 && $count > -1 ){
                $query .= " LIMIT ".$start.", ".$count."";
            }
            
            $buffer = QueryUtil::excute_select( $query, $params );

            $products = self::format_product_objects($buffer);

        } catch (Exception $e) {
             throw new CustomException( 'Error in : get products', $e );//from php 5.3 no need to custum
        }

        return $products;
    }

    public static function get_similar_products($parent_id = -1, $product_id = -1, $options = -1, $active = -1, $start = -1, $count = -1, $order_by = '`product_id`', $arrange = 'ASC'){

        $products = null;

        try {

            $params = array( $product_id, $parent_id );

            $query = "SELECT * FROM `products` WHERE 1 "
                    . " AND `product_id` <> ?  AND `parent_id` = ? ";
            
            if( $options > -1 ){
                $params [] = $options;
                $query .= " AND `options` = ? ";
            }

            if( $active > 0 ){
                $query .= " AND `active` = 1 ";
            }

            if( $order_by != '' ){
                $query .= " ORDER BY ".$order_by." ".$arrange;
            }

            if( $start > -1 && $count > -1 ){
                $query .= " LIMIT ".$start.", ".$count."";
            }

            $buffer = null;
            $buffer = QueryUtil::excute_select( $query, $params );

            $products = self::format_product_objects($buffer);

        } catch (Exception $e) {
             throw new CustomException( 'Error in : get products', $e );//from php 5.3 no need to custum
        }

        return $products;
    }


    public static function search_products($product_params, $start = -1, $count = -1, $order_by = '`product_id`', $arrange = 'ASC'){

        $products = array();

        try {
            
            extract( $product_params );

            $all_empty = true;
            
            $params = array();            

            $query = "SELECT * FROM `products` "
                    . " WHERE 1 ";

            if( !empty($product_id) ){
                $params [] = $product_id;
                $query    .= " AND `product_id` = ? ";
                $all_empty = false;
            }

            if( !empty($pids) ){
                $ques_marks = implode(',', array_fill(0, count($pids), '?'));
                $params     = array_merge($params, $pids);
                $query     .= " AND `product_id` IN ( ".$ques_marks." ) ";
                $all_empty  = false;
            }
            
            if( !empty($title) ){
                $params [] = "%".$title."%";
                $params [] = "%".$title."%";
                $query    .= " AND `title_ar` LIKE ? OR `title_en` LIKE ? ";
                $all_empty = false;
            }
            
            if( !empty($options) ){
                $params [] = $options;
                $query    .= " AND `options` = ? ";
                $all_empty = false;
            }


            if( $featured > 0 ){
                $params [] = PRODUCT_FEATURED;
                $query .= " AND ( `options` & ? ) > 0  ";
                $all_empty = false;
            }

            if( $offer > 0 ){
                $params [] = PRODUCT_OFFER;
                $query .= " AND ( `options` & ? ) > 0  ";
                $all_empty = false;
            }

            if( $sale > 0 ){
                $params [] = PRODUCT_SALE;
                $query .= " AND ( `options` & ? ) > 0  ";
                $all_empty = false;
            }

            if( $recent > 0 ){
                $params [] = PRODUCT_RECENT;
                $query .= " AND ( `options` & ? ) > 0  ";
                $all_empty = false;
            }

            
            if( !empty($status) ){
                $params [] = $status;
                $query    .= " AND `status` = ? ";
                $all_empty = false;
            }
            
            if( !empty($parent_id) ){
                $params [] = $parent_id;
                $query    .= " AND `parent_id` = ? ";
                $all_empty = false;
            }
            
            if( $all_empty && !$all_results ){
                $query .= " AND 0 ";
            }            
            
            $query .= " GROUP BY `product_id` ";
            
            if( $order_by != '' ){
                $query .= " ORDER BY ".$order_by." ".$arrange;
            }

            if( $start > -1 && $count > -1 ){
                $query .= " LIMIT ".$start.", ".$count."";
            }
            
            $buffer = QueryUtil::excute_select( $query, $params );

            $products = self::format_objects($buffer);

        } catch (Exception $e) {
            throw new CustomException( 'Error in : search products', $e );//from php 5.3 no need to custum
        }

        return $products;
    }

    public static function search_products_count( $product_params ){

        $count = -1;

        try {
            
            extract( $product_params );
            
            $all_empty = true;
            
            $params = array();
            
            $query = "SELECT COUNT(*) AS `count` FROM `products` "
                  . " WHERE 1 ";
            
            if( !empty($product_id) ){
                $params [] = $product_id;
                $query    .= " AND `product_id` = ? ";
                $all_empty = false;
            }

            if( !empty($title) ){
                $params [] = "%".$title."%";
                $query    .= " AND `title` LIKE ? ";
                $all_empty = false;
            }
            
            if( !empty($options) ){
                $params [] = $options;
                $query    .= " AND `options` = ? ";
                $all_empty = false;
            }
            
            if( $featured > 0 ){
                $params [] = PRODUCT_FEATURED;
                $query .= " AND ( `options` & ? ) > 0  ";
                $all_empty = false;
            }

            if( $offer > 0 ){
                $params [] = PRODUCT_OFFER;
                $query .= " AND ( `options` & ? ) > 0  ";
                $all_empty = false;
            }

            if( $sale > 0 ){
                $params [] = PRODUCT_SALE;
                $query .= " AND ( `options` & ? ) > 0  ";
                $all_empty = false;
            }

            if( $recent > 0 ){
                $params [] = PRODUCT_RECENT;
                $query .= " AND ( `options` & ? ) > 0  ";
                $all_empty = false;
            }
            
            if( !empty($status) ){
                $params [] = $status;
                $query    .= " AND `status` = ? ";
                $all_empty = false;
            }
            
            if( !empty($parent_id) ){
                $params [] = $parent_id;
                $query    .= " AND `parent_id` = ? ";
                $all_empty = false;
            }
            
            if( $all_empty && !$all_results ){                
                $query .= " AND 0 ";
            }


            $buffer = QueryUtil::excute_select( $query, $params );

            if( count($buffer) > 0 ){
                $count = $buffer[0]["count"];
            }
            
        } catch (Exception $e) {
            throw new CustomException( 'Error in : get product search result count', $e );//from php 5.3 no need to custum
        }

        return $count;
    }

    
    public static function add_products_list($product_records){

        $result = 0;

        try {

            $params      = array();
            $ques_marks  = array();
            
            $query = "INSERT INTO `products` ( `title_ar`, `title_en`, `desc_ar`, `desc_en`, `content_ar`, `content_en`, `keys_ar`, `keys_en`, `icon`, `image`, `format`, `menu`, `options`, `price`, `discount`, `installment1`, `installment2`, `available`, `order`, `active`, `parent_id` ) VALUES ";
            
            foreach ($product_records as $product) {
                
                $params [] = $product->title_ar;
                $params [] = $product->title_en;
                $params [] = $product->content_ar;
                $params [] = $product->content_en;
                $params [] = $product->keys_ar;
                $params [] = $product->keys_en;
                $params [] = $product->icon;
                $params [] = $product->image;
                $params [] = $product->format;
                $params [] = $product->menu;
                $params [] = $product->options;
                $params [] = $product->price;
                $params [] = $product->discount;
                $params [] = $product->available;
                $params [] = $product->status;
                $params [] = $product->order;
                $params [] = $product->active;
                $params [] = $product->parent_id;
                
                $ques_marks [] = " ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? ) ";
                
            }
            
            $query  .= implode(", ", $ques_marks);

            $result  = QueryUtil::excute_update( $query, $params );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : add products list', $e );//from php 5.3 no need to custum
        }
        
        return $result;
    }



    public static function get_product($product_id){

        $product = null;

        try {

            $query = "SELECT * FROM `products` WHERE "
                ." `product_id` = ".$product_id;


            $buffer = QueryUtil::excute_select( $query );

            $products = self::format_product_objects($buffer);

            $product = $products[0];

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get product info', $e );//from php 5.3 no need to custum
        }

        return $product;
    }


    /**
     * Returns array of objects that from format query result set
     * output into array of objects
     * @param buffer 2D array that is result of a query
     * @return objects array
     */
    public static function format_product_objects($buffer){

        $products = array();

        try {

            for($i=0; $i<count($buffer); $i++){

                $product = (object) $buffer[$i];

                $products[] = $product;
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : format product objects', $e );//from php 5.3 no need to custum
        }

        return $products;
    }

}
?>
