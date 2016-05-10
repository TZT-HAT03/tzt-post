<?php
session_start();
date_default_timezone_set('Etc/UTC');


if (isset($_POST["naam"]) & isset($_POST["email"]) & isset($_POST["bericht"])) {
	require 'phpmailer/PHPMailerAutoload.php';

	//Create a new PHPMailer instance
	$mail = new PHPMailer;

	//Tell PHPMailer to use SMTP
	$mail->isSMTP();
	$mail->SMTPDebug = 2;
	$mail->Debugoutput = 'html';

	$mail->Host = 'smtp.gmail.com';
	$mail->Port = 587;

	$mail->SMTPSecure = 'tls';
	$mail->SMTPAuth = true;
	$mail->Username = "tztmailer@gmail.com";
	$mail->Password = "TZTisdebom";

	$mail->setFrom('info@tzt.nl', 'TZT | Berichten');

	$mail->addReplyTo($_POST["email"]);

	//Set who the message is to be sent to
	$mail->addAddress("elstreekie@gmail.com");

	//Set the subject line
	$mail->Subject = 'Contactbericht van ' . $_POST["naam"];

	$mail->msgHTML($_POST["bericht"]);

	$mail->AltBody = 'This is a plain-text message body';

	//send the message, check for errors
	if (!$mail->send()) {
	    $_SESSION["contact-geslaagd"] = false;
	} else {
	    $_SESSION["contact-geslaagd"] = true;
	}
	header("location: /contact");
}


