<?php
    $target            = $data->target;
    //$home_products     = $data->home_products;
    
    $featured_products = $data->featured_products;
    $offer_products    = $data->offer_products;
    $sale_products     = $data->sale_products;
    $recent_products   = $data->recent_products;
?>



<?/*
    $content = Dictionary::get_text_by_lang($target, "content");

<div id="page-info" class="clearfix">
    <? if (  @getimagesize( BASE_URL.'uploads/targets/'. $target->image )  ) { ?>
    <div class="image"><img src="<?= ROOT_URL ?>uploads/targets/<?= $target->image ?>" /></div>
    <? } ?>
    <div class="content"><?= $content ?></div>
</div>

*/?>

<div class="list-title-bar"></div>
<div class="list-title" class="clearfix"><span><?= Dictionary::get_text("Featured_lbl") ?></span></div>

<div id="list" class="clearfix">

    <?
        foreach( $featured_products as $product ){

            $title = Dictionary::get_text_by_lang($product, "title");
            $desc  = Dictionary::get_text_by_lang($product, "desc");

            $href   = UrlUtil::get_product_href($product);
            $folder = 'products';
            
            $icon   = $product->icon;
            
            $sale_price = $product->price - ( $product->price * $product->discount );

    ?>
        <div class="list-item-product clearfix" data-featured="<?= $featured ?>" data-offer="<?= $offer ?>" data-sale="<?= $sale ?>" data-recent="<?= $recent ?>">
            <a    class="icon"  href="<?= $href ?>" title="<?= $title ?>"><img src="<?= ROOT_URL ?>uploads/<?= $folder ?>/<?= $icon ?>" /></a>
            <a    class="title" href="<?= $href ?>" title="<?= $title ?>"><?= $title ?></a>
            <? if( $product->discount > 0 ){ ?>
                <div class="price"><span><?= Dictionary::get_text('Price_lbl') ?>: </span><?= $product->price ?></div>
                <div class="save"><span><?= Dictionary::get_text('Sale_lbl') ?>: </span><?= $sale_price ?></div>
                <div class="off"><?= $product->discount * 100 ?></div>
            <? } else { ?>
                <div class="save"><span><?= Dictionary::get_text('Price_lbl') ?>: </span><?= $product->price ?></div>
            <? } ?>
        </div>
    <? } ?>
    
</div>


<div class="list-title-bar"></div>
<div class="list-title" class="clearfix"><span><?= Dictionary::get_text("Offer_lbl") ?></span></div>
<div id="list" class="clearfix">

    <?
        foreach( $offer_products as $product ){

            $title = Dictionary::get_text_by_lang($product, "title");
            $desc  = Dictionary::get_text_by_lang($product, "desc");

            $href   = UrlUtil::get_product_href($product);
            $folder = 'products';
            
            $icon   = $product->icon;

            $sale_price = $product->price - ( $product->price * $product->discount );
    ?>
        <div class="list-item-product clearfix" data-featured="<?= $featured ?>" data-offer="<?= $offer ?>" data-sale="<?= $sale ?>" data-recent="<?= $recent ?>">
            <a    class="icon"  href="<?= $href ?>" title="<?= $title ?>"><img src="<?= ROOT_URL ?>uploads/<?= $folder ?>/<?= $icon ?>" /></a>
            <a    class="title" href="<?= $href ?>" title="<?= $title ?>"><?= $title ?></a>
            <? if( $product->discount > 0 ){ ?>
                <div class="price"><span><?= Dictionary::get_text('Price_lbl') ?>: </span><?= $product->price ?></div>
                <div class="save"><span><?= Dictionary::get_text('Sale_lbl') ?>: </span><?= $sale_price ?></div>
                <div class="off"><?= $product->discount * 100 ?></div>
            <? } else { ?>
                <div class="save"><span><?= Dictionary::get_text('Price_lbl') ?>: </span><?= $product->price ?></div>
            <? } ?>
        </div>
    <? } ?>
    
</div>

