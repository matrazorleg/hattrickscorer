<?php
	//script di login ad Hattrick Scorer
	require 'engine/database.php';
	require_once 'engine/PHT/autoload.php';
	
	
	/* ============================================================ */
	function requireAccess($userId,$db,$userCode)
	{
		
		$config = array(
			'CONSUMER_KEY' => 'Vrpq4zIU78ySwlrFMvo4nY',
			'CONSUMER_SECRET' => 'dXbNHNqkQlBKZ5dHakJyIojFjvYCFHlukUTjJVkSaNy'
		);
		
		$HT = new \PHT\Connection($config);
		
		//produzione
		$auth = $HT->getPermanentAuthorization('http://www.hattrickscorer.com/engine/auth.php?userCode='.$userCode); // put your own url :)
		//sviluppo
		//$auth = $HT->getPermanentAuthorization('http://dev.hattrickscorer.com/engine/auth.php?userCode='.$userCode); // put your own url :)
		
		if ($auth === false) {
			// handle failed connection
			echo "Impossible to initiate chpp connection";
			exit();
		}
		
		$tmpToken = $auth->temporaryToken; // save this token somewhere (session, database, file, ...) it's needed in next step
		
		//salvo il token temporaneo, mi serve allo step successivo
		$db->query("DELETE FROM `auth` WHERE id = '".$userId."'"); //mi serve per liberarmi di eventuali tentativi falliti
		$db->query("INSERT INTO `auth`(`id`,`tmp_token`,`oauth_token`,`oauth_token_secret`) VALUES ('".$userId."','".$tmpToken."','','')");		
				
		header('Location: ' . $auth->url); // redirect to hattrick login page, or get the url and show a link on your site
		exit();		
	}		
	/* ============================================================ */
	
	
	
	$post = $_POST;
	if(!isset($post["login_username"]) || !isset($post["login_password"])) //mancano i dati passati dal form
	{
		header("Location: index.php?code=e"); //mancanza dei dati
	}
	else
	{
		//verifico le credenziali
		$result = $db->query("SELECT * FROM `user` WHERE `username` = '".$post["login_username"]."' AND `password` = '".md5($post["login_password"])."'");
		if($result->num_rows == 1) //utente registrato trovato
		{
			//faccio partire il sistema di autorizzazione CHPP
			$row = $result->fetch_array(MYSQLI_ASSOC);
			$userId = $row["id"];
			$userCode = $row["user_code"];
			
			//aggiorno la data di login dell'utente
			$result = $db->query("UPDATE `user` SET `last_visit` = NOW() WHERE id = '".$userId."'");
			
			//controllo se l'utente ha già autorizzato...
			$result = $db->query("SELECT * FROM `auth` WHERE `id` = ".$userId);	//provvisoria, poi metto a punto un sistema migliore
			if($result)
			{
				$row = $result->fetch_array(MYSQLI_ASSOC);
				if($row["oauth_token"] != "" && $row["oauth_token_secret"] != "") //l'utente ha già richiesto l'autorizzazione, quindi posso mandarlo all'analizzatore
					header('Location: analyzer.php?id='.$userCode);				
				else 
					requireAccess($userId,$db,$userCode);
			}
			else
				requireAccess($userId,$db,$userCode);
			
		}
		else
		{
			header("Location: index.php?code=n"); //credenziali non valide
		}
	}
	
?>