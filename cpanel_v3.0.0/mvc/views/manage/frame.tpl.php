<?php
    
    $page_start     = $data->page_start;
    $page_end       = $data->page_end;
    
    $page_content   = $data->page_content;
    //$main_data      = $data->main_data;

    $lang           = $data->lang;

    $lang_ar        = $data->lang_ar;
    $lang_en        = $data->lang_en;
    
    $menu           = $data->menu;

?>

<?php include(BASE_DIR.'/mvc/views/frame/page_start.tpl'); ?>

<?= $page_start; ?>

<div id="layout">
    <div id="head">

        <!--<div id="org-logo" ><a href="#"     ><img src="<?= ROOT_URL ?>images/org-logo.png"  /></a></div>-->
        <div id="main-logo"><a href="http://www.arakjo.com" ><img src="<?= ROOT_URL ?>images/main-logo.png" /></a></div>

        <!--<div id="arak-logo"  ><a href="http://www.psms-app.com"  ><img src="<?= ROOT_URL ?>images/arak-logo.png"   /></a></div>-->

        <!--<div id="languages">
            <a id="arabic" href="?lang=ar">عربي</a>
            <span> | </span>
            <a id="english" href="?lang=en">English</a>
        </div>-->

        <div id="top-menu">

<!--            <a href="http://localhost/certveri2/"> onclick="return false;"
                <i class="fa fa-home" aria-hidden="true"></i> 
                Home
            </a>

            <a href="http://localhost/certveri2/about/"> onclick="return false;"
                <i class="fa fa-cog" aria-hidden="true"></i> 
                About
            </a>

            <a href="http://localhost/certveri2/manage/"> onclick="return false;"
                <i class="fa fa-user" aria-hidden="true"></i> 
                User
            </a>

            <a href="#" onclick="AdminAuth.logout(); return false;">
                <i class="fa fa-sign-out" aria-hidden="true"></i> 
                Logout
            </a>-->
        </div>
        
    </div>
    
    <?= $page_content; ?>
    
    <div id="foot">
        <div id="side1">
            All rights reserved to <a target="blank" title="Arak for Information Technology" href="http://www.arakjo.com/">Arak</a> &copy; <?= date('Y') ?>
        </div>
        <div id="side2">
            <!-- Developed by <a target="blank" href="http://www.arakjo.com/">Arak</a> -->
        </div>
    </div>
</div>
<!--
<div id="overlay1" class="mode1">
    <div id="popup" class="clearfix">
        <div id="title-bar" class="clearfix">
            <div id="title">CertVeri System</div>
            <div id="close"></div>
        </div>
        <div id="content">
            
        </div>
    </div>
</div>
-->

<?= $page_end; ?>

<?php include(BASE_DIR.'/mvc/views/frame/page_end.tpl'); ?>
