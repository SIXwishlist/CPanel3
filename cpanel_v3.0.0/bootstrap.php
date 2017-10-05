<?php

    try{

        //production (live) setting
        error_reporting(E_ERROR);//E_WARNING//E_ERROR//E_ALL

        ini_set('display_errors', 0);
        ini_set('log_errors',     1);

        //Global Constants

        ini_set('memory_limit',       '-1' );
        ini_set('max_execution_time', '1200');




        define( "CAPATCHA_TRIALS", 2 );

        define( "REQUEST_NOT_FOUND",  -10 );
        define( "CAPATCHA_REQUIRED",  -11 );
        define( "CAPATCHA_INCORRECT", -12 );

        define( "SERVER_ERROR",       -20 );
        define( "USER_NOT_EXIST",     -21 );
        define( "USER_NOT_VERIFIED",  -22 );


        
        
        define( "ICON_PAGES_WIDTH",  200 );
        define( "ICON_PAGES_HEIGHT", 200 );

        define( "ICON_MEDIA_HEIGHT", 200 );
        define( "ICON_MEDIA_WIDTH",  200 );


        define( "SUCCESS",  1  );
        define( "FAILED",   -1 );



        define("ACTION_ADD",      1);
        define("ACTION_EDIT",     2);
        define("ACTION_REMOVE",   3);
        define("ACTION_VIEW",     4);
        define("ACTION_VIEW_ALL", 5);


        define( "FILE_TYPE_UNKNOWN",     0 );
        define( "FILE_TYPE_DOWNLOAD",    1 );
        define( "FILE_TYPE_IMAGE",       2 );
        define( "FILE_TYPE_SWF",         3 );
        define( "FILE_TYPE_SOUND",       4 );
        define( "FILE_TYPE_VIDEO",       5 );
        define( "FILE_TYPE_YOUTUBE",     6 );
        define( "FILE_TYPE_VIMEO",       7 );
        define( "FILE_TYPE_EMBED_CODE",  8 );
        define( "FILE_TYPE_SOUND_CLOUD", 9 );


        define( "CHILD_TYPE_SECTION", 1 );
        define( "CHILD_TYPE_TARGET",  2 );
        define( "CHILD_TYPE_EMBED",   3 );
        define( "CHILD_TYPE_LINK",    4 );


        define( "CHILD_TYPE_CATEGORY", 1 );
        define( "CHILD_TYPE_PRODUCT",  2 );


        define( "HIT_SOURCE_SECTION",  1 );
        define( "HIT_SOURCE_TARGET",   2 );
        define( "HIT_SOURCE_EMBED",    3 );
        define( "HIT_SOURCE_CATEGORY", 4 );
        define( "HIT_SOURCE_PRODUCT",  5 );
        define( "HIT_SOURCE_SEARCH",   6 );

        define( "AD_PLACE_HEADER",  1 );
        define( "AD_PLACE_HOME",    2 );
        define( "AD_PLACE_USER",    3 );
        define( "AD_PLACE_PRODUCT", 4 );
        define( "AD_PLACE_ANY",     10 );


        define( "USER_STATUS_NOT_VERIFIED",    0 );
        define( "USER_STATUS_EMAIL_VERIFIED",  1 );
        define( "USER_STATUS_PHONE_VERIFIED",  2 );


        define( "USER_RULE_NORMAL",    1 );
        define( "USER_RULE_PRIVATE",   2 );
        define( "USER_RULE_SUSPENDED", 3 );
        define( "USER_RULE_BLOCKED",   4 );

        
        define( "PRODUCT_FEATURED", 0x01 );
        define( "PRODUCT_OFFER",    0x02 );
        define( "PRODUCT_RECENT",   0x04 );
        define( "PRODUCT_SALE",     0x08 );

        define( "STYLE_DEFAULT", 1 );
        define( "STYLE_MEDIA",   2 );

        define("HOME_MENU", 1);
        define("HOME_PAGE", 1);
        define("CONTACT_PAGE", 4);


        define( "CACHING_ENABLED",    false );
        define( "AUTO_CACHING_CMS",   true  );
        define( "USE_MEANINGFUL_URL", true  );

        define( "KEY_CODE",  "Arak for Information Technology");



        //Current Application Constants
        define("USER_TYPE_MASTER",  1);
        define("USER_TYPE_ORG",     2);
        define("USER_TYPE_CHECKER", 3);
        define("USER_TYPE_ENTRY",   4);


        define("ORG_STATUS_HOLD",    0 );
        define("ORG_STATUS_EXPIRED", 1 );
        define("ORG_STATUS_TRIAL",   2 );
        define("ORG_STATUS_ACTIVE",  3 );


        define("ORG_TYPE_UNKNOWN",    0 );
        define("ORG_TYPE_UNIVERSITY", 1 );
        define("ORG_TYPE_COLLAGE",    2 );
        define("ORG_TYPE_INSTITUTE",  3 );
        define("ORG_TYPE_TRAINING",   4 );
        define("ORG_TYPE_SCHOOL",     5 );
        define("ORG_TYPE_OTHER",      6 );


        define("ORG_LOGO_WIDTH",   200 );
        define("ORG_LOGO_HEIGHT",  200 );


        define( "VERIFIED",      1 );
        define( "NOT_VERIFIED", -1 );
        define( "NOT_EXIST",    -2 );
        define( "CODE_ERROR",   -3 );
        define( "BLOCKED",      -4 );


        define( "CAPATCHA_TRIALS", 2 );

        define( "REQUEST_NOT_FOUND",  -10 );
        define( "CAPATCHA_REQUIRED",  -11 );
        define( "CAPATCHA_INCORRECT", -12 );

        define( "SERVER_ERROR",        -20 );
        define( "USER_NOT_EXIST",      -21 );
        define( "ACCOUNT_EXPIRED",     -22 );
        define( "ACCOUNT_SUSPENDED",   -23 );
        define( "ACCOUNT_BLOCKED",     -24 );
        define( "USER_ALREADY_EXIST",  -25 );
        define( "UNAUTHORIZED_ACCESS", -26 );
        define( "PRODUCT_NOT_EXIST",   -27 );

        define( "SQL_DUPLICATE_USER", 1062 );


        define( "SUCCESS",  1  );
        define( "FAILED",   -1 );

        define( "EDITABLE",   0x01 );
        define( "REMOVABLE",  0x02 );
        define( "NEW_WINDOW", 0x04 );
        define( "SHOW_SUB",   0x08 );
        define( "SHOW_MENU",  0x10 );//this equal 16

        define( "TOP_MENU",  0x01 );
        define( "SIDE_MENU", 0x02 );
        define( "FOOT_MENU", 0x04 );

        define( "LIST_ICON_WIDTH",   180 );
        define( "LIST_ICON_HEIGHT",  130 );

        define( "EMBED_ICON_WIDTH",   200 );
        define( "EMBED_ICON_HEIGHT",  200 );

        
        define( "PAYPAL_MODE_SANDBOX", 1 );
        define( "PAYPAL_MODE_LIVE",    2 );

        define( "PAYPAL_MODE", PAYPAL_MODE_SANDBOX );

        //spl autoload register
        //function __autoload($class_name) {}
        spl_autoload_register( autoload_classes );

        //set error handler
        set_error_handler( custom_error_handler, E_USER_ERROR );


        define( "BASE_DIR",    dirname(__FILE__) );
        define( "CONFIG_FILE", BASE_DIR . '/config/properties/config.properties' );

        define( "UPLOAD_DIR",  BASE_DIR . '/uploads/' );    
        define( "UPLOAD_URL",  BASE_URL . 'uploads'  );


        ConstantLoader::load_config_properties();

        Dictionary::define_variables();


        if ( !defined("COUNTRY_DIAL") ) {
            define("COUNTRY_DIAL", "962");
        }

    } catch (Exception $e) {
        echo( 'Error in bootstrap ' . $e->getMessage() );
    }

    function custom_error_handler($errno, $errstr) {
        $error_msg = "[$errno] $errstr";//Error:
        throw new Exception( 'Breaking Exception: '.$errstr, $errno );
        Logger::log("Uncaughted Error: ".$error_msg, ERROR);
    }

    function autoload_classes($class_name) {
        
        try{

            //class directories
            $directories = array(
                'mvc/controllers/',
                'mvc/controllers/manage/',
                'mvc/controllers/mobile/',
                'mvc/controllers/front/',
                'mvc/controllers/utils/',
                'mvc/libraries/',
                'mvc/libraries/common/',
                'mvc/libraries/db/',
                'mvc/libraries/http/',
                'mvc/libraries/json/',
                'mvc/libraries/xml/',
                'mvc/models/',
                'mvc/models/cache/',
                'mvc/models/db/',
                'mvc/models/json/',
                'mvc/models/session/',
                'mvc/models/xml/'
            );

            //for each directory
            foreach($directories as $directory){
                //see if the file exsists
                if( file_exists(BASE_DIR.'/'.$directory.$class_name . '.php') ){
                    require_once(BASE_DIR.'/'.$directory.$class_name . '.php');
                    //only require the class once, so quit after to save effort (if you got more, then name them something else
                    return;
                }
            }
                    
        } catch (Exception $e) {
            throw new CustomException( 'Error in autoload class', $e );//from php 5.3 no need to custum
        }
    }
    
?>
