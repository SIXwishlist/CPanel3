<?php

    $embed    = $data->embed;
    $path     = $data->embed_path;

    $page_url = $data->page_url;

    $title    = Dictionary::get_text_by_lang($embed, "title");
    $link     = UrlUtil::get_section_child_href( $embed );
    
    //$page_url = $link;
?>

<div id="path">
    <?
       foreach($path as $pchild){
           $p_title = Dictionary::get_text_by_lang($pchild, "title");
           $p_link  = UrlUtil::get_section_child_href( $pchild );
    ?>
    <div class="cell"> <a href="<?= $p_link ?>"><?= $p_title ?></a> </div>
    <? } ?> 
</div>
<div id="page-info">

    <div class="title"><?= $title ?></div>
    <div class="embed">

        <div style="text-align: center;">
        <?            
            $title    = Dictionary::get_text_by_lang($embed, "title");
            $filename = UrlUtil::get_embed_file_name( $embed );

            $width  = ( $embed->type == 2 ) ? -1 : 300;
            $height = ( $embed->type == 2 ) ? -1 : 275;

            echo '<div class="embed_elem" id="embed_'.$embed->embed_id.'" data-id="'. $embed->embed_id .'" data-type="'. $embed->type .'" data-folder="uploads/embeds" data-file="'. $filename .'" data-width="'. $width .'" data-height="'. $height .'"></div>';
        ?>
        
        <? /* = OutputUtil::get_embed_output( $embed->file, $embed->type, 600, 450, ROOT_URL."uploads/embeds", false ) */ ?>
        </div>
    </div>

</div>
        