<?php

	define(DATE_FORMAT, "Y-m-d");
		
	/* funzioni di utilità del servizio */
	
	/**************************************************************************/
	
	//compatta gli array dei goal
	/***
		$array -> array da compattare
		$playerList -> giocatori nell'attuale rosa
	***/
	
	function compressCapArraySingle($array,$playersList)
	{
		$output = array();
		foreach($array as $key => $value)
		{
			if($value != null)
			{
				foreach($value as $player => $capEntry)
				{
					if(!isset($output[$player]))
					{
						$output[$player] = $capEntry;
					}
					else
					{
						$output[$player]["count"] += $capEntry["count"];
						if($capEntry["end_date"] > $output[$player]["end_date"]) //aggiorno se la data del gol è successiva
							$output[$player]["end_date"] = $capEntry["end_date"];
						if($capEntry["start_date"] < $output[$player]["start_date"]) 
							$output[$player]["start_date"] = $capEntry["start_date"];
							
						if($capEntry["end_season"] > $output[$player]["end_season"]) //aggiorno se la stagione del gol è successiva
							$output[$player]["end_season"] = $capEntry["end_season"];
						if($capEntry["start_season"] < $output[$player]["start_season"]) //aggiorno se la stagione del gol è successiva
							$output[$player]["start_season"] = $capEntry["start_season"];
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
			
			$ordinale[] = array("id" => $key, "name" => $value["name"], "count" => $value["count"], "start_date" => $startDate, "end_date" => $endDate,
								"start_season" => $value["start_season"], "end_season" => $value["end_season"],
								/* logici, non a database */	
								"in_team" => $inTeam);
		}
		$output = $ordinale;
		
		return $output;
	}
			
?>