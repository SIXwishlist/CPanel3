<?php

/*
 *
 */

//include_once BASE_DIR.'/mvc/libraries/json/JSONDocument.php';

//include_once BASE_DIR.'/mvc/models/db/SectionDB.php';
//include_once BASE_DIR.'/mvc/models/db/TargetDB.php';

//include_once BASE_DIR.'/mvc/libraries/QueryUtil.php';
//include_once BASE_DIR.'/mvc/libraries/TextConverter.php';

/**
 * Description of SectionTreeJSON
 *
 * @author Ahmad
 */

class SectionTreeJSON {

    public static $file_loaded = false;
    public static $dir = 'json/';
    public static $filename = "section_tree.json";
    public static $json_array;
    public static $MAX_DEPTH_LEVELS = 2;

    public static function build() {

        try {

            QueryUtil::connect();

            $childs = SectionChildDB::get_section_childs(0);

            $json_array = array();

            self::add_childs_to_array($json_array, $childs, 0);

            self::$json_array = $json_array;

            $status = self::save_changes();

            QueryUtil::close();

        } catch (Exception $e) {
            throw new CustomException( 'Error : ' .  $e->getMessage() . "" );
        }
        

        return $status;
    }

    private static function add_childs_to_array(array &$array, array $childs, $grade = 0) {

        $result = 0;

        try {

            foreach ($childs as $item) {
                
                switch ($item->child_type) {

                    case 1:
                        $section = $item;

                        $element = self::get_json_from_section($section);

                        $sub_childs = SectionChildDB::get_section_childs($section->section_id);
                        if ( count($sub_childs) > 0 ) {//if ( count($sub_sections) > 0 && $grade<=self::$MAX_DEPTH_LEVELS ) {
                            self::add_childs_to_array($element, $sub_childs, ++$grade);
                        }

                        $element = (object) $element;
                        $array["childs"][] = $element;
                        break;

                    case 2:
                        $target  = $item;
                        $element = self::get_json_from_target($target);

                        $element = (object) $element;
                        $array["childs"][] = $element;
                        break;

                    case 3:
                        $embed   = $item;
                        $element = self::get_json_from_embed($embed);

                        $element = (object) $element;
                        $array["childs"][] = $element;
                        break;

                    case 4:
                        $link    = $item;
                        $element = self::get_json_from_link($link);

                        $element = (object) $element;
                        $array["childs"][] = $element;
                        break;

                }
            }
            
        } catch (Exception $e) {
            throw new CustomException( 'Error : ' .  $e->getMessage() . "" );
        }

        return $result;
    }

    private static function get_json_from_section($section) {

        $element = array();

        try {

            $element["item_id"]    = $section->section_id;
            $element["section_id"] = $section->section_id;
            $element["title_ar"]   = $section->title_ar;
            $element["title_en"]   = $section->title_en;
            $element["desc_ar"]    = $section->desc_ar;
            $element["desc_en"]    = $section->desc_en;
            $element["icon"]       = $section->icon;
            $element["child_type"] = $section->child_type;
            $element["menu"]       = $section->menu;
            $element["top_menu"]   = $section->top_menu;
            $element["side_menu"]  = $section->side_menu;
            $element["foot_menu"]  = $section->foot_menu;
            $element["show_menu"]  = $section->show_menu;
            $element["show_sub"]   = $section->show_sub;
            $element["order"]      = $section->order;
            $element["active"]     = $section->active;
            $element["parent_id"]  = $section->parent_id;

        } catch (Exception $e) {
            throw new CustomException( 'Error in : fill section variables: \n' . $e->getMessage() . "\n");
        }

        return $element;
    }

