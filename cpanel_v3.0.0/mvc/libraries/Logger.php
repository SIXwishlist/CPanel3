<?

class Logger{

    private static $defined  = false;
    private static $folder   = 'logs';
    private static $filename = 'log.txt';

    private static function construct_log() {

        if (!defined("LOG"))
            define("LOG", 1);
        if (!defined("INFO"))
            define("INFO", 2);
        if (!defined("WARN"))
            define("WARN", 3);
        if (!defined("ERROR"))
            define("ERROR", 4);

        if (!defined("BASE_DIR")){
            define( "BASE_DIR", dirname(__FILE__) );
        }
        
        $folderpath = BASE_DIR.'/'.self::$folder;

        if ( ! is_dir( $folderpath ) ){
            mkdir( $folderpath );
        }

        if (!defined("NL"))
            define("NL", "\r\n");

        self::$defined = true;
        
    }

    public static function log($string, $type = LOG, $email = false) {

        if( ! self::$defined ){
            self::construct_log();
        }

        $type = ( intval($type) > 0 ) ? $type : LOG;

        $message = '';
        //[Wed Dec 05 08:34:36 2012] [error] [client 157.55.32.80] 
        $message .= '['. date('D d/m/Y H:i:s') .'] ';

        switch ($type) {
            case LOG:
                $message .= 'Log     : ' . $string . ' ' . NL;
                break;
            case INFO:
                $message .= 'Info    : ' . $string . ' ' . NL;
                break;
            case WARN:
                $message .= 'Warning : ' . $string . ' ' . NL;
                break;
            case ERROR:
                $message .= 'Error   : ' . $string . ' ' . NL;
                break;
        }

        $filepath = BASE_DIR.'/'.self::$folder.'/'.self::$filename;

        if($email){
            Mailer::send_mail(SENDER_MAIL, ADMIN_EMAIL, $type, $message);
        }

        $fp = fopen( $filepath, "a+" );
        fputs( $fp, $message );
        fclose($fp);
        
    }

}

?>