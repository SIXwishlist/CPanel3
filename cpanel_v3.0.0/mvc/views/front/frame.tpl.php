<?php

    //$page_start     = $data->page_start;
    //$page_end       = $data->page_end;

    $page_content   = $data->page_content;

    $lang_en        = $data->lang_en;
    $lang_ar        = $data->lang_ar;

    $lang           = $data->lang;


    $section_tree   = $data->section_tree;
    $category_tree  = $data->category_tree;
    
    $banners        = $data->banners;
    $banner         = $banners[0];
    
    $slide_mode    = $data->slide_mode;
    
    $ads            = $data->ads;

    $wide_main         = $data->wide_main;
    $sub_category_menu = $data->sub_category_menu;

?>

<?php include(BASE_DIR.'/mvc/views/frame/page_start.tpl'); ?>

<!-- Start of Page Contents -->

<div id="layout">
    
    <div id="head">

        <div id="search">
            <!--<span id="search-label"><?= Dictionary::get_text('Search_lbl') ?></span>-->
            <input id="search-input" type="text" class="search_textfield" name="search_item" value="<?= Dictionary::get_text('Search_lbl') ?>" />
            <a id="search-button"><i class="fa fa-search" aria-hidden="true"></i></a>
        </div>

        <a id="cart" href="<?= UrlUtil::get_cart_href() ?>">
            <span class="cart-items-count">0</span>
            <span class="cart-label"><?= Dictionary::get_text('Cart_lbl') ?></span>
        </a>

        <div id="follow">
            <a class="facebook"  target="_blank" href="http://facebook.com/zadlet/"></a>
            <a class="twitter"   target="_blank" href="http://facebook.com/zadlet/"></a>
            <a class="instegram" target="_blank" href="http://facebook.com/zadlet/"></a>
        </div>

        <div id="logo"><a href="<?= ROOT_URL ?>"><img src="<?= ROOT_URL ?>images/logo.png" /></a></div>

        <div id="languages">
            <a id="english" class="lang_en" href="<?= $lang_en ?>">English</a>
            <span> | </span>
            <a id="arabic"  class="lang_ar" href="<?= $lang_ar ?>">عربي</a>
        </div>

        <div id="sign">
        <!--
            <a href=""> Login </a>
            <span> | </span>
            <a href=""> Sign Up </a>
            <span> | </span>
            <a href=""> Logout </a>
        -->
        </div>

        <div id="head-menu">
            <?= get_head_menu_output($section_tree); ?>
        </div>

        <div id="top-menu" class="top_menu">
            <div id="button"></div>
            <?= get_top_menu_output($category_tree); ?>
        </div>

    </div>            

    <? if( $slide_mode ){ ?>
    <div id="slide">
        <div id="image">
            <!--<img width="960" height="420" src="<?= ROOT_URL ?>images/slide-image.jpg" />-->
        </div>
        <div id="navs">
        <?  
            $i = 0;
            foreach($slides as $slide){

                $slide_title = Dictionary::get_text_by_lang($slide, "title");
                $slide_desc  = Dictionary::get_text_by_lang($slide, "desc");

                $slide_link = Dictionary::get_text_by_lang($slide, "link");
        ?>
                <a id="<?= $slide->slide_id ?>" class="nav" data-index="<?= $i ?>" data-title="<?= $slide_title ?>" data-desc="<?= $slide_desc ?>" data-type="<?= $slide->type ?>" data-file="<?= $slide->file ?>" data-link="<?= $slide_link ?>"></a>
        <?
                $i++;
            }
        ?>
        </div>
    </div>
    <? } ?>

    <div id="body" class="clearfix <?= $wide_main ?>">

        <div id="main-side">
            <?= $page_content; ?>
        </div>
            
        <div id="nav-side">

            <div id="product-options">
                
                <div class="title"><?= Dictionary::get_text("FilterProducts_lbl") ?></div>
                
                <div class="option" data-filter="featured"><?= Dictionary::get_text("Featured_lbl") ?></div>
                
                <div class="option" data-filter="offer"><?= Dictionary::get_text("Offer_lbl") ?></div>
                
                <div class="option" data-filter="sale"><?= Dictionary::get_text("Sale_lbl") ?></div>
                
                <div class="option" data-filter="recent"><?= Dictionary::get_text("Recent_lbl") ?></div>
                
            </div>

            
            <? if( !empty($sub_category_menu) ){ ?>
            <div id="category-products">
                
                <?
                    $title = $sub_category_menu->title;
                    $href  = $sub_category_menu->href;
                ?>
                
                <a class="title" href="<?= $href ?>" title="<?= $title ?>"><?= $title ?></a>
                                
                <?
                    foreach( $sub_category_menu->childs as $child ){

                        if( $child->child_type != 1 ) { continue; }

                        $title = Dictionary::get_text_by_lang($child, "title");
                        $href  = UrlUtil::get_category_child_href($child);
                ?>
                
                <a class="item" href="<?= $href ?>" title="<?= $title ?>"><?= $title ?></a>
                
                <?  } ?>

            </div>

            <? } ?>

            
            <!--<div id="category-products">
                
                <div class="title">Shoes</div>
                
                <div class="item">Winter Shows</div>
                
                <div class="item selected">Summer Shows</div>
                
                <div class="item">Leather</div>
                
                <div class="item">Khochok</div>
                
            </div>-->
            
            
            <div id="ads-area">
                <?= get_ads_output( $ads ); ?>
            </div>

        </div>

    </div>

    <div class="clearfix"></div>
    
