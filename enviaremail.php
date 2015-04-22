<?php
require("PHPMailer/PHPMailerAutoload.php");
function mandarEmail($CRregistrado,$observaciones,$estado,$municipio,$EfectoAdverso){

$mail = new PHPMailer();
//$mail->SMTPDebug = 1;
$mail->IsSMTP();                                      // set mailer to use SMTP
$mail->Host = "smtp.gmail.com";  // specify main and backup server
$mail->SMTPAuth = true;     // turn on SMTP authentication
$mail->SMTPSecure = "tls";
$mail->Username = "cenacom.pc@gmail.com";  // SMTP username
$mail->Password = "c3n4c0m123"; // SMTP password
$mail->Port = 587;

$mail->From = "cenacom.pc@gmail.com";
$mail->FromName = "Centro Nacional de Comunicaciones";
//receiver
$mail->AddAddress("cenacom.pc@gmail.com");
$mail ->AddAddress ("anr.administracion@cenapred.unam.mx");
//$mail ->AddAddress ("mespinosa@segob.gob.mx");


$mail->WordWrap = 50;                                 // set word wrap to 50 characters
$mail->IsHTML(true);                                  // set email format to HTML

$mail->Subject = "Nuevo Evento";
$mail->Body    = "Se ha agregado el nuevo registro n&uacute;mero <b>".$CRregistrado."</b> a CENACOM. <br/> <b>T&iacute;tulo: </b>".$EfectoAdverso." <br/><b>Estado:</b>".$estado.".<br/><b>Municipio:</b>".$municipio.".<br/><b>Observaciones: </b>".$observaciones.". <br/> Pruebas BETA, DE ENVIOS.";
$mail->AltBody = "Se ha agregado el nuevo registro n&uacute;mero <b>".$CRregistrado."<b> a CENACOM. <br/> <b>Titulo:</b>".$EfectoAdverso." <br/><b>Estado de :</b>".$estado.".<br/><b>Municipio de</b>:".$municipio.".<br/><b>Observaciones:</b>".$observaciones.". <br/> Pruebas BETA, DE ENVIOS.";

//Config
$mail->CharSet = 'UTF-8';

if(!$mail->Send())
{
   echo "El Mensaje no pudo ser enviado. <p>";
   echo "Error: " . $mail->ErrorInfo;
   exit;
}

//echo "Message has been sent";
}
?>