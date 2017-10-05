<?php
/*
 *
 */

/**
 * Description of JSONDocument
 *
 * @author Ahmad
 */

class JSONDocument {

    private $encoding = "UTF-8";

    private $json_string;

    private $json_array;

    private $converted = false;

    public function JSONDocument($json_array=null){
        if( $json_array != null ){
            $this->json_array = $json_array;
        }
    }


    public function get_json_array(){
        return $this->json_array;
    }

    public function set_json_array($json_array){
        $this->json_array = $json_array;
    }


    public function get_json_string(){
        return $this->json_string;
    }

    public function set_json_string($json_string){
        $this->json_string = $json_string;
    }


    public function get_encoding(){
        return $this->encoding;
    }

    public function set_encoding($encoding){
        $this->encoding = $encoding;
    }


    public function load_json_file($filename){

        try{

            $this->json_string = implode("", file($filename));

            $this->parse_json_string();

        } catch (Exception $e) {
            throw new CustomException( 'Error in : load json file', $e );//from php 5.3 no need to custum
        }

    }

    public function load_json_string($json_string){

        $this->json_string = $json_string;

        $this->parse_json_string();

    }

    private function parse_json_string(){

        $this->json_array = json_decode( $this->json_string, true );

    }

    public function convert_to_json_string(){

        $this->json_string = '';
   
        $this->json_string .= json_encode($this->json_array);

        $this->converted = true;
    }

    public function save_json_file($path, $filename){

        $state = 0;

        if($this->converted){

            try{

                if( !is_dir($path) ){
                    mkdir($path);
                }

                $fp      = fopen( $path.$filename, "w+" );
                $actual  = fwrite( $fp, $this->json_string );
                $success = fclose( $fp );

                //$state = (  $actual == count($this->json_string)  );
                $state = ( $actual > 0 );

            } catch (Exception $e) {
                throw new CustomException( 'Error in : save json file', $e );//from php 5.3 no need to custum
            }
        }

        return $state;
    }

}

?>
