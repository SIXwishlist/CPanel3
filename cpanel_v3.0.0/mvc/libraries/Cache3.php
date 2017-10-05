<?

/**
 *
 */
class Cache3 {

    public static $dir = "cache";
    
    function __construct() {

        $path = UPLOAD_DIR . self::$dir;

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
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

        $cache = '';

        if (filesize($cache_path) > 0) {
            $cache = unserialize(fread($fp, filesize($cache_path)));
        } else {
            $cache = NULL;
        }

        flock($fp, LOCK_UN);
        fclose($fp);

        return $cache;
    }

    public static function set($key, $data) {

        $path = UPLOAD_DIR . self::$dir;
        
        if (!is_dir($path) OR !is_writable($path)) {
            return FALSE;
        }

        $cache_path = self::get_file_path($key);

        if (!$fp = fopen($cache_path, 'wb')) {
            return FALSE;
        }

        if (flock($fp, LOCK_EX)) {
            fwrite($fp, serialize($data));
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