<?php ?>

<div class="forget_form_tpl">

    <form id="forget_form" class="style1" method="post" action="" enctype="application/x-www-form-urlencoded">

        <div class="label"><i class="fa fa-user" aria-hidden="true"></i> <?= Dictionary::get_text('ForgetForm_EmailUsername_lbl') ?></div>
        <input type="text" name="username" value="" />

        <div class="clearfix"></div><br />

        <div class="errors alert-error clearfix"></div>
        <div class="capatcha"></div>
        <div class="clearfix"></div>

        <input type="submit" value="<?= Dictionary::get_text('ForgetForm_Submit_lbl') ?>" />
        <input type="reset"  value="<?= Dictionary::get_text('ForgetForm_Reset_lbl') ?>" />

        <div class="clearfix"></div><br />

    </form>

</div>
