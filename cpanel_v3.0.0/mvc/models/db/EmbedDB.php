<?php
/*
 *
 */

/**
 * Description of EmbedDB
 *
 * @author Ahmad
 */
class EmbedDB extends DataDB {

    public static function add_embed($embed){

        $result = 0;

        try {

            $params = array( $embed->title_ar, $embed->title_en, $embed->desc_ar, $embed->desc_en, $embed->icon, $embed->file, $embed->type, $embed->order, $embed->active, $embed->parent_id, $embed->parent_type );
            
            $query = "INSERT INTO `embeds` ( `title_ar`, `title_en`, `desc_ar`, `desc_en`, `icon`, `file`, `type`, `order`, `active`, `parent_id`, `parent_type` ) "
                . " VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? ) ";

            $result = QueryUtil::excute_update( $query, $params );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : add embed', $e );//from php 5.3 no need to custum
        }
        return $result;
    }

    public static function update_embed($embed){

        $result = 0;

        try {

            $params = array( $embed->title_ar, $embed->title_en, $embed->desc_ar, $embed->desc_en, $embed->icon, $embed->file, $embed->type, $embed->order, $embed->active, $embed->parent_id, $embed->parent_type );

            $query = "UPDATE `embeds` SET "
                ." `title_ar` = ?, `title_en` = ?, `desc_ar` = ?, `desc_en` = ?, `icon` = ?, `file` = ?, `type` = ?, `order` = ?, `active` = ?, `parent_id` = ?, `parent_type` = ? "
                ." WHERE `embed_id` = ".$embed->embed_id;

            $result = QueryUtil::excute_update( $query, $params );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : update embed', $e );//from php 5.3 no need to custum
        }
        return $result;
    }

    public static function remove_embed($embed){

        $result = 0;

        try {

            $query = "DELETE FROM `embeds` WHERE `embed_id` = ".$embed->embed_id;

            $result = QueryUtil::excute_update( $query );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : remove embed', $e );//from php 5.3 no need to custum
        }

        return $result;
    }



    public static function get_embeds($parent_id = -1, $active = -1, $start = -1, $count = -1, $order_by = '`embed_id`', $arrange = 'ASC'){

        $embeds = null;

        try {

            $params = array();

            $query = "SELECT * FROM `embeds` WHERE 1 ";

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

            $embeds = self::format_objects($buffer);

        } catch (Exception $e) {
             throw new CustomException( 'Error in : get embeds', $e );//from php 5.3 no need to custum
        }

        return $embeds;
    }

    public static function get_embeds_count($parent_id = -1, $active = -1){

        $count = 0;

        try {

            $params = array();

            $query = "SELECT COUNT(*) AS `count` FROM `embeds` WHERE 1 ";

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
             throw new CustomException( 'Error in : get embeds count', $e );//from php 5.3 no need to custum
        }

        return $count;
    }


    public static function search_embeds($word = '', $active = -1, $start = -1, $count = -1){

        $embeds = null;

        try {

            $params = array( "%".$word."%", "%".$word."%" );

            $query = "SELECT * FROM `embeds` WHERE 1 "
                ." AND ( `title_ar` LIKE ? OR `title_en` LIKE ? ) ";

            if( $active > 0 ){
                $query .= " AND `active`= ".$active." ";
            }

            if( $start > -1 && $count > -1 ){
                $query .= " LIMIT ".$start.", ".$count."";
            }

            
            $buffer = QueryUtil::excute_select( $query, $params );

            $embeds = self::format_objects($buffer);

        } catch (Exception $e) {
            throw new CustomException( 'Error in : search embeds', $e );//from php 5.3 no need to custum
        }

        return $embeds;
    }

    public static function search_embeds_count($word = '', $active = -1){

        $count = 0;

        try {

            $params = array( "%".$word."%", "%".$word."%" );

            $query = "SELECT COUNT(*) AS `count` FROM `embeds` WHERE 1 "
                ." AND ( `title_ar` LIKE ? OR `title_en` LIKE ? ) ";

            if( $active > 0 ){
                $query .= " AND `active`= 1 ";
            }

            
            $buffer = QueryUtil::excute_select( $query, $params );

            $count = $buffer[0]["count"];

        } catch (Exception $e) {
            throw new CustomException( 'Error in : search embeds count', $e );//from php 5.3 no need to custum
        }

        return $count;
    }


    public static function get_embed($embed_id){

        $embed = null;

        try {

            $query = "SELECT * FROM `embeds` WHERE "
                ." `embed_id` = ".$embed_id;

            
            $buffer = QueryUtil::excute_select( $query );

            $embeds = self::format_objects($buffer);

            $embed = $embeds[0];

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get embed info', $e );//from php 5.3 no need to custum
        }

        return $embed;
    }


    /**
     * Returns array of objects that from format query result set
     * output into array of objects
     * @param buffer 2D array that is result of a query
     * @return objects array
     */
    public static function format_objects($buffer){

        $embeds = array();

        try {

            for($i=0; $i<count($buffer); $i++){

                $embed = (object) $buffer[$i];
                
                $embed->child_type = CHILD_TYPE_EMBED;

                $embed = SectionChildDB::get_formated_section_child($embed);
                
                $embeds[] = $embed;
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : format embed objects', $e );//from php 5.3 no need to custum
        }

        return $embeds;
    }

}
?>
