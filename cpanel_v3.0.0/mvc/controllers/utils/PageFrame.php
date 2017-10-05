<?php
/*
 *
 */

/**
 * Description of PageFrame
 *
 * @author Ahmad
 */
class PageFrame {

    public $title;
    public $title_postfix;
    public $styles;
    public $js_files;
    public $tags;
    public $cells;
    public $pre_path;

    private static $instance = null;
    private static $parsed = false;

    private function PageFrame() {
    }

    public static function get_instance() {

        if( ! self::$parsed ) {
            self::$instance = new PageFrame();
            self::$parsed = true;
        }

        return self::$instance;
    }

    public static function get_manage_layout() {

        $layout = self::get_instance();
        
        $lang   = Dictionary::get_language();

        $title_array = array( 
            "title_ar" => TITLE_POSTFIX_AR,
            "title_en" => TITLE_POSTFIX_EN
        );

        $layout->title         = Dictionary::get_text_by_lang( $title_array, "title" );
        $layout->title_postfix = Dictionary::get_text_by_lang( $title_array, "title" );

        $layout->pre_path = "";


        //$layout->styles [] = ROOT_URL."css/ui-lightness/jquery-ui-1.10.1.custom.min.css";
        $layout->styles [] = "https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css";
        
        $layout->styles [] = ROOT_URL."css/lib-combined.css?v=".VERSION;

        $layout->styles [] = ROOT_URL."css/manage.css?v=".VERSION;

        $layout->styles [] = ROOT_URL."css/print.css?v=".VERSION;

        if ( $lang == "ar" ) {
            $layout->styles [] = ROOT_URL."css/manage-rtl.css?v=".VERSION;
        }



        $layout->js_files [] = ROOT_URL."js/lib/lib-manage-combined.js?v=".VERSION;

        $layout->js_files [] = ROOT_URL."js/ckeditor/ckeditor.js";
        $layout->js_files [] = ROOT_URL."js/ckeditor/adapters/jquery.js";

        $layout->js_files [] = ROOT_URL."js/utils/utils-manage-combined.js?v=".VERSION;

        $layout->js_files [] = ROOT_URL."js/manage/manage-combined.js?v=".VERSION;


        //for debugging utils-front-combined
        //$layout->js_files [] = ROOT_URL."js.source/utils.source/console.js";
        //$layout->js_files [] = ROOT_URL."js.source/utils.source/sprintf.js";
        //$layout->js_files [] = ROOT_URL."js.source/utils.source/util-classes/c-utils_1.0.14.js";
        //$layout->js_files [] = ROOT_URL."js.source/utils.source/util-classes/c-dictionary_v1.0.3.js";
        //$layout->js_files [] = ROOT_URL."js.source/utils.source/util-classes/c-display_util_1.0.6.js";
        //$layout->js_files [] = ROOT_URL."js.source/utils.source/util-classes/c-form_v1.0.9.js";
        //$layout->js_files [] = ROOT_URL."js.source/utils.source/util-classes/c-popup_v1.0.2.js";
        //$layout->js_files [] = ROOT_URL."js.source/utils.source/util-classes/cover_v1.0.0.js";
        //$layout->js_files [] = ROOT_URL."js.source/utils.source/util-classes/cms-util_v1.0.7.js";
        //$layout->js_files [] = ROOT_URL."js.source/utils.source/util-classes/request-util_v1.0.7.js";
        //$layout->js_files [] = ROOT_URL."js.source/utils.source/util-classes/editor_popup_1.0.7.js";
        //$layout->js_files [] = ROOT_URL."js.source/utils.source/util-classes/validation_1.0.2.js";
        //$layout->js_files [] = ROOT_URL."js.source/utils.source/util-classes/resource-util_1.0.6.js";
        //$layout->js_files [] = ROOT_URL."js.source/utils.source/util-classes/print-util_v1.0.1.js";
        //$layout->js_files [] = ROOT_URL."js.source/utils.source/util-classes/print-area_v1.0.1.js";

        //for debugging manage-combined
        //$layout->js_files [] = ROOT_URL."js.source/manage.source/config.js";
        //$layout->js_files [] = ROOT_URL."js.source/manage.source/common.js";
        //$layout->js_files [] = ROOT_URL."js.source/manage.source/boot.js";
        //$layout->js_files [] = ROOT_URL."js.source/manage.source/utils.js";
        //$layout->js_files [] = ROOT_URL."js.source/manage.source/auth.js";
        //$layout->js_files [] = ROOT_URL."js.source/manage.source/account.js";
        //$layout->js_files [] = ROOT_URL."js.source/manage.source/extra-utils_v1.0.1.js";
        //
        //$layout->js_files [] = ROOT_URL."js.source/manage.source/display_modules.js";
        //$layout->js_files [] = ROOT_URL."js.source/manage.source/background_requests.js";
        //
        //$layout->js_files [] = ROOT_URL."js.source/manage.source/manage_cache.js";
        //$layout->js_files [] = ROOT_URL."js.source/manage.source/manage_admins.js";
        //
        //$layout->js_files [] = ROOT_URL."js.source/manage.source/manage_section_childs.js";
        //$layout->js_files [] = ROOT_URL."js.source/manage.source/manage_sections.js";
        //$layout->js_files [] = ROOT_URL."js.source/manage.source/manage_targets.js";
        //$layout->js_files [] = ROOT_URL."js.source/manage.source/manage_embeds.js";
        //$layout->js_files [] = ROOT_URL."js.source/manage.source/manage_links.js";
        //
        //$layout->js_files [] = ROOT_URL."js.source/manage.source/manage_category_childs.js";
        //$layout->js_files [] = ROOT_URL."js.source/manage.source/manage_categories.js";
        //$layout->js_files [] = ROOT_URL."js.source/manage.source/manage_products.js";
        //$layout->js_files [] = ROOT_URL."js.source/manage.source/manage_shots.js";
        //
        //$layout->js_files [] = ROOT_URL."js.source/manage.source/manage_slides.js";
        //$layout->js_files [] = ROOT_URL."js.source/manage.source/manage_ads.js";
        //
        //$layout->js_files [] = ROOT_URL."js.source/manage.source/manage_users.js";
        //
        //$layout->js_files [] = ROOT_URL."js.source/manage.source/manage_payments.js";
        //$layout->js_files [] = ROOT_URL."js.source/manage.source/manage_wished_items.js";


        $layout->cells [] = "c_popup.tpl";
        $layout->cells [] = "editor_popup.tpl";

        return $layout;

    }

