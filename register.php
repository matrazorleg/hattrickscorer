<?php

	//script di registrazione ad Hattrick Scorer
	require 'engine/database.php';
	$post = $_POST;
	//innanzi tutto il captcha...	
	if($post["g-recaptcha-response"] != "") //posso verificare
	{
		$secret = '6LerAxYUAAAAAFdR5Dod4ruT5o9h2_fqqdNhMb9C';
		$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$post['g-recaptcha-response']);
		$responseData = json_decode($verifyResponse);
		if($responseData->success) //captcha verificato
		{
			//posso registrare l'utente!
			//ma prima verifico che non ci sia già la mail registrata
			
			$result = $db->query("SELECT * FROM `user` WHERE `email` = '".$post["register_email"]."'");
			if($result->num_rows > 0) //ho già una mail registrata identica...errore
			{
				header("Location: index.php?code=1");
			}
			else
			{
				//mail non c'è..verifico il nome utente
				$result = $db->query("SELECT * FROM `user` WHERE `username` = '".$post["register_username"]."'");
				if($result->num_rows > 0)
				{
					header("Location: index.php?code=2");
				}
				else
				{
					$result = $db->query("INSERT INTO `user`(`username`, `email`, `password`, `register_date`, `last_visit`, `user_code`) 
								VALUES ('".$post["register_username"]."','".$post["register_email"]."', '".md5($post["register_password"])."', NOW(), NOW(), '".sha1($post["register_username"])."')");
					
					header("Location: index.php?code=3");
				}
			}			
		}
		else //problemi sul captcha..
		{
			header("Location: index.php?code=0");
		}
	}
	else //manca il click sul captcha
	{
		header("Location: index.php?code=0");
	}

	
	
?>