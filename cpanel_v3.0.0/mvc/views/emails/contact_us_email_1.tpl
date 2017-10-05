<?php

    $dir          = $data->dir;
    $contact_form = $data->contact_form;
?>

You have been received the following new Contact message:

<br /><br />

<table style="border: none;" dir="<?= $dir ?>">
    <tr>
        <td><?= Dictionary::get_text('ContactForm_Name_lbl'); ?></td>
        <td><?= ucwords($contact_form->name) ?></td>
    </tr>
    <tr>
        <td><?= Dictionary::get_text('CardRequestForm_Email_lbl'); ?></td>
        <td><?= $contact_form->email ?></td>
    </tr>
    <tr>
        <td><?= Dictionary::get_text('CardRequestForm_Text_lbl'); ?></td>
        <td><?= $contact_form->text ?></td>
    </tr>
</table>
