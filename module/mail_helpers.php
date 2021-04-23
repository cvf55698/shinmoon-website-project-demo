<?php

use App\Mail\MailUtility;

if(!function_exists('send_mail')){
    function send_mail($email_to,$subject,$mail_content){
        $to = [$email_to,$email_to];
        return MailUtility::send_mail($to,$subject,$mail_content,'',true);
    }
}

?>