
<div class="organization_profile_preview_tpl">

    <h1><?= Dictionary::get_text('OrganizationForm_MainLabel_lbl') ?></h1>

    <form id="organization_profile_preview" class="style1">

        <div class="grid">
            <div class="cell-x2">

                <div class="clearfix"></div>

                <div class="label"><i class="fa fa-building" aria-hidden="true"></i> <?= Dictionary::get_text('OrganizationForm_Name_lbl') ?></div>
                <div class="value" name="name"></div>

                <div class="clearfix"></div>

                <div class="label"><i class="fa fa-user" aria-hidden="true"></i> <?= Dictionary::get_text('OrganizationForm_Username_lbl') ?></div>
                <div class="value" name="username"></div>

                <div class="clearfix"></div>

                <div class="label"><i class="fa fa-at" aria-hidden="true"></i> <?= Dictionary::get_text('OrganizationForm_Email_lbl') ?></div>
                <div class="value" name="email"></div>

                <div class="clearfix"></div>

                <div class="label"><i class="fa fa-lock" aria-hidden="true"></i> <?= Dictionary::get_text('OrganizationForm_Password_lbl') ?></div>
                <div class="value" name="password"></div>

                <div class="clearfix"></div>

            </div>
            <div class="cell-x2">

                <div class="label"><i class="fa fa-upload" aria-hidden="true"></i> <?= Dictionary::get_text('OrganizationForm_TradeLicenseImage_lbl') ?> </div>
                <div class="value clearfix" name="license_image"></div>

                <div class="clearfix" style="margin-top: 40px"></div><br />

                <div class="label"><i class="fa fa-graduation-cap" aria-hidden="true"></i> <?= Dictionary::get_text('OrganizationForm_Type_lbl') ?> </div>
                <div class="value" name="type"></div>

            </div>
        </div>

        <br /><br />
        
        
        <div class="grid">
            <div class="cell-x2">

                <div class="label"><i class="fa fa-flag" aria-hidden="true"></i> <?= Dictionary::get_text('OrganizationForm_Country_lbl') ?></div>
                <div class="value" name="country"></div>

                <div class="clearfix"></div>

            </div>
            <div class="cell-x2">

                <div class="label"><i class="ion-pound"></i> <?= Dictionary::get_text('OrganizationForm_Status_lbl') ?></div>
                <div class="value" name="status"></div>

                <div class="clearfix"></div>

            </div>
        </div>
        
        <hr />

        <div class="grid">
            <div class="cell-x2">

                <div class="label"><i class="fa fa-calendar" aria-hidden="true"></i> <?= Dictionary::get_text('OrganizationForm_CreationDate_lbl') ?></div>
                <div class="value" name="creation_date"></div>

                <div class="clearfix"></div>

            </div>
            <div class="cell-x2">

                <div class="label"><i class="fa fa-calendar" aria-hidden="true"></i> <?= Dictionary::get_text('OrganizationForm_ExpirationDate_lbl') ?></div>
                <div class="value" name="expiration_date"></div>

                <div class="clearfix"></div>

            </div>
        </div>

        <div class="label"><i class="fa fa-location-arrow" aria-hidden="true"></i> <?= Dictionary::get_text('OrganizationForm_IP_lbl') ?></div>
        <div class="value" name="ip"></div>

        <div class="clearfix"></div>

        <br /><br />

        <h2><?= Dictionary::get_text('OrganizationForm_OrgContact_lbl') ?></h2>

        <div class="grid">
            <div class="cell-x2">

                <div class="label"><i class="fa fa-user" aria-hidden="true"></i> <?= Dictionary::get_text('OrganizationForm_DirectorName_lbl') ?></div>
                <div class="value" name="director_name"></div>

                <div class="clearfix"></div>

                <div class="label"><i class="fa fa-user-secret" aria-hidden="true"></i> <?= Dictionary::get_text('OrganizationForm_DirectorPosition_lbl') ?></div>
                <div class="value" name="director_position"></div>

                <div class="clearfix"></div>

            </div>
            <div class="cell-x2">

                <div class="label"><i class="fa fa-mobile" aria-hidden="true"></i> <?= Dictionary::get_text('OrganizationForm_DirectorMobile_lbl') ?></div>
                <div class="value" name="director_mobile"></div>

                <div class="clearfix"></div>

                <div class="label"><i class="fa fa-at" aria-hidden="true"></i> <?= Dictionary::get_text('OrganizationForm_Email_lbl') ?></div>
                <div type="value" name="director_email"></div>

                <div class="clearfix"></div>

            </div>
        </div>

    </form>

</div>
