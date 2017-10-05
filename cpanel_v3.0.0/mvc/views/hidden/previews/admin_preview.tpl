<?php ?>

<div class="admin_preview_tpl">
    <div class="preview">
        <div class="form_row">
            <div class="heading"> <?= Dictionary::get_text('AdminForm_Name_lbl'); ?>: </div>
            <div class="data" name="name"></div>
        </div>
        <div class="form_row">
            <div class="heading"> <?= Dictionary::get_text('AdminForm_Password_lbl'); ?>: </div>
            <div class="data" name="password"></div>
        </div>
        <div class="form_row">
            <div class="heading">&nbsp;</div>
            <div class="data">&nbsp;</div>
        </div>
        <!--
        <div class="form_row">
            <div class="heading">&nbsp;</div>
            <div class="data" name="rule_id"></div>
            <div class="data" name="mail"></div>
            <div class="data" name="admin_id"></div>
        </div>
        -->
    </div>

</div>
<?php ?>