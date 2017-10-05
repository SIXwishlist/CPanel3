
<div class="certificate_preview_tpl">

    <form id="organization_preview" class="style1">

        <h2><?= Dictionary::get_text('CertificateForm_HolderInfo_lbl') ?></h2>

        <div class="label"><i class="fa fa-user" aria-hidden="true"></i> <?= Dictionary::get_text('CertificateForm_Type_lbl') ?></div>
        <div class="value" name="type"></div>

        <div class="clearfix"></div>

        <div class="grid">
            <div class="cell-x3">

                <div class="label"><i class="fa fa-user" aria-hidden="true"></i> <?= Dictionary::get_text('CertificateForm_Name_lbl') ?></div>
                <div class="value" name="name"></div>

            </div>
            <div class="cell-x3">

                <div class="label"><i class="fa fa-building" aria-hidden="true"></i> <?= Dictionary::get_text('CertificateForm_Number_lbl') ?></div>
                <div class="value" name="number"></div>

            </div>
            <div class="cell-x3">

                <div class="label"><i class="fa fa-at" aria-hidden="true"></i> <?= Dictionary::get_text('CertificateForm_Email_lbl') ?></div>
                <div class="value" name="email"></div>

            </div>
        </div>

        <div class="clearfix"></div>
        <div class="clearfix" style="margin-top: 20px"></div><br />

        <div class="label"><i class="fa fa-upload" aria-hidden="true"></i> <?= Dictionary::get_text('CertificateForm_Image_lbl') ?> </div>
        <div class="value" name="image"></div>

        <div class="clearfix"></div>
        
        <div class="label"><i class="fa fa-upload" aria-hidden="true"></i> <?= Dictionary::get_text('CertificateForm_QrImage1_lbl') ?> </div>
        <div class="value" name="qr_image1"></div>

        <div class="clearfix"></div>
        
        <div class="label"><i class="fa fa-upload" aria-hidden="true"></i> <?= Dictionary::get_text('CertificateForm_QrImage2_lbl') ?> </div>
        <div class="value" name="qr_image2"></div>

        <div class="clearfix"></div>
        <div class="clearfix" style="margin-top: 20px"></div><br />


        <h2><?= Dictionary::get_text('CertificateForm_CourseInfo_lbl') ?></h2>

        <div class="grid">
            <div class="cell-x2">

                <div class="label"><i class="fa fa-user" aria-hidden="true"></i> <?= Dictionary::get_text('CertificateForm_Discipline_lbl') ?>*</div>
                <div class="value" name="course_title"></div>

            </div>
            <div class="cell-x2">

                <div class="label"><i class="fa fa-building" aria-hidden="true"></i> <?= Dictionary::get_text('CertificateForm_Department_lbl') ?></div>
                <div class="value" name="department"></div>

            </div>
        </div>

        <div class="grid">
            <div class="cell-x2">

                <div class="label"><i class="fa fa-user" aria-hidden="true"></i> <?= Dictionary::get_text('CertificateForm_GraduationDateFrom_lbl') ?> *</div>
                <div class="value" name="issue_date_from"></div>

            </div>
            <div class="cell-x2">

                <div class="label"><i class="fa fa-building" aria-hidden="true"></i> <?= Dictionary::get_text('CertificateForm_GraduationDateTo_lbl') ?> *</div>
                <div class="value" name="issue_date_to"></div>

            </div>
        </div>

        <div class="grid">
            <div class="cell-x2">

                <div class="label"><i class="fa fa-user" aria-hidden="true"></i> <?= Dictionary::get_text('CertificateForm_Grade_lbl') ?></div>
                <div class="value" name="grade"></div>

            </div>
            <div class="cell-x2">

                <div class="label"><i class="fa fa-building" aria-hidden="true"></i> <?= Dictionary::get_text('CertificateForm_Score_lbl') ?></div>
                <div class="value" name="score"></div>

            </div>
        </div>

        <div class="grid">
            <div class="cell-x2">

                <div class="label"><i class="fa fa-user" aria-hidden="true"></i> <?= Dictionary::get_text('CertificateForm_Country_lbl') ?></div>
                <div class="value" name="country"></div>

            </div>
            <div class="cell-x2">

                <div class="label"><i class="fa fa-building" aria-hidden="true"></i> <?= Dictionary::get_text('CertificateForm_City_lbl') ?></div>
                <div class="value" name="city"></div>

            </div>
        </div>
        
        <div class="label"><i class="fa fa-building" aria-hidden="true"></i> <?= Dictionary::get_text('CertificateForm_OrgId_lbl') ?></div>
        <div class="value" name="org_id"></div>

        <div class="clearfix"></div>
        
        <div class="label"><i class="fa fa-building" aria-hidden="true"></i> <?= Dictionary::get_text('CertificateForm_Approved_lbl') ?></div>
        <div class="value" name="approved"></div>

        <div class="clearfix"></div>

    </form>

</div>
