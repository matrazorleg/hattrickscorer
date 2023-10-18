<?php

	/* file di servizio richiamato dal javascript che ritorna i giocatori di una determinata categoria */
	require_once '../engine/database.php';
	require_once '../engine/constant.php';
	require_once '../engine/PHT/autoload.php';
	include 'utilsScorerSingle.php'; //funzioni per compattare ed ordinare i risultati;
	include 'utilsScorerMultiple.php';
	
	//debug
	/*
	error_reporting(E_ALL | E_WARNING | E_NOTICE);
	ini_set('display_errors', TRUE);
	*/
	
	$userCode = $_GET["id"]; //sha1 dell'utente
	$request = $_GET["request"]; //campionato, coppa, amichevole, nazionale, masters
	$order = $_GET["order"]; //asc , desc
	$field = $_GET["field"]; //campo su cui effettuare l'ordinamento (goals / media / presenze)
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
		$result = $db->query("SELECT A.`".$request."`, B.`p_".$request."` 
							  FROM `request` AS A
							  LEFT JOIN `presence` AS B ON A.id = B.id
							  WHERE A.`id` = '".$userId."'");
							  
		$array = $result->fetch_array(MYSQLI_ASSOC);
		$goalCategory = json_decode($array[$request],true);
		$presenzeCategory = json_decode($array["p_".$request],true);

		$goalCategoryCompressed = compressGoalArraySingle($goalCategory,$playersList);
		$presenzeCategoryCompressed = compressPresenzeArraySingle($presenzeCategory);
		
		$outputString = generateTableString($goalCategoryCompressed,$presenzeCategoryCompressed,$field,$order,$request);
		echo json_encode($outputString);
	}	
	if($request == "ufficiali")
	{
		//ricavo i dati salvati dal calculator
		$result = $db->query("SELECT A.*, B.p_campionato, B.p_coppa, B.p_masters
							  FROM `request` AS A
							  LEFT JOIN `presence` AS B ON A.id = B.id
							  WHERE A.`id` = '".$userId."'");
							  
		$array = $result->fetch_array(MYSQLI_ASSOC);
		
		//ricavo i dati dell'ultima richiesta
		$goalCampionato = json_decode($array["campionato"],true);
		$goalCoppa = json_decode($array["coppa"],true);
		$goalMasters = json_decode($array["masters"],true);
		
		$presenzeCampionato = json_decode($array["p_campionato"],true);
		$presenzeCoppa = json_decode($array["p_coppa"],true);
		$presenzeMasters = json_decode($array["p_masters"],true);
		
		$goalsGlobal = array();
		$presenzeGlobal = array();
					
		//compatto le strutture per mostrare la classifica (l'array globale è passato per indirizzo)
		compressGoalArrayMultiple($goalCampionato,$goalsGlobal);
		compressGoalArrayMultiple($goalCoppa,$goalsGlobal);
		compressGoalArrayMultiple($goalMasters,$goalsGlobal);
		
		compressPresenzeArrayMultiple($presenzeCampionato,$presenzeGlobal);
		compressPresenzeArrayMultiple($presenzeCoppa,$presenzeGlobal);
		compressPresenzeArrayMultiple($presenzeMasters,$presenzeGlobal);
		
		$outputString = generateTableStringMultiple($goalsGlobal,$presenzeGlobal,$playersList,$field,$order,$request);
		echo json_encode($outputString);		
		
	}
	if($request == "tutti")
	{
		//ricavo i dati salvati dal calculator
		$result = $db->query("SELECT A.*, B.p_campionato, B.p_coppa, B.p_amichevole, B.p_masters 
							  FROM `request` AS A
							  LEFT JOIN `presence` AS B ON A.id = B.id
							  WHERE A.`id` = '".$userId."'");
							  
		$array = $result->fetch_array(MYSQLI_ASSOC);
		
		//ricavo i dati dell'ultima richiesta
		$goalCampionato = json_decode($array["campionato"],true);
		$goalCoppa = json_decode($array["coppa"],true);
		$goalAmichevole = json_decode($array["amichevole"],true);
		$goalMasters = json_decode($array["masters"],true);
		
		$presenzeCampionato = json_decode($array["p_campionato"],true);
		$presenzeCoppa = json_decode($array["p_coppa"],true);
		$presenzeAmichevole = json_decode($array["p_amichevole"],true);
		$presenzeMasters = json_decode($array["p_masters"],true);
		
		$goalsGlobal = array();
		$presenzeGlobal = array();
					
		//compatto le strutture per mostrare la classifica (l'array globale è passato per indirizzo)
		compressGoalArrayMultiple($goalCampionato,$goalsGlobal);
		compressGoalArrayMultiple($goalCoppa,$goalsGlobal);
		compressGoalArrayMultiple($goalAmichevole,$goalsGlobal);
		compressGoalArrayMultiple($goalMasters,$goalsGlobal);
		
		compressPresenzeArrayMultiple($presenzeCampionato,$presenzeGlobal);
		compressPresenzeArrayMultiple($presenzeCoppa,$presenzeGlobal);
		compressPresenzeArrayMultiple($presenzeAmichevole,$presenzeGlobal);
		compressPresenzeArrayMultiple($presenzeMasters,$presenzeGlobal);
		
		$outputString = generateTableStringMultiple($goalsGlobal,$presenzeGlobal,$playersList,$field,$order,$request);
		echo json_encode($outputString);	
	}
	
?>