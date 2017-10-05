<?php
/* 
 * 
 */

/**
 * Description of DataDB
 *
 * @author Ahmad
 */
class DataDB {

    protected static $database = DATABASE;
    
    public static function format_objects($buffer){

        $objects = array();

        try {

            for($i=0; $i<count($buffer); $i++){

                $object = (object) $buffer[$i];

                $objects[] = $object;
            }

        } catch (Exception $e) {
            throw new CustomException( 'Error in : format objects', $e );//from php 5.3 no need to custum
        }

        return $objects;
    }

}
?>
