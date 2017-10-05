<?php ?>

<div class="reset_form_tpl">

    <form id="reset_form" class="style1" method="post" action="" enctype="application/x-www-form-urlencoded">

        <div class="label"><i class="fa fa-lock" aria-hidden="true"></i> <?= Dictionary::get_text('ResetForm_NewPassword_lbl') ?></div>
        <input type="password" name="new_password" value="" />

        <div class="clearfix"></div>

        <div class="label"><i class="fa fa-lock" aria-hidden="true"></i> <?= Dictionary::get_text('ResetForm_NewPasswordConfirm_lbl') ?></div>
        <input type="password" name="new_password_confirm" value="" />

        <div class="clearfix"><br /></div>

        <div class="errors alert-error clearfix"></div>
        <div class="capatcha"></div>
        <div class="clearfix"></div>
        
        <input type="hidden" name="user_id"    value="" />
        <input type="hidden" name="user_key"   value="" />

        <input type="submit" value="<?= Dictionary::get_text('ResetForm_Submit_lbl') ?>" />
        <input type="reset"  value="<?= Dictionary::get_text('ResetForm_Reset_lbl') ?>" />

        <div class="clearfix"></div>

    </form>

</div>

<div class="clearfix"><br /><br /></div>
