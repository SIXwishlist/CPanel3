<?php

    include_once '../bootstrap.php';

    try {

        Dictionary::set_source('back');

    } catch (Exception $e) {
        Logger::log( $e->getTraceAsString(), ERROR );
    }
    
?>