    private static function get_json_from_target($target) {

        $element = array();

        try {

            $element["item_id"]    = $target->target_id;
            $element["target_id"]  = $target->target_id;
            $element["title_ar"]   = $target->title_ar;
            $element["title_en"]   = $target->title_en;
            $element["desc_ar"]    = $target->desc_ar;
            $element["desc_en"]    = $target->desc_en;
            $element["icon"]       = $target->icon;
            $element["icon_menu"]  = $target->icon_menu;
            $element["child_type"] = $target->child_type;
            $element["menu"]       = $target->menu;
            $element["top_menu"]   = $target->top_menu;
            $element["side_menu"]  = $target->side_menu;
            $element["foot_menu"]  = $target->foot_menu;
            $element["order"]      = $target->order;
            $element["active"]     = $target->active;
            $element["parent_id"]  = $target->parent_id;

        } catch (Exception $e) {
            throw new CustomException( 'Error in : fill target variables: \n' . $e->getMessage() . "\n");
        }

        return $element;
    }

    private static function get_json_from_embed($embed) {

        $element = array();

        try {

            $element["item_id"]    = $embed->embed_id;
            $element["embed_id"]   = $embed->embed_id;
            $element["title_ar"]   = $embed->title_ar;
            $element["title_en"]   = $embed->title_en;
            $element["desc_ar"]    = $embed->desc_ar;
            $element["desc_en"]    = $embed->desc_en;
            $element["icon"]       = $embed->icon;
            $element["file"]       = $embed->file;
            $element["type"]       = $embed->type;
            $element["child_type"] = $embed->child_type;
            $element["menu"]       = 0;
            $element["order"]      = $embed->order;
            $element["active"]     = $embed->active;
            $element["parent_id"]  = $embed->parent_id;

        } catch (Exception $e) {
            throw new CustomException( 'Error in : fill embed variables: \n' . $e->getMessage() . "\n");
        }

        return $element;
    }

    private static function get_json_from_link($link) {

        $element = array();

        try {

            $element["item_id"]    = $link->link_id;
            $element["link_id"]    = $link->link_id;
            $element["title_ar"]   = $link->title_ar;
            $element["title_en"]   = $link->title_en;
            $element["desc_ar"]    = $link->desc_ar;
            $element["desc_en"]    = $link->desc_en;
            $element["icon"]       = $link->icon;
            $element["child_type"] = $link->child_type;
            $element["url_ar"]     = $link->url_ar;
            $element["url_en"]     = $link->url_en;
            $element["menu"]       = $link->menu;
            $element["top_menu"]   = $link->top_menu;
            $element["side_menu"]  = $link->side_menu;
            $element["foot_menu"]  = $link->foot_menu;
            $element["options"]    = $link->options;
            $element["new_window"] = $link->new_window;
            $element["order"]      = $link->order;
            $element["active"]     = $link->active;
            $element["parent_id"]  = $link->parent_id;

        } catch (Exception $e) {
            throw new CustomException( 'Error in : fill link variables: \n' . $e->getMessage() . "\n");
        }

        return $element;
    }

    private static function save_changes() {

        $path = UPLOAD_DIR . self::$dir;

        $jsonWriter = new JSONDocument(self::$json_array);

        $jsonWriter->set_encoding(DEFAULT_ENCODING);
        $jsonWriter->convert_to_json_string();

        $status = $jsonWriter->save_json_file($path, self::$filename);

        return $status;
    }
    
    /**************************************************************************/
    
    private static function load() {

        if( !self::$file_loaded ){
           
            $path = UPLOAD_DIR . self::$dir;

            $json_parser = new JSONDocument();

            $json_parser->load_json_file($path . self::$filename);

            self::$json_array = $json_parser->get_json_array();
            
            self::$file_loaded = true;

        }
    }

    public static function get_tree_as_array() {

        $json_array = array();

        self::load();

        $json_array = self::$json_array["childs"];

        return $json_array;
    }

    public static function get_tree() {

        $json_array = array();

        self::load();

        $json_array = TextUtil::convert_array_to_object( self::$json_array["childs"] );
        
        return $json_array;
    }