    public static function get_front_layout() {

        $layout = self::get_instance();
        
        $lang   = Dictionary::get_language();

        $title_array = array( 
            "title_ar" => TITLE_POSTFIX_AR,
            "title_en" => TITLE_POSTFIX_EN
        );

        $layout->title         = Dictionary::get_text_by_lang( $title_array, "title" );
        $layout->title_postfix = Dictionary::get_text_by_lang( $title_array, "title" );

        $layout->pre_path = "";

        //$layout->styles [] = ROOT_URL."css/ui-lightness/jquery-ui-1.10.1.custom.min.css";
        $layout->styles [] = "https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css";

        $layout->styles [] = ROOT_URL."css/lib-combined.css?v=".VERSION;
        
        $layout->styles [] = ROOT_URL."css/front.css?v=".VERSION;
        
        if ( $lang == "ar" ) {
            $layout->styles [] = ROOT_URL."css/front-rtl.css?v=".VERSION;
        }

        //for checking front-combined.css
        //$layout->styles [] = ROOT_URL."css.source/desktop-wide.css";



        $layout->js_files [] = ROOT_URL."js/lib/lib-front-combined.js?v=".VERSION;

        $layout->js_files [] = ROOT_URL."js/utils/utils-front-combined.js?v=".VERSION;

        $layout->js_files [] = ROOT_URL."js/front/front-combined.js?v=".VERSION;

        //for debugging utils-front-combined
        //$layout->js_files [] = ROOT_URL."js.source/utils.source/console.js";
        //$layout->js_files [] = ROOT_URL."js.source/utils.source/sprintf.js";
        //$layout->js_files [] = ROOT_URL."js.source/utils.source/util-classes/c-utils_1.0.14.js";
        //$layout->js_files [] = ROOT_URL."js.source/utils.source/util-classes/c-dictionary_v1.0.3.js";
        //$layout->js_files [] = ROOT_URL."js.source/utils.source/util-classes/c-display_util_1.0.6.js";
        //$layout->js_files [] = ROOT_URL."js.source/utils.source/util-classes/c-form_v1.0.9.js";
        //$layout->js_files [] = ROOT_URL."js.source/utils.source/util-classes/c-popup_v1.0.2.js";
        //$layout->js_files [] = ROOT_URL."js.source/utils.source/util-classes/cover_v1.0.0.js";
        //$layout->js_files [] = ROOT_URL."js.source/utils.source/util-classes/request-util_v1.0.7.js";
        //$layout->js_files [] = ROOT_URL."js.source/utils.source/util-classes/validation_1.0.2.js";
        //$layout->js_files [] = ROOT_URL."js.source/utils.source/util-classes/resource-util_1.0.6.js";
        //$layout->js_files [] = ROOT_URL."js.source/utils.source/util-classes/print-util_v1.0.1.js";
        //$layout->js_files [] = ROOT_URL."js.source/utils.source/util-classes/print-area_v1.0.1.js";

        //for debugging front-combined
        //$layout->js_files [] = ROOT_URL."js.source/front.source/config.js";
        //$layout->js_files [] = ROOT_URL."js.source/front.source/front.js";
        //$layout->js_files [] = ROOT_URL."js.source/front.source/url-utils.js";
        //$layout->js_files [] = ROOT_URL."js.source/front.source/login.js";
        //$layout->js_files [] = ROOT_URL."js.source/front.source/register.js";
        //$layout->js_files [] = ROOT_URL."js.source/front.source/forget.js";
        //$layout->js_files [] = ROOT_URL."js.source/front.source/reset.js";
        //$layout->js_files [] = ROOT_URL."js.source/front.source/cart.js";
        //$layout->js_files [] = ROOT_URL."js.source/front.source/checkout.js";
        //$layout->js_files [] = ROOT_URL."js.source/front.source/embeds.js";
        //$layout->js_files [] = ROOT_URL."js.source/front.source/shots.js";
        //$layout->js_files [] = ROOT_URL."js.source/front.source/products-filter.js";

        $layout->cells [] = "c_popup.tpl";
        //$layout->cells [] = "editor_popup.tpl";

        $layout->forms[] = 'login_form.tpl';
        $layout->forms[] = 'signup_form.tpl';
        $layout->forms[] = 'forget_form.tpl';
        $layout->forms[] = 'reset_form.tpl';

        return $layout;

    }
}

?>