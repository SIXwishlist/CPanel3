<?php
/*
 *
 */

/**
 * Description of Mailer
 *
 * @author Ahmad
 */
class Mailer {

//example
//
//$headers  = 'MIME-Version: 1.0' . "\r\n";
//$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
//
//// Additional headers
//$headers .= 'To: Mary <mary@example.com>, Kelly <kelly@example.com>' . "\r\n";
//$headers .= 'From: Birthday Reminder <birthday@example.com>' . "\r\n";
//$headers .= 'Cc: birthdayarchive@example.com' . "\r\n";
//$headers .= 'Bcc: birthdaycheck@example.com' . "\r\n";

    public static function send_mail($from, $to, $subject, $message, $senderName=null, $cc=null, $bcc=null){

        $result = 0;

        try{
            $headers  = 'MIME-Version: 1.0' . chr(13) . chr(10);
            $headers .= 'Content-type: text/html; charset=' . DEFAULT_ENCODING . chr(13) . chr(10);

            // Additional headers
            //if( $to != null){
                //$headers .= 'To: '.$to.'' . chr(13) . chr(10);
            //}

            if( $senderName != null){
                $headers .= 'From: "' . $senderName . '" <' . $from . '>' . chr(13) . chr(10);
            }else{
                $headers .= 'From: <' . $from . '>' . chr(13) . chr(10);
            }

            if( $cc != null){
                $headers .= 'Cc: '.$cc.'' . chr(13) . chr(10);
            }
            if( $bcc != null){
                $headers .= 'Bcc: '.$bcc.'' . chr(13) . chr(10);
            }

            $additional_headers = $headers;

            $result = mail($to, $subject, $message, $additional_headers);

        } catch (Exception $e) {
            throw new CustomException( 'Error in : sending email', $e );//from php 5.3 no need to custum
        }

        return $result;
    }

}
?>