</div>

<div id="foot-bg">
    
    <div id="foot">
            
        <div id="foot-menu" class="foot_menu clearfix">
            <?= get_foot_menu_output($category_tree); ?>
        </div>

        <div id="logo">
            <img src="<?= ROOT_URL ?>images/logo-footer.png" />
        </div>

        <div id="copy">
            All rights reserved for ZadLet &copy; 2015, developed by <a title="Arak For Information Technology" href="http://www.arakjo.com/">Arak</a>.
        </div>
        
    </div>
    
    <a class="go-to-top" href="#top">
        <span class="go-to-top-icon"></span>
        <span class="go-to-top-txt">Go to top</span>
    </a>

</div>

<?php include(BASE_DIR.'/mvc/views/frame/page_end.tpl'); ?>

<?

    function get_head_menu_output($section_tree){

        $output = '';

        $i = 0;

        $output .= '';

        foreach($section_tree as $child){

            if( $child->top_menu <= 0 ) continue; 
            //if( $child->child_type == 3 ) continue; 

            $title = Dictionary::get_text_by_lang($child, "title");
            $href  = UrlUtil::get_section_child_href( $child );

            $output .= '<a class="item" href="'.$href.'" title="'. $title .'">'.$title.'</a>';

            $i++;

        }

        $output .= '';


        return $output;

    }

    function get_top_menu_output($category_tree){

        $output = '';

        $i = 0;

        $output .= '<ul class="main">';

        foreach($category_tree as $child){

            if( $child->top_menu <= 0 ) continue; 
            //if( $child->child_type == 3 ) continue; 

            $title = Dictionary::get_text_by_lang($child, "title");
            $href  = UrlUtil::get_category_child_href( $child );

            $output .= '<li>'
                         . '<a class="title" href="'.$href.'" title="'. $title .'">'.$title.'</a>';
            
            if( count($child->childs) > 0 ){
                $output .= get_top_submenu_output($child->childs);
            }

            $output .= '</li>';

            $i++;

        }

        $output .= '</ul>';


        return $output;

    }

    function get_top_submenu_output($category_childs){

        $output = '';

        $i = 0;

        $output .= '<div class="submenu">';

        foreach($category_childs as $child){
            
            if($child->child_type != 1){continue;}
            
            $output .= '<div class="col">';

            $title  = Dictionary::get_text_by_lang($child, "title");
            $href   = UrlUtil::get_category_child_href( $child );
            $folder = UrlUtil::get_category_child_folder( $child );

            $output .= '<a class="icon" href="'.$href.'" title="'. $title .'">'
                        . '<img src="'.ROOT_URL.'uploads/'.$folder.'/'.$child->icon.'" />'
                     . '</a>'
                     . '<a class="title" href="'.$href.'" title="'. $title .'">'.$title.'</a>';

            $j = 0;

            foreach($child->childs as $sub){

                //if($sub->child_type != 1){continue;}
                
                if( $j >= 4 ){
                    $title = Dictionary::get_text_by_lang($child, "title");
                    $href  =  UrlUtil::get_category_child_href( $child );
                    $output .= '<a class="item" href="'.$href.'" title="'. $title .'">'. Dictionary::get_text('More_lbl') .'...</a>';
                    break;
                }

                $title = Dictionary::get_text_by_lang($sub, "title");
                $href  =  UrlUtil::get_category_child_href( $sub );

                $output .= '<a class="item" href="'.$href.'" title="'. $title .'">'.$title.'</a>';

                $j++;

            }
            
            $output .= '</div>';

            $i++;

        }

        $output .= '</div>';

        return $output;

    }

    function get_foot_menu_output($category_tree){

        $output = '';

        $i = 0;

        foreach($category_tree as $item){

            if( $item->foot_menu <= 0 ) continue;
            //if( $item->child_type == 3 ) continue;

            $output .= '<div class="col">';

            $title  = Dictionary::get_text_by_lang($item, "title");
            $href   =  UrlUtil::get_category_child_href( $item );

            $output .= '<a class="title" href="'.$href.'" title="'. $title .'">'.$title.'</a>';

            $j = 0;
            
            foreach($item->childs as $sub){

                //if( $j > 5 ) break;

                $title  = Dictionary::get_text_by_lang($sub, "title");
                $href   =  UrlUtil::get_category_child_href( $sub );

                $output .= '<a class="item" href="'.$href.'" title="'. $title .'">'.$title.'</a>';

                $j++;

            }
            

            $i++;
            
            $output .= '</div>';
        
        }

        return $output;

    }

    function get_ads_output($ads){

        $output = '';

        $i = 0;

        foreach($ads as $ad){

            if( $ad->type == 2 ){
                $width  = -1;
                $height = -1;
            }else{
                $width  = 260;
                $height = 96;
            }
            
            $output .= OutputUtil::get_embed_output( $ad->file, $ad->type, $width, $height, ROOT_URL."uploads/ads", false );

            $i++;
        }

        return $output;

    }

?>