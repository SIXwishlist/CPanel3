<?
    $lang          = $data->lang;

    $search_item   = $data->search_item;

    $items         = $data->items;
    $result_count  = $data->result_count;
?>

<div class="clearfix"></div>

<? if( $result_count > 0 ){ ?>

    <div id="search-results-label"> <?= Dictionary::get_text('SearchResults_lbl') .': '. $search_item  ?> </div>

    <div class="clearfix pagination" data-parent="1" data-count="6" data-elem="group1">
    <? 
        if( !empty($items) ){
        
            foreach($items as $item){

                if($item->src == 1){
                
                    $title  = Dictionary::get_text_by_lang($item, "title");
                    $desc   = Dictionary::get_text_by_lang($item, "desc");

                    $href   = UrlUtil::get_section_child_href($item);                
                    $folder = UrlUtil::get_section_child_folder($item);
                    
                }else{
                
                    $title  = Dictionary::get_text_by_lang($item, "title");
                    $desc   = Dictionary::get_text_by_lang($item, "desc");

                    $href   = UrlUtil::get_category_child_href($item);                
                    $folder = UrlUtil::get_category_child_folder($item);
                    
                }
    ?>
            <div class="search-item group1"><a href="<?= $href ?>"><?= $title ?></a></div> 
    <?
            }
        }
    ?>
    </div>
    
    <div id="results"> <?= $result_count .' '. Dictionary::get_text('Results_lbl') ?> </div>

<? } else { ?>

    <div style="text-align: center;">
        <div class="feedback">
            <?= Dictionary::get_text('SearchNoResults_lbl')?>
            <br />
            <?= Dictionary::get_text('GoogleSearch_lbl')?>: <a href="https://www.google.com/search?q=<?= $search_item ?>&sitesearch=<?= BASE_URL ?>"><?= $search_item ?></a>
        </div>
    </div>

<? } ?>

<div class="clearfix"></div>
