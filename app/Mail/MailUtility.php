<?php

namespace App\Mail;

use App\Result\ResultData;

class MailUtility{

    public static function send_mail($to,$subject,$mail_content,$alt,$is_html=true,$attachment_file_arr = [],$embedded_file_arr = [])
    {
        $mail_config = require CONFIG_PATH."mail.php";
        $mail = new PHPMailer();
        $result = new ResultData();
        try {
            $mail->Charset='UTF-8';
            $mail->SMTPDebug = $mail_config['debug_level'];
            $mail->IsSMTP();                                      
			$mail->SMTPAuth = $mail_config['auth'];     
			$mail->SMTPSecure = $mail_config['secure'];
			$mail->Host = $mail_config['host'];  
			$mail->Port = $mail_config['port'];
			$mail->Username = $mail_config['mail_address'];  
			$mail->Password = $mail_config['mail_app_password']; 
			$mail->From = $mail_config['mail_address'];
			$mail->FromName = $mail_config['from_name'];
            $mail->AddAddress($to[0], '');
            foreach($attachment_file_arr as $attachment){
                $mail->addAttachment($attachment[0],$attachment[1]);  
            }

            foreach($embedded_file_arr as $embedded){
                $mail->AddEmbeddedImage($embedded[0],$embedded[1],$embedded[2]);
            }

            $mail->WordWrap = $mail_config["word_wrap"];
            $mail->IsHTML($is_html);
            $mail->Subject = "=?utf-8?B?".base64_encode($subject)."?=";
			$mail->Body    = $mail_content;
			$mail->AltBody = $alt;
            if(!$mail->Send()){
                $result->setErrorMessage(["AA Message could not be sent. Mailer Error: {$mail->ErrorInfo}"]);
			}else{
                $result->setSuccess(true);
            }

        } catch (Exception $e) {
            $result->setErrorMessage(["BB Message could not be sent. Mailer Error: {$mail->ErrorInfo}"]);
        }
        
        return $result;
    }

}

?>