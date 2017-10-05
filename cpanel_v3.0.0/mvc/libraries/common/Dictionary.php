<?php
/*
 *
 */

//include_once BASE_DIR.'/mvc/libraries/http/HttpRequest.php';
//include_once BASE_DIR.'/mvc/libraries/http/HttpSession.php';

//include_once BASE_DIR.'/includes/classes/util/Properties.php';

class Dictionary {

    private static $defined   = false;
    private static $sources   = array();
    private static $languages = array();
    private static $variables = array();

    private static $current_lang   = 'ar';
    private static $current_source = 'lang';
    
    public static function define_variables($source=null) {

        try {

            if ( self::$defined ) {
                return;
            }

            self::$sources   = split(',', SOURCES);
            self::$languages = split(',', LANGUAGES);// array('ar', 'en');
            
            if( !empty($source) ){
                self::$current_source = $source;
            }else{
                self::$current_source = self::$sources[0];
            }

            $request = HttpRequest::get_instance();
            $session = HttpSession::get_instance();

            $lang = $request->get_parameter("lang");

            if ($lang == null) {

                $lang = $session->get_attribute("lang");

                if ($lang == null) {
                    $lang = DEFAULT_LANG;
                }
            }

            if ( ! self::is_language_supported( $lang )) {
                $lang = DEFAULT_LANG;
            }

            $session->set_attribute("lang", $lang);

            self::$current_lang = $lang;

            $dictionary = array();

            foreach (self::$sources as $source) {

                $dictionary[$source] = array();

                foreach (self::$languages as $dic_lang) {

                    $lang_properties = new Properties();

                    $lang_properties->load( file_get_contents(  BASE_DIR.'/locale/lang/'.$source.'-'.$dic_lang.'.properties'  ) );
                    //$lang_properties->load(file_get_contents(BASE_DIR . '/locale/lang/lang-' . $dic_lang . '.properties'));

                    $dictionary[$source][$dic_lang] = $lang_properties->toArray();

                }

            }

            self::$variables = $dictionary;

            self::$defined = true;

        } catch (Exception $e) {
            throw new CustomException( 'Error in : loading dictionary variables : \n' . $e->getMessage() . "\n");
        }
    }

    
    public static function set_variable($name, $value){

        self::$variables[$name] = $value;

    }

    public static function get_variable($name) {

        return self::$variables[$name];
    }

    public static function get_text($name, $lang = null) {

        $source = self::$current_source;

        if ($lang == null) {
            $lang = self::$current_lang;
        } else if ( ! self::is_language_supported($lang)) {
            $lang = DEFAULT_LANG;
        }

        return self::$variables[$source][$lang][$name];
    }

    public static function get_text_by_lang_old($textAr, $textEn) {

        $text = '';

        $text = ( self::$current_lang == "ar" ) ? $textAr : $textEn;

        $text = ( $text == null ) ? "" : $text;

        return $text;
    }

    public static function get_text_by_lang($object, $varname, $camel_case=false, $lang=null) {

        $text = '';
        
        if( is_object($object) ){
            $array = (array) $object;
        }else{
            $array = $object;
        }
        
        if( ! isset($lang) ){
            $lang = self::$current_lang;
        }
        
        if( $camel_case ){
            $newvar = $varname.ucfirst($lang);
        }else{   
            $newvar = $varname.'_'.$lang;
        }
        
        $text = $array[$newvar];

        return $text;
    }

    public static function is_language_supported($lang = null) {

        $found = false;

        foreach (self::$languages as $dLanguage) {
            if ($lang == $dLanguage) {
                $found = true;
            }
        }

        return $found;
    }

    public static function get_variables() {

        return self::$variables;
    }

    public static function set_language($new_lang) {

        self::$current_lang = $new_lang;
    }

    public static function get_language() {

        return self::$current_lang;
    }

    public static function set_source($new_source) {

        self::$current_source = $new_source;
    }

    public static function get_source() {

        return self::$current_source;
    }
}

?>
