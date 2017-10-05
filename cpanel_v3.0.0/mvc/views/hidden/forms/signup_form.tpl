<?php ?>

<div class="signup_form_tpl">

    <form id="signup_form" class="style1" method="post" action="" enctype="application/x-www-form-urlencoded">

        <div class="label"><i class="fa fa-envelope" aria-hidden="true"></i> <?= Dictionary::get_text('RegisterForm_Email_lbl') ?></div>
        <input type="text" name="email" value="" />

        <div class="clearfix"></div>

        <div class="label"><i class="fa fa-lock" aria-hidden="true"></i> <?= Dictionary::get_text('RegisterForm_Password_lbl') ?></div>
        <input type="password" name="password" value="" />

        <div class="clearfix"></div>

        <div class="label"><i class="fa fa-lock" aria-hidden="true"></i> <?= Dictionary::get_text('RegisterForm_PasswordConfirm_lbl') ?></div>
        <input type="password" name="password_confirm" value="" />

        <div class="clearfix"></div>

        <div class="label"><i class="fa fa-user" aria-hidden="true"></i> <?= Dictionary::get_text('RegisterForm_Name_lbl') ?></div>
        <input type="text" name="username" value="" />

        <div class="clearfix"></div>

        <!--<input id="accept" type="checkbox" name="accept" value="1" />
        <label for="accept"><?= Dictionary::get_text('RegisterForm_Accept_lbl') ?></label>-->

        <div class="clearfix"></div>

        <div class="errors alert-error clearfix"></div>
        <div class="capatcha"></div>
        
        <div class="clearfix"></div><br />

        <input type="submit" value="<?= Dictionary::get_text('RegisterForm_Submit_lbl') ?>" />
        <input type="reset"  value="<?= Dictionary::get_text('RegisterForm_Reset_lbl') ?>" />

        <div class="clearfix"></div>

    </form>

</div>
