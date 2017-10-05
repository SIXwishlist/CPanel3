
<div class="user_preview_tpl">

    <form id="user_preview" class="style1">

        <div class="label"><i class="fa fa-user" aria-hidden="true"></i> <?= Dictionary::get_text('UserForm_FullName_lbl') ?></div>
        <div class="value" name="full_name"></div>

        <div class="clearfix"></div>

        <div class="label"><i class="fa fa-user" aria-hidden="true"></i> <?= Dictionary::get_text('UserForm_Username_lbl') ?></div>
        <div class="value" name="name"></div>

        <div class="clearfix"></div>

        <div class="label"><i class="fa fa-lock" aria-hidden="true"></i> <?= Dictionary::get_text('UserForm_Password_lbl') ?></div>
        <div class="value" name="password"></div>

        <div class="clearfix"></div>

        <div class="label"><i class="fa fa-lock" aria-hidden="true"></i> <?= Dictionary::get_text('UserForm_PasswordConfirm_lbl') ?></div>
        <div class="value" name="password_confirm"></div>

        <div class="clearfix"></div>

        <div class="label"><i class="fa fa-at" aria-hidden="true"></i> <?= Dictionary::get_text('UserForm_Email_lbl') ?></div>
        <div class="value" name="email"></div>
        
        <div style="margin-top: 30px;" class="clearfix"></div>

        <div class="label"><i class="fa fa-lock" aria-hidden="true"></i> <?= Dictionary::get_text('UserForm_RuleId_lbl') ?></div>
        <div class="value" name="rule_id"></div>

        <div class="clearfix"></div>

        <div class="label"    data-group="org"><i class="fa fa-in" aria-hidden="true"></i> <?= Dictionary::get_text('UserForm_OrgId_lbl') ?></div>
        <div class="value" name="org_id" data-group="org"></div>

        <div class="clearfix" data-group="org"></div>

        <div style="margin-top: 20px;" class="clearfix"></div>

        <div class="label"><?= Dictionary::get_text('UserForm_Active_lbl') ?></div>
        <div class="value" name="active"></div>

        <div class="clearfix"></div>

    </form>

</div>
