<?php
/*
 *
 */

/**
 * Description of LinkDB
 *
 * @author Ahmad
 */
class LinkDB extends DataDB {

    public static function add_link($link){

        $result = 0;

        try {

            $params = array( $link->title_ar, $link->title_en, $link->desc_ar, $link->desc_en, $link->icon, $link->url_ar, $link->url_en, $link->menu, $link->options, $link->order, $link->active, $link->parent_id );
            
            $query = "INSERT INTO `links` ( `title_ar`, `title_en`, `desc_ar`, `desc_en`, `icon`, `url_ar`, `url_en`, `menu`, `options`, `order`, `active`, `parent_id` ) "
                . " VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? ) ";

            $result = QueryUtil::excute_update( $query, $params );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : add link', $e );//from php 5.3 no need to custum
        }

        return $result;
    }

    public static function update_link($link){

        $result = 0;

        try {

            $params = array( $link->title_ar, $link->title_en, $link->desc_ar, $link->desc_en, $link->icon, $link->url_ar, $link->url_en, $link->menu, $link->options, $link->order, $link->active, $link->parent_id );

            $query = "UPDATE `links` SET "
                ." `title_ar` = ?, `title_en` = ?, `desc_ar` = ?, `desc_en` = ?, `icon` = ?, `url_ar` = ?, `url_en` = ?, `menu` = ?, `options` = ?, `order` = ?, `active` = ?, `parent_id` = ? "
                ." WHERE `link_id` = ".$link->link_id;

            $result = QueryUtil::excute_update( $query, $params );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : update link', $e );//from php 5.3 no need to custum
        }
        return $result;
    }

    public static function remove_link($link){

        $result = 0;

        try {

            $query = "DELETE FROM `links` WHERE `link_id` = ".$link->link_id;

            $result = QueryUtil::excute_update( $query );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : remove link', $e );//from php 5.3 no need to custum
        }

        return $result;
    }



    public static function get_links($parent_id = -1, $active = -1, $start = -1, $count = -1, $order_by = '`link_id`', $arrange = 'ASC'){

        $links = null;

        try {

            $params = array();

            $query = "SELECT * FROM `links` WHERE 1 ";

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

            $links = self::format_objects($buffer);

        } catch (Exception $e) {
             throw new CustomException( 'Error in : get links', $e );//from php 5.3 no need to custum
        }

        return $links;
    }

    public static function get_links_count($parent_id = -1, $active = -1){

        $count = 0;

        try {

            $params = array();

            $query = "SELECT COUNT(*) AS `count` FROM `links` WHERE 1 ";

            if( $parent_id > -1 ){
                $params [] = $parent_id;
                $query .= " AND `parent_id` = ? ";
            }
            if( $active > 0 ){
                $query .= " AND `active` = 1 ";
            }

            
            $buffer = QueryUtil::excute_select( $query, $params );

            $count = $buffer[0]["count"];

        } catch (Exception $e) {
             throw new CustomException( 'Error in : get links count', $e );//from php 5.3 no need to custum
        }

        return $count;
    }


    public static function search_links($word = '', $active = -1, $start = -1, $count = -1){

        $links = null;

        try {

            $params = array( "%".$word."%", "%".$word."%" );

            $query = "SELECT * FROM `links` WHERE 1 "
                ." AND ( `title_ar` LIKE ? OR `title_en` LIKE ? ) ";

            if( $active > 0 ){
                $query .= " AND `active`= ".$active." ";
            }

            if( $start > -1 && $count > -1 ){
                $query .= " LIMIT ".$start.", ".$count."";
            }

            
            $buffer = QueryUtil::excute_select( $query, $params );

            $links = self::format_objects($buffer);

        } catch (Exception $e) {
            throw new CustomException( 'Error in : search links', $e );//from php 5.3 no need to custum
        }

        return $links;
    }

    public static function search_links_count($word = '', $active = -1){

        $count = 0;

        try {

            $params = array( "%".$word."%", "%".$word."%" );

            $query = "SELECT COUNT(*) AS `count` FROM `links` WHERE 1 "
                ." AND ( `title_ar` LIKE ? OR `title_en` LIKE ? ) ";

            if( $active > 0 ){
                $query .= " AND `active`= 1 ";
            }

            
            $buffer = QueryUtil::excute_select( $query, $params );

            $count = $buffer[0]["count"];

        } catch (Exception $e) {
            throw new CustomException( 'Error in : search links count', $e );//from php 5.3 no need to custum
        }

        return $count;
    }


    public static function get_link($link_id){

        $link = null;

        try {

            $query = "SELECT * FROM `links` WHERE "
                ." `link_id` = ".$link_id;

            
            $buffer = QueryUtil::excute_select( $query );

            $links = self::format_objects($buffer);

            $link = $links[0];

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get link info', $e );//from php 5.3 no need to custum
        }

        return $link;
    }


    /**
     * Returns array of objects that from format query result set
     * output into array of objects
     * @param buffer 2D array that is result of a query
     * @return objects array
     */
    public static function format_objects($buffer){

        $links = array();

        try {

            for($i=0; $i<count($buffer); $i++){

                $link = (object) $buffer[$i];

                $link->child_type = CHILD_TYPE_EMBED;

                $link = SectionChildDB::get_formated_section_child($link);
                
                $links[] = $link;
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : format link objects', $e );//from php 5.3 no need to custum
        }

        return $links;
    }

}
?>
