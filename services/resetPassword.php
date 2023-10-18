<?php

	/* file di servizio per il restore della password di un account dato username e email */
	require_once '../engine/database.php';
	require '../phpmailer/PHPMailerAutoload.php';
	
	function generateRandomString($length = 10) { //funzione per generare la password casuale da inviare via email
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
	
	//debug
	/*
	error_reporting(E_ALL | E_WARNING | E_NOTICE);
	ini_set('display_errors', TRUE);
	*/
	
	$username = $_GET["username"]; //nome utente per la ricerca
	$email = $_GET["email"]; //email per la ricerca
	
	if($username == null || $username == "" || $email == null || $email == "")
		die("1.0");
	$result = $db->query("SELECT id FROM `user` WHERE `username` = '".$username."' AND `email` = '".$email."'");
	if($result->num_rows == 1) //codice fornito in get -> valido!
	{
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$userId = $row["id"];		
		
		$newPassword = generateRandomString();
		
		//mando la mail di recupero all'indirizzo usato per la registrazione
		$mail = new PHPMailer;
		$mail->isSMTP();
		$mail->SMTPOptions = array(
          'ssl' => array(
          'verify_peer' => false,
          'verify_peer_name' => false,
          'allow_self_signed' => true
         )
        );
		//$mail->SMTPDebug = SMTP::DEBUG_SERVER;
		$mail->IsHTML(true);
		$mail->Host = 'mail.hattrickscorer.com'; 
		$mail->SMTPAuth = true;                               
		$mail->Username = 'hattrickscorer.com';               
		$mail->Password = 'h4ttr1ckscorer2017';             
		$mail->SMTPSecure = 'tls';                            
		$mail->Port = 587;                                
		$mail->setFrom("info@hattrickscorer.com","Hattrick Scorer Website");
		$mail->addAddress($email, $username);     // Add a recipient
		$mail->Subject = 'Restore password from www.hattrickscorer.com';
		$mail->Body    = 'Dear '.$username.',<br/>you can access your account using this password: "'.$newPassword.'".<br/><br/>Enjoy! Hattrick Scorer Team.';
		
		if($mail->send())
		{			
			//aggiorno la password sul database
			$db->query("UPDATE `user` SET `password` = '".md5($newPassword)."' WHERE `id` = '".$userId."'");			
			die("OK");
		}
		else
			die("2.0");
	}
	else
	{
		die("3.0");
	}
		
?>