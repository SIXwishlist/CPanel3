<?
    $lang         = $data->lang;

    $cart_items   = $data->cart_items;
    $total_items  = $data->total_items;
    $total_price  = $data->total_price;

    $cart_products = $data->cart_products;
    
?>

<h1><?= Dictionary::get_text('ShoppingCart_lbl') ?></h1>

<? if( !empty($cart_products) ){ ?>

    <div id="cart_view" class="grid clearfix">

        <div id="cart_items" class="cell-x clearfix">

            <div class="item_label cell-x"><?=  Dictionary::get_text('CartItemsList_Item_lbl') ?></div>
            <div class="price_label cell-x"><?=  Dictionary::get_text('CartItemsList_Price_lbl') ?></div>
            <div class="quantity_label cell-x"><?= Dictionary::get_text('CartItemsList_Quantity_lbl') ?></div>

            <?
                foreach( $cart_products as $product ){

                    $title = Dictionary::get_text_by_lang($product, "title");
                    $desc  = Dictionary::get_text_by_lang($product, "desc");

                    //$href   = UrlUtil::get_category_child_href($product);
                    //$folder = UrlUtil::get_category_child_folder($product);
                    $href   = UrlUtil::get_product_href($product);
                    $folder = "products";

                    $icon   = $product->icon;

                    $featured = intval( $product->featured );
                    $offer    = intval( $product->offer    );
                    $sale     = intval( $product->sale     );
                    $recent   = intval( $product->recent   );

                    $sale_price = $product->price - ( $product->price * $product->discount );
                    //$you_save   = ( $product->price * $product->discount );
                    //$save_percentage = ( $product->discount * 100 ) .' %';

            ?>
                <div class="cart-item grid clearfix" data-featured="<?= $featured ?>" data-offer="<?= $offer ?>" data-sale="<?= $sale ?>" data-recent="<?= $recent ?>">
                    <div class="icon cell-x"><img src="<?= ROOT_URL ?>uploads/<?= $folder ?>/<?= $icon ?>" /></div>
                    <div class="cell-x">
                        <div class="title"><?= $title ?></div>
                        <div class="desc"><?= $desc ?></div>
                    </div>
                    <span class="price cell-x"><?= $sale_price ?> JD</span>
                    <span class="quantity cell-x"><?= $product->quantity ?></span>
                </div>
            <? } ?>

            <div class="subtotal"><?=  Dictionary::get_text('CartItemsList_Subtotal_lbl') ?> (<?= $total_items ?> <?=  Dictionary::get_text('CartItemsList_Items_lbl') ?>): <span class="subtotal_price"><?= $total_price ?> JD</span></div>

        </div>

        <div id="proceed_checkout" class="cell-x">

            <div class="total_items"><?=  Dictionary::get_text('CartItemsList_Subtotal_lbl') ?> (<?= $total_items ?> <?=  Dictionary::get_text('CartItemsList_Items_lbl') ?>): <span class="subtotal_price"><?= $total_price ?> JD</span></div>

            <input type="button" name="proceed_checkout" value="<?= Dictionary::get_text('CartItemsList_ProceedToCheckout_lbl')?>" />
            
        </div>

    </div>

<? } ?>

<div class="clearfix"><br /> <br /> <br /></div>
