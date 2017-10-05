<?php
/*
 *
 */

//include_once BASE_DIR.'/mvc/libraries/Properties.php';

class ConstantLoader {

    // define default log file
    private static $defined = false;

    // set log file (path and name)
    public static function load_config_properties() {

        try {

            if ( ! self::$defined ) {

                $variables = ResourceArray::get_properties_array(CONFIG_FILE);

                foreach ($variables as $key => $value) {
                    define($key, $value);
                }

                self::$defined = true;
            }
        } catch (Exception $e) {
            throw new CustomException('Error : \n' . $e->getMessage() . "\n");
        }
    }

    public static function load_constants_ini() {

        try {

            if ( ! self::$defined ) {

                $variables = parse_ini_file(BASE_DIR.'/'."config/ini/constants.ini", true);

                foreach ($variables as $key => $value) {
                    define($key, $value);
                }

                self::$defined = true;
            }
        } catch (Exception $e) {
            throw new CustomException('Error : \n' . $e->getMessage() . "\n");
        }
    }
    
}

?>
