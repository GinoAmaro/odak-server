<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMail/Exception.php';
require 'PHPMail/PHPMailer.php';
require 'PHPMail/SMTP.php';

function enviarCorreo($correo,$token,$usuario)
{

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = 0;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'emzero1@gmail.com';                     //SMTP username
        $mail->Password   = '';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('emzero1@gmail.com', 'ODAK');
        $mail->addAddress($correo, $usuario);     //Add a recipient
        // $mail->addAddress('ellen@example.com');               //Name is optional
        // $mail->addReplyTo('info@example.com', 'Information');
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');

        //Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Validacion de Ingreso';
        $mail->Body    = 'Copia este token en el login y podrás acceder a tu cuenta:</b><br>'.$token;
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        return; echo 'Message has been sent';
    } catch (Exception $e) {
        return; echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

}
