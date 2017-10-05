<?
    $lang     = $data->lang;

    $path     = $data->section_path;
    $section  = $data->section;
    
    $childs       = $data->childs;
    $result_count = $data->result_count;

    $embeds       = $data->embeds;
    $embeds_count = $data->embeds_count;

?>

<div id="path">
    <div class="cell"> <a href="<?= UrlUtil::get_home_href() ?>"><?= Dictionary::get_text("Home_lbl") ?></a> </div>
    <?
       foreach($path as $pchild){
           $title = Dictionary::get_text_by_lang($pchild, "title");
           $link  = UrlUtil::get_section_child_href( $pchild );
    ?>
    <div class="cell"> <a href="<?= $link ?>"><?= $title ?></a> </div>
    <? } ?>
</div>

<?

    if( $section->show_sub > 0 ){

        $title   = Dictionary::get_text_by_lang($section, "title");
        $content = Dictionary::get_text_by_lang($section, "content");

?>
    <div id="page-info" class="clearfix">
        <? if (  @getimagesize( BASE_URL.'uploads/sections/'. $section->image )  ) { ?>
        <div class="image"><img src="<?= ROOT_URL ?>uploads/sections/<?= $section->image ?>" /></div>
        <? } ?>
        <div class="title"><?= $title ?></div>
        <div class="content"><?= $content ?></div>
    </div>
<?
    }
    
    if( $section->show_menu > 0 ){

        switch( $section->format ){

            case STYLE_DEFAULT:
                get_section_style_default($childs, $result_count);
                break;

            case STYLE_MEDIA:
                get_section_style_media($embeds, $embeds_count);
                break;

            //case STYLE_LISTING2:
            //    get_section_style_style2($childs, $result_count);
            //    break;

            default :
                get_section_style_default($childs, $result_count);
                break;
        }
    }

/* * *************************************************************************** */

function get_section_style_default($childs, $result_count) {
    ?>
    <div id="list" class="clearfix pagination" data-parent="1" data-index="0" data-count="12" data-elem="group1">
    <?
        foreach($childs as $item){

            if( $item->child_type == 3 ) continue;

            $title_long = Dictionary::get_text_by_lang($item, "title");
            //$title = OutputUtil::trim_text( Dictionary::get_text_by_lang($item, "title"), 45 );
            //$desc  = OutputUtil::trim_text( Dictionary::get_text_by_lang($item, "desc"),  75 );
            $title = Dictionary::get_text_by_lang($item, "title");
            $desc  = Dictionary::get_text_by_lang($item, "desc");

            $href   = UrlUtil::get_section_child_href($item);                
            $folder = UrlUtil::get_section_child_folder($item);                

    ?>
        <div class="list-item-page group1">
            <div class="icon"><a href="<?= $href ?>" title="<?= $title_long ?>"><img src="<?= ROOT_URL ?>uploads/<?= $folder ?>/<?= $item->icon ?>" alt="<?= $title ?>" /></a></div>
            <div class="title"><a href="<?= $href ?>" title="<?= $title_long ?>"><?= $title ?></a></div>
            <div class="desc"><?= $desc ?></div>
            <div class="more"><a href="<?= $href ?>" title="<?= $title_long ?>"><?= Dictionary::get_text('ReadMore_lbl'); ?></a></div>
        </div>
        <div class="list-item-page-sep group1"></div>
    <?
        }
    ?>
    </div>

    <div id="results"> <?= $result_count .' '. Dictionary::get_text('Results_lbl') ?> </div>
    <?
}

/* * *************************************************************************** */

