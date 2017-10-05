<?php

define("LOG_TYPE_VIEW_CERTIFICATE",         0 );
define("LOG_TYPE_ADD_CERTIFICATE",          1 );
define("LOG_TYPE_EDIT_CERTIFICATE",         2 );
define("LOG_TYPE_REMOVE_CERTIFICATE",       3 );
define("LOG_TYPE_IMPORT_CERTIFICATE",       4 );
define("LOG_TYPE_EXPORT_CERTIFICATE",       5 );
define("LOG_TYPE_APPROVE_CERTIFICATE",      6 );
define("LOG_TYPE_SEARCH_CVC_CERTIFICATE",   7 );
define("LOG_TYPE_SEARCH_OTHER_CERTIFICATE", 8 );

/*
 * 
 */

/**
 * Description of ReportsUtil
 *
 * @author ahmad
 */
class ReportsUtil {

    private static $defined  = false;

    private static $folder   = 'reports';
    private static $filename = 'report.txt';

    private static function construct_log() {

        if (  !defined("BASE_DIR")  ){
            define( "BASE_DIR", dirname(__FILE__) );
        }

        $folderpath = BASE_DIR.'/'.self::$folder;

        if ( ! is_dir( $folderpath ) ){
            mkdir( $folderpath );
        }

        if (  !defined("NL")  ) define("NL", "\r\n");

        self::$defined = true;

    }

    public static function log($string, $type = 0) {

        try {

            if( ! self::$defined ){
                self::construct_log();
            }

            //$type = ( intval($type) > 0 ) ? $type : LOG_TYPE_VIEW_CERTIFICATE;
            $type = intval($type);

            $message = '';
            //[Wed Dec 05 08:34:36 2012] [error] [client 157.55.32.80] 

            switch ($type) {

                case LOG_TYPE_ADD_CERTIFICATE:
                    $message .= 'Add                  : ' . $string . ' ';
                    break;
                case LOG_TYPE_EDIT_CERTIFICATE:
                    $message .= 'Edit                 : ' . $string . ' ';
                    break;
                case LOG_TYPE_REMOVE_CERTIFICATE:
                    $message .= 'Remove               : ' . $string . ' ';
                    break;
                case LOG_TYPE_IMPORT_CERTIFICATE:
                    $message .= 'Import               : ' . $string . ' ';
                    break;
                case LOG_TYPE_EXPORT_CERTIFICATE:
                    $message .= 'Export               : ' . $string . ' ';
                    break;
                case LOG_TYPE_SEARCH_CVC_CERTIFICATE:
                    $message .= 'Search using cvc     : ' . $string . ' ';
                    break;
                case LOG_TYPE_SEARCH_OTHER_CERTIFICATE:
                    $message .= 'Search other options : ' . $string . ' ';
                    break;

                case LOG_TYPE_VIEW_CERTIFICATE:
                    $message .= 'View                 : ' . $string . ' ';
                    break;

            }

            $message .= ' at ['. date('D d/m/Y H:i:s') .'] ' . NL;

            $filepath = BASE_DIR.'/'.self::$folder.'/'.self::$filename;

            $fp = fopen( $filepath, "a+" );
            fputs( $fp, $message );
            fclose($fp);

        } catch (Exception $e) {
            Logger::log( $e->getMessage(), ERROR );
        }
    }

    public static function get_logfile_path() {
        return BASE_DIR . '/' . self::$folder . '/' . self::$filename;
    }
    
    public static function clear_logs() {
        
        $status = 0;
        
        try {
    
            $filepath = BASE_DIR . '/' . self::$folder . '/' . self::$filename;

            $fp = fopen($filepath, 'w');
            
            fputs( $fp, '' );
            
            fclose($fp);
            
            $status = 1;
            
        } catch (Exception $e) {
            Logger::log( $e->getMessage(), ERROR );
        }
        
        return $status;
        
    }

}
