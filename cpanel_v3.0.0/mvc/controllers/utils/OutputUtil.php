<?php
/*
 *
 */

/**
 * Description of OutputUtil
 *
 * @author Ahmad
 */
class OutputUtil {

    //public static $OPTION  = 0;

    private function OutputUtil(){
    }

    public static function replace_label_with_link( $label, $link, $text){

        $index1 = 0;
        $index2 = 0;
        
        $reach_end = false;

        $replaced_text = '';
        
        $index1 = 0;
        $index2 = 0;

        while ( !$reach_end ) {
            
            //$sub = get_text($index1, $index2);
            
            $index2 = strpos($text, "<a", $index1);
                      
            if( !$index2 ){
                $index2 = strlen($text);
                $reach_end = true;
            }
            
            $sub_text = substr($text, $index1, $index2-$index1);
            $sub_text = str_replace($label, $link, $sub_text);
            $replaced_text .= $sub_text;
            
            $index1 = strpos($text, "</a>", $index2);
            
            if( !$reach_end ){
                $sub_text = substr($text, $index2, ($index1+4)-$index2);
                $replaced_text .= $sub_text;
            }
            
        }
        
        return $replaced_text;
    }


    private static function cmp_val($a,$b){
        return strlen($b)-strlen($a);
    }

    private static function cmp_key($a, $b){
        if (strlen($a) == strlen($b))
            return 0;
        if (strlen($a) > strlen($b))
            return 1;
        return -1;
    }

    private static function cmp_key_new($a, $b){

        $lang = Dictionary::get_language();
        
        $v1 = (array) $a;
        $v2 = (array) $b;

        if (strlen($v1['title_'.$lang]) == strlen($v2['title_'.$lang]))
            return 0;
        if (strlen($v1['title_'.$lang]) > strlen($v2['title_'.$lang]))
            return -1;

        return 1;
    }

//
//    private static function get_sorted_titles($items){
//
//        uksort($items, "cmp");
//        usort($items,'sort');
//        
//        return $items;
//    }

    public static function convert_to_1d_array($items, &$newarray){

        if( !empty( $items ) ){

            foreach($items as $item){
                $newarray[] = $item;
                
                if( !empty($item->items) ){
                    convert_to_1d_array($item->items, $newarray);
                }
            }

        }
    }

    public static function apply_auto_links($items, $text){
        return $text;
    }
    
    //@deprecated
    public static function apply_auto_links_2($items, $text){
        

        $replced_text = $text;
        
        $lang   = Dictionary::get_language();
        
        $items_1d = array();
        
        convert_to_1d_array($items, $items_1d);
        
        //uksort($title_array, "cmp_key");
        uasort($items_1d, "cmp_key_new");

        if( !empty( $items_1d ) ){

            foreach($items_1d as $item){

                $title = Dictionary::get_text_by_lang($item, "title");
                $href  = UrlUtil::get_section_child_href($item);
                
                $anchor = '<a href="'.$href.'">'.$title.'</a>';

                //$search  = $title;
                /*$pattern = "(?!<a[^>]*>)($title)(?![^<]*<\/a>) ";*/
                /*$pattern = '(?!<a[^>]*>)('.$title.')(?![^<]*<\/a>)';*/

                $pattern = '(?!<a[^>]*>)('.$title.')(?![^<]*<\/a>)';
                $regex   = '/'.$pattern.'/si';
                
                $replace = $anchor;

                $text = replace_label_with_link($title, $replace, $text); 
                //$text = str_replace($search, $replace, $text); 
                //preg_replace($regex, $replace, $text);
                //$replced_text  = preg_replace($regex, $replace, $text);

                $replced_text = $text;
            }

        }
        
        return $replced_text;
        
    }

    
    // XML Entity Mandatory Escape Characters
    public static function xmlentities($string) {
        return str_replace ( array ( '&', '"', "'", '<', '>', '�' ), array ( '&amp;' , '&quot;', '&apos;' , '&lt;' , '&gt;', '&apos;' ), $string );
    } 
    


    public static function trim_text($string, $max_chars) {
        $string = trim($string);
        //$truncated = (strlen($string) > $max_chars) ? substr($string, 0, $max_chars-2) . '...' : $string;
        $truncated = (strlen($string) > $max_chars) ? mb_substr($string, 0, $max_chars-3, 'utf-8') . '...' : $string;
        return $truncated;
    }
    

