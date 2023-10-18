<?php

	/* file di servizio richiamato dal javascript che ritorna i giocatori di una determinata categoria */
	require_once '../engine/database.php';
	require_once '../engine/constant.php';
			
	//debug
	/*
	error_reporting(E_ALL | E_WARNING | E_NOTICE);
	ini_set('display_errors', TRUE);
	*/
	
	$request = $_GET["request"]; //campionato, coppa, amichevole, nazionale, masters, tutti, ufficiali
	$userCode = $_GET["id"]; //userCode dell'utente loggato che vuole vedere la bomber list
	
	//controlli di validità	
	if($request == null || $request == "" || $userCode == null || $userCode == "")
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
	
	//ricavo i dati salvati dal calculator solo per la singola categoria
	$output = array();
	$result = $db->query("SELECT * FROM bomber_list WHERE competition = '".$request."' AND user_id = '".$userId."'");	
	echo json_encode($result->fetch_array(MYSQLI_ASSOC));
	
	
?>