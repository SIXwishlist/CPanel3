<?php
/*
 *
 */

/**
 * Description of SectionDisplay
 *
 * @author Ahmad
 */

class SectionDisplay {

    public static function get_section_info(){

        $output_string = '';

        try {

            $request = HttpRequest::get_instance();

            $active = 1;

            $lang   = Dictionary::get_language();

            $section_id = $request->get_int_parameter("section_id");
            $section_id = ( $section_id  > 0 ) ? $section_id : 0;
            
            if( CACHING_ENABLED ){
                $cache  = Cache::create_instance("section.$section_id.cache.json");
                $output = $cache->get_data($lang);
                if( !empty($output) ){ return $output; }
            }

            QueryUtil::connect();

            $frame_data = FrontUtil::get_default_data_array();

            $layout     = PageFrame::get_front_layout();

            $section    = SectionDB::get_section($section_id);
            
            //$section_path = SectionDB::get_section_path($section_id);
            $section_path = SectionTreeJSON::get_section_path($section_id);

            //$section_childs = SectionDB::get_section_childs($section_id);
            $parent_section = SectionTreeJSON::get_section($section_id);
            $section_childs = $parent_section->childs;


            if( $section != null ){
                $layout->title  = Dictionary::get_text_by_lang($section, "title") . ' | ' . $layout->title_postfix;
                $layout->tags[] = array( "name" => "keywords"   , "content" => Dictionary::get_text_by_lang($section, "keys") );
                $layout->tags[] = array( "name" => "description", "content" => Dictionary::get_text_by_lang($section, "desc") );
            }

            $childs = self::pick_section_target_link_objects($section_childs);
            $embeds = self::pick_embed_objects($section_childs);

            usort($childs, array(self, cmp_items_func));
            usort($embeds, array(self, cmp_items_func));

            $result_count = count($childs);
            $embeds_count = count($embeds);

            //$auto_links = SectionTreeJSON::get_tree();

            $related_items = array();

            $parent_section = SectionTreeJSON::get_section($section->parent_id);
            if ( isset($parent_section) ) {
                $related_items = $parent_section->childs;
            }
            
            
            $page_data = array(
                "section_path"  => $section_path,
                "section"       => $section,
                "childs"        => $childs,
                "result_count"  => $result_count,
                "embeds"        => $embeds,
                "embeds_count"  => $embeds_count
                //"auto_links"    => $auto_links,
                //"related_items" => $related_items,
            );


            
            $section->child_type = 1;
            
            $frame_data["lang_ar"] = UrlUtil::get_section_child_href($section, "ar");
            $frame_data["lang_en"] = UrlUtil::get_section_child_href($section, "en");
            
            $frame_data["frame"] = $layout;
            $frame_data["lang"]  = $lang;

            $front_tpl = TplLoader::get_tpl_data('section_info.tpl.php', 'mvc/views/pages', $page_data);

            $frame_data["page_content"] = $front_tpl;

            $output_string = TplLoader::get_tpl_data('frame.tpl.php', 'mvc/views/front', $frame_data);         
            
            QueryUtil::close();
            
            if( CACHING_ENABLED ){
                $cache->set_data($lang, $output_string);
            }
            
        } catch (Exception $e) {
            Logger::log( $e->getMessage(), ERROR );
        }
        
        return $output_string;
    }

    private static function pick_section_target_link_objects($childs){
        
        $picked_childs = array();

        for ( $i=0; $i<count($childs); $i++ ){

            if ( $childs[$i]->child_type == CHILD_TYPE_EMBED ) continue;

            //$childs[$i]->item_id = $childs[$i]->item_id;

            $picked_childs[] = $childs[$i];

        }
        
        return $picked_childs;
    }

    private static function pick_embed_objects($childs){

        $picked_childs = array();

        for ( $i=0; $i<count($childs); $i++ ){

            if ( $childs[$i]->child_type != CHILD_TYPE_EMBED ) continue;

            //$childs[$i]->item_id = $childs[$i]->child_id;

            $picked_childs[] = $childs[$i];

        }
        
        return $picked_childs;
    }

    private static function cmp_items_func($a, $b){

        if (intval($a->order) == intval($b->order))
            return 0;
        if (intval($a->order) >  intval($b->order))
            return 1;

        return -1;
    }
}

?>