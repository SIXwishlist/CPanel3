<?php
/*
 *
 */

/**
 * Description of Resource
 *
 * @author Ahmad
 */
class Resource {

    private function Resource(){
    }

    public static function getResource($resourcePath, $array){

        $resource = '';

        try {

            ob_start();

            $tplFile = BASE_DIR.$resourcePath;

            include( $tplFile );

            $resource = ob_get_contents();

            ob_end_clean();

        } catch (Exception $e) {
            throw new CustomException( 'Error in : loading resource', $e );//from php 5.3 no need to custum;
        }

        return $resource;
    }
}
?>
