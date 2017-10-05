<?
    $lang          = $data->lang;

    $section_tree  = $data->section_tree;
    $category_tree = $data->category_tree;
?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

    <url>
        <loc><?= BASE_URL.'ar' ?></loc>
        <changefreq>daily</changefreq>
        <priority>1.000</priority>
    </url>

    <?= get_sitemap_xml_section_childs_output($section_tree, 0, "ar"); ?>

    <?= get_sitemap_xml_category_childs_output($category_tree, 0, "ar"); ?>


    <url>
        <loc><?= BASE_URL.'en' ?></loc>
        <changefreq>daily</changefreq>
        <priority>1.000</priority>
    </url>

    <?= get_sitemap_xml_section_childs_output($section_tree, 0, "en"); ?>

    <?= get_sitemap_xml_category_childs_output($category_tree, 0, "en"); ?>

</urlset>

<?

function get_sitemap_xml_section_childs_output($items, $level, $lang){

    $output = '';

    if( !empty( $items ) ){

        foreach($items as $item){
      
            if( $item->child_type == 3 ) continue;
            if( $item->menu <= 0 && $item->parent_id <= 0 ) continue;

            $href  = UrlUtil::get_section_child_href($item, $lang);
            $href  = OutputUtil::xmlentities( $href );

            $output .= '<url>'.
                            '<loc>'. $href .'</loc>'.
                            '<changefreq>monthly</changefreq>'.
                            '<priority>0.5000</priority>'.
                       '</url>';

            if( is_array( $item->childs ) ){
                $level++;
                $output .= get_sitemap_xml_item_output( $item->childs, $level, $lang );
            }

        }

    }

    return $output;

}

function get_sitemap_xml_category_childs_output($childs, $level, $lang){

    $output = '';

    if( !empty( $childs ) ){

        foreach($childs as $child){
      
            if( $child->menu <= 0 && $child->parent_id <= 0 ) continue;

            $href  = UrlUtil::get_category_child_href($child, $lang);
            $href  = OutputUtil::xmlentities( $href );

            $output .= '<url>'.
                            '<loc>'. $href .'</loc>'.
                            '<changefreq>monthly</changefreq>'.
                            '<priority>0.5000</priority>'.
                       '</url>';

            if( is_array( $child->childs ) ){
                $level++;
                $output .= get_sitemap_xml_category_childs_output( $child->childs, $level, $lang );
            }

        }

    }

    return $output;

}

?>