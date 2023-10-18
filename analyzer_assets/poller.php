<?php

	//file di servizio che mi restituisce il contenuto del file di interscambio tra l'analyzer e il calculator
	$userCode = $_GET["userCode"];
	
	$filename = $userCode.".txt";
	$pollingFile = fopen($filename, "r");
	$contents = fread($pollingFile, filesize($filename));
	fclose($pollingFile);
	
	echo $contents;

?>