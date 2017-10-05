<?php ?>

<div class="media_center_tpl">

    <div id="MediaCenter">

        <form id="file_upload" action="ajax.php?page=upload_file" method="post" enctype="multipart/form-data">

            Current Directory :
            <input type="text"   name="folderDisplay" value="" size="60" disabled />
            <input type="text"   name="newfolder"  value="" size="20" />
            <input type="button" value="create"    onclick="create_folder()" />

            <input type="hidden" name="folder"     value="" />
            <input type="hidden" name="uploadDir"  value="<?= UPLOAD_URL . '/media_center' ?>" />


            <br /><br />

            Upload File : <input type="file"   name="file" value="Browse" />
            <input type="submit" value="upload file" />
            <input type="button" value="clear" onclick="reset_form()" />
            
        </form>

        <br /><hr /><br />

        File Path:
        <input id="copy_text"   type="text"   name="fileDisplay" value="" size="88" /><!-- disabled -->
        <input id="copy_button" type="button" name="copyButton"  value="Copy to clipboard" />

        <br /><hr /><br />

        <div id="FilesList"></div>

    </div>
</div>

<?php ?>