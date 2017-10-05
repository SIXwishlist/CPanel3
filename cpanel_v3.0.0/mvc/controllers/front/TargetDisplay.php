<?php
/*
 *
 */

/**
 * Description of TargetDisplay
 *
 * @author Ahmad
 */

class TargetDisplay {

    public static function get_target_info(){

        $output_string = '';

        try {

            $request = HttpRequest::get_instance();

            $active = 1;

            $lang   = Dictionary::get_language();

            $target_id = $request->get_int_parameter("target_id");
            $target_id = ( $target_id  <= 0 ) ? -1 : $target_id;

            if( CACHING_ENABLED ){
                $cache  = Cache::create_instance("target.$target_id.cache.json");
                $output = $cache->get_data($lang);
                if( !empty($output) ){ return $output; }
            }

            QueryUtil::connect();

            $frame_data = FrontUtil::get_default_data_array();

            $layout     = PageFrame::get_front_layout();

            
            $target = TargetDB::get_target($target_id);
            $parent_id = $target->parent_id;

            //$target_path = SectionDB::get_section_path($parent_id);
            $target_path = SectionTreeJSON::get_section_path($parent_id);
            $target_path[] = $target;

            if( isset($target) ){
                $layout->title  = Dictionary::get_text_by_lang($target, "title") . ' | ' . $layout->title_postfix;
                $layout->tags[] = array( "name" => "keywords"   , "content" => Dictionary::get_text_by_lang($target, "keys") );
                $layout->tags[] = array( "name" => "description", "content" => Dictionary::get_text_by_lang($target, "desc") );
            }


            $related_items = array();

            $parent_section = SectionTreeJSON::get_section($parent_id);
            if ( isset($parent_section) ) {
                $related_items = $parent_section->childs;
            }


            $page_data = array(
                "target_path"   => $target_path,
                "target"        => $target,
                //"auto_links"    => $auto_links,
                "related_items" => $related_items
            );


            $target->child_type = 2;

            $frame_data["lang_ar"] = UrlUtil::get_section_child_href($target, "ar");
            $frame_data["lang_en"] = UrlUtil::get_section_child_href($target, "en");

            $frame_data["frame"] = $layout;
            $frame_data["lang"]  = $lang;

            $front_tpl = TplLoader::get_tpl_data('target_info.tpl.php', 'mvc/views/pages', $page_data);

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

            if ( $childs[$i]->child_type == 3 ) continue;

            //$childs[$i]->item_id = $childs[$i]->item_id;

            $picked_childs[] = $childs[$i];

        }

        return $picked_childs;
    }

    private static function pick_embed_objects($childs){

        $picked_childs = array();

        for ( $i=0; $i<count($childs); $i++ ){

            if ( $childs[$i]->child_type != 3 ) continue;

            //$childs[$i]->item_id = $childs[$i]->child_id;

            $picked_childs[] = $childs[$i];

        }

        return $picked_childs;
    }

    public static function cmp_items_func($a, $b){

        if (intval($a->order) == intval($b->order))
            return 0;
        if (intval($a->order) >  intval($b->order))
            return 1;

        return -1;
    }
}

?>
