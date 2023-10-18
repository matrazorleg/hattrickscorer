<?php

	//questo file si occupa di ottenere le credenziali oauth permanenti per fare le richieste dei file XML
	//le salva nel database al corrispettivo utente

	require_once 'database.php';
	require_once 'PHT/autoload.php';
	
	$userCode = $_GET["userCode"];
	$userId = -1; //inizializzazione
	
	//recupero l'id dell'utente dal codice
	$result = $db->query("SELECT `id` FROM `user` WHERE `user_code` = '".$userCode."'");
	if($result->num_rows == 1)
	{
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$userId = $row["id"];
	}
	else
	{
		echo "Invalid userCode";
		exit();
	}
	
	$config = array(
		'CONSUMER_KEY' => 'Vrpq4zIU78ySwlrFMvo4nY',
		'CONSUMER_SECRET' => 'dXbNHNqkQlBKZ5dHakJyIojFjvYCFHlukUTjJVkSaNy',
		'LOG_TYPE' => 'file',
		'LOG_LEVEL' => \PHT\Log\Level::DEBUG,
		'LOG_FILE' => __DIR__ . '/pht.log',
	);
	$HT = new \PHT\Connection($config);
	
	// retrive the $tmpToken saved in previous step
	$tmpToken = "returned";
	$result = $db->query("SELECT `tmp_token` FROM `auth` WHERE id = '".$userId."'");	//provvisoria, poi metto a punto un sistema migliore
	if($result)
	{
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$tmpToken = $row["tmp_token"];
    }
	else
	{
		echo "Impossible retrieve token";
		exit();
	}    
	
	$access = $HT->getChppAccess($tmpToken, $_REQUEST['oauth_token'], $_REQUEST['oauth_verifier']);
	if ($access === false) {
		// handle failed connection
		echo "Impossible to confirm chpp connection";
		exit();
	}
	
	// if you want to save user credentials for future use
	// do it now by saving $access->oauthToken and $access->oauthTokenSecret
	// then you can request xml data
	$config['OAUTH_TOKEN'] = $access->oauthToken;
	$config['OAUTH_TOKEN_SECRET'] = $access->oauthTokenSecret;
	
	$db->query("UPDATE `auth` SET `oauth_token`= '".$access->oauthToken."',`oauth_token_secret`= '".$access->oauthTokenSecret."' WHERE id = '".$userId."'");
	 	
	header('Location: ../dashboard.php?id='.$userCode);
?>