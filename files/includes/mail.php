<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once '../phpmailer/Exception.php';
require_once '../phpmailer/PHPMailer.php';
require_once '../phpmailer/SMTP.php';

// Load Composer's autoloader
require_once '../../vendor/autoload.php';

function sendmail($email,$code)
{
	$mail = new PHPMailer(); // create a new object
	$mail->IsSMTP(); // enable SMTP
	$mail->SMTPDebug = 0; // debugging: 1 = errors and messages, 2 = messages only
	$mail->SMTPAuth = true; // authentication enabled
	$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
	$mail->Host = "smtp.gmail.com";
	$mail->Port = 465; // or 587
	$mail->IsHTML(true);
	$mail->Username = "contactprojectportfolio@gmail.com";
	$mail->Password = 'ProjectPortfolio@2k19';
	$mail->SetFrom("contactprojectportfolio@gmail.com");

	$mail->Subject = "Project Portfolio account verification";
	$mail->Body = "Your account verification link https://projectportfolio.cf/verify.php?code=".$code;
	$mail->AddAddress($email);
	if(!$mail->Send()) {
		return false;
	}
	return true;
}
?>