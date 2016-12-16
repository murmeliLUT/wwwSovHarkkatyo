<?php
define("EMAIL", EMAIL YOU WANT TO USE TO SEND VALIDATIONS);
define("PASS", PASSWORD TO THAT EMAIL);
function send_e_Mail($email,$detail){

    require 'class.phpmailer.php';
    require 'class.smtp.php';

    //email sender, uses PHPMailer lib to send emails

    $mail = new PHPMailer;

    //$mail->SMTPDebug = 3;      for debugging

    $mail->isSMTP();

    //change this to ur smtp -server
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;

    //change these to your mail service's username and password
    $mail->Username = EMAIL; 
    $mail->Password = PASS;
    $mail->SMTPSecure = "tls";
    $mail->Port = 587;
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    //From field doesn't work if you aren't using open smtp -server without authentication
    $mail->From = "Forums@no-reply.com";
    $mail->FromName = "ForumOfGreenShades";

    $mail->addAddress($email, $email);

    $mail->isHTML(true);

    $mail->Subject = "Your validation key!";
    $mail->Body = "<p>".$detail."</p>";
    $mail->AltBody = "";

    if(!$mail->send()) {
        echo "Error: " . $mail->ErrorInfo;
    } else {
        echo "Message sent";
    }
}


?>
