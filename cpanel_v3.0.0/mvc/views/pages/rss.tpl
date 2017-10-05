<?
    $lang         = $data->lang;    
    $items        = $data->items;
?>

<rss version="2.0">
    <channel>
        <title><?= BASE_URL ?></title>
        <link><?= WEBSITE_NAME ?></link>
        <description><?= MATA_TAG_DESCRIPTION ?></description>
        <language><?= $lang ?></language>
        <copyright>Copyright (C) 2013 layyous.com</copyright>

<?= get_rss_item_output($items, 0); ?>

    </channel>
</rss>

<?

function get_rss_item_output($items, $grade){

    $output = '';
    
    if( !empty( $items ) ){

        $menu = ( $grade > 0 ) ? 'subsub' : 'sub';

        foreach($items as $item){

            if( $item->child_type == 3 ) continue;
            if( $item->menu <= 0 ) continue;
        
            $title = Dictionary::get_text_by_lang($item, "title");
            $desc  = Dictionary::get_text_by_lang($item, "desc");

            $title = trim( preg_replace('/&+/', '', $title) );
            $desc  = trim( preg_replace('/&+/', '', $desc ) );

            $title = trim( preg_replace('/\s+/', ' ', $title) );
            $desc  = trim( preg_replace('/\s+/', ' ', $desc ) );
            
            $href  = UrlUtil::get_section_child_href( $item );
            
            $href  = str_replace('&amp;', 'and', $href );
            $href  = str_replace('&', 'and', $href );

            $date  = date("D, d M Y h:i:s A");
?>
        <item>
            <title><?= $title ?></title>
            <description><?= $desc ?></description>
            <link><?= $href ?></link>
            <pubDate><?= $date ?></pubDate>
        </item>
<?  
            if( !empty( $item->childs ) ){
                $new_grade = $grade + 1;
                $output .= get_rss_item_output( $item->items, $new_grade);
            }
        }
    }
}

?>