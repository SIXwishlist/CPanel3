<?php
/*
 *
 */

/**
 * Description of UrlUtil
 *
 * @author Ahmad
 */
class UrlUtil {

    //public static $OPTION  = 0;

    private function UrlUtil(){
    }


    public static function get_home_href( $lang=null ){

        $href = '';
        
        if( ! USE_MEANINGFUL_URL ){
            $href  = '?page=home&lang='.$lang;
            return $href;
        }

        $lang = ( $lang == null ) ? Dictionary::get_language() : $lang;

        $href = BASE_URL.$lang;

        return $href;
    }

    public static function get_contact_us_href( $lang=null ){

        $href = '';
        
        if( ! USE_MEANINGFUL_URL ){
            $href  = '?page=contact_us&lang='.$lang;
            return $href;
        }
        
        $lang = ( $lang == null ) ? Dictionary::get_language() : $lang;
        
        $href = BASE_URL.$lang."/".UrlUtil::fix_url(Dictionary::get_text("ContactUs_lbl", $lang)).'/4';
        
        return $href;
    }

    public static function get_cart_href( $lang=null ){

        $href = '';
        
        if( ! USE_MEANINGFUL_URL ){
            $href  = '?page=cart&lang='.$lang;
            return $href;
        }
        
        $lang = ( $lang == null ) ? Dictionary::get_language() : $lang;
        
        $href = BASE_URL.$lang."/".UrlUtil::fix_url(Dictionary::get_text("Cart_lbl", $lang)).'/12';
        
        return $href;
    }

    public static function get_checkout_href( $lang=null ){

        $href = '';
        
        if( ! USE_MEANINGFUL_URL ){
            $href  = '?page=checkout&lang='.$lang;
            return $href;
        }
        
        $lang = ( $lang == null ) ? Dictionary::get_language() : $lang;
        
        $href = BASE_URL.$lang."/".UrlUtil::fix_url(Dictionary::get_text("Checkout_lbl", $lang)).'/13';
        
        return $href;
    }

    
    public static function get_user_profile_href($user, $lang = null){

        $href = '';
        
        if( ! USE_MEANINGFUL_URL ){
            $href  = '?page=user_profile&user_id='.$user->user_id.'&lang='.$lang;
            return $href;
        }
        
        $lang = ( $lang == null ) ? Dictionary::get_language() : $lang;
        
        $href = BASE_URL . $lang
            . '/' . UrlUtil::fix_url(Dictionary::get_text("UserProfile_lbl", $lang))
            . '/' . UrlUtil::fix_url($user->name).'/9-'.$user->user_id;
        
        return $href;
    }


    public static function get_verify_href($user_id, $ukey, $lang = null){

        $href = '';
        
        if( ! USE_MEANINGFUL_URL ){
            $href  = '?page=verify&lang='.$lang.'&user_id='.$user_id.'&ukey='.$ukey;
            return $href;
        }
        
        $lang = ( $lang == null ) ? Dictionary::get_language() : $lang;
        
        $href = BASE_URL.$lang."/".UrlUtil::fix_url(Dictionary::get_text("Verify_lbl", $lang)).'/10/'.$user_id.'/'.$ukey;
        
        return $href;
    }

    public static function get_reset_href($user_id, $ukey, $lang = null){

        $href = '';
        
        if( ! USE_MEANINGFUL_URL ){
            $href  = '?page=reset&lang='.$lang.'&user_id='.$user_id.'&ukey='.$ukey;
            return $href;
        }
        
        $lang = ( $lang == null ) ? Dictionary::get_language() : $lang;
        
        $href = BASE_URL.$lang."/".UrlUtil::fix_url(Dictionary::get_text("Reset_lbl", $lang)).'/11/'.$user_id.'/'.$ukey;
        
        return $href;
    }

    
    public static function get_sitemap_href( $lang=null ){

        $href = '';
        
        if( ! USE_MEANINGFUL_URL ){
            $href  = '?page=sitemap&lang='.$lang;
            return $href;
        }
        
        $lang = ( $lang == null ) ? Dictionary::get_language() : $lang;

        $href = BASE_URL.$lang."/".UrlUtil::fix_url(Dictionary::get_text("Sitemap_lbl", $lang)).'/100';
        
        return $href;
    }


