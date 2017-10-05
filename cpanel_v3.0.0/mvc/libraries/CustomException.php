<?

/**
 *
 */

class CustomException extends Exception {

//throw new CustomException( $e->getMessage().' ['.$e->getFile().' : '.$e->getLine().'] ', $e );//from php 5.3 no need to custum
//throw new CustomException( 'Error in : .....: \n' . $e->getMessage() . "\n" );

    public function __construct($message = "", Exception $previous = null){

        if (!defined("NEW_LINE"))
            define("NEW_LINE", "\r\n");

        $this->message .= $message .' ['. $this->file .' : '. $this->line .' ]';

        if( $previous != null ) {
            $this->message .= NEW_LINE . $previous->getMessage();
        }

    }

    public function get_error_messages() {

        $errors = array();

        $ex = $this->getPrevious();

        while ( $ex != null ){
            $errors [] = $this->getMessage();
            $ex = $ex->getPrevious();
        }

        return $errors;
    }

}

?>
