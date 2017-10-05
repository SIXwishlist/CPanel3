<?php
    $lang   = $data->lang;
    $layout = $data->frame;
    $device = $data->device;
?>
<!DOCTYPE html>
<html lang="<?= $lang; ?>">
    <head>

        <title><?= $layout->title ?></title>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=3.0;">
        
        <link href="<?= ROOT_URL ?>images/favicons/favicon.ico" rel="shortcut icon" type="image/x-icon">

    <?
        if ( $layout->tags != null ){
            foreach ($layout->tags as $meta){
    ?>
        <meta <? foreach ($meta as $key => $value ) { ?> <?= $key ?>="<?= $value ?>" <? } ?> />
    <?
            }
        }
    ?>

        <!--link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Tinos" -->
        <!--link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet"-->
        <!--link href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet"-->

    <?
        if ( $layout->styles != null ){
            foreach ($layout->styles as $style){
    ?>
        <link href="<?= $style ?>" rel="stylesheet" type="text/css" />
    <?
            } 
        }
    ?>

        <script type="text/javascript" src="<?= ROOT_URL ?>js/lib/modernizr.js"></script>

    </head>

    <body>
        
    <!-- Start of Page Contents -->

<?php ?>