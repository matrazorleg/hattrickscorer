<?php

if(!isset($_POST))
	header("Location: contact.php?e=1");

if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
	header("Location: contact.php?e=2");

if($_POST["email"] != "" && $_POST["name"] != "" && $_POST["message"] != "")
{
	require 'phpmailer/PHPMailerAutoload.php';

	$mail = new PHPMailer;
	//incredibilmente dava problemi...
	$mail->isSMTP();                                      // Set mailer to use SMTP
	
	$mail->Host = 'smtp.gmail.com';  			  // Specify main and backup SMTP servers
	$mail->SMTPAuth = true;                               // Enable SMTP authentication
	$mail->Username = 'htscorer@gmail.com';               // SMTP username
	$mail->Password = 'kawasakier6n';                         // SMTP password
	
	//parametri di sviluppo
	$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
	$mail->Port = 587;                                    // TCP port to connect to
	
	$mail->setFrom($_POST["email"], $_POST["name"]);
	$mail->addAddress('htscorer@gmail.com', 'Hattrick Scorer Team');     // Add a recipient
	$mail->addReplyTo($_POST["email"], $_POST["name"]);

	$mail->Subject = 'Mail from HattrickScorer.com';
	$mail->Body    = $_POST["message"];
	
	
	// DEBUG
	/*$mail->SMTPDebug = 1;                               // Enable verbose debug output
	$result = $mail->send();
	die(var_dump($result));*/
	///
	
	if($mail->send())
		header("Location: contact.php?s=1");
	else
		header("Location: contact.php?e=3");
	
}


?>