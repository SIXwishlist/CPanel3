<?php

class HttpSession {

    private $attributes = null;

    private static $instance = null;
    private static $parsed = false;

    private function HttpSession() {
        
    }

    public static function get_instance() {

        if (!self::$parsed) {
            self::$instance = new HttpSession();
            self::$instance->parse_parameters();
        }

        return self::$instance;
    }

    private function parse_parameters() {

        //$this->attributes = array();
        //$this->attributes = $_SESSION;

        self::$parsed = true;
    }


    public function get_session_id() {
        $sessionId = session_id();
        return $sessionId;
    }

    public function set_attribute($name, $object) {

        $_SESSION[$name] = $object;
    }

    public function get_attribute($name) {

        return $_SESSION[$name];
    }

    public function get_int_attribute($name) {

        if( isset($_SESSION[$name]) ){
            return intval($_SESSION[$name]);
        }

        return 0;
    }

    public function remove_attribute($name) {
        if( isset($_SESSION[$name]) ){
            unset($_SESSION[$name]);
        }
    }


    public function set_cookie($name, $object) {
        //Calculate 14 days in the future
        //seconds * minutes * hours * days + current time
        $in_two_weeks = ( 60 * 60 * 24 * 14 ) + time();
        setcookie($name, $object, $in_two_weeks);
        //$_COOKIE[$name] = $object;
    }

    public function get_cookie($name) {

        return $_COOKIE[$name];
    }

    public function get_int_cookie($name) {

        if ( isset($_COOKIE[$name]) ) {
            return intval($_COOKIE[$name]);
        }

        return 0;
    }

    public function remove_cookie($name) {
        setcookie($name, "", time()-3600);
        unset($_COOKIE[$name]);
    }

}

?>