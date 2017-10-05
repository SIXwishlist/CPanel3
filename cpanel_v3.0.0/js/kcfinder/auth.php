<?php

session_start();

if( @$_SESSION['admin_id'] == null ) {
    exit();
}

?>