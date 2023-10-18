<?php

	//funzioni per situazioni dove gli array compressi vanno a finire un un unico contenitore

	//compatta gli array dei goal
	/***
		$array -> array da analizzare
		$goalsGlobal -> raccolta di valori
	***/
	
	function compressCapArrayMultiple($array,&$capGlobal)
	{
		foreach($array as $key => $value)
		{
			if($value != null)
			{
				foreach($value as $player => $capEntry)
				{
					//array multiplo
					if(!isset($capGlobal[$player]))
					{
						$capGlobal[$player] = $capEntry;
					}
					else
					{
						$capGlobal[$player]["count"] += $capEntry["count"];
						if($capEntry["end_date"] > $capGlobal[$player]["end_date"])
							$capGlobal[$player]["end_date"] = $capEntry["end_date"];
						if($capEntry["start_date"] < $capGlobal[$player]["start_date"])
							$capGlobal[$player]["start_date"] = $capEntry["start_date"];
						
						if($capEntry["end_season"] > $capGlobal[$player]["end_season"])
							$capGlobal[$player]["end_season"] = $capEntry["end_season"];
						if($capEntry["start_season"] < $capGlobal[$player]["start_season"])
							$capGlobal[$player]["start_season"] = $capEntry["start_season"];
					}									
				}
			}
		}
	}
	
	//aggiunge la descrizione se il giocatore è presente in squadra o meno
	/*
		$playersList -> giocatori attualmente in rosa
		$capGlobal -> array capitano globali
	*/
	function detectInTeam($playersList,$capGlobal)
	{
		//devo "ordinalizzare" i due array
		//conversione ordinale -> l'array aveva come chiavi gli id dei giocatori
		$ordinale = array();
		foreach($capGlobal as $key => $value)
		{
			$inTeam = 0;
			if(isset($playersList[$key])) //è nella lista dei giocatori appartenenti alla squadra attuale
				$inTeam = 1;
			
			$startDate = explode(" ",$value["start_date"]); $startDate = $startDate[0];
			$endDate = explode(" ",$value["end_date"]); $endDate = $endDate[0];
						
			$ordinale[] = array("id" => $key, "name" => $value["name"], "count" => $value["count"], "start_date" => $startDate, "end_date" => $endDate,
								"start_season" => $value["start_season"], "end_season" => $value["end_season"],
								/* logici, non a database */	
								"in_team" => $inTeam);
		}
		return $ordinale;
	}
	
?>