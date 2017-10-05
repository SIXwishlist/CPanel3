<?php

//include_once BASE_DIR.'/mvc/libraries/http/UploadedFile.php';

class HttpRequest {

    private $parameters = null;
    private $attributes = null;

    private $request_uri = null;
    private $remote_addr = null;
    private $script_name = null;
    private $user_agent  = null;

    private $files = null;

    private static $instance = null;
    private static $parsed   = false;

    private function HttpRequest() {
    }

    public static function get_instance() {

        if (!self::$parsed) {
            self::$instance = new HttpRequest();
            self::$instance->parse_variables();
            self::$instance->parse_files();
            self::$parsed = true;
        }

        return self::$instance;
    }

    private function parse_variables() {

        $this->parameters = array_merge($_GET, $_POST);

        $this->request_uri = $_SERVER["REQUEST_URI"];
        $this->remote_addr = $_SERVER['REMOTE_ADDR'];
        $this->script_name = $_SERVER["SCRIPT_NAME"];
        $this->user_agent  = $_SERVER["HTTP_USER_AGENT"];

    }

    private function parse_files() {

        $this->files = array();

        foreach ($_FILES as $key => $file) {

            if( ! is_array($file["name"]) ){

                $uploaded_file     = (object) $file;

                $this->files[$key] = $uploaded_file;

            }else{
                
                $uploaded_files = array();

                for($i=0; $i<count($file["name"]); $i++) {

                    $uploaded_file  = new stdClass();

                    $uploaded_file->name     = $file["name"][$i];
                    $uploaded_file->tmp_name = $file["tmp_name"][$i];
                    $uploaded_file->type     = $file["type"][$i];
                    $uploaded_file->size     = $file["size"][$i];
                    $uploaded_file->error    = $file["error"][$i];

                    $uploaded_files[] = $uploaded_file;

                }

                $this->files[$key] = $uploaded_files;

            }

        }        
        
        //Name:           '. $myFile["name"][$i]     . '<br />'
        //Temporary file: '. $myFile["tmp_name"][$i] . '<br />'
        //Type:           '. $myFile["type"][$i]     . '<br />'
        //Size:           '. $myFile["size"][$i]     . '<br />'
        //Error:          '. $myFile["error"][$i]    . '<br />'
    }

    public function set_attribute($name, $object) {

        $this->attributes[$name] = $object;
    }

    public function get_attribute($name) {

        return $this->attributes[$name];
    }

    public function add_parameter($name, $object) {

        $this->parameters[$name] = $object;
    }

    public function set_parameter($name, $object) {

        $this->parameters[$name] = $object;
    }

    public function get_parameter($name) {

        return $this->parameters[$name];
    }

    public function get_int_parameter($name) {

        if ($this->parameters[$name] != null) {
            return intval($this->parameters[$name]);
        }

        return 0;
    }

    public function get_double_parameter($name) {

        if ($this->parameters[$name] != null) {
            return doubleval($this->parameters[$name]);
        }

        return 0;
    }

    public function get_file($name) {

        return $this->files[$name];
    }

    public function get_file_name($name) {
        //$filename = $this->files[$name]["name"];
        $filename = $_FILES[$name]["name"];
        return $filename;
    }

    public function get_file_size($name) {
        //$filesize = @filesize( $this->files[$name]["tmp_name"] );
        $filesize = @filesize( $_FILES[$name]["tmp_name"] );
        return $filesize;
    }

    public function save_uploaded_file($temp_file, $path, $filename) {

        $status = 0;

        try {

            if (!is_dir($path)) {
                mkdir($path);
            }
            
            $status = move_uploaded_file($temp_file, $path . "/" . $filename);

        } catch (Exception $e) {
            throw new CustomException( 'Error in : save uploaded file', $e );//from php 5.3 no need to custum
        }

        return $status;
    }

    public function file_upload_error_message($error_code) {
        switch ($error_code) {
            case UPLOAD_ERR_INI_SIZE:
                return 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
            case UPLOAD_ERR_FORM_SIZE:
                return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
            case UPLOAD_ERR_PARTIAL:
                return 'The uploaded file was only partially uploaded';
            case UPLOAD_ERR_NO_FILE:
                return 'No file was uploaded';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Missing a temporary folder';
            case UPLOAD_ERR_CANT_WRITE:
                return 'Failed to write file to disk';
            case UPLOAD_ERR_EXTENSION:
                return 'File upload stopped by extension';
            default:
                return 'Unknown upload error';
        }
    }

    //////<form enctype="multipart/form-data" action="_URL_" method="post">
    //////   <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
    //////   Send this file: <input name="userfile" type="file" />
    //////   <input type="submit" value="Send File" />
    //////</form>
    //////
    //////Warning
    //////The MAX_FILE_SIZE is advisory to the browser, although PHP also checks it. Changing this on the browser size is quite easy, so you can never rely on files with a greater size being blocked by this feature. The PHP-settings for maximum-size, however, cannot be fooled. You should add the MAX_FILE_SIZE form variable anyway as it saves users the trouble of waiting for a big file being transferred only to find that it was too big and the transfer actually failed.
    ////
    //////$_FILES['userfile']['name']
    //////The original name of the file on the client machine.
    //////
    //////$_FILES['userfile']['type']
    //////The mime type of the file, if the browser provided this information. An example would be "image/gif".
    //////
    //////$_FILES['userfile']['size']
    //////The size, in bytes, of the uploaded file.
    //////
    //////$_FILES['userfile']['tmp_name']
    //////The temporary filename of the file in which the uploaded file was stored on the server.
    //////
    //////$_FILES['userfile']['error']
    //////The error code associated with this file upload. ['error'] was added in PHP 4.2.0


    public function get_remote_addr() {
        return $this->remote_addr;
    }

    public function get_request_uri() {
        return $this->request_uri;
    }

    public function get_script_name() {
        return $this->script_name;
    }

    public function get_user_agent() {
        return $this->user_agent;
    }

}

?>