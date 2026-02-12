<?php

require_once('class.phpmailer.php');

class Mailer {
    private $mail;


    public function __construct() {
        require './mail_config.php'; // Access global $username and $password
        $this->mail = new PHPMailer(true);
        $this->mail->IsSMTP();
        $this->mail = new PHPMailer(true); 
        $this->mail->IsSMTP();
        $this->mail->SMTPDebug  = 2;                     
        $this->mail->SMTPAuth   = true; 
        $this->mail->SMTPSecure = "ssl";                 
        $this->mail->Host       = "mail.tsandulescu.daw.ssmr.ro";      // TO CHANGE
        $this->mail->Port       = 465;         
        

        $this->mail->Username   = $username;
        $this->mail->Password   = $password;
    }

    public function sendMail($to, $nume, $subject, $message) {
        try {
            // Wrap message if any line exceeds 160 characters
            $message = wordwrap($message, 160, "<br />\n");
            
            $this->mail->AddAddress($to, $nume);
            $this->mail->SetFrom($this->username, 'Daw Email');// TO CHANGE
            $this->mail->Subject = $subject;
            $this->mail->AltBody = 'To view this post you need a compatible HTML viewer!'; // TO CHANGE
            $this->mail->MsgHTML($message);
            $this->mail->Send();
            echo "Message Sent OK</p>\n";
        } catch (phpmailerException $e) {
            echo $e->errorMessage(); //error from PHPMailer
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
