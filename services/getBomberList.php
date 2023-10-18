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
	$pagination = $_GET["pagination"]; //range dei risultati (1-2-3-...)
	$userId = -1;
	
	//controlli di validità	
	if($request == null || $request == "" || $pagination == null || $pagination == "")
		die("1.0");
	
	$paginationSize = 100;
	$paginationFrom = (($pagination-1)*$paginationSize);
	$paginationTo = $pagination*$paginationSize;
	
	//ricavo i dati salvati dal calculator solo per la singola categoria
	$output = array();
	$result = $db->query("SELECT * FROM bomber_list WHERE competition = '".$request."' ORDER BY goals DESC, appearance");
	while($row = $result->fetch_array(MYSQLI_ASSOC))
	{
		$row["team_name"] = utf8_encode($row["team_name"]);
		$row["player_name"] = utf8_encode($row["player_name"]);
		
		$output[] = $row;
	}
		
	echo json_encode($output);
	
	
?>