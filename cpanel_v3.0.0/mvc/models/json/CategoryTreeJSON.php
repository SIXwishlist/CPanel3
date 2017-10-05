<?php

/*
 *
 */

/**
 * Description of CategoryTreeJSON
 *
 * @author Ahmad
 */

class CategoryTreeJSON {

    public static $file_loaded = false;
    public static $dir = 'json/';
    public static $filename = "category_tree.json";
    public static $json_array;
    public static $MAX_DEPTH_LEVELS = 2;

    public static function build() {

        try {

            QueryUtil::connect();

            $childs = CategoryChildDB::get_category_childs(0);

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

                    case CHILD_TYPE_CATEGORY:
                        $category = $item;

                        $element = self::get_json_from_category($category);

                        $sub_childs = CategoryChildDB::get_category_childs($category->category_id);
                        if ( count($sub_childs) > 0 ) {//if ( count($sub_categorys) > 0 && $grade<=self::$MAX_DEPTH_LEVELS ) {
                            self::add_childs_to_array($element, $sub_childs, ++$grade);
                        }

                        $element = (object) $element;
                        $array["childs"][] = $element;
                        break;

                    case CHILD_TYPE_PRODUCT:
                        $product  = $item;
                        $element = self::get_json_from_product($product);

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

    private static function get_json_from_category($category) {

        $element = array();

        try {

            $element["child_id"]    = $category->category_id;
            $element["category_id"] = $category->category_id;
            
            $element["title_ar"]    = $category->title_ar;
            $element["title_en"]    = $category->title_en;
            $element["desc_ar"]     = $category->desc_ar;
            $element["desc_en"]     = $category->desc_en;
            $element["icon"]        = $category->icon;
            
            $element["child_type"]  = $category->child_type;
            
            $element["top_menu"]    = $category->top_menu;
            $element["side_menu"]   = $category->side_menu;
            $element["foot_menu"]   = $category->foot_menu;
            
            $element["order"]       = $category->order;
            $element["active"]      = $category->active;
            $element["parent_id"]   = $category->parent_id;

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get json from category : \n' . $e->getMessage() . "\n");
        }

        return $element;
    }

    private static function get_json_from_product($product) {

        $element = array();

        try {

            $element["child_id"]   = $product->product_id;
            $element["product_id"] = $product->product_id;
            $element["title_ar"]   = $product->title_ar;
            $element["title_en"]   = $product->title_en;
            $element["desc_ar"]    = $product->desc_ar;
            $element["desc_en"]    = $product->desc_en;
            $element["icon"]       = $product->icon;
            
            $element["child_type"] = $product->child_type;
            
            $element["featured"]   = $product->featured;
            $element["offer"]      = $product->offer;
            $element["sale"]       = $product->sale;
            $element["recent"]     = $product->recent;
            
            $element["price"]      = $product->price;
            $element["discount"]   = $product->discount;
            $element["available"]  = $product->available;
            
            $element["order"]      = $product->order;
            $element["active"]     = $product->active;
            $element["parent_id"]  = $product->parent_id;

        } catch (Exception $e) {
            throw new CustomException( 'Error in : get json from product : \n' . $e->getMessage() . "\n");
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

    public static function get_category_path($category_id){

        $path_array = array();

        $path_array = self::_get_category_path($category_id, $path_array);

        $path_array = array_reverse($path_array);
        
        return $path_array;

    }

    private static function _get_category_path($category_id, &$path_array){

        $parent_category = self::get_category($category_id);
        
        if( !empty($parent_category) ){

            $path_array[] = $parent_category;

            if( $parent_category->parent_id > 0 ){
                self::_get_category_path($parent_category->parent_id, $path_array);
            }
        }
        
        return $path_array;

    }
    
    /**************************************************************************/
   
    public static function get_category($category_id){

        $category_object = null;

        try{

            self::load();

            $category_array  = self::get_element( self::$json_array["childs"], "category_id", $category_id );

            //$category_object = (object) $category_array;
            $category_object = TextUtil::convert_array_to_object($category_array);

        } catch (Exception $e) {
            throw new Exception( 'Error : can not get category object \n',  $e->getMessage(), "\n" );
        }

        return $category_object;
    }

    public static function get_product($product_id){

        $product_object = null;

        try{

            self::load();

            $product_array  = self::get_element( self::$json_array["childs"], "product_id", $product_id );
            $product_object = (object) $product_array;

        } catch (Exception $e) {
            throw new Exception( 'Error : can not get product object \n',  $e->getMessage(), "\n" );
        }

        return $product_object;
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
