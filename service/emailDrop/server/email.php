<?php

require_once 'template.php';
$tpl 		= new template();
$to 		= isset($_REQUEST['to'])?$_REQUEST['to']:"";
$from 		= isset($_REQUEST['from'])?$_REQUEST['from']:"";
$subject 	= isset($_REQUEST["subject"])?$_REQUEST["subject"]:"";
$body 		= isset($_REQUEST["body"])?$_REQUEST["body"]:"";
$type 		= isset($_REQUEST["type"])?$_REQUEST["type"]:"test";
$debug 		= isset($_REQUEST["debug"])?$_REQUEST["debug"]:"false";
$tpl->type	= $type;
if($body=="" || $type!="custome"){$body 		= $tpl->templateView($type);}
if($debug=="true"){	echo $body;exit;}
$mailSend	= $tpl->emailSend($to, $from, $subject, $body);
//var_dump($mailSend);
?>
<?php
/*
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/gmail/vendor/phpmailer/src/Exception.php';
require_once __DIR__ . '/gmail/vendor/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/gmail/vendor/phpmailer/src/SMTP.php';

// passing true in constructor enables exceptions in PHPMailer
$mail = new PHPMailer(true);
$mail->SMTPDebug = SMTP::DEBUG_SERVER; // for detailed debug output
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;

$mail->Username = 'vishwajeet9201@gmail.com'; // YOUR gmail email
$mail->Password = 'wbgactamcdtrwasa'; // YOUR gmail password

// Sender and recipient settings
$mail->setFrom($from);
$mail->addAddress($to);
$mail->addReplyTo($from); // to set the reply to

// Setting the email content
$mail->IsHTML(true);
$mail->Subject = $subject;
$mail->Body = $body;
$mail->AltBody = 'Plain text message body for non-HTML email client. Gmail SMTP email body.';

$mail->send();
echo "Email message sent.";
*/
?>

