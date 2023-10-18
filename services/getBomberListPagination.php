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
	
	//controlli di validità	
	if($request == null || $request == "")
		die("1.0");
	
	//ricavo i dati salvati dal calculator solo per la singola categoria
	$output = array();
	$result = $db->query("SELECT count(*) as records FROM bomber_list WHERE competition = '".$request."'");
	$output = $result->fetch_array(MYSQLI_ASSOC);		
	
	//calcolo le pagine
	$pages = intval($output["records"]/100);
	$resto = $output["records"]%100;
	if($resto > 0)
		$pages++;
	
	echo $pages;
	
	
?>