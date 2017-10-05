<?
    $lang            = $data->lang;

    $path            = $data->product_path;
    $product         = $data->product;

    $other_products  = $data->other_products;

    $shots           = $data->shots;
    $shots_count     = $data->shots_count;
?>

<div id="path">
    <?
       foreach($path as $pchild){
           $title = Dictionary::get_text_by_lang($pchild, "title");
           $link  = UrlUtil::get_category_child_href( $pchild );
    ?>
    <div class="cell"> <a href="<?= $link ?>"><?= $title ?></a> </div>
    <? } ?>
</div>


<? if( $product != null ){ 

    $title   =  Dictionary::get_text_by_lang($product, "title");
    $content =  Dictionary::get_text_by_lang($product, "content");

?>

<div id="product-info" class="clearfix">
    
    <div class="grid">

        <div class="cell-x left">

            <div class="title"><?= $title ?></div>

            <div class="content"><?= $content ?></div>
            
            <div class="clearfix"></div>
            
            <div class="grid">

                <? if( $product->discount > 0 ){ ?>

                <div class="cell-x price_label"><?= Dictionary::get_text('Price_lbl') ?>:</div>
                <div class="cell-x price"><?= $product->price ?> JD</div>

                <div class="clearfix"></div>

                <div class="cell-x sale_label"><?= Dictionary::get_text('Sale_lbl') ?>:</div>
                <div class="cell-x sale"><?= $product->price - ( $product->price * $product->discount ) ?> JD</div>

                <div class="clearfix"></div>

                <div class="cell-x save_label"><?= Dictionary::get_text('YouSave_lbl') ?>:</div>
                <div class="cell-x save"> <?= ( $product->price * $product->discount ) ?> JD  ( <?= ( $product->discount * 100 ) ?> %) </div>

                <? } else { ?>

                <div class="cell-x sale_label"><?= Dictionary::get_text('Price_lbl') ?>:</div>
                <div class="cell-x sale"><?= $product->price ?> JD</div>

                <? } ?>
            
            </div>

            <div class="clearfix"><br /></div>

            <div class="cart_controls">

                <input type="hidden" name="product_id"       value="<?= $product->product_id ?>" />
                <input type="hidden" name="price"            value="<?= $product->price ?>" />
                <input type="text"   name="quantity"         value="1" />
                <input type="button" name="add_to_cart"      value="<?= Dictionary::get_text('AddToCart_lbl')?>" />
                <input type="button" name="update_quantity"  value="<?= Dictionary::get_text('UpdateQuantity_lbl')?>" />
                <input type="button" name="remove_from_cart" value="<?= Dictionary::get_text('RemoveFromCart_lbl')?>" />

            </div>

        </div>

        <div class="cell-x right">

            <div class="image"></div>
            
            <div class="clearfix"></div>
            
            <? if( !empty($shots) ){ ?>

            <div class="clearfix shots" data-parent="1" data-index="0" data-count="24" data-elem="group1">
                <?
                $i = 0;
                foreach($shots as $shot){

                    $title    = Dictionary::get_text_by_lang($shot, "title");
                    $filename = $shot->file;

                    $width  = ( $shot->type == 2 ) ? -1 : 350;
                    $height = ( $shot->type == 2 ) ? -1 : 300;
                ?>
                <div id="shot_<?= $shot->shot_id ?>" class="shot group1" data-index="<?= $i ?>" data-type="<?= $shot->type ?>" data-folder="<?= ROOT_URL ?>uploads/shots" data-file="<?= $filename ?>" data-width="<?= $width ?>" data-height="<?= $height ?>">
                    <img src="<?= ROOT_URL ?>uploads/shots/<?= $shot->icon ?>" alt="<?= $title ?>" />
                </div>
                <?
                    $i++;
                }
                ?>
            </div>
            <? } ?>

            <div id="share-page-tools" class="clearfix">

            <!-- AddThis Button BEGIN -->
                <div class="addthis_toolbox addthis_default_style">
                    <a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
                    <a class="addthis_button_tweet"></a>
                    <a class="addthis_button_google_plusone"></a>
                    <a class="addthis_counter addthis_pill_style"></a>
                </div>
                <!--<script type="text/javascript" src="http://s7.addthis.com/js/300/addthis_widget.js#pubid=xa-507949d72df4eb00"></script>-->
            <!-- AddThis Button END -->
            </div>
            
        </div>

    </div>

    <div class="clearfix"></div> 
       
</div>


<? if( !empty($other_products) ){ ?>

<div class="list-title-bar"></div>
<div class="list-title" class="clearfix"><span><?= Dictionary::get_text("OtherProducts_lbl") ?></span></div>

<div id="list" class="clearfix clearfix pagination" data-parent="2" data-index="0" data-count="4" data-elem="group2">

    <?
        foreach( $other_products as $o_product ){
            
            $title = Dictionary::get_text_by_lang($o_product, "title");
            $desc  = Dictionary::get_text_by_lang($o_product, "desc");

            //$href   = UrlUtil::get_category_child_href($product);
            //$folder = UrlUtil::get_category_child_folder($product);
            $href   = UrlUtil::get_product_href($o_product);
            $folder = "products";

            $icon   = $o_product->icon;

            $featured = intval( $o_product->featured );
            $offer    = intval( $o_product->offer    );
            $sale     = intval( $o_product->sale     );
            $recent   = intval( $o_product->recent   );

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

<? } ?>

<?php } ?>