    /**************************************************************************/
    
    
    public static function get_section_path($section_id){

        $path_array = array();

        $path_array = self::_get_section_path($section_id, $path_array);

        $path_array = array_reverse($path_array);
        
        return $path_array;

    }

    private static function _get_section_path($section_id, &$path_array){

        $parent_section = self::get_section($section_id);
        
        if( !empty($parent_section) ){

            $path_array[] = $parent_section;

            if( $parent_section->parent_id > 0 ){
                self::_get_section_path($parent_section->parent_id, $path_array);
            }
        }
        
        return $path_array;

    }
    
    /**************************************************************************/
   
    public static function get_section($section_id){

        $section_object = null;

        try{

            self::load();

            $section_array  = self::get_element( self::$json_array["childs"], "section_id", $section_id );

            //$section_object = (object) $section_array;
            $section_object = TextUtil::convert_array_to_object($section_array);

        } catch (Exception $e) {
            throw new Exception( 'Error : can not get section object \n',  $e->getMessage(), "\n" );
        }

        return $section_object;
    }
    public static function get_target($target_id){

        $target_object = null;

        try{

            self::load();

            $target_array  = self::get_element( self::$json_array["childs"], "target_id", $target_id );
            $target_object = (object) $target_array;

        } catch (Exception $e) {
            throw new Exception( 'Error : can not get target object \n',  $e->getMessage(), "\n" );
        }

        return $target_object;
    }
    public static function get_embed($embed_id){

        $embed_object = null;

        try{

            self::load();

            $embed_array  = self::get_element( self::$json_array["childs"], "embed_id", $embed_id );
            $embed_object = (object) $embed_array;

        } catch (Exception $e) {
            throw new Exception( 'Error : can not get embed object \n',  $e->getMessage(), "\n" );
        }

        return $embed_object;
    }
    public static function get_link($link_id){

        $link_object = null;

        try{

            self::load();

            $link_array  = self::get_element( self::$json_array["childs"], "link_id", $link_id );
            $link_object = (object) $link_array;

        } catch (Exception $e) {
            throw new Exception( 'Error : can not get link object \n',  $e->getMessage(), "\n" );
        }

        return $link_object;
    }

    public static function get_item($item_id, $type){

        $item_object = null;

        try{

            self::load();

            $item_array  = self::get_element( self::$json_array["childs"], "item_id", $item_id );
            $item_object = (object) $item_array;

        } catch (Exception $e) {
            throw new Exception( 'Error : can not get item array \n',  $e->getMessage(), "\n" );
        }

        return $item_object;
    }

    private static function get_element($array, $label, $val){

        for($i=0; $i<count($array); $i++){
            if( $array[$i][$label] == $val ){
                return $array[$i];
            }
            
            $item = self::get_element($array[$i]["childs"], $label, $val);

            if( $item != null ){
                return $item;
            }
        }

        return null;
    }

    private static function get_index($array, $label, $val){

        for($i=0; $i<count($array); $i++){
            if( $array[$i][$label] == $val ){
                return $i;
            }
        }

        return -1;
    }

    /**************************************************************************/
    
    public static function search_item($label, $val){

        $matches = array();

        try{

            self::load();

            $matches = self::p_search_element(self::$json_array["childs"], $label, $val, $matches);

            $matches = TextUtil::convert_array_to_object( $matches );
            
        } catch (Exception $e) {
            throw new Exception( 'Error : can not get item array \n',  $e->getMessage(), "\n" );
        }

        return $matches;
    }

    private static function p_search_element($array, $label, $val, &$matches){

        for($i=0; $i<count($array); $i++){
            
            if (mb_stripos($array[$i][$label], $val) !== false) {
                $matches[] = $array[$i];
            }
            
            //if( $array[$i][$label] == $val ){
            //    return $array[$i];
            //}
            
            self::p_search_element($array[$i]["childs"], $label, $val, $matches);

        }

        return $matches;
    }

}

?>
