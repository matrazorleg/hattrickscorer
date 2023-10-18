<?php

	//funzioni di utilità per Hattrick Scorer
	
	function bomberListCheckMax($goals,$presenze)
	{
		$bomberMax = 0;
		$bomberRecord = array();
		
		for($i = 0; $i < count($goals); $i++)
		{
			if($goals[$i]["goals"] > $bomberMax)
			{
				$bomberRecord["player_id"] = $goals[$i]["id"];
				$bomberRecord["player_name"] = $goals[$i]["name"];
				$bomberRecord["goals"] = $goals[$i]["goals"];
				
				$history = array();				
				$history["start_date"] = $goals[$i]["start_date"];
				$history["start_season"] = $goals[$i]["start_season"];
				$history["end_date"] = $goals[$i]["end_date"];
				$history["end_season"] = $goals[$i]["end_season"];
				$bomberRecord["history"] = json_encode($history);
				
				$bomberRecord["in_team"] = $goals[$i]["in_team"];
								
				$bomberMax = $goals[$i]["goals"];
			}
		}		
		
		//se ha segnato posso dare per scontato che almeno una presenza l'ha fatta, quindi posso evitare controlli "inutili"
		$bomberRecord["appearance"] = $presenze[$bomberRecord["player_id"]]["count"];
		return $bomberRecord;
	}
	
	//torna valore per bomber list frutto di analisi multipla (indica se il giocatore è ancora appartenente alla squadra o meno)
	function bomberListCheckMaxMultiple($goals,$presenze,$playersList)
	{
		$bomberMax = 0;
		$bomberRecord = array();
		
		foreach($goals as $key => $value)
		{
			if($value["goals"] > $bomberMax)
			{
				$bomberRecord["player_id"] = $key;
				$bomberRecord["player_name"] = $value["name"];
				$bomberRecord["goals"] = $value["goals"];
				
				$history = array();
				$startDate = explode(" ",$value["start_date"]); $startDate = $startDate[0];
				$endDate = explode(" ",$value["end_date"]); $endDate = $endDate[0];
				
				$history["start_date"] = $startDate;
				$history["start_season"] = $value["start_season"];
				$history["end_date"] = $endDate;
				$history["end_season"] = $value["end_season"];
				$bomberRecord["history"] = json_encode($history);
				
				if(isset($playersList[$key])) //è nella lista dei giocatori appartenenti alla squadra attuale
					$bomberRecord["in_team"] = 1;
				else
					$bomberRecord["in_team"] = 0;
				
				$bomberMax = $value["goals"];
			}
			
		}
		
		//se ha segnato posso dare per scontato che almeno una presenza l'ha fatta, quindi posso evitare controlli "inutili"
		$bomberRecord["appearance"] = $presenze[$bomberRecord["player_id"]]["count"]; 	
		return $bomberRecord;
	}

?>