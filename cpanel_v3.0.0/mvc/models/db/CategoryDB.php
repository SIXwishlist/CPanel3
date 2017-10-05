<?php
/*
 *
 */

/**
 * Description of CategoryDB
 *
 * @author Ahmad
 */
class CategoryDB extends DataDB {

    public static function add_category($category){

        $result = 0;

        try {

            $params = array( $category->title_ar, $category->title_en, $category->desc_ar, $category->desc_en, $category->content_ar, $category->content_en, $category->keys_ar, $category->keys_en, $category->icon, $category->image, $category->format, $category->menu, $category->options, $category->order, $category->active, $category->parent_id );
            
            $query = "INSERT INTO `categories` ( `title_ar`, `title_en`, `desc_ar`, `desc_en`, `content_ar`, `content_en`, `keys_ar`, `keys_en`, `icon`, `image`, `format`, `menu`, `options`, `order`, `active`, `parent_id` ) "
                . " VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? ) ";

            $result = QueryUtil::excute_update( $query, $params );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : add category', $e );//from php 5.3 no need to custum
        }

        return $result;
    }

    public static function update_category($category){

        $result = 0;

        try {

            $params = array( $category->title_ar, $category->title_en, $category->desc_ar, $category->desc_en, $category->content_ar, $category->content_en, $category->keys_ar, $category->keys_en, $category->icon, $category->image, $category->format, $category->menu, $category->options, $category->order, $category->active, $category->parent_id );

            $query = "UPDATE `categories` SET "
                ." `title_ar` = ?, `title_en` = ?, `desc_ar` = ?, `desc_en` = ?, `content_ar` = ?, `content_en` = ?, `keys_ar` = ?, `keys_en` = ?, `icon` = ?, `image` = ?, `format` = ?, `menu` = ?, `options` = ?, `order` = ?, `active` = ?, `parent_id` = ? "
                ." WHERE `category_id` = ".$category->category_id;

            $result = QueryUtil::excute_update( $query, $params );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : update category', $e );//from php 5.3 no need to custum
        }

        return $result;
    }

    public static function remove_category($category){

        $result = 0;

        try {

            $query = "DELETE FROM `categories` WHERE `category_id` = ".$category->category_id;

            $result = QueryUtil::excute_update( $query );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : remove category', $e );//from php 5.3 no need to custum
        }

        return $result;
    }


    public static function get_categories($parent_id = -1, $active = -1, $start = -1, $count = -1, $order_by = '`category_id`', $arrange = 'ASC'){

        $categories = null;

        try {

            $params = array();

            $query = "SELECT * FROM `categories` WHERE 1 ";

            if( $parent_id > -1 ){
                $params [] = $parent_id;
                $query .= " AND `parent_id` = ? ";
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

            $buffer = QueryUtil::excute_select( $query, $params );

            $categories = self::format_objects($buffer);

        } catch (Exception $e) {
             throw new CustomException( 'Error in : get categories', $e );//from php 5.3 no need to custum
        }

        return $categories;
    }

    public static function get_categories_count($parent_id = -1, $active = -1){

        $count = 0;

        try {

            $params = array();

            $query = "SELECT COUNT(*) AS `count` FROM `categories` WHERE 1 ";

            if( $parent_id > -1 ){
                $params [] = $parent_id;
                $query .= " AND `parent_id` = ? ";
            }
            if( $active > 0 ){
                $query .= " AND `active` = 1 ";
            }

            $buffer = QueryUtil::excute_select( $query, $params );

            if( count($buffer) > 0 ){
                $count = $buffer[0]["count"];                
            }

        } catch (Exception $e) {
             throw new CustomException( 'Error in : get categories count', $e );//from php 5.3 no need to custum
        }

        return $count;
    }


    public static function search_categories($word = '', $active = -1, $start = -1, $count = -1){

        $categories = null;

        try {

            $params = array( "%".$word."%", "%".$word."%" );

            $query = "SELECT * FROM `categories` WHERE 1 "
                ." AND ( `title_ar` LIKE ? OR `title_en` LIKE ?  ) ";

            if( $active > 0 ){
                $query .= " AND `active`= ".$active." ";
            }

            if( $start > -1 && $count > -1 ){
                $query .= " LIMIT ".$start.", ".$count."";
            }

            $buffer = QueryUtil::excute_select( $query, $params );

            $categories = self::format_objects($buffer);

        } catch (Exception $e) {
            throw new CustomException( 'Error in : search categories', $e );//from php 5.3 no need to custum
        }

        return $categories;
    }

    public static function search_categories_count($word = '', $active = -1){

        $count = 0;

        try {

            $params = array( "%".$word."%", "%".$word."%", "%".$word."%" );

            $query = "SELECT COUNT(*) AS `count` FROM `categories` WHERE 1 "
                ." AND ( `title_ar` LIKE ? OR `title_en` LIKE ? ) ";

            if( $active > 0 ){
                $query .= " AND `active`= 1 ";
            }

            $buffer = QueryUtil::excute_select( $query, $params );

            $count = $buffer[0]["count"];

        } catch (Exception $e) {
            throw new CustomException( 'Error in : search categories count', $e );//from php 5.3 no need to custum
        }

        return $count;
    }

    
    public static function get_sub_categories($category_id){

        $categories = null;

        try {

            $params = array( $category_id );
            
            $query = "CALL get_sub_categories( ? )" ;

            $buffer = QueryUtil::excute_select( $query, $params );

            $categories = self::format_objects($buffer);
            
            $categories = array_reverse($categories);

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get sub categories', $e );//from php 5.3 no need to custum
        }

        return $categories;
    }


    public static function get_category($category_id){

        $category = null;

        try {

            $query = "SELECT * FROM `categories` WHERE "
                ." `category_id` = ".$category_id;

            $buffer = QueryUtil::excute_select( $query );

            $categories = self::format_objects($buffer);

            if( count($categories) > 0 ){
                $category = $categories[0];
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get category info', $e );//from php 5.3 no need to custum
        }

        return $category;
    }


    public static function format_objects($buffer){

        $categories = array();

        try {

            for($i=0; $i<count($buffer); $i++){

                $category = (object) $buffer[$i];

                $category->child_type = CHILD_TYPE_SECTION;

                $category = CategoryChildDB::get_formated_category_child($category);

                $categories[] = $category;
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : format category objects', $e );//from php 5.3 no need to custum
        }

        return $categories;
    }
    
    

}
?>