function get_section_style_style2($childs, $result_count) {
    ?>
    <div id="list" class="clearfix pagination" data-parent="1" data-index="0" data-count="12" data-elem="group1">
    <?
        foreach($childs as $item){

            if( $item->child_type == 3 ) continue;

            $title_long = Dictionary::get_text_by_lang($item, "title");
            //$title = OutputUtil::trim_text( Dictionary::get_text_by_lang($item, "title"), 45 );
            //$desc  = OutputUtil::trim_text( Dictionary::get_text_by_lang($item, "desc"),  75 );
            $title = Dictionary::get_text_by_lang($item, "title");
            $desc  = Dictionary::get_text_by_lang($item, "desc");

            $href   = UrlUtil::get_section_child_href($item);                
            $folder = UrlUtil::get_section_child_folder($item);                

    ?>
        <div class="list-item-style2 group1">
            <div class="icon"><a href="<?= $href ?>" title="<?= $title_long ?>"><img src="<?= ROOT_URL ?>uploads/<?= $folder ?>/<?= $item->icon ?>" alt="<?= $title ?>" /></a></div>
            <div class="title"><a href="<?= $href ?>" title="<?= $title_long ?>"><?= $title ?></a></div>
            <div class="desc"><?= $desc ?></div>
            <div class="more"><a href="<?= $href ?>" title="<?= $title_long ?>"><?= Dictionary::get_text('ReadMore_lbl'); ?></a></div>
        </div>
        <div class="list-item-style2-sep group1"></div>
    <?
        }
    ?>
    </div>

    <!-- <div id="results"> <?= $result_count .' '. Dictionary::get_text('Results_lbl') ?> </div> -->
    <?
}

/* * *************************************************************************** */

function get_section_style_downloads($embeds, $embeds_count) {
    ?>
    <div id="list" class="clearfix embeds pagination" data-parent="1" data-index="0" data-count="12" data-elem="group1">
    <?
        $i = 0;
        foreach($embeds as $embed){

            if( $embed->child_type != 3 ) continue;

            $title    = Dictionary::get_text_by_lang($embed, "title");
            $filename = UrlUtil::get_embed_file_name( $embed );

            $width  = ( $embed->type == 2 ) ? -1 : 350;
            $height = ( $embed->type == 2 ) ? -1 : 300;
            
            $href   = ROOT_URL . 'uploads/embeds/' . $filename;

    ?>
        <div class="list-item-download group1">
            <div class="icon"><a href="<?= $href ?>" title="<?= $title ?>"><img src="<?= ROOT_URL ?>uploads/embeds/<?= $embed->icon ?>" alt="<?= $title ?>" /></a></div>
            <div class="download" onclick="javascript:window.open('<?= $href ?>');" title="<?= $title ?>"><?= Dictionary::get_text('Download_lbl'); ?></div>
            <div class="title"><a href="<?= $href ?>" title="<?= $title ?>"><?= $title ?></a></div>
        </div>
    <?
            $i++;
        }
    ?>
    </div>

    <!--<div id="results"> <?= $embeds_count .' '. Dictionary::get_text('Results_lbl') ?> </div>-->
    <?
}

/* * *************************************************************************** */

function get_section_style_media($embeds, $embeds_count) {
    ?>
    <div id="list" class="clearfix embeds pagination" data-parent="1" data-index="0" data-count="12" data-elem="group1">
    <?
        $i = 0;
        foreach($embeds as $embed){

            if( $embed->child_type != 3 ) continue;

            $title    = Dictionary::get_text_by_lang($embed, "title");
            $filename = UrlUtil::get_embed_file_name( $embed );

            $width  = ( $embed->type == 2 ) ? -1 : 350;
            $height = ( $embed->type == 2 ) ? -1 : 300;
            
            $href   = ROOT_URL . 'uploads/embeds' . $filename;

    ?>
        <div class="list-item-embed embed group1" data-index="<?= $i ?>" data-type="<?= $embed->type ?>" data-folder="<?= ROOT_URL ?>uploads/embeds" data-file="<?= $filename ?>" data-width="<?= $width ?>" data-height="<?= $height ?>">
            <div class="icon"><a href="<?= $href ?>" title="<?= $title ?>"><img src="<?= ROOT_URL ?>uploads/embeds/<?= $item->icon ?>" alt="<?= $title ?>" /></a></div>
            <div class="title"><a href="<?= $href ?>" title="<?= $title ?>"><?= $title ?></a></div>
        </div>
    <?
            $i++;
        }
    ?>
    </div>

    <div id="results"> <?= $embeds_count .' '. Dictionary::get_text('Results_lbl') ?> </div>
    <?
}
?>
