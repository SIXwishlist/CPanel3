<?php
/*
 *
 */

/**
 * Description of SectionHandler
 *
 * @author Ahmad
 */

class SearchDisplay {

    public static $request = null;

    
    public static function quick_search(){

        $items_array = array();

        try {

            $items = array();

            $request = HttpRequest::get_instance();

            //$status = AdminHandler::check_admin_logged();
            $active = 1;//$active = ( $status <= 0 ) ? 1 : 0;

            $term  = $request->get_parameter("term");
            
            //$items = SectionDB::search_section_childs_list($term);
            
            $lang = TextUtil::is_arabic($term) ? "ar" : "en";

            $items = array();

            $section_items  = SectionTreeJSON::search_item("title_".$lang, $term);
            $category_items = CategoryTreeJSON::search_item("title_".$lang, $term);
            
            $items = array_merge($section_items, $category_items);
            
            if(count($items) > 10 ){
                $items = array_slice($items, 0, 10);
            }

            for ( $i=0; $i<count($items); $i++ ){
                $item = $items[$i];
                $items_array[] = Dictionary::get_text_by_lang($item, "title", false, $lang);
            }

        } catch (Exception $e) {
            Logger::log( $e->getMessage(), ERROR );
        }
                
        return $items_array;
    }
    
    public static function search(){

        try {

            $request = HttpRequest::get_instance();

            $active = 1;
            
            $lang    = Dictionary::get_language();

            QueryUtil::connect();

            $search_item = $request->get_parameter("search_item");

            $search_item  = str_replace('-', ' ', $search_item);
            
            $index = $request->get_int_parameter("index");
            $count = $request->get_int_parameter("count");

            $search_item  = ( empty($search_item) ) ? '' : $search_item;

            $index = ( $index  > 0 ) ? $index : 0;
            $count = ( $count  > 0 ) ? $count : 10;
            
            $frame_data = FrontUtil::get_default_data_array();

            $layout     = PageFrame::get_front_layout();

            //$items  = SectionDB::search_section_childs($search_item);
            
            $text  = Dictionary::get_text('SearchResults_lbl') .' - '. $search_item;

            $layout->title  = $text . ' | ' . $layout->title_postfix;
            $layout->tags[] = array( "name" => "keywords"   , "content" => $text );
            $layout->tags[] = array( "name" => "description", "content" => $text );
            
            $wlang = TextUtil::is_arabic($search_item) ? "ar" : "en";

            $items = array();
            
            $section_items  = SectionTreeJSON::search_item("title_".$lang,  $search_item);
            foreach($section_items as $item){
                $item->src = 1;
            }
            
            $category_items = CategoryTreeJSON::search_item("title_".$lang, $search_item);
            foreach($category_items as $item){
                $item->src = 2;
            }
            
            $items = array_merge($section_items, $category_items);
            
            $result_count = count($items);

            $page_data = array(
                "search_item"   => $search_item,
                "items"         => $items,
                "result_count"  => $result_count,
            );
            
            $frame_data["lang_ar"] = UrlUtil::get_search_href($search_item, 'ar');
            $frame_data["lang_en"] = UrlUtil::get_search_href($search_item, 'en');

            $frame_data["frame"]  = $layout;
            $frame_data["lang"]   = $lang;

            $search_tpl = TplLoader::get_tpl_data('search.tpl', 'mvc/views/pages', $page_data);

            $frame_data["page_content"] = $search_tpl;

            $output_string = TplLoader::get_tpl_data('frame.tpl.php', 'mvc/views/front', $frame_data);
            
            
            QueryUtil::close();

            
        } catch (Exception $e) {
            Logger::log( $e->getMessage(), ERROR );
        }
        
        return $output_string;
    }
    
}

?>