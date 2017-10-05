<?php

    $dir         = $data->dir;

    //$user_id     = $data->user_id;
    //$user_key    = $data->user_key;

    $verify_link = $data->verify_link;

?>

<?= Dictionary::get_text('RegisterVerify_Message_lbl'); ?>

<br /><br />

<div style="direction: <?= $dir ?>">
    
    <a href="<?= $verify_link ?>"><?= Dictionary::get_text('RegisterVerify_Link_lbl'); ?></a>

</div>
