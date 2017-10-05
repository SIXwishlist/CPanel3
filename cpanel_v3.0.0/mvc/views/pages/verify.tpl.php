<?php
    $status = $data->status;
?>

<div id="form-box" class="clearfix"> 

    <!--<div id="title">
        <i class="fa fa-pencil" aria-hidden="true"></i>
        <?= Dictionary::get_text("RegisterVerify_MainTitle_lbl") ?>
    </div>-->

    <div class="clearfix"><br /><br /></div>
    
    <? if( $status > 0 ){ ?>
        <div class="alert alert-success" style="display: inline-block;">
            <i class="fa fa-check-circle-o" aria-hidden="true"></i>
            <div class="title"><?= Dictionary::get_text("RegisterVerifyCallback_Success_Title_lbl") ?></div>
            <br />
            <?= Dictionary::get_text("RegisterVerifyCallback_Success_Message_lbl") ?>
        </div>
    
        <script type="text/javascript">

            window.setTimeout(function(){ 
                window.location = UrlUtil.get_home_href();
            }, 10000);

        </script>
    
    <? } else { ?>
        <div class="alert alert-error">
            <i class="fa fa-times-circle-o" aria-hidden="true"></i>
            <div class="title"><?= Dictionary::get_text("RegisterVerifyCallback_Failure_Title_lbl") ?></div>
            <br />
            <?= Dictionary::get_text("RegisterVerifyCallback_Failure_Message_lbl") ?>
        </div>
    <? } ?>
    
</div>

<div class="clearfix"><br /><br /></div>

<div class="clearfix"><br /><br /></div>
                