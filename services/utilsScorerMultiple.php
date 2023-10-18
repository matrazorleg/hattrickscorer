<?php

	//funzioni per situazioni dove gli array compressi vanno a finire un un unico contenitore

	//compatta gli array dei goal
	/***
		$array -> array da analizzare
		$goalsGlobal -> raccolta di valori
	***/
	
	function compressGoalArrayMultiple($array,&$goalsGlobal)
	{
		foreach($array as $key => $value)
		{
			if($value != null)
			{
				foreach($value as $player => $goalEntry)
				{
					//array multiplo
					if(!isset($goalsGlobal[$player]))
					{
						$goalsGlobal[$player] = $goalEntry;
					}
					else
					{
						$goalsGlobal[$player]["goals"] += $goalEntry["goals"];
						if($goalEntry["end_date"] > $goalsGlobal[$player]["end_date"])
							$goalsGlobal[$player]["end_date"] = $goalEntry["end_date"];
						if($goalEntry["start_date"] < $goalsGlobal[$player]["start_date"])
							$goalsGlobal[$player]["start_date"] = $goalEntry["start_date"];
						
						if($goalEntry["end_season"] > $goalsGlobal[$player]["end_season"])
							$goalsGlobal[$player]["end_season"] = $goalEntry["end_season"];
						if($goalEntry["start_season"] < $goalsGlobal[$player]["start_season"])
							$goalsGlobal[$player]["start_season"] = $goalEntry["start_season"];
					}									
				}
			}
		}
	}
	
	/***
		$array -> array da analizzare
		$presenzeGlobal -> raccolta di valori
	***/
	function compressPresenzeArrayMultiple($array,&$presenzeGlobal)
	{
		foreach($array as $key => $value)
		{
			if($value != null)
			{
				foreach($value as $player => $presenzaEntry)
				{
					if(!isset($presenzeGlobal[$player]))
					{
						$presenzeGlobal[$player] = $presenzaEntry;
					}
					else
					{
						$presenzeGlobal[$player]["count"] += $presenzaEntry["count"];
						if($presenzaEntry["best_rating"] > $presenzeGlobal[$player]["best_rating"]) //aggiorno se il giocatore ha fatto una prestazione migliore
							$presenzeGlobal[$player]["best_rating"] = $presenzaEntry["best_rating"];
					}	
				}
			}
		}
	}	
	
	
	//genero la tabella in output in base ai valori passati al servizio
	function generateTableStringMultiple($goalsGlobal,$presenzeGlobal,$playersList,$field,$order,$request)
	{
		//devo "ordinalizzare" i due array
		//conversione ordinale -> l'array aveva come chiavi gli id dei giocatori
		$ordinale = array();
		foreach($goalsGlobal as $key => $value)
		{
			$inTeam = 0;
			if(isset($playersList[$key])) //è nella lista dei giocatori appartenenti alla squadra attuale
				$inTeam = 1;
			
			$startDate = explode(" ",$value["start_date"]); $startDate = $startDate[0];
			$endDate = explode(" ",$value["end_date"]); $endDate = $endDate[0];
						
			$ordinale[] = array("id" => $key, "name" => $value["name"], "goals" => $value["goals"], "start_date" => $startDate, "end_date" => $endDate,
								"start_season" => $value["start_season"], "end_season" => $value["end_season"],
								/* logici, non a database */	
								"in_team" => $inTeam);
		}
		$goalsGlobal = $ordinale;
				
		//aggiungo all'array dei goal: media e presenze
		//die(var_dump($presenzeGlobal));
		$output = array();
		foreach($goalsGlobal as $entry)
		{
			if(!isset($presenzeGlobal[$entry["id"]]))
				continue;
			
			$entry["presenze"] = $presenzeGlobal[$entry["id"]]["count"];
			$entry["media"] = round(floatval($entry["goals"] / $presenzeGlobal[$entry["id"]]["count"]),2);
			$output[] = $entry;
		}
		
		//ordino in base ai filtri
		//bubblesort per ordinarli in base ai goals in maniera ascendente / discendente
		if($order == "desc")
		{
			for($i = 0; $i<count($output); $i++)
			{
				for($j = 0; $j<count($output)-1; $j++)
				{
					if($output[$j][$field] < $output[$j+1][$field])
					{
						$temp = $output[$j];
						$output[$j] = $output[$j+1];
						$output[$j+1] = $temp;
					}
				}
			}	
		}
		else
		{
			for($i = 0; $i<count($output); $i++)
			{
				for($j = 0; $j<count($output)-1; $j++)
				{
					if($output[$j][$field] > $output[$j+1][$field])
					{
						$temp = $output[$j];
						$output[$j] = $output[$j+1];
						$output[$j+1] = $temp;
					}
				}
			}
		}
						
		return $output;		
	}
?>