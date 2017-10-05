<?php

    $target    = $data->target;

    $title   = Dictionary::get_text_by_lang($target, "title");
    $content = Dictionary::get_text_by_lang($target, "content");

?>

<div id="detail">

    <div id="icon"><img src="<?= ROOT_URL ?>uploads/targets/<?= $target->icon ?>" /></div>
    <div id="title"><?= $title ?></div>

    <div class="clearfix"></div>
    
    <? if (  @getimagesize( BASE_URL.'uploads/targets/'. $target->image )  ) { ?>
    <div id="image"> 
        <img src="<?= ROOT_URL ?>uploads/targets/<?= $target->image ?>" />
    </div>
    <? } ?>

    <div id="content">
        <?= $content ?>
    </div>

</div>
