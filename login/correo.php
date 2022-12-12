<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMail/Exception.php';
require 'PHPMail/PHPMailer.php';
require 'PHPMail/SMTP.php';


function enviarToken($correo,$token,$usuario)
{

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = 0;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'mail.labtouch.cl';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'validador@labtouch.cl';                     //SMTP username
        $mail->Password   = '';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('validador@labtouch.cl', 'ODAK');
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

function enviarSeguimiento($correo,$usuario,$seguimiento,$descripcion)
{

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = 0;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'mail.labtouch.cl';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'validador@labtouch.cl';                     //SMTP username
        $mail->Password   = '';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('validador@labtouch.cl', 'ODAK');
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
        $mail->Subject = 'Solicitud de Cotizacion';
        $mail->Body    = 'Hola '.$usuario.', haz cotizado lo siguiente:<br><br>'.$descripcion.'<br><br><br> Tu N° de seguimiento es: '.$seguimiento.'<br><br> Puedes revisar la solicitud en http://localhost:4200/odak/seguimiento';
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        return; echo 'Message has been sent';
    } catch (Exception $e) {
        return; echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

}

function enviarRepuesta($correo,$usuario,$seguimiento,$descripcion)
{

    $titulo = '';
    $texto = '';
    $texto_dos = '';
    $texto_tres = '';

    if ($descripcion=='Aceptada') {
        $titulo = 'ODAK - Solicitud Aceptada';
        $texto = 'Nos es grato informar que tu solicitud ha sido aceptada.';
        $texto_dos = 'Recuerda revisar tu número de seguimiento en http://localhost:4200/odak/seguimiento';
        $texto_tres = 'Tu N° de seguimiento es: ';
    }

    if ($descripcion=='Rechazada') {
        $titulo = 'ODAK - Solicitud Rechazada';
        $texto = 'Lamentamos informarte que tu solicitud ha sido rechazada.';
        $texto_dos = 'Siempre puedes volver a buscar lo que necesitas en http://localhost:4200/odak/pyme';
        $texto_tres = 'Nos vemos pronto!.';
        $seguimiento = '';
    }

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = 0;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'mail.labtouch.cl';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'validador@labtouch.cl';                     //SMTP username
        $mail->Password   = '';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('validador@labtouch.cl', 'ODAK');
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
        $mail->Subject = $titulo;
        $mail->Body    = 'Hola '.$usuario.'. '.$texto.'<br><br>'.$texto_dos.'<br><br>'.$texto_tres.'<b>'.$seguimiento.'</b>';
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        return; echo 'Message has been sent';
    } catch (Exception $e) {
        return; echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

}