<?

/**
 *
 */
class Cache2 {

    public static $dir = "cache";
    
    function __construct() {

        $path = UPLOAD_DIR . self::$dir;

        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
    }

    private static function get_file_path($key) {
        $path = UPLOAD_DIR . self::$dir;
        return sprintf("%s/%s", $path, sha1($key));
    }

    public static function get($key, $expiration = 3600) {

        $path = UPLOAD_DIR . self::$dir;
        
        if (!is_dir($path) OR !is_writable($path)) {
            return FALSE;
        }

        $cache_path = self::get_file_path($key);

        if (!@file_exists($cache_path)) {
            return FALSE;
        }

        if (filemtime($cache_path) < (time() - $expiration)) {
            self::clear($key);
            return FALSE;
        }

        if (!$fp = @fopen($cache_path, 'rb')) {
            return FALSE;
        }

        flock($fp, LOCK_SH);

        $data_array = NULL;

        if (filesize($cache_path) > 0) {
            $data       = fread($fp, filesize($cache_path));
            $data_array = json_decode( $data, true );
        } else {
            $data_array = NULL;
        }

        flock($fp, LOCK_UN);
        fclose($fp);

        return $data_array;
    }

    public static function set($key, $data_array) {

        $path = UPLOAD_DIR . self::$dir;
        
        if (!is_dir($path) OR !is_writable($path)) {
            return FALSE;
        }

        $cache_path = self::get_file_path($key);

        if (!$fp = fopen($cache_path, 'wb')) {
            return FALSE;
        }

        //$store_data = array(
        //   'time'   => time(),
        //   'expire' => $expiration,
        //   'data'   => $data
        //);
        
        if (flock($fp, LOCK_EX)) {
            $data = json_encode($data_array);
            fwrite($fp, $data);
            flock($fp, LOCK_UN);
        } else {
            return FALSE;
        }

        fclose($fp);
        @chmod($cache_path, 0777);

        return TRUE;
    }

    public static function clear($key) {

        $cache_path = self::get_file_path($key);

        if (file_exists($cache_path)) {
            unlink($cache_path);
            return TRUE;
        }

        return FALSE;
    }

}

?>