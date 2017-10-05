<?php

/*
 *
 */

//include_once BASE_DIR.'/mvc/libraries/PHPMailer/PHPMailerAutoload.php';
require_once BASE_DIR.'/mvc/libraries/PHPMailer/PHPMailerAutoload.php';

/**
 * Description of ExcelUtil
 *
 * @author Arak
 */

class MailSender {

    public static function send_google_mail($sender_mail, $sender_pass, $sender_name, $receiver_mail, $subject, $message, $attachments){

        $status = 0;

        try{

            //SMTP needs accurate times, and the PHP time zone MUST be set
            //This should be done in your php.ini, but this is how to do it if you don't have access to that
            //date_default_timezone_set('Etc/UTC');

            //Create a new PHPMailer instance
            $mail = new PHPMailer;

            $mail->CharSet = 'utf-8';
            
            //Tell PHPMailer to use SMTP
            $mail->isSMTP();

            //Enable SMTP debugging
            // 0 = off (for production use)
            // 1 = client messages
            // 2 = client and server messages
            $mail->SMTPDebug = 0;

            //Ask for HTML-friendly debug output
            $mail->Debugoutput = 'html';

            //Set the hostname of the mail server
            $mail->Host = 'smtp.gmail.com';
            // use
            // $mail->Host = gethostbyname('smtp.gmail.com');
            // if your network does not support SMTP over IPv6

            //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
            $mail->Port = 587;

            //Set the encryption system to use - ssl (deprecated) or tls
            $mail->SMTPSecure = 'tls';

            //Whether to use SMTP authentication
            $mail->SMTPAuth = true;

            //Username to use for SMTP authentication - use full email address for gmail
            $mail->Username = $sender_mail;

            //Password to use for SMTP authentication
            $mail->Password = $sender_pass;

            //Set who the message is to be sent from
            //$mail->setFrom('from@example.com', 'First Last');
            $mail->setFrom($sender_mail, $sender_name);

            //Set an alternative reply-to address
            //$mail->addReplyTo('replyto@example.com', 'First Last');

            //Set who the message is to be sent to
            $mail->addAddress($receiver_mail, $sender_name);

            //Set the subject line
            //$mail->Subject = 'PHPMailer GMail SMTP test';
            $mail->Subject = $subject;

            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
            $mail->msgHTML($message);

            //Replace the plain text body with one created manually
            //$mail->AltBody = 'This is a plain-text message body';

            foreach ($attachments as $key => $attachment) {
                //Attach an image file
                $attachment_path = BASE_DIR . $attachment;
                $mail->addAttachment( $attachment_path );
            }

            //send the message, check for errors
            if (!$mail->send()) {
                //echo "Mailer Error: " . $mail->ErrorInfo;
                throw new CustomException( 'Mailer Error' . $mail->ErrorInfo );
                $status = 0;
            } else {
                $status = 1;
                //echo "Message sent!";
            }
            
        } catch (Exception $e) {
            throw new CustomException( 'Error in : send google mail', $e );//from php 5.3 no need to custum
        }

        return $status;
    }

}

?>