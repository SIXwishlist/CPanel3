<?php

    $path          = $data->target_path;
    $target        = $data->target;

    $related_pages = $data->related_pages;

?>

<div id="path">
    <div class="cell"> <a href="<?= UrlUtil::get_home_href() ?>"><?= Dictionary::get_text("Home_lbl") ?></a> </div>
<?
    foreach ($path as $pchild) {
        $title = Dictionary::get_text_by_lang($pchild, "title");
        $link = UrlUtil::get_section_child_href($pchild);
?>
        <div class="cell"> <a href="<?= $link ?>"><?= $title ?></a> </div>
    <? } ?>
</div>

<?
switch ($target->style) {


    case STYLE_DEFAULT:
        get_page_style_default($target);
        break;

    case STYLE_ABOUT:
        get_page_style_about($target, $related_pages);
        break;
    
    default :
        get_page_style_default($target);
        break;
}

/* * *************************************************************************** */

function get_page_style_default($target) {

    $title   = Dictionary::get_text_by_lang($target, "title");
    $content = Dictionary::get_text_by_lang($target, "content");
?>
    <div id="page-info" class="clearfix">
        <? if (  @getimagesize( BASE_URL.'uploads/targets/'. $target->image )  ) { ?>
        <div class="image"><img src="<?= ROOT_URL ?>uploads/targets/<?= $target->image ?>" /></div>
        <? } ?>
        <div class="title"><?= $title ?></div>
        <div class="content"><?= $content ?></div>
    </div>
<?
}

/* * *************************************************************************** */

function get_page_style_about($target, $related_pages) {

    $title   = Dictionary::get_text_by_lang($target, "title");
    $content = Dictionary::get_text_by_lang($target, "content");
    ?>
    <div id="page-about" class="clearfix">
        <ul class="related-pages">
        <?
        foreach ($related_pages as $page) {

            if ($page->child_type == 3) continue;
            $page_title = Dictionary::get_text_by_lang($page, "title");
            $page_href = UrlUtil::get_section_child_href($page);
        ?>
            <li><a href="<?= $page_href ?>" title="<?= $page_title ?>"><?= $page_title ?></a></li>
        <?
        }
        ?>
        </ul>
        <div class="page-info">
            <div class="image"><img src="<?= ROOT_URL ?>uploads/targets/<?= $target->image ?>"></div>
            <div class="title"><?= $title ?></div>
            <div class="content"><?= $content ?></div>
        </div>
    </div>

    <?
}
