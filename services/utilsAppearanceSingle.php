<?php

	define(DATE_FORMAT, "Y-m-d");
		
	/* funzioni di utilità del servizio */
	
	/**************************************************************************/
	
	//compatta gli array delle presenze
	/***
		$array -> array da compattare
	***/
	function compressPresenzeArraySingle($array,$playersList)
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
						if($presenzaEntry["end_date"] > $output[$player]["end_date"])
						{
							$output[$player]["end_season"] = $presenzaEntry["end_season"];
							$output[$player]["end_date"] = $presenzaEntry["end_date"];
						}
						if($presenzaEntry["start_date"] < $output[$player]["start_date"])
						{
							$output[$player]["start_season"] = $presenzaEntry["start_season"];
							$output[$player]["start_date"] = $presenzaEntry["start_date"];
						}
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
		
			$ordinale[] = array("id" => $key, "name" => $value["name"], "presenze" => $value["count"], "valutazione" => $value["best_rating"], "in_team" => $inTeam,
								"start_season" => $value["start_season"], "start_date" => date(DATE_FORMAT, strtotime($value["start_date"])), "end_season" => $value["end_season"], "end_date" => date(DATE_FORMAT, strtotime($value["end_date"])));
		}
		$output = $ordinale;
				
		return $output;
	}	
	
	
	//genero la tabella in output in base ai valori passati al servizio
	function generateTableString($presenzeCategoryCompressed,$field,$order,$request)
	{
		//ordino in base ai filtri
		//bubblesort per ordinarli in base ai goals in maniera ascendente / discendente
		if($order == "desc")
		{
			for($i = 0; $i<count($presenzeCategoryCompressed); $i++)
			{
				for($j = 0; $j<count($presenzeCategoryCompressed)-1; $j++)
				{
					if($presenzeCategoryCompressed[$j][$field] < $presenzeCategoryCompressed[$j+1][$field])
					{
						$temp = $presenzeCategoryCompressed[$j];
						$presenzeCategoryCompressed[$j] = $presenzeCategoryCompressed[$j+1];
						$presenzeCategoryCompressed[$j+1] = $temp;
					}
				}
			}	
		}
		else
		{
			for($i = 0; $i<count($presenzeCategoryCompressed); $i++)
			{
				for($j = 0; $j<count($presenzeCategoryCompressed)-1; $j++)
				{
					if($presenzeCategoryCompressed[$j][$field] > $presenzeCategoryCompressed[$j+1][$field])
					{
						$temp = $presenzeCategoryCompressed[$j];
						$presenzeCategoryCompressed[$j] = $presenzeCategoryCompressed[$j+1];
						$presenzeCategoryCompressed[$j+1] = $temp;
					}
				}
			}
		}
						
		return $presenzeCategoryCompressed;		
	}
	
?>