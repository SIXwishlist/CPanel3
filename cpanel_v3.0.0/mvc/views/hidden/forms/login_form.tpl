<?php ?>

<div class="login_form_tpl">

    <form id="login_form" class="style1" method="post" action="" enctype="application/x-www-form-urlencoded">

        <!--
        <div class="label"><i class="fa fa-user" aria-hidden="true"></i> <?= Dictionary::get_text('LoginForm_Username_lbl') ?></div>
        <input type="text" name="username" value="" />
        -->
        <div class="label"><i class="fa fa-envelope" aria-hidden="true"></i> <?= Dictionary::get_text('RegisterForm_Email_lbl') ?></div>
        <input type="text" name="username" value="" />

        <div class="clearfix"></div>

        <div class="label"><i class="fa fa-lock" aria-hidden="true"></i> <?= Dictionary::get_text('LoginForm_Password_lbl') ?></div>
        <input type="password" name="password" value="" />

        <div class="clearfix"></div>

        <input id="remember" type="checkbox" name="remember" value="1" />
        <label for="remember"><?= Dictionary::get_text('LoginForm_Remeber_lbl') ?></label>

        <div class="clearfix"></div><br />

        <div class="errors alert-error clearfix"></div>
        <div class="capatcha"></div>
        <div class="clearfix"></div>

        <input type="submit" value="<?= Dictionary::get_text('LoginForm_Login_lbl') ?>" />
        <input type="reset"  value="<?= Dictionary::get_text('LoginForm_Reset_lbl') ?>" />

        <div class="clearfix"></div><br />
                
        <div class="note"><?= Dictionary::get_text('LoginForm_ForgetNote_lbl') ?>...<a href="#" onclick="ForgetForm.show_form(); return false;"><?= Dictionary::get_text('LoginForm_ClickHere_lbl') ?></a></div>

    </form>

</div>
