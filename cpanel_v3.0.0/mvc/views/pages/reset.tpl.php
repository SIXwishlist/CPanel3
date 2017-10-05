<?php
    $user_id  = $data->user_id;
    $user_key = $data->user_key;
?>

<input type="hidden" name="reset_form" value="1" />
<input type="hidden" name="user_id"    value="<?= $user_id  ?>" />
<input type="hidden" name="user_key"   value="<?= $user_key ?>" />

<div class="clearfix"><br /><br /></div>

<!--
< script type = " text / javascript " >
//
//window.setTimeout(function(){ 
//    ResetForm.show_form();
//}, 1500);

< / script >
-->