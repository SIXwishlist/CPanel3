<?php

class HttpResponse {

    public $encoding = null;
    public $buffer = null;

    public $status = 0;

    private static $instance = null;
    private static $parsed = false;

    private function HttpResponse(){

        $encoding = DEFAULT_ENCODING;
        $buffer   = '';

        $this->status = 0;
    }

    public static function getInstance() {

        if(!self::$parsed) {
            self::$instance = new HttpResponse();
            self::$parsed = true;
        }

        return self::$instance;
    }

    public static function writeOutput() {
        echo $this->buffer;
    }

    public static function enableOutputBuffering() {
        ob_start();
    }

    public static function disableOutputBuffering() {
        ob_end_flush();
    }

    public static function cleanBuffer() {
        ob_clean();
    }

    public static function flushBuffer() {
        ob_flush();
    }

    public static function getBufferContent() {
        $this->buffer = ob_get_contents();
        return $this->buffer;
    }



}
?>