    public static function get_pagination_html_output($link, $result_count, $index, $count, $group_count){

        //echo( $link+", "+$result_count+", "+$index+", "+$count+", "+$group_count );
        $output = '';

        $start = intval( $index / $group_count ) * $group_count;

        $output .= '<div class="clearfix"></div>';

        $output .= '<div id="Pagination" class="pagination">';

            $output .= '<div id="Links">';

                if($start >= $group_count){
                    $output .= '<a href="'.$link.'/index='.($start-$group_count).'"> &laquo; </a>';
                }
                //if($index > 0){
                //    $output .= '<a href="'.$link.'/index='.($index-$count).'">' . Dictionary::get_text('Back_lbl'') . '</a>';
                //}
                for($i=$start; $i<$result_count && $i<$start+$group_count; $i+=$count ){
                    $output .= '<a href="'.$link.'/index='.$i.'"> '.(intval($i/$count)+1).' </a>';
                }
                //if( $index+$count < $result_count){
                //    $output .= '<a href="'.$link.'/index='.($index+$count).'">' . Dictionary::get_text('Next_lbl'') . '</a>';
                //}
                if( $start+$group_count <= $result_count){
                    $output .= '<a href="'.$link.'/index='.($start+$group_count).'"> &raquo; </a>';
                }

            $output .= '</div>';

            $output .= '<div id="Text"> &nbsp; ' . $result_count . ' ' . Dictionary::get_text('Results_lbl') . '</div>';

        $output .= '</div>';

        return $output;
    }

    


    public static function get_embed_output( $file, $type, $width, $height, $folder, $autoplay=false, $params ){

        $output = '';

        switch( $type ){

            case FILE_TYPE_DOWNLOAD :
                $output = self::get_download_embed($file);
                break;

            case FILE_TYPE_IMAGE    :
                $output = self::get_image_embed($folder.'/'.$file, $width, $height);
                break;

            case FILE_TYPE_FLASH    :
                $output = self::get_flash_embed($folder.'/'.$file, $width, $height, $params);
                break;

            case FILE_TYPE_SOUND    :
                $output = self::get_sound_embed($folder.'/'.$file, $width, $height, $autoplay);
                break;

            case FILE_TYPE_VIDEO    :
                $output = self::get_jsplayer_video_embed($file, $width, $height, $folder, $thumb, $autoplay);
                //$output = get_jwplayer_video_embed($file, $width, $height, $folder, $thumb, $autoplay);
                //$output = get_video_embed($file, $width, $height, $folder, $autoplay);
                break;

            case FILE_TYPE_YOUTUBE  :
                $output = self::get_youtube_video_embed($file, $width, $height, $autoplay);
                break;

            case FILE_TYPE_VIMEO  :
                $output = self::get_vimeo_video_embed($file, $width, $height, $autoplay);
                break;

            case FILE_TYPE_EMBED_CODE  :
                $output = $file;
                break;

            case FILE_TYPE_UNKNOWN  :
                break;

            default:
                break;
        }

        return $output;
    }


    public static function get_image_embed($filePath, $width, $height){

        $output = '';

        if( $width > 10 && $height > 10 ){
            $output .= '<img src="' . $filePath . '" alt="" width="' . $width . '" height="' . $height . '"   />';
        }else{
            $output .= '<img src="' . $filePath . '" alt="" />';
        }

        return $output;
    }

    public static function get_flash_embed($filePath, $width, $height, $params){

        $output = '';

        //alert(wmode);

        $output .= '<embed src="' . $filePath . '"'
                    .  'type="application/x-shockwave-flash" '
                    .  'allowfullscreen="true" '
                    .  'allowscriptaccess="always" ';

        if( $params != null ){
            for($i=0; $i<$params.length; $i++){
                $param = $params[$i];
                $output .= $param.name . '="' + $param.value . '" ';
                //alert( $param.name . '="' + $param.value . '" ' );
            }
        }

        $output .= 'width="' . $width . '" height="' . $height . '">'
            .'</embed>';

        return $output;
    }

    public static function get_sound_embed($filePath, $width, $height, $autoplay){

        $output = '';

        //<audio src="sounds/ufc.mp3" autoplay="autoplay" preload="auto"></audio>
        
        //$output .= '<embed height="'.$height.'" align="middle" width="'.$width.'" wmode="opaque" type="application/x-shockwave-flash" flashvars="ampplayerID=1&amp;soundFile=' . filePath . '" salign="" allowscriptaccess="sameDomain" allowfullscreen="true" menu="true" name="player" bgcolor="#FFF" devicefont="false" wmode="opaque" scale="showall" loop="true" play="true" pluginspage="http://www.macromedia.com/go/getflashplayer" quality="high" src="'.prePath.'players/soundplayer.swf">';
        $output .= '<embed height="40" align="middle" width="350" wmode="opaque" type="application/x-shockwave-flash" flashvars="ampplayerID=1&amp;soundFile=' . $filePath . '" salign="" allowscriptaccess="sameDomain" allowfullscreen="true" menu="true" name="player" bgcolor="#FFF" devicefont="false" wmode="opaque" scale="showall" loop="true" play="true" pluginspage="http://www.macromedia.com/go/getflashplayer" quality="high" src="players/soundplayer.swf">';

        //var $id   = file.substring( file.lastIndexOf("_")+1, file.length );
        //var $name = file.substring( 0, file.lastIndexOf("_") );

        $output .= '<iframe width="'.$width.'" height="'.$height.'" scrolling="no" frameborder="0" src="'+ROOT_URL+'audio.php?id='.$id.'&name='.$name.'&folder='.$folder.'&width='.$width.'&height='.$height.'">';

        return $output;
    }

