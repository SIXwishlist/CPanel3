<?php
/*
 *
 */

include_once BASE_DIR.'/mvc/libraries/qrcode/qrlib.php';

//include('./qrcode/qrlib.php');

define("", "");

/**
 * Description of FileUtil
 *
 * @author Ahmad
 */
class QRCodeUtil {

    private function QRCodeUtil(){
    }

    public static function create_qr($content, $path, $image, $level, $size){

        $types = array();
        
        try {

            // how to save PNG codes to server

            $file_path = $path .'/'. $image;
            
            if ( !file_exists($path) ) {
                mkdir($path);
            }
            //if (!is_dir($path)) {
            //    mkdir($path);
            //}
            
            // generating
            if( !file_exists($file_path) ) {
                QRcode::png($content, $file_path, QR_ECLEVEL_H, 8, 2); 
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : Create QR', $e );//from php 5.3 no need to custum
        }

        return $types;
    }

}
?>
