<?php
/*
 *
 */

/**
 * Description of SectionDB
 *
 * @author Ahmad
 */
class SectionDB extends DataDB {

    public static function add_section($section){

        $result = 0;

        try {

            $params = array( $section->title_ar, $section->title_en, $section->desc_ar, $section->desc_en, $section->content_ar, $section->content_en, $section->keys_ar, $section->keys_en, $section->icon, $section->image, $section->format, $section->menu, $section->options, $section->order, $section->active, $section->parent_id );
            
            $query = "INSERT INTO `sections` ( `title_ar`, `title_en`, `desc_ar`, `desc_en`, `content_ar`, `content_en`, `keys_ar`, `keys_en`, `icon`, `image`, `format`, `menu`, `options`, `order`, `active`, `parent_id` ) "
                . " VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? ) ";

            $result = QueryUtil::excute_update( $query, $params );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : add section', $e );//from php 5.3 no need to custum
        }

        return $result;
    }

    public static function update_section($section){

        $result = 0;

        try {

            $params = array( $section->title_ar, $section->title_en, $section->desc_ar, $section->desc_en, $section->content_ar, $section->content_en, $section->keys_ar, $section->keys_en, $section->icon, $section->image, $section->format, $section->menu, $section->options, $section->order, $section->active, $section->parent_id );

            $query = "UPDATE `sections` SET "
                ." `title_ar` = ?, `title_en` = ?, `desc_ar` = ?, `desc_en` = ?, `content_ar` = ?, `content_en` = ?, `keys_ar` = ?, `keys_en` = ?, `icon` = ?, `image` = ?, `format` = ?, `menu` = ?, `options` = ?, `order` = ?, `active` = ?, `parent_id` = ? "
                ." WHERE `section_id` = ".$section->section_id;

            $result = QueryUtil::excute_update( $query, $params );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : update section', $e );//from php 5.3 no need to custum
        }

        return $result;
    }

    public static function remove_section($section){

        $result = 0;

        try {

            $query = "DELETE FROM `sections` WHERE `section_id` = ".$section->section_id;

            $result = QueryUtil::excute_update( $query );

        } catch (Exception $e) {
            throw new CustomException( 'Error in : remove section', $e );//from php 5.3 no need to custum
        }

        return $result;
    }


    public static function get_sections($parent_id = -1, $active = -1, $start = -1, $count = -1, $order_by = '`section_id`', $arrange = 'ASC'){

        $sections = null;

        try {

            $params = array();

            $query = "SELECT * FROM `sections` WHERE 1 ";

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

            $sections = self::format_objects($buffer);

        } catch (Exception $e) {
             throw new CustomException( 'Error in : get sections', $e );//from php 5.3 no need to custum
        }

        return $sections;
    }

    public static function get_sections_count($parent_id = -1, $active = -1){

        $count = 0;

        try {

            $params = array();

            $query = "SELECT COUNT(*) AS `count` FROM `sections` WHERE 1 ";

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
             throw new CustomException( 'Error in : get sections count', $e );//from php 5.3 no need to custum
        }

        return $count;
    }


    public static function search_sections($word = '', $active = -1, $start = -1, $count = -1){

        $sections = null;

        try {

            $params = array( "%".$word."%", "%".$word."%" );

            $query = "SELECT * FROM `sections` WHERE 1 "
                ." AND ( `title_ar` LIKE ? OR `title_en` LIKE ?  ) ";

            if( $active > 0 ){
                $query .= " AND `active`= ".$active." ";
            }

            if( $start > -1 && $count > -1 ){
                $query .= " LIMIT ".$start.", ".$count."";
            }

            $buffer = QueryUtil::excute_select( $query, $params );

            $sections = self::format_objects($buffer);

        } catch (Exception $e) {
            throw new CustomException( 'Error in : search sections', $e );//from php 5.3 no need to custum
        }

        return $sections;
    }

    public static function search_sections_count($word = '', $active = -1){

        $count = 0;

        try {

            $params = array( "%".$word."%", "%".$word."%", "%".$word."%" );

            $query = "SELECT COUNT(*) AS `count` FROM `sections` WHERE 1 "
                ." AND ( `title_ar` LIKE ? OR `title_en` LIKE ? ) ";

            if( $active > 0 ){
                $query .= " AND `active`= 1 ";
            }

            $buffer = QueryUtil::excute_select( $query, $params );

            $count = $buffer[0]["count"];

        } catch (Exception $e) {
            throw new CustomException( 'Error in : search sections count', $e );//from php 5.3 no need to custum
        }

        return $count;
    }


    public static function get_section($section_id){

        $section = null;

        try {

            $query = "SELECT * FROM `sections` WHERE "
                ." `section_id` = ".$section_id;

            $buffer = QueryUtil::excute_select( $query );

            $sections = self::format_objects($buffer);

            if( count($sections) > 0 ){
                $section = $sections[0];
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get section info', $e );//from php 5.3 no need to custum
        }

        return $section;
    }


    public static function format_objects($buffer){

        $sections = array();

        try {

            for($i=0; $i<count($buffer); $i++){

                $section = (object) $buffer[$i];

                $section->child_type = CHILD_TYPE_SECTION;

                $section = SectionChildDB::get_formated_section_child($section);

                $sections[] = $section;
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : format section objects', $e );//from php 5.3 no need to custum
        }

        return $sections;
    }
    
    

}
?>
