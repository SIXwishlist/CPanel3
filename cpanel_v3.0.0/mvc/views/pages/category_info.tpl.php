<?
    $lang         = $data->lang;

    $path         = $data->category_path;
    //$category     = $data->category;
    
    $products     = $data->products;
    $result_count = $data->result_count;
    
?>

<? if( !empty($path) ){ ?>
<div id="path" class="path">
<?
    foreach($path as $pcategory){
        $title = Dictionary::get_text_by_lang($pcategory, "title");
        $href  = UrlUtil::get_category_child_href($pcategory);
    ?>
        <div class="cell"><a href="<?= $href ?>"><?= $title ?></a></div>
    <? } ?>
</div>
<? } ?>

<? if( !empty($products) ){ ?>

    <div id="list" class="clearfix clearfix pagination" data-parent="1" data-index="0" data-count="6" data-elem="group1">

        <?
            foreach( $products as $product ){

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

<div id="results"> <?= $result_count .' '. Dictionary::get_text('Results_lbl') ?> </div>
