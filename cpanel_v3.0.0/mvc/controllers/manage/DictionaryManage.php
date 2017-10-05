<?php
/*
 *
 */

class DictionaryManage {

    public static function get_frontend_dictionary(){

        $output_array = array();
        
        try {

            $variables = Dictionary::get_variables();

            $output_array["dictionary"] = $variables['front'];

        } catch (Exception $e) {
            Logger::log( $e->getMessage(), ERROR );
        }
        
        return $output_array;
    }

    public static function get_backend_dictionary(){

        $output_array = array();
        
        try {

            $variables = Dictionary::get_variables();

            $output_array["dictionary"] = $variables['back'];

        } catch (Exception $e) {
            Logger::log( $e->getMessage(), ERROR );
        }
        
        return $output_array;
    }

}

?>