<div class="list-title-bar"></div>
<div class="list-title" class="clearfix"><span><?= Dictionary::get_text("Sale_lbl") ?></span></div>
<div id="list" class="clearfix">

    <?
        foreach( $sale_products as $product ){

            $title = Dictionary::get_text_by_lang($product, "title");
            $desc  = Dictionary::get_text_by_lang($product, "desc");

            $href   = UrlUtil::get_product_href($product);
            $folder = 'products';
            
            $icon   = $product->icon;

            $sale_price = $product->price - ( $product->price * $product->discount );
    ?>
        <div class="list-item-product clearfix" data-featured="<?= $featured ?>" data-offer="<?= $offer ?>" data-sale="<?= $sale ?>" data-recent="<?= $recent ?>">
            <a    class="icon"  href="<?= $href ?>" title="<?= $title ?>"><img src="<?= ROOT_URL ?>uploads/<?= $folder ?>/<?= $icon ?>" /></a>
            <a    class="title" href="<?= $href ?>" title="<?= $title ?>"><?= $title ?></a>
            <? if( $product->discount > 0 ){ ?>
                <div class="price"><span><?= Dictionary::get_text('Price_lbl') ?>: </span><?= $product->price ?></div>
                <div class="save"><span><?= Dictionary::get_text('Sale_lbl') ?>: </span><?= $sale_price ?></div>
                <div class="off"><?= $product->discount * 100 ?></div>
            <? } else { ?>
                <div class="save"><span><?= Dictionary::get_text('Price_lbl') ?>: </span><?= $product->price ?></div>
            <? } ?>
        </div>
    <? } ?>
    
</div>

<div class="list-title-bar"></div>
<div class="list-title" class="clearfix"><span><?= Dictionary::get_text("Recent_lbl") ?></span></div>
<div id="list" class="clearfix">

    <?
        foreach( $recent_products as $product ){

            $title = Dictionary::get_text_by_lang($product, "title");
            $desc  = Dictionary::get_text_by_lang($product, "desc");

            $href   = UrlUtil::get_product_href($product);
            $folder = 'products';
            
            $icon   = $product->icon;
            
            $sale_price = $product->price - ( $product->price * $product->discount );
    ?>
        <div class="list-item-product clearfix" data-featured="<?= $featured ?>" data-offer="<?= $offer ?>" data-sale="<?= $sale ?>" data-recent="<?= $recent ?>">
            <a    class="icon"  href="<?= $href ?>" title="<?= $title ?>"><img src="<?= ROOT_URL ?>uploads/<?= $folder ?>/<?= $icon ?>" /></a>
            <a    class="title" href="<?= $href ?>" title="<?= $title ?>"><?= $title ?></a>
            <? if( $product->discount > 0 ){ ?>
                <div class="price"><span><?= Dictionary::get_text('Price_lbl') ?>: </span><?= $product->price ?></div>
                <div class="save"><span><?= Dictionary::get_text('Sale_lbl') ?>: </span><?= $sale_price ?></div>
                <div class="off"><?= $product->discount * 100 ?></div>
            <? } else { ?>
                <div class="save"><span><?= Dictionary::get_text('Price_lbl') ?>: </span><?= $product->price ?></div>
            <? } ?>
        </div>
    <? } ?>
    
</div>

    
    
<?/*
    $page  =  $data->page;
    $menu  =  $data->menu;
    
    $style =  $data->style;
*/?>

<!--<div id="body" style="<?= $style ?>">

    <form id="verify_form" method="post" action="<?= ROOT_URL ?>certificate" enctype="application/x-www-form-urlencoded">

        <div id="search-box">
            
            <div>Insert Certificate Verification Code (CVC) :</div>

            <input type="text"   class="search_text"   name="cvc" value="">
            <input type="submit" class="search_button" value="Verify">

        </div>

    </form>

    <div id="text">
        <span class="title">Verify using other options</span>
        <br /><br />
        If you do not have your own certificate verification code CVC, you can verify using more verifying option....
        <a href="verify-form">click here</a>
        <br /><br />
        In case your certificate information does not exist in this system, you can contact your education organization.

    </div>

</div>-->



<!--<div id="path">
    <div class="cell">
        <a href="<?= UrlUtil::get_home_href(); ?>">
            <?= Dictionary::get_text("Home_lbl") ?>
        </a> 
    </div>
</div>-->