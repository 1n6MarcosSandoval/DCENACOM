<?php
/*$para      = 'waschbarzombie@gmail.com';
$titulo    = 'Nuevo Reporte';
$mensaje   = 'Se ha tenie un nuevo reporte';
$cabeceras = 'From: waschbarzombie@gmail.com' . "\r\n" .
    'Reply-To: carlos.mendez@sigsa.info' . "\r\n" .
    'X-Mailer: PHP/'.phpversion();
if(mail($para, $titulo, $mensaje, $cabeceras)){
	echo 'sí se envió mensaje';
}
else{
	echo 'no se envió mensaje';
}*/
require_once("PHPMailer/class.phpmailer.php");
require_once ("PHPMailer/PHPMailerAutoload.php");
include ('PHPMailer/class.smtp.php');

function mandarEmail(){

$mail = new PHPMailer();
//$mail->SMTPDebug = 1;

$mail->IsSMTP();                                      // set mailer to use SMTP
$mail->Host = "smtp.gmail.com";  // specify main and backup server
$mail->SMTPAuth = true;     // turn on SMTP authentication
$mail->SMTPSecure = "tls";
$mail->Username = "cenacom.pc@gmail.com";  // SMTP username
$mail->Password = "c3n4c0m123"; // SMTP password
$mail->Port = 587;

$mail->SetFrom = "cenacom.pc@gmail.com";
$mail->FromName = "CENACOM";
$mail->AddAddress("cenacom.pc@gmail.com"); //receiver

$mail->WordWrap = 50;                                 // set word wrap to 50 characters
$mail->IsHTML(true);                                  // set email format to HTML

$mail->Subject = "Here is the subject";
$mail->Body    = "This is the HTML message body <b>in bold!</b>";
$mail->AltBody = "This is the body in plain text for non-HTML mail clients";

if(!$mail->Send())
{
   echo "Message could not be sent. <p>";
   echo "Mailer Error: " . $mail->ErrorInfo;
   exit;
}

echo "Message has been sent";
}
?>