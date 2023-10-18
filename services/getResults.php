<?php

	/* file di servizio richiamato dal javascript che ritorna i giocatori di una determinata categoria */
	require_once '../engine/database.php';
	require_once '../engine/constant.php';
	require_once '../engine/PHT/autoload.php';
	
	/****
	funzioni
	****/
	
	function addResults($a1,$a2)
	{
		$a3 = array();
		foreach($a1 as $key => $value)
		{
			$a3[$key] = $value;
			if(isset($a2[$key]))
			{
				$a3[$key][0] += $a2[$key][0];
				$a3[$key][1] += $a2[$key][1];
				$a3[$key][2] += $a2[$key][2];
			}
		}
		return $a3;
	}
	
	function getDates($seasons,$db)
	{		
		$result = $db->query("SELECT * FROM `season`");
		$output = array();
		while($row = $result->fetch_array(MYSQLI_ASSOC))
		{
			if(in_array($row["season_id"],$seasons))
			{
				$output[] = $row;
			}
		}
		return $output;
	}
	
	/******/
		
	//debug
	/*
	error_reporting(E_ALL | E_WARNING | E_NOTICE);
	ini_set('display_errors', TRUE);
	*/
	
	$userCode = $_GET["id"]; //sha1 dell'utente
	$request = $_GET["request"]; //campionato, coppa, amichevole, nazionale, masters
	$userId = -1;
	
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
		
	
	//richiesta singola
	if($request == "campionato" || $request == "coppa" || $request == "amichevole" || $request == "masters" || $request == "nazionale")
	{
		//ricavo i dati salvati dal calculator solo per la singola categoria
		$result = $db->query("SELECT `m_".$request."` 
							  FROM `matches`
							  WHERE `id` = '".$userId."'");
		$array = $result->fetch_array(MYSQLI_ASSOC);
		
		$mResults = json_decode($array["m_".$request],true);
		
		$seasons = array_keys($mResults);
		$seasons = getDates($seasons,$db);
		
		$output = array();
		$output["data"] = $mResults;
		$output["seasons"] = $seasons;
		
		echo json_encode($output);		
		
	}	
	if($request == "ufficiali")
	{
		//ricavo i dati salvati dal calculator
		$result = $db->query("SELECT *
							  FROM `matches` AS A
							  WHERE A.`id` = '".$userId."'");
							  
		$array = $result->fetch_array(MYSQLI_ASSOC);
		
		//ricavo i dati dell'ultima richiesta
		$mCampionato = json_decode($array["m_campionato"],true);
		$mCoppa = json_decode($array["m_coppa"],true);
		$mMasters = json_decode($array["m_masters"],true);
				
		$mGlobal = addResults($mCampionato,$mCoppa);
		$mGlobal = addResults($mGlobal,$mMasters);
		
		$seasons = array_keys($mGlobal);
		$seasons = getDates($seasons,$db);
		
		$output = array();
		$output["data"] = $mGlobal;
		$output["seasons"] = $seasons;
		
		echo json_encode($output);		
		
	}
	if($request == "tutti")
	{
		//ricavo i dati salvati dal calculator
		$result = $db->query("SELECT *
							  FROM `matches` AS A
							  WHERE A.`id` = '".$userId."'");
							  
		$array = $result->fetch_array(MYSQLI_ASSOC);
		
		//ricavo i dati dell'ultima richiesta
		$mCampionato = json_decode($array["m_campionato"],true);
		$mCoppa = json_decode($array["m_coppa"],true);
		$mMasters = json_decode($array["m_masters"],true);
		$mAmichevoli = json_decode($array["m_amichevole"],true);
				
		$mGlobal = addResults($mCampionato,$mCoppa);
		$mGlobal = addResults($mGlobal,$mMasters);
		$mGlobal = addResults($mGlobal,$mAmichevoli);
		
		$seasons = array_keys($mGlobal);
		$seasons = getDates($seasons,$db);
		
		$output = array();
		$output["data"] = $mGlobal;
		$output["seasons"] = $seasons;
		
		echo json_encode($output);	
	}
	
	
	
?>