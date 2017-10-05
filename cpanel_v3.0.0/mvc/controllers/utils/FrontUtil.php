<?php
/*
 *
 */

/**
 * Description of FrontUtil
 *
 * @author Ahmad
 */

class FrontUtil {

    public static function get_default_data_array(){

        $data_array = array();

        try {

            $request = HttpRequest::get_instance();

            $active  = 1;

            $lang          = Dictionary::get_language();

            $section_tree  = SectionTreeJSON::get_tree();

            $category_tree = CategoryTreeJSON::get_tree();

            $slides  = SlideDB::get_slides(-1, -1, $active, -1, -1);
            $ads     = AdDB::get_ads( $active, -1, -1 );


            $data_array = array(
                "lang"          => $lang,
                "section_tree"  => $section_tree,
                "category_tree" => $category_tree,
                "slides"        => $slides,
                "ads"           => $ads,
                "wide_main"     => 'wide_main'
            );

        } catch (Exception $e) {
            Logger::log( $e->getMessage(), ERROR );
        }
        
        return $data_array;
    }

}

?>