    public static function get_video_embed($file, $width, $height, $folder_path, $thumb, $autoplay){

        $output = '';

        $baseUrl = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        //$config = '{controlBarBackgroundColor:\'0x623a24\',loop:false,baseURL:\''.baseUrl.'/'.folder_path.'/\',showVolumeSlider:true,controlBarGloss:\'high\',playList:[{url:\'' . file . '\'}],showPlayListButtons:true,usePlayOverlay:false,menuItems:[false,false,false,false,true,true,false],initialScale:\'fit\',autoPlay:false,autoBuffering:true,showMenu:true,showMuteVolumeButton:true,showFullScreenButton:true,embedded:true}';
        //$output .= '<embed src="players/FlowPlayerLight.swf?config='. escape(config) .'" width="' . width . '" height="' . height . '" wmode="opaque" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" />';

        $thumbUrl = $baseUrl.'/'+$folder_path.'/'+$thumb;
        $videoUrl = $baseUrl.'/'+$folder_path.'/'+$file;

        $output .= '<object width="' . $width . '" height="' . $height . '" name="video_1" id="video_1" data="players/MediaPlayback.swf" type="application/x-shockwave-flash">' . 
                    '<$param name="movie"             value="players/MediaPlayback.swf"/>' . 
                    '<$param name="flashvars"         value="readyFunction=_V_.flash.onReady&amp;eventProxyFunction=_V_.flash.onEvent&amp;errorEventProxyFunction=_V_.flash.onError&amp;autoplay=false&amp;preload=auto&amp;loop=false&amp;muted=false&amp;poster='.urlencode($thumbUrl).'&amp;src='.urlencode($videoUrl).'&amp;" />' . 
                    '<$param name="allowScriptAccess" value="always"  />' . 
                    '<$param name="allowNetworking"   value="all"     />' . 
                    '<$param name="allowfullscreen"   value="true"    />' . 
                    '<$param name="wmode"             value="opaque"  />' . 
                    '<$param name="bgcolor"           value="#000000" />' . 
                '</object>';

        return $output;
    }

    public static function get_jwplayer_video_embed($file, $width, $height, $folder, $thumb, $autoplay){

        $output = '';
        
        $autoplay_val = ($autoplay==true) ? 1 : 0; 

        $output .= '<div id="jwPlayerVideo" file="'.$file.'" autoplay="'.$autoplay_val.'" width="'.$width.'" height="'.$height.'" folder="'.$folder.'" thumb="'.$thumb.'">Loading the player...</div>';

        return $output;

    }

    public static function get_jsplayer_video_embed($file, $width, $height, $folder, $thumb, $autoplay){

        $output = '';

        $autoplay_val = ($autoplay==true) ? 'autoplay' : ''; 
        
        $baseUrl = "http://" . $_SERVER['HTTP_HOST'];// . $_SERVER['REQUEST_URI'];

        //$thumbUrl = $baseUrl.'/'.$folder.'/'.$thumb;
        //$videoUrl = $baseUrl.'/'.$folder.'/'.$file;

        $thumbUrl = $folder.'/'.$thumb;
        $videoUrl = $folder.'/'.$file;

        $output = '<video id="video_'.$file.'" class="video-js vjs-default-skin vjs-big-play-centered" '.$autoplay_val.' controls preload="none" '
                       .' width="'.$width.'" height="'.$height.'" '
                       .' poster="'.$thumbUrl.'" '
                       .' data-setup="{}">'
                    .'<source src="'.$videoUrl.'.mp4"  type="video/mp4"  />'
                    .'<source src="'.$videoUrl.'.webm" type="video/webm" />'
                    .'<source src="'.$videoUrl.'.ogv"  type="video/ogg"  />'
                    //'<track kind="captions" src="captions.vtt" srclang="en" label="English" />'
                 .'</video>';
        
        return $output;

    }

    public static function get_youtube_video_embed($youtubeID, $width, $height, $autoplay){

        $output = '';

        $autoplay_val = ($autoplay==true) ? 1 : 0; 
        
        //$youtubeID = get_youtube_id( ytUrl );
        $output .= '<iframe width="'.$width.'" height="'.$height.'" src="http://www.youtube.com/embed/'.$youtubeID.'?rel=0&amp;autoplay='.$autoplay_val.'"  allowfullscreen></iframe>';
        //http://www.youtube.com/v/+youtubeID

        return $output;
    }

    public static function get_vimeo_video_embed($vimeoID, $width, $height, $autoplay){

        $output = '';

        $autoplay_val = ($autoplay==true) ? 1 : 0; 
        
        $output .= '<iframe src="http://player.vimeo.com/video/'.$vimeoID.'?title=0&amp;byline=0&amp;portrait=0&amp;autoplay='.$autoplay_val.'" width="'.$width.'" height="'.$height.'"  webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';

        return $output;
    }

    public static function get_download_embed($filePath){

        $output = '';

        $output .= '<div><br /><a href="' . $filePath . '">' + get_dictionary_text("Download_lbl") . '</a><br /></div>';

        return $output;
    }

}
?>
