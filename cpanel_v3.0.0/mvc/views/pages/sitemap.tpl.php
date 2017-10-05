<?
    $lang          = $data->lang;

    $section_tree  = $data->section_tree;
    $category_tree = $data->category_tree;
?>

<div id="page-info">

    <div class="title"><?= Dictionary::get_text('SiteMap_lbl'); ?></div>

</div>

<div class="clearfix"></div>

<div id="SiteMap" class="sitemap">

    <ul lang="<?= $lang ?>" class="main <?= $lang ?>">

        <?
            foreach($section_tree as $item){

                if( $item->child_type == 3 ) continue;
                if( $item->menu <= 0 ) continue;
    
                $title = Dictionary::get_text_by_lang($item, "title", false, $lang);
                $href  = UrlUtil::get_section_child_href( $item, $lang );
        ?>
            <li>
                <a href="<?= $href ?>"> <?= $title ?></a>
                <?= get_sitemap_section_child_output($item, 0, $lang); ?>
            </li>
        <?  
            }
        ?>
        
    </ul>

    <ul lang="<?= $lang ?>" class="main <?= $lang ?>">

        <?
            foreach($category_tree as $child){

                //if( $child->menu <= 0 ) continue;
    
                $title = Dictionary::get_text_by_lang($child, "title", false, $lang);
                $href  = UrlUtil::get_category_child_href( $child, $lang );
        ?>
            <li>
                <a href="<?= $href ?>"> <?= $title ?></a>
                <?= get_sitemap_category_child_output($child, 0, $lang); ?>
            </li>
        <?  
            }
        ?>
        
    </ul>
    
</div>

<?

function get_sitemap_section_child_output($item, $level, $lang){

    $output = '';

    if( !empty( $item->childs ) ){

        $sub_items = $item->childs;

        $menu = ( $level > 0 ) ? 'subsub' : 'sub';

        $output .= '<ul class="'.$menu.'">';

        foreach($sub_items as $sub_item){
      
            if( $sub_item->child_type == 3 ) continue;
        
            $title = Dictionary::get_text_by_lang($sub_item, "title", false, $lang);
            $link  = UrlUtil::get_section_child_href($sub_item, $lang);

            $output .= '<li>';

                $output .= '<a href="'.$link.'">' . $title . '</a>';

                if( is_array( $sub_item->childs ) ){
                    $level++;
                    $output .= get_sitemap_item_output( $sub_item, $level, $lang );
                }

            $output .= '</li>';
        }

        $output .= '</ul>';

    }

    return $output;

}

function get_sitemap_category_child_output($child, $level, $lang){

    $output = '';

    if( !empty( $child->childs ) ){

        $sub_childs = $child->childs;

        $menu = ( $level > 0 ) ? 'subsub' : 'sub';

        $output .= '<ul class="'.$menu.'">';

        foreach($sub_childs as $sub_item){
              
            $title = Dictionary::get_text_by_lang($sub_item, "title", false, $lang);
            $link  = UrlUtil::get_category_child_href($sub_item, $lang);

            $output .= '<li>';

                $output .= '<a href="'.$link.'">' . $title . '</a>';

                if( is_array( $sub_item->childs ) ){
                    $level++;
                    $output .= get_sitemap_item_output( $sub_item, $level, $lang );
                }

            $output .= '</li>';
        }

        $output .= '</ul>';

    }

    return $output;

}

?>

<? ?>
