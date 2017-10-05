<?php ?>

<div class="contact_us_form_tpl">

    <div id="error_cell" class="error"></div>

    <form id="contact_us_form" action='' method="post" enctype="application/x-www-form-urlencoded">

        <div class="form">
            
            <div class="form_row">
                <div class="heading"> <?= Dictionary::get_text('ContactUsForm_Name_lbl'); ?><span class="required">*</span></div>
                <div class="data">
                    <input name="name" type="text" class="input_data" value="" />
                </div>
            </div>

            <div class="form_row">
                <div class="heading"> <?= Dictionary::get_text('ContactUsForm_Email_lbl'); ?><span class="required">*</span></div>
                <div class="data">
                    <input name="email" type="text" class="input_data" value="" />
                </div>
            </div>

            <div class="form_row">
                <div class="heading"> <?= Dictionary::get_text('ContactUsForm_Text_lbl'); ?><span class="required">*</span></div>
                <div class="data">
                    <textarea name="text" class="text_data" cols="40" rows="3"></textarea>
                </div>
            </div>

            
            <div class="form_row">
                <div class="heading">&nbsp;</div>
                <div class="data">
                    <img id="captcha" src="captcha.php"   />
                    <br /><br />
                    <span class="note"><?= Dictionary::get_text('CantRead_lbl'); ?> <a class="here" href="javascript:reloadCaptcha();"><?= Dictionary::get_text('Here_lbl'); ?></a>.</span>
                </div>
            </div>

            <div class="form_row">
                <div class="heading">&nbsp;</div>
                <div class="data">
                    <input type="text" name="captcha_text" />
                    <br />
                    <span class="note"><?= Dictionary::get_text('ContactUsForm_Captcha_lbl'); ?><span class="required">*</span>
                </div>
            </div>

            <br />

            <div class="form_row">
                <div class="heading">&nbsp;</div>
                <div class="data">
                    <!--<input type='submit' name='submit' value='< ?= Dictionary::get_text('Submit_lbl'); ?>' />-->
                    <input type='button' name='button' class="button" value='<?= Dictionary::get_text('Submit_lbl'); ?>' />
                    <input type='reset'  name='reset'  class="button" value='<?= Dictionary::get_text('Reset_lbl'); ?>'  />
                </div>
            </div>

        </div>

    </form>

</div>

<?php ?>