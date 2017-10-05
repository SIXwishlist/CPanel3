<?php

    $dir        = $data->dir;

    //$user_id  = $data->user_id;
    //$user_key = $data->user_key;

    $reset_link = $data->reset_link;

?>

<?= Dictionary::get_text('ResetEmail_Message_lbl'); ?>

<br /><br />

<div style="direction: <?= $dir ?>">
    
    <a href="<?= $reset_link ?>"><?= Dictionary::get_text('ResetEmail_Link_lbl'); ?></a>

</div>
