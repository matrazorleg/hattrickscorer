<?php

	define(DATE_FORMAT, "Y-m-d");
		
	/* funzioni di utilità del servizio */
	
	/**************************************************************************/
	
	//compatta gli array dei goal
	/***
		$array -> array da compattare
		$playerList -> giocatori nell'attuale rosa
	***/
	
	function compressGoalArraySingle($array,$playersList)
	{
		$output = array();
		foreach($array as $key => $value)
		{
			if($value != null)
			{
				foreach($value as $player => $goalEntry)
				{
					if(!isset($output[$player]))
					{
						$output[$player] = $goalEntry;
					}
					else
					{
						$output[$player]["goals"] += $goalEntry["goals"];
						if($goalEntry["end_date"] > $output[$player]["end_date"]) //aggiorno se la data del gol è successiva
							$output[$player]["end_date"] = $goalEntry["end_date"];
						if($goalEntry["start_date"] < $output[$player]["start_date"]) 
							$output[$player]["start_date"] = $goalEntry["start_date"];
							
						if($goalEntry["end_season"] > $output[$player]["end_season"]) //aggiorno se la stagione del gol è successiva
							$output[$player]["end_season"] = $goalEntry["end_season"];
						if($goalEntry["start_season"] < $output[$player]["start_season"]) //aggiorno se la stagione del gol è successiva
							$output[$player]["start_season"] = $goalEntry["start_season"];
					}
				}
			}
		}
		
		//conversione ordinale -> l'array aveva come chiavi gli id dei giocatori
		$ordinale = array();
		foreach($output as $key => $value)
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
		$output = $ordinale;
		
		return $output;
	}
		
	//compatta gli array delle presenze
	/***
		$array -> array da compattare
	***/
	function compressPresenzeArraySingle($array)
	{
		$output = array();
		foreach($array as $key => $value)
		{
			if($value != null)
			{
				foreach($value as $player => $presenzaEntry)
				{
					if(!isset($output[$player]))
					{
						$output[$player] = $presenzaEntry;
					}
					else
					{
						$output[$player]["count"] += $presenzaEntry["count"];
						if($presenzaEntry["best_rating"] > $output[$player]["best_rating"]) //aggiorno se il giocatore ha fatto una prestazione migliore
							$output[$player]["best_rating"] = $presenzaEntry["best_rating"];
					}	
				}
			}
		}
				
		return $output;
	}	
	
	
	//genero la tabella in output in base ai valori passati al servizio
	function generateTableString($goalCategoryCompressed,$presenzeCategoryCompressed,$field,$order,$request)
	{
		//aggiungo all'array dei goal: media e presenze
		$output = array();
		foreach($goalCategoryCompressed as $entry)
		{
			if(!isset($presenzeCategoryCompressed[$entry["id"]]))
				continue;
			
			$entry["presenze"] = $presenzeCategoryCompressed[$entry["id"]]["count"];
			$entry["media"] = round(floatval($entry["goals"] / $presenzeCategoryCompressed[$entry["id"]]["count"]),2);
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