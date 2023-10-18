<?php

	//funzioni per situazioni dove gli array compressi vanno a finire un un unico contenitore
	
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
						if($presenzaEntry["end_date"] > $presenzeGlobal[$player]["end_date"])
						{
							$presenzeGlobal[$player]["end_season"] = $presenzaEntry["end_season"];
							$presenzeGlobal[$player]["end_date"] = $presenzaEntry["end_date"];
						}
						if($presenzaEntry["start_date"] < $presenzeGlobal[$player]["start_date"])
						{
							$presenzeGlobal[$player]["start_season"] = $presenzaEntry["start_season"];
							$presenzeGlobal[$player]["start_date"] = $presenzaEntry["start_date"];
						}
					}	
				}
			}
		}
	}	
	
	
	//genero la tabella in output in base ai valori passati al servizio
	function generateTableStringMultiple($presenzeGlobal,$playersList,$field,$order,$request)
	{
		//devo "ordinalizzare" i l'array
		//conversione ordinale -> l'array aveva come chiavi gli id dei giocatori
		$output = array();
		foreach($presenzeGlobal as $key => $value)
		{
			$inTeam = 0;
			if(isset($playersList[$key])) //è nella lista dei giocatori appartenenti alla squadra attuale
				$inTeam = 1;
		
			$output[] = array("id" => $key, "name" => $value["name"], "presenze" => $value["count"], "valutazione" => $value["best_rating"], "in_team" => $inTeam,
								"start_season" => $value["start_season"], "start_date" => date(DATE_FORMAT, strtotime($value["start_date"])), "end_season" => $value["end_season"], "end_date" => date(DATE_FORMAT, strtotime($value["end_date"])));
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