    public static function get_search_href( $search_item, $lang=null ){

        $href = '';
        
        if( ! USE_MEANINGFUL_URL ){
            $href  = '?page=search&search_item='.$search_item.'&lang='.$lang;
            return $href;
        }
        
        $lang = ( $lang == null ) ? Dictionary::get_language() : $lang;

        $href = BASE_URL.$lang."/".UrlUtil::fix_url(Dictionary::get_text("Search_lbl", $lang)).'/'.$search_item.'/5';
        
        return $href;
    }


    public static function get_section_root_href( $lang=null ){

        $href = '';
        
        
        if( ! USE_MEANINGFUL_URL ){
            $href  = '?page=sections&lang='.$lang;
            return $href;
        }
        
        $lang = ( $lang == null ) ? Dictionary::get_language() : $lang;
        
        $href = BASE_URL.$lang."/".UrlUtil::fix_url(Dictionary::get_text("Sections_lbl", $lang)).'/1-0';
        
        return $href;
    }

    public static function get_product_root_href( $lang=null ){

        $href = '';
        
        if( ! USE_MEANINGFUL_URL ){
            $href  = '?page=categories&lang='.$lang;
            return $href;
        }
        
        $lang = ( $lang == null ) ? Dictionary::get_language() : $lang;
        
        $href = BASE_URL.$lang."/".UrlUtil::fix_url(Dictionary::get_text("Products_lbl", $lang)).'/6-0';

        return $href;
    }

    public static function get_category_root_href( $lang=null ){

        $href = '';
        
        if( ! USE_MEANINGFUL_URL ){
            $href  = '?page=categories&lang='.$lang;
            return $href;
        }
        
        $lang = ( $lang == null ) ? Dictionary::get_language() : $lang;
        
        $href = BASE_URL.$lang."/".UrlUtil::fix_url(Dictionary::get_text("Categories_lbl", $lang)).'/6-0';

        return $href;
    }

    
    public static function get_section_child_href( $item, $lang=null ){

        $href = '';

        switch( $item->child_type ){

            case 1:
                $href = self::get_section_href($item, $lang);
                break;

            case 2:
                $href = self::get_target_href($item, $lang);
                break;

            case 3:
                $href = self::get_embed_href($item, $lang);
                break;

            case 4:
                $href = self::get_link_href($item, $lang);
                break;

            default:
                break;
        }
        
        return $href;
    }
    
    public static function get_section_href( $section, $lang=null ){

        $href = '';
        
        if( ! USE_MEANINGFUL_URL ){
            $href  = '?page=section_info&section_id='.$section->section_id.'&lang='.$lang;
            return $href;
        }


        if( empty($section) ){
            return $href;
        }
        
        $lang = ( $lang == null ) ? Dictionary::get_language() : $lang;

        $path_string = '';
        
        $path_array = SectionTreeJSON::get_section_path($section->section_id);
        
        foreach ($path_array as $psection){

            $title = trim( Dictionary::get_text_by_lang($psection, "title", false, $lang) );

            $path_string .= $title.'/';
            
        }
 
        $sub_url = $lang.'/'.$path_string.'1-'.$section->section_id;
        
        $sub_url = self::fix_item_href($sub_url);

        $href    = BASE_URL.$sub_url;

        //$href  = '?page=section_info&section_id='.$section->section_id.'';

        return $href;
    }

