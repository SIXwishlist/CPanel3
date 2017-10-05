<?php

    $contact = $data->contact;

?>

You have been received the following new Contact message:
<br /><br />

<table style="border: none;">
    <tr>
        <td><?= Dictionary::get_text('ContactUsFormForm_Name_lbl'); ?></td>
        <td><?= ucfirst($contact->name) ?></td>
    </tr>
    <tr>
        <td><?= Dictionary::get_text('ContactUsFormForm_Email_lbl'); ?></td>
        <td><?= $contact->email ?></td>
    </tr>
    <tr>
        <td><?= Dictionary::get_text('ContactUsFormForm_Text_lbl'); ?></td>
        <td><?= $contact->text ?></td>
    </tr>
</table>
