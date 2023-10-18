<?php

	/* file di servizio richiamato dal javascript che ritorna i giocatori di una determinata categoria */
	require_once '../engine/database.php';
	require_once '../engine/constant.php';
	require_once '../engine/PHT/autoload.php';
	include 'utilsAppearanceSingle.php'; //funzioni per compattare ed ordinare i risultati;
	include 'utilsAppearanceMultiple.php';
	
	//debug
	/*
	error_reporting(E_ALL | E_WARNING | E_NOTICE);
	ini_set('display_errors', TRUE);
	*/
	
	$userCode = $_GET["id"]; //sha1 dell'utente
	$request = $_GET["request"]; //campionato, coppa, amichevole, nazionale, masters
	$order = $_GET["order"]; //asc , desc
	$field = $_GET["field"]; //campo su cui effettuare l'ordinamento (presenze / valutazione )
	$userId = -1;
	
	if($request == null || $request == "" || $order == null || $order == "" || $userCode == null || $userCode == "" || $field == null || $field == "")
		die("1.0");
	
	$result = $db->query("SELECT id FROM `user` WHERE `user_code` = '".$userCode."'");
	if($result->num_rows == 1) //codice fornito in get -> valido!
	{
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$userId = $row["id"];		
	}
	else
	{
		die("2.0");
	}
	
	$authParams = array();
	$result = $db->query("SELECT * FROM `auth` WHERE id = '".$userId."'");
	if($result)
	{
		$authParams = $result->fetch_array(MYSQLI_ASSOC);
    }
	
	//provo a fare una richiesta
	$config = array(
		'CONSUMER_KEY' => 'Vrpq4zIU78ySwlrFMvo4nY',
		'CONSUMER_SECRET' => 'dXbNHNqkQlBKZ5dHakJyIojFjvYCFHlukUTjJVkSaNy',
		'OAUTH_TOKEN' => $authParams["oauth_token"],
		'OAUTH_TOKEN_SECRET' => $authParams["oauth_token_secret"]
	);
	
	//classe principale
	$HT = new \PHT\PHT($config);
	$team = $HT->getSeniorTeam();
	
	//estraggo i giocatori attuali della squadra, per verificare se sono ancora attivi
	$teamPlayers = $team->getPlayers();
	$playersList = array();
	foreach($teamPlayers->getPlayers() as $key => $value)
	{
		$playersList[$value->getId()] = $value->getName();
	}
	
	//controllo la richiesta e l'ordinamento della tabella per poter procedere alla richiesta giusta
	
	//richiesta singola
	if($request == "campionato" || $request == "coppa" || $request == "amichevole" || $request == "masters" || $request == "nazionale")
	{
		//ricavo i dati salvati dal calculator solo per la singola categoria
		$result = $db->query("SELECT B.`p_".$request."` 
							  FROM `presence` AS B
							  WHERE B.`id` = '".$userId."'");
							  
		$array = $result->fetch_array(MYSQLI_ASSOC);
		$presenzeCategory = json_decode($array["p_".$request],true);
		$presenzeCategoryCompressed = compressPresenzeArraySingle($presenzeCategory,$playersList);		
		$outputString = generateTableString($presenzeCategoryCompressed,$field,$order,$request);
		echo json_encode($outputString);
	}	
	if($request == "ufficiali")
	{
		//ricavo i dati salvati dal calculator
		$result = $db->query("SELECT B.p_campionato, B.p_coppa, B.p_masters
							  FROM `presence` AS B
							  WHERE B.`id` = '".$userId."'");
							  
		$array = $result->fetch_array(MYSQLI_ASSOC);
		
		//ricavo i dati dell'ultima richiesta		
		$presenzeCampionato = json_decode($array["p_campionato"],true);
		$presenzeCoppa = json_decode($array["p_coppa"],true);
		$presenzeMasters = json_decode($array["p_masters"],true);
		
		$presenzeGlobal = array();
							
		compressPresenzeArrayMultiple($presenzeCampionato,$presenzeGlobal);
		compressPresenzeArrayMultiple($presenzeCoppa,$presenzeGlobal);
		compressPresenzeArrayMultiple($presenzeMasters,$presenzeGlobal);
		
		$outputString = generateTableStringMultiple($presenzeGlobal,$playersList,$field,$order,$request);
		echo json_encode($outputString);		
		
	}
	if($request == "tutti")
	{
		//ricavo i dati salvati dal calculator
		$result = $db->query("SELECT B.p_campionato, B.p_coppa, B.p_amichevole, B.p_masters 
							  FROM `presence` AS B
							  WHERE B.`id` = '".$userId."'");
							  
		$array = $result->fetch_array(MYSQLI_ASSOC);
		
		//ricavo i dati dell'ultima richiesta
				
		$presenzeCampionato = json_decode($array["p_campionato"],true);
		$presenzeCoppa = json_decode($array["p_coppa"],true);
		$presenzeAmichevole = json_decode($array["p_amichevole"],true);
		$presenzeMasters = json_decode($array["p_masters"],true);
		
		$presenzeGlobal = array();
									
		compressPresenzeArrayMultiple($presenzeCampionato,$presenzeGlobal);
		compressPresenzeArrayMultiple($presenzeCoppa,$presenzeGlobal);
		compressPresenzeArrayMultiple($presenzeAmichevole,$presenzeGlobal);
		compressPresenzeArrayMultiple($presenzeMasters,$presenzeGlobal);
		
		$outputString = generateTableStringMultiple($presenzeGlobal,$playersList,$field,$order,$request);
		echo json_encode($outputString);	
	}
	
?>