    public static function get_target_href( $target, $lang=null ){

        $href = '';

        $lang = ( $lang == null ) ? Dictionary::get_language() : $lang;
        
        if( $target->target_id == HOME_PAGE ){
            $href  = self::get_home_href($lang);
            return $href;
        }
        
        if( $target->target_id == CONTACT_PAGE ){
            $href  = self::get_contact_us_href($lang);
            return $href;
        }
        
        if( ! USE_MEANINGFUL_URL ){
            $href  = '?page=target_info&target_id='.$target->target_id.'&lang='.$lang;
            return $href;
        }
        
        if( empty($target) ){
            return $href;
        }
                
        $path_string = '';
        
        $path_array = SectionTreeJSON::get_section_path($target->parent_id);
        
        foreach ($path_array as $psection){

            $title = trim( Dictionary::get_text_by_lang($psection, "title", false, $lang) );

            $path_string .= $title.'/';
            
        }
        
        $title = trim( Dictionary::get_text_by_lang($target, "title", false, $lang) );

        $path_string .= $title.'/';
        
        $sub_url = $lang.'/'.$path_string.'2-'.$target->target_id;
        
        $sub_url = self::fix_item_href($sub_url);

        $href    = BASE_URL.$sub_url;

        //$href  = '?page=target_info&target_id='.$target->target_id.'';
        
        return $href;
    }

    public static function get_embed_href( $embed, $lang=null ){

        $href = '';

        if( ! USE_MEANINGFUL_URL ){
            $href  = '?page=embed_info&embed_id='.$embed->embed_id.'';
            return $href;
        }
        
        if( empty($embed) ){
            return $href;
        }
        
        $lang = ( $lang == null ) ? Dictionary::get_language() : $lang;
        
        $path_string = '';
        
        $path_array = SectionTreeJSON::get_section_path($embed->parent_id);
        
        foreach ($path_array as $psection){

            $title = trim( Dictionary::get_text_by_lang($psection, "title", false, $lang) );

            $path_string .= $title.'/';
            
        }
        
        $title = trim( Dictionary::get_text_by_lang($embed, "title", false, $lang) );

        $path_string .= $title.'/';
        
        $sub_url = $lang.'/'.$path_string.'3-'.$embed->embed_id;
        
        $sub_url = UrlUtil::fix_item_href($sub_url);

        $href    = BASE_URL.$sub_url;

        //$href  = '?page=embed_info&embed_id='.$embed->embed_id.'';
        
        return $href;
    }

    public static function get_link_href( $link, $lang=null ){

        $href = '';

        $lang = ( $lang == null ) ? Dictionary::get_language() : $lang;
        
        $href = Dictionary::get_text_by_lang($link, "url", false, $lang);
        
        return $href;
    }

    
    public static function get_section_child_target( $item ){

        $target = '_self';

        switch( $item->child_type ){

            case 1:
                $target = '_self';
                break;

            case 2:
                $target = '_self';
                break;

            case 3:
                $target = '_self';
                break;

            case 4:
                $target = ( $item->new_window > 0 ) ? '_blank' : '_self';
                break;

            default:
                break;
        }

        return $target;
    }

    
    public static function get_embed_file_name( $embed ){

        $filename = '';

        $orgfile = $embed->file;
        $ext     = '';

        if( strpos($orgfile, ".") ){
            $ext = substr($orgfile, strpos($orgfile, ".")+1, strlen($orgfile) );
        }
        
        if( $embed != null ){
            $title = Dictionary::get_text_by_lang($embed, "title");

            $title = trim($title);

            $title = str_replace("  ", " ", $title);
            $title = str_replace(" ", "-", $title);
        }

        switch( $embed->type ){

            case FILE_TYPE_DOWNLOAD :
                $filename = $title.'_'.$embed->embed_id.'.'.$ext;
                break;

            case FILE_TYPE_IMAGE    :
                $filename = $title.'_'.$embed->embed_id.'.'.$ext;
                break;

            case FILE_TYPE_FLASH    :
                $filename = $title.'_'.$embed->embed_id.'.'.$ext;
                break;

            case FILE_TYPE_SOUND    :
                $filename = $title.'_'.$embed->embed_id;
                break;

            case FILE_TYPE_VIDEO    :
                $filename = $title.'_'.$embed->embed_id;
                break;

            case FILE_TYPE_YOUTUBE  :
                $filename = $embed->file;
                break;

            case FILE_TYPE_VIMEO  :
                $filename = $embed->file;
                break;

            case FILE_TYPE_EMBED_CODE  :
                $filename = $embed->file;
                break;

            case FILE_TYPE_UNKNOWN  :
                $filename = $embed->file;
                break;

            default:
                break;
        }
        

        $href = preg_replace('/--+/', '-', $href);
        $href = strtolower($href);

        $href = str_replace("&", "&amp;", $href);
    
        return $filename;
    }

    

    public static function get_category_child_href( $item, $lang=null ){

        $href = '';

        switch( intval($item->child_type) ){

            case 1:
                $href = self::get_category_href($item, $lang);
                break;

            case 2:
                $href = self::get_product_href($item, $lang);
                break;

            default:
                break;
        }
        
        return $href;
    }
    
    public static function get_category_href( $category, $lang=null ){

        $href = '';
        
        if( ! USE_MEANINGFUL_URL ){
            $href  = '?page=category_info&category_id='.$category->category_id.'&lang='.$lang;
            return $href;
        }

        
        if( empty($category) ){
            return $href;
        }
        
        $lang = ( $lang == null ) ? Dictionary::get_language() : $lang;

        $path_string = '';
        
        $path_array = CategoryTreeJSON::get_category_path($category->category_id);
        
        foreach ($path_array as $pcategory){

            $title = trim( Dictionary::get_text_by_lang($pcategory, "title", false, $lang) );

            $path_string .= $title.'/';
            
        }
 
        $sub_url = $lang.'/'.$path_string.'6-'.$category->category_id;
        
        $sub_url = self::fix_item_href($sub_url);

        $href    = BASE_URL.$sub_url;

        //$href  = '?page=category_info&category_id='.$category->category_id.'';

        return $href;
    }

    public static function get_product_href( $product, $lang=null ){

        $href = '';
        
        if( ! USE_MEANINGFUL_URL ){
            $href  = '?page=product_info&product_id='.$product->product_id.'&lang='.$lang;
            return $href;
        }
        
        if( empty($product) ){
            return $href;
        }
        
        $lang = ( $lang == null ) ? Dictionary::get_language() : $lang;
        
        $path_string = '';
        
        $path_array = CategoryTreeJSON::get_category_path($product->parent_id);
        
        foreach ($path_array as $pcategory){

            $title = trim( Dictionary::get_text_by_lang($pcategory, "title", false, $lang) );

            $path_string .= $title.'/';
            
        }
        
        $title = trim( Dictionary::get_text_by_lang($product, "title", false, $lang) );

        $path_string .= $title.'/';
        
        $sub_url = $lang.'/'.$path_string.'7-'.$product->product_id;
        
        $sub_url = self::fix_item_href($sub_url);

        $href    = BASE_URL.$sub_url;

        //$href  = '?page=product_info&product_id='.$product->product_id.'';
        
        return $href;
    }

    

    public static function get_section_child_folder( $item ){

        $folder = '';

        switch( $item->child_type ){

            case 1:
                $folder = 'sections';
                break;

            case 2:
                $folder = 'targets';
                break;

            case 3:
                $folder = 'embeds';
                break;

            case 4:
                $folder = 'links';
                break;

            default:
                break;
        }

        return $folder;
    }

    public static function get_category_child_folder( $item ){

        $folder = '';

        switch( $item->child_type ){

            case 1:
                $folder = 'categories';
                break;

            case 2:
                $folder = 'products';
                break;

            default:
                break;
        }

        return $folder;
    }

    
    
    public static function get_item_anchor( $item ){

        $anchor = '';

        $anchor = $item->child_type.'-'.$item->item_id;

        return $anchor;
    }

    
    
    private static function fix_item_href($href){      
        
        //$href = str_replace("  ", " ", $href);
        //$href = str_replace(" ", "-", $href);
        //$href = str_replace("&", "&amp;", $href);

        //preg_replace//mb_ereg_replace//

        $href = preg_replace( '/\s+/', '-',      $href );
        $href = preg_replace( '/--+/', '-',      $href );
        $href = preg_replace( '/\?+/', '',       $href );
        $href = preg_replace( '/\&/',  '&amp;',  $href );
        $href = strtolower($href);
        //$href = htmlspecialchars($href);
        
        return $href;
    }


    public static function fix_url($href){
        
        $href = preg_replace('/ +/', '-', $href);

        $href = preg_replace('/--+/', '-', $href);

        $href = strtolower($href);

        $href = str_replace("&", "&amp;", $href);
        
        return $href;
    }

}

?>