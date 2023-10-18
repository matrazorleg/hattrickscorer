<?php

	//lato model dello script...ho lo sha1 trovo l'id...
	require_once 'engine/database.php';
	require_once 'engine/constant.php';
	require_once 'engine/PHT/autoload.php';
	
	//necessari per bomber list
	require_once 'engine/manipulator.php';
	require_once 'services/utilsScorerSingle.php';
	require_once 'services/utilsScorerMultiple.php';
	
	
	//siccome viene eseguito con exec, non posso passargli il parametro in get, devo usare gli argv della funzione
	//$argv -> 0 nome dello script, 1 primo parametro, 2 secondo parametro,....
	
	$userCode = $argv[1]; 
	//$userCode = $_GET["id"]; //DEBUG DA BROWSER
	//$userId = -1;
	
	$result = $db->query("SELECT id FROM `user` WHERE `user_code` = '".$userCode."'");
	if($result->num_rows == 1) //codice fornito in get -> valido!
	{
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$userId = $row["id"];		
	}
	else
	{
		echo "Invalid userCode";
		exit();
	}
	
	//predispongo il file su cui "analyzer" fa polling per conoscere lo stato dell'elaborazione
	$pollingFile = fopen("analyzer_assets/".$userCode.".txt", "w");
	fwrite($pollingFile, "REQUESTING DATA");
	fclose($pollingFile);
		
	//analisi del calcolo
	$today = date("Y-m-d 00:00:00");
		
	$authParams = array();
	$result = $db->query("SELECT * FROM `auth` WHERE id = '".$userId."'");
	if($result)
	{
		$authParams = $result->fetch_array(MYSQLI_ASSOC);
    }
	
	//provo a fare una richiesta
	$config = array(
		'CONSUMER_KEY' => 'Vrpq4zIU78ySwlrFMvo4nY',
		'CONSUMER_SECRET' => 'dXbNHNqkQlBKZ5dHakJyIojFjvYCFHlukUTjJVkSaNy',
		'OAUTH_TOKEN' => $authParams["oauth_token"],
		'OAUTH_TOKEN_SECRET' => $authParams["oauth_token_secret"]
	);
	
	//classe principale
	$HT = new \PHT\PHT($config);
	
	//informazioni generali
	$user = $HT->getUser();
	$team = $HT->getSeniorTeam();
	$teamId = $team->getId();
	$teamName = $team->getName();
	$logoUrl = $team->getLogoUrl();
			
	//controllo se l'utente ha già fatto una ricerca una volta
	$result = $db->query("SELECT A.*, B.p_campionato, B.p_coppa, B.p_amichevole, B.p_masters
						  FROM `request` AS A
						  LEFT JOIN `presence` AS B ON A.id = B.id
						  WHERE A.`id` = '".$userId."'");
						  
	$request = $result->fetch_array(MYSQLI_ASSOC);
	if($request != null && isset($request["last_request"])) //cerco dalla data dell'ultima richiesta in poi...
	{
		$dataIniziale = $request["last_request"];
				
		$result = $db->query("SELECT * FROM `season` WHERE `date_start` <= '".$dataIniziale."' && `date_end` >= '".$dataIniziale."'");
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$stagioneIniziale = $row["season_id"];
		
		$result = $db->query("SELECT * FROM `season` WHERE `date_start` <= '".$today."' && `date_end` >= '".$today."'");
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$stagioneFinale = $row["season_id"];
	}
	else //cerco dalla data di attivazione dell'utente in poi...
	{
		//data di attivazione del team
		$activationDate = $team->getActivationDate();
		$activationDate = explode(" ",$activationDate);
		$activationDate = $activationDate[0]; //con questa mossa ho eliminato il tempo
				
		//in base alle date devo identificare le stagioni
		//poi richiedere i match per ogni stagione...
		
		$result = $db->query("SELECT * FROM `season` WHERE `date_start` <= '".$activationDate."' && `date_end` >= '".$activationDate."'");
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$stagioneIniziale = $row["season_id"];
		
		$result = $db->query("SELECT * FROM `season` WHERE `date_start` <= '".$today."' && `date_end` >= '".$today."'");
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$stagioneFinale = $row["season_id"];
	}
	
	//in ogni caso ora devo trovare la nazionalità dell'utente per settare correttamente l'offset tra la stagione italiana (svezia) e la sua:
	$country = $team->getCountry();
	$countryName = $team->getCountryName();
	$leagueId = $team->getLeagueId();
	$seasonOffset = $country->getSeasonOffset();
	//scalo l'offset
	$stagioneIniziale += $seasonOffset;
	$stagioneFinale += $seasonOffset;
	
	//ricavo i dati dell'ultima richiesta
	$goalCampionato = array();
	$goalAmichevole = array();
	$goalCoppa = array();
	$goalMasters = array();
	
	$presenzeCampionato = array();
	$presenzeAmichevole = array();
	$presenzeCoppa = array();
	$presenzeMasters = array();
	
	if($request != null && isset($request["last_request"]))
	{
		$goalCampionato = json_decode($request["campionato"],true);
		$goalAmichevole = json_decode($request["amichevole"],true);
		$goalCoppa = json_decode($request["coppa"],true);
		$goalMasters = json_decode($request["masters"],true);
		
		$presenzeCampionato = json_decode($request["p_campionato"],true);
		$presenzeAmichevole = json_decode($request["p_amichevole"],true);
		$presenzeCoppa = json_decode($request["p_coppa"],true);
		$presenzeMasters = json_decode($request["p_masters"],true);
				
		//devo pulire i dati dalla stagione iniziale a quella finale...solitamente pulisco i dati dell'ultima stagione nel normale dei casi
		for($i = $stagioneIniziale; $i<=$stagioneFinale; $i++)
		{
			$goalCampionato[$i] = null;
			$goalAmichevole[$i] = null;
			$goalCoppa[$i] = null;
			$goalMasters[$i] = null;
			
			$presenzeCampionato[$i] = null;
			$presenzeAmichevole[$i] = null;
			$presenzeCoppa[$i] = null;
			$presenzeMasters[$i] = null;
		}
	}
	
	//comincio la richiesta	
	for($i = $stagioneIniziale; $i <= $stagioneFinale; $i++)
	//for($i = $stagioneIniziale; $i <= $stagioneIniziale+1; $i++) //DEBUGPURP.
	{					
		$matchStagionali = $team->getMatchesArchive(null,null,$i);		
		
		//polling file
		$pollingFile = fopen("analyzer_assets/".$userCode.".txt", "w");
		fwrite($pollingFile, "G-".$i."-".$stagioneFinale);
		fclose($pollingFile);
		
		foreach($matchStagionali->getMatches() as $key => $value)
		{
			//variabili di calcolo
			$goals = 0; //suppongo nessuna rete
			$matchPlayers = null; //suppongo nessuna formazione
			
			//dati ricavati
			$homeTeamId = $value->getHomeTeamId();
			$matchType = $value->getType();					
			$match = $value->mat_getMatch(false);
			$matchDate = $match->getStartDate(); //data del match
			
			//passo al match successivo se la data del match è precedente alla data di registrazione dell'utente, registrazione a stagione in corso
			if($i == $stagioneIniziale && $matchDate < $activationDate)
				continue;
						
			if($homeTeamId == $teamId) //giocava in casa...
			{
				$goals = $value->getHomeGoals();
				$matchPlayers = $match->getHomeTeam()->getLineup()->mg_getStartingPlayers();
			}
			else //giocava in trasferta...
			{
				$goals = $value->getAwayGoals();
				$matchPlayers = $match->getAwayTeam()->getLineup()->mg_getStartingPlayers();
			}	

			//aggiorno la lista delle presenze
			// NB -> le partite vinte a tavolino non contano nelle presenze
			foreach($matchPlayers as $lineupPlayer)
			{
				$idPlayer = $lineupPlayer->getId();
				$playerRatingStars = $lineupPlayer->getRatingStars();
				
				if($playerRatingStars > 0)
				{
					//in base alla competizione lo vado ad inserire nella struttura dati più adatta
					if($matchType == HS_CAMPIONATO || $matchType == HS_SPAREGGIO)
					{
						if(isset($presenzeCampionato[$i][$idPlayer])) //inserisco nella lista presenze
						{
							$presenzeCampionato[$i][$idPlayer]["count"] += 1; //aumento il numero di presenze
							if($playerRatingStars > $presenzeCampionato[$i][$idPlayer]["best_rating"]) //aggiorno la miglior valutazione se maggiore...
								$presenzeCampionato[$i][$idPlayer]["best_rating"] = $playerRatingStars;
							$presenzeCampionato[$i][$idPlayer]["end_season"] = $i;
							$presenzeCampionato[$i][$idPlayer]["end_date"] = $matchDate;							
						}
						else
						{							
							$presenzeCampionato[$i][$idPlayer] = array("count" => 1, "best_rating" => $playerRatingStars, "name" => $lineupPlayer->getName(),
																		"start_season" => $i, "start_date" => $matchDate, "end_season" => $i, "end_date" => $matchDate); ///problema con il rating null -> risolto moddando la libreria
						}
					}
					else if($matchType == HS_COPPA)
					{
						if(isset($presenzeCoppa[$i][$idPlayer]))
						{
							$presenzeCoppa[$i][$idPlayer]["count"] += 1;
							if($playerRatingStars > $presenzeCoppa[$i][$idPlayer]["best_rating"])
								$presenzeCoppa[$i][$idPlayer]["best_rating"] = $playerRatingStars;
							$presenzeCoppa[$i][$idPlayer]["end_season"] = $i;
							$presenzeCoppa[$i][$idPlayer]["end_date"] = $matchDate;	
						}
						else
						{							
							$presenzeCoppa[$i][$idPlayer] = array("count" => 1, "best_rating" => $playerRatingStars, "name" => $lineupPlayer->getName(),
																	"start_season" => $i, "start_date" => $matchDate, "end_season" => $i, "end_date" => $matchDate);
						}
					}
					else if($matchType == HS_AMICHEVOLE_NORMALE || $matchType == HS_AMICHEVOLE_COPPA || $matchType == HS_INTERNAZIONALE_NORMALE || $matchType == HS_INTERNAZIONALE_COPPA)
					{
						if(isset($presenzeAmichevole[$i][$idPlayer]))
						{
							$presenzeAmichevole[$i][$idPlayer]["count"] += 1;
							if($playerRatingStars > $presenzeAmichevole[$i][$idPlayer]["best_rating"])
								$presenzeAmichevole[$i][$idPlayer]["best_rating"] = $playerRatingStars;
							$presenzeAmichevole[$i][$idPlayer]["end_season"] = $i;
							$presenzeAmichevole[$i][$idPlayer]["end_date"] = $matchDate;	
						}
						else
						{							
							$presenzeAmichevole[$i][$idPlayer] = array("count" => 1, "best_rating" => $playerRatingStars, "name" => $lineupPlayer->getName(),
																		"start_season" => $i, "start_date" => $matchDate, "end_season" => $i, "end_date" => $matchDate);
						}
					}
					else if($matchType == HS_MASTERS)
					{
						if(isset($presenzeMasters[$i][$idPlayer]))
						{
							$presenzeMasters[$i][$idPlayer]["count"] += 1;
							if($playerRatingStars > $presenzeMasters[$i][$idPlayer]["best_rating"])
								$presenzeMasters[$i][$idPlayer]["best_rating"] = $playerRatingStars;
							$presenzeMasters[$i][$idPlayer]["end_season"] = $i;
							$presenzeMasters[$i][$idPlayer]["end_date"] = $matchDate;	
						}
						else
						{							
							$presenzeMasters[$i][$idPlayer] = array("count" => 1, "best_rating" => $playerRatingStars, "name" => $lineupPlayer->getName(),
																	"start_season" => $i, "start_date" => $matchDate, "end_season" => $i, "end_date" => $matchDate);
						}
					}
				}//end player rating > 0
			} //end match lineup
			
			//aggiorno le presenze per eventuali giocatori espulsi
			if($match->getRedCardNumber() > 0) //ci sono stati giocatori espulsi, devo aggiornare le presenze
			{
				$cards = $match->getRedCards();
				foreach($cards as $card)
				{
					if($card->getTeamId() == $teamId)
					{
						$idPlayer = $card->getPlayerId();
						
						//in base alla competizione lo vado ad inserire nella struttura dati più adatta
						if($matchType == HS_CAMPIONATO || $matchType == HS_SPAREGGIO)
						{
							if(isset($presenzeCampionato[$i][$idPlayer])) //inserisco nella lista presenze
							{
								$presenzeCampionato[$i][$idPlayer]["count"] += 1; //aumento il numero di presenze
								$presenzeCampionato[$i][$idPlayer]["end_season"] = $i;
								$presenzeCampionato[$i][$idPlayer]["end_date"] = $matchDate;							
							}
							else
							{							
								$presenzeCampionato[$i][$idPlayer] = array("count" => 1, "best_rating" => 0, "name" => $card->getPlayerName(),
																			"start_season" => $i, "start_date" => $matchDate, "end_season" => $i, "end_date" => $matchDate);
							}
						}
						else if($matchType == HS_COPPA)
						{
							if(isset($presenzeCoppa[$i][$idPlayer]))
							{
								$presenzeCoppa[$i][$idPlayer]["count"] += 1;
								$presenzeCoppa[$i][$idPlayer]["end_season"] = $i;
								$presenzeCoppa[$i][$idPlayer]["end_date"] = $matchDate;	
							}
							else
							{							
								$presenzeCoppa[$i][$idPlayer] = array("count" => 1, "best_rating" => 0, "name" => $card->getPlayerName(),
																		"start_season" => $i, "start_date" => $matchDate, "end_season" => $i, "end_date" => $matchDate);
							}
						}
						else if($matchType == HS_AMICHEVOLE_NORMALE || $matchType == HS_AMICHEVOLE_COPPA || $matchType == HS_INTERNAZIONALE_NORMALE || $matchType == HS_INTERNAZIONALE_COPPA)
						{
							if(isset($presenzeAmichevole[$i][$idPlayer]))
							{
								$presenzeAmichevole[$i][$idPlayer]["count"] += 1;
								$presenzeAmichevole[$i][$idPlayer]["end_season"] = $i;
								$presenzeAmichevole[$i][$idPlayer]["end_date"] = $matchDate;	
							}
							else
							{							
								$presenzeAmichevole[$i][$idPlayer] = array("count" => 1, "best_rating" => 0, "name" => $card->getPlayerName(),
																			"start_season" => $i, "start_date" => $matchDate, "end_season" => $i, "end_date" => $matchDate);
							}
						}
						else if($matchType == HS_MASTERS)
						{
							if(isset($presenzeMaster[$i][$idPlayer]))
							{
								$presenzeMasters[$i][$idPlayer]["count"] += 1;
								$presenzeMasters[$i][$idPlayer]["end_season"] = $i;
								$presenzeMasters[$i][$idPlayer]["end_date"] = $matchDate;	
							}
							else
							{							
								$presenzeMasters[$i][$idPlayer] = array("count" => 1, "best_rating" => 0, "name" => $card->getPlayerName(),
																		"start_season" => $i, "start_date" => $matchDate, "end_season" => $i, "end_date" => $matchDate);
							}
							
						}
					}
				}
			}
			//end calcolo presenze 
			
			
			//procedo con il calcolo dei goal			
			if($goals > 0) //è stato segnato almeno un goal, vado a vedere chi erano i marcatori
			{
								
				foreach($match->getGoals() as $goalKey => $goalValue)
				{
					//cercare i marcatori e verificare se sono della squadra
					//se si inserirli in una struttura dati apposita, tenendo conto della tipologia di partita 
					$scorerTeamId = $goalValue->getScorerTeamId();						
					
					if($scorerTeamId == $teamId)
					{
						$scorerName = $goalValue->getScorerName();
						$scorerId = $goalValue->getScorerId();
												
						//in base alla competizione lo vado ad inserire nella struttura dati più adatta
						if($matchType == HS_CAMPIONATO || $matchType == HS_SPAREGGIO)
						{
							if(isset($goalCampionato[$i][$scorerId]))
							{
								$goalCampionato[$i][$scorerId]["goals"] += 1;
								$goalCampionato[$i][$scorerId]["end_date"] = $matchDate;
								$goalCampionato[$i][$scorerId]["end_season"] = $i;
							}
							else
							{
								$goalCampionato[$i][$scorerId] = array("name" => $scorerName, "goals" => 1, "start_date" => $matchDate, "end_date" => $matchDate,
																		"start_season" => $i, "end_season" => $i);
							}
						}
						else if($matchType == HS_COPPA)
						{
							if(isset($goalCoppa[$i][$scorerId]))
							{
								$goalCoppa[$i][$scorerId]["goals"] += 1;
								$goalCoppa[$i][$scorerId]["end_date"] = $matchDate;
								$goalCoppa[$i][$scorerId]["end_season"] = $i;
							}
							else
							{
								$goalCoppa[$i][$scorerId] = array("name" => $scorerName, "goals" => 1, "start_date" => $matchDate, "end_date" => $matchDate,
																		"start_season" => $i, "end_season" => $i);
							}
						}
						else if($matchType == HS_AMICHEVOLE_NORMALE || $matchType == HS_AMICHEVOLE_COPPA || $matchType == HS_INTERNAZIONALE_NORMALE || $matchType == HS_INTERNAZIONALE_COPPA)
						{
							if(isset($goalAmichevole[$i][$scorerId]))
							{
								$goalAmichevole[$i][$scorerId]["goals"] += 1;
								$goalAmichevole[$i][$scorerId]["end_date"] = $matchDate;
								$goalAmichevole[$i][$scorerId]["end_season"] = $i;
							}
							else
							{
								$goalAmichevole[$i][$scorerId] = array("name" => $scorerName, "goals" => 1, "start_date" => $matchDate, "end_date" => $matchDate,
																		"start_season" => $i, "end_season" => $i);
							}
						}
						else if($matchType == HS_MASTERS)
						{
							if(isset($goalMasters[$i][$scorerId]))
							{
								$goalMasters[$i][$scorerId]["goals"] += 1;
								$goalMasters[$i][$scorerId]["end_date"] = $matchDate;
								$goalMasters[$i][$scorerId]["end_season"] = $i;
							}
							else
							{
								$goalMasters[$i][$scorerId] = array("name" => $scorerName, "goals" => 1, "start_date" => $matchDate, "end_date" => $matchDate,
																		"start_season" => $i, "end_season" => $i);
							}
						}
						
					}
					
				} //end analisi goals			
							
			} //end "se sono stati segnati goals"	
			
		} //end analisi match
				
	} //end analisi stagione
		
		
	//salvo i dati dell'analisi statistica nel database
	$db->query("DELETE FROM `request` WHERE id = '".$userId."'"); //elimino la vecchia richiesta
	$db->query("INSERT INTO `request`(`id`, `last_request`, `campionato`, `coppa`, `amichevole`, `masters`) VALUES
				('".$userId."','".date("Y-m-d")."','".mysqli_real_escape_string($db,json_encode($goalCampionato))."','".mysqli_real_escape_string($db,json_encode($goalCoppa))."',
				'".mysqli_real_escape_string($db,json_encode($goalAmichevole))."','".mysqli_real_escape_string($db,json_encode($goalMasters))."')");
	
	$db->query("DELETE FROM `presence` WHERE id = '".$userId."'"); //elimino la vecchia richiesta
	$db->query("INSERT INTO `presence`(`id`, `p_campionato`, `p_coppa`, `p_amichevole`, `p_masters`) VALUES
				('".$userId."','".mysqli_real_escape_string($db,json_encode($presenzeCampionato))."','".mysqli_real_escape_string($db,json_encode($presenzeCoppa))."',
				'".mysqli_real_escape_string($db,json_encode($presenzeAmichevole))."','".mysqli_real_escape_string($db,json_encode($presenzeMasters))."')");
	
	/***************/
	/* BOMBER LIST */
	/***************/
		
	$pollingFile = fopen("analyzer_assets/".$userCode.".txt", "w");
	fwrite($pollingFile, "B-1-2");
	fclose($pollingFile);	
		
	//update: aggiorno la BOMBER LIST
	//elimino i vecchi riferimenti della squadra nella lista
	$db->query("DELETE FROM `bomber_list` WHERE team_id = '".$teamId."'");
	
	//estraggo i giocatori attuali della squadra, per verificare se sono ancora attivi
	$teamPlayers = $team->getPlayers();
	$playersList = array();
	foreach($teamPlayers->getPlayers() as $key => $value)
	{
		$playersList[$value->getId()] = $value->getName();
	}
	
	//inserisco i riferimenti per le 6 categorie: campionato, coppa, amichevole, masters, all goals, official goals
	$blGoalCategory = compressGoalArraySingle($goalCampionato,$playersList);
	$blPresenzeCategory = compressPresenzeArraySingle($presenzeCampionato);
	$bomberCategory = bomberListCheckMax($blGoalCategory,$blPresenzeCategory);
	if(isset($bomberCategory["player_id"]))
	$db->query("INSERT INTO `bomber_list`(`user_id`,`team_id`, `competition`, `team_name`, `team_country`, `player_id`, `player_name`, `goals`, `appearance`, `history`, `in_team`) 
				VALUES ('".$userId."','".$teamId."','campionato','".$teamName."','".$leagueId."','".$bomberCategory["player_id"]."','".mysqli_real_escape_string($db,$bomberCategory["player_name"])."','".$bomberCategory["goals"]."','".$bomberCategory["appearance"]."',
				'".$bomberCategory["history"]."','".$bomberCategory["in_team"]."')");
	
	$blGoalCategory = compressGoalArraySingle($goalCoppa,$playersList);
	$blPresenzeCategory = compressPresenzeArraySingle($presenzeCoppa);
	$bomberCategory = bomberListCheckMax($blGoalCategory,$blPresenzeCategory);
	if(isset($bomberCategory["player_id"]))
	$db->query("INSERT INTO `bomber_list`(`user_id`,`team_id`, `competition`, `team_name`, `team_country`, `player_id`, `player_name`, `goals`, `appearance`, `history`, `in_team`) 
				VALUES ('".$userId."','".$teamId."','coppa','".$teamName."','".$leagueId."','".$bomberCategory["player_id"]."','".mysqli_real_escape_string($db,$bomberCategory["player_name"])."','".$bomberCategory["goals"]."','".$bomberCategory["appearance"]."',
				'".$bomberCategory["history"]."','".$bomberCategory["in_team"]."')");
	
	$blGoalCategory = compressGoalArraySingle($goalAmichevole,$playersList);
	$blPresenzeCategory = compressPresenzeArraySingle($presenzeAmichevole);
	$bomberCategory = bomberListCheckMax($blGoalCategory,$blPresenzeCategory);
	if(isset($bomberCategory["player_id"]))
	$db->query("INSERT INTO `bomber_list`(`user_id`,`team_id`, `competition`, `team_name`, `team_country`, `player_id`, `player_name`, `goals`, `appearance`, `history`, `in_team`) 
				VALUES ('".$userId."','".$teamId."','amichevole','".$teamName."','".$leagueId."','".$bomberCategory["player_id"]."','".mysqli_real_escape_string($db,$bomberCategory["player_name"])."','".$bomberCategory["goals"]."','".$bomberCategory["appearance"]."',
				'".$bomberCategory["history"]."','".$bomberCategory["in_team"]."')");
				
	$blGoalCategory = compressGoalArraySingle($goalMasters,$playersList);
	$blPresenzeCategory = compressPresenzeArraySingle($presenzeMasters);
	$bomberCategory = bomberListCheckMax($blGoalCategory,$blPresenzeCategory);
	if(isset($bomberCategory["player_id"]))
	$db->query("INSERT INTO `bomber_list`(`user_id`,`team_id`, `competition`, `team_name`, `team_country`, `player_id`, `player_name`, `goals`, `appearance`, `history`, `in_team`) 
				VALUES ('".$userId."','".$teamId."','masters','".$teamName."','".$leagueId."','".$bomberCategory["player_id"]."','".mysqli_real_escape_string($db,$bomberCategory["player_name"])."','".$bomberCategory["goals"]."','".$bomberCategory["appearance"]."',
				'".$bomberCategory["history"]."','".$bomberCategory["in_team"]."')");
				
	//all goals e official goals vanno calcolati
	$blGoalCategory = array();
	$blPresenzeCategory = array();
					
	//compatto le strutture per mostrare la classifica (l'array globale è passato per indirizzo)
	//ufficiali
	compressGoalArrayMultiple($goalCampionato,$blGoalCategory);
	compressGoalArrayMultiple($goalCoppa,$blGoalCategory);
	compressGoalArrayMultiple($goalMasters,$blGoalCategory);
	
	compressPresenzeArrayMultiple($presenzeCampionato,$blPresenzeCategory);
	compressPresenzeArrayMultiple($presenzeCoppa,$blPresenzeCategory);
	compressPresenzeArrayMultiple($presenzeMasters,$blPresenzeCategory);
	
	$bomberCategory = bomberListCheckMaxMultiple($blGoalCategory,$blPresenzeCategory,$playersList);
	if(isset($bomberCategory["player_id"]))
	$db->query("INSERT INTO `bomber_list`(`user_id`,`team_id`, `competition`, `team_name`, `team_country`, `player_id`, `player_name`, `goals`, `appearance`, `history`, `in_team`) 
				VALUES ('".$userId."','".$teamId."','ufficiali','".$teamName."','".$leagueId."','".$bomberCategory["player_id"]."','".mysqli_real_escape_string($db,$bomberCategory["player_name"])."','".$bomberCategory["goals"]."','".$bomberCategory["appearance"]."',
				'".$bomberCategory["history"]."','".$bomberCategory["in_team"]."')");
				
	//per tutti i goal basta aggiungere a quelli ufficiali quelli relativi alle amichevoli
	compressGoalArrayMultiple($goalAmichevole,$blGoalCategory);
	compressPresenzeArrayMultiple($presenzeAmichevole,$blPresenzeCategory);
	$bomberCategory = bomberListCheckMaxMultiple($blGoalCategory,$blPresenzeCategory,$playersList);
	if(isset($bomberCategory["player_id"]))
	$db->query("INSERT INTO `bomber_list`(`user_id`,`team_id`, `competition`, `team_name`, `team_country`, `player_id`, `player_name`, `goals`, `appearance`, `history`, `in_team`) 
				VALUES ('".$userId."','".$teamId."','tutti','".$teamName."','".$leagueId."','".$bomberCategory["player_id"]."','".mysqli_real_escape_string($db,$bomberCategory["player_name"])."','".$bomberCategory["goals"]."','".$bomberCategory["appearance"]."',
				'".$bomberCategory["history"]."','".$bomberCategory["in_team"]."')");
	
	$pollingFile = fopen("analyzer_assets/".$userCode.".txt", "w");
	fwrite($pollingFile, "B-2-2");
	fclose($pollingFile);	
	/**************/
	
	/*****************/
	/* CAP & STATS */
	/*****************/
	//non ho voluto fare il reset dei dati degli utenti con questa modifica, quindi sarà adattiva: vengono verificati e popolati i dati a parte
	//controllo se l'utente ha già analizzato la statistica relativa ai capitani
		
	$result = $db->query("SELECT A.*, B.m_campionato, B.m_amichevole, B.m_coppa, B.m_masters
						  FROM `captains` AS A
						  LEFT JOIN `matches` AS B on A.id = B.id
						  WHERE A.`id` = '".$userId."'");
						  
	$request = $result->fetch_array(MYSQLI_ASSOC);
	if($request != null && isset($request["last_request"])) //cerco dalla data dell'ultima richiesta in poi...
	{
		$dataIniziale = $request["last_request"];
				
		$result = $db->query("SELECT * FROM `season` WHERE `date_start` <= '".$dataIniziale."' && `date_end` >= '".$dataIniziale."'");
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$stagioneIniziale = $row["season_id"];
		
		$result = $db->query("SELECT * FROM `season` WHERE `date_start` <= '".$today."' && `date_end` >= '".$today."'");
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$stagioneFinale = $row["season_id"];
	}
	else //cerco dalla data di attivazione dell'utente in poi...
	{
		//data di attivazione del team
		$activationDate = $team->getActivationDate();
		$activationDate = explode(" ",$activationDate);
		$activationDate = $activationDate[0]; //con questa mossa ho eliminato il tempo
				
		//in base alle date devo identificare le stagioni
		//poi richiedere i match per ogni stagione...
		
		$result = $db->query("SELECT * FROM `season` WHERE `date_start` <= '".$activationDate."' && `date_end` >= '".$activationDate."'");
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$stagioneIniziale = $row["season_id"];
		
		$result = $db->query("SELECT * FROM `season` WHERE `date_start` <= '".$today."' && `date_end` >= '".$today."'");
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$stagioneFinale = $row["season_id"];
	}
	
	//calcolo l'offset tra le stagioni generali (italiana, svedese) e quella della nazionalità dell'utente... ho ricavato prima i dati su quanto è lo scostamento
	$stagioneIniziale += $seasonOffset;
	$stagioneFinale += $seasonOffset;
		
	//ricavo i dati dell'ultima richiesta
	$capCampionato = array();
	$capAmichevole = array();
	$capCoppa = array();
	$capMasters = array();
	
	$vitCampionato = array();
	$vitAmichevole = array();
	$vitCoppa = array();
	$vitMasters = array();
		
	if($request != null && isset($request["last_request"]))
	{
		$capCampionato = json_decode($request["c_campionato"],true);
		$capAmichevole = json_decode($request["c_amichevole"],true);
		$capCoppa = json_decode($request["c_coppa"],true);
		$capMasters = json_decode($request["c_masters"],true);
		
		$vitCampionato = json_decode($request["m_campionato"],true);
		$vitAmichevole = json_decode($request["m_amichevole"],true);
		$vitCoppa = json_decode($request["m_coppa"],true);
		$vitMasters = json_decode($request["m_masters"],true);
		
		//devo pulire i dati dalla stagione iniziale a quella finale...solitamente pulisco i dati dell'ultima stagione nel normale dei casi
		for($i = $stagioneIniziale; $i<=$stagioneFinale; $i++)
		{
			$capCampionato[$i] = null;
			$capAmichevole[$i] = null;
			$capCoppa[$i] = null;
			$capMasters[$i] = null;
			
			$vitCampionato[$i] = array(0,0,0);
			$vitAmichevole[$i] = array(0,0,0);
			$vitCoppa[$i] = array(0,0,0);
			$vitMasters[$i] = array(0,0,0);
		}
	}
	
	//comincio la richiesta	
	for($i = $stagioneIniziale; $i <= $stagioneFinale; $i++)
	{					
		$matchStagionali = $team->getMatchesArchive(null,null,$i);		
		
		//polling file
		$pollingFile = fopen("analyzer_assets/".$userCode.".txt", "w");
		fwrite($pollingFile, "M-".$i."-".$stagioneFinale);
		fclose($pollingFile);
		
		//inizializzo le variabili contenitore per la stagione in corso qualora non fosse mai stata
		if(!isset($vitCampionato[$i]))
		{
			$vitCampionato[$i] = array(0,0,0);
			$vitAmichevole[$i] = array(0,0,0);
			$vitCoppa[$i] = array(0,0,0);
			$vitMasters[$i] = array(0,0,0);
		}
		//è un controllo utile praticamente solo quando l'utente fa la prima analisi in assoluto...sicuramente c'è un'opzione migliore
		
		foreach($matchStagionali->getMatches() as $key => $value)
		{
			
			$matchPlayers = null; //suppongo nessuna formazione
			
			//dati ricavati
			$homeTeamId = $value->getHomeTeamId();
			$matchType = $value->getType();					
			$match = $value->mat_getMatch(false);
			$matchDate = $match->getStartDate(); //data del match
			
			//passo al match successivo se la data del match è precedente alla data di registrazione dell'utente, registrazione a stagione in corso
			if($i == $stagioneIniziale && $matchDate < $activationDate)
				continue;
						
			if($homeTeamId == $teamId) //giocava in casa...
			{
				$matchPlayers = $match->getHomeTeam()->getLineup()->mg_getStartingPlayers();
			}
			else //giocava in trasferta...
			{
				$matchPlayers = $match->getAwayTeam()->getLineup()->mg_getStartingPlayers();
			}	
			
			/***************/
			//aggiorno la lista delle vittorie
			//vit = 0; par = 1; sco = 2;
			$ris = MA_PAREGGIO;
			if(($homeTeamId == $teamId && $match->getHomeTeam()->getGoals() > $match->getAwayTeam()->getGoals()) || ($homeTeamId != $teamId && $match->getHomeTeam()->getGoals() < $match->getAwayTeam()->getGoals()))
				$ris = MA_VITTORIA;
			if(($homeTeamId == $teamId && $match->getHomeTeam()->getGoals() < $match->getAwayTeam()->getGoals()) || ($homeTeamId != $teamId && $match->getHomeTeam()->getGoals() > $match->getAwayTeam()->getGoals()))
				$ris = MA_SCONFITTA;
						
			if($matchType == HS_CAMPIONATO || $matchType == HS_SPAREGGIO)
			{
				if(isset($vitCampionato[$i][$ris]))
				{
					$vitCampionato[$i][$ris]++;					
				}
			}
			else if($matchType == HS_COPPA)
			{
				if(isset($vitCoppa[$i][$ris]))
				{
					$vitCoppa[$i][$ris]++;					
				}
			}
			else if($matchType == HS_AMICHEVOLE_NORMALE || $matchType == HS_AMICHEVOLE_COPPA || $matchType == HS_INTERNAZIONALE_NORMALE || $matchType == HS_INTERNAZIONALE_COPPA)
			{
				if(isset($vitAmichevole[$i][$ris]))
				{
					$vitAmichevole[$i][$ris]++;					
				}
			}
			else if($matchType == HS_MASTERS)
			{
				if(isset($vitMasters[$i][$ris]))
				{
					$vitMasters[$i][$ris]++;					
				}
			}			
			
			/****************/

			//aggiorno la lista dei capitani
			// NB -> le partite vinte a tavolino non contano nelle presenze
			foreach($matchPlayers as $lineupPlayer)
			{
				//cerco il capitano, se non lo era passo al prossimo giocatore
				if($lineupPlayer->getRole() != 18) //codice del capitano = 18
					continue;
				
				$idPlayer = $lineupPlayer->getId();
								
				//in base alla competizione lo vado ad inserire nella struttura dati più adatta
				if($matchType == HS_CAMPIONATO || $matchType == HS_SPAREGGIO)
				{
					if(isset($capCampionato[$i][$idPlayer])) //inserisco nella lista presenze
					{
						$capCampionato[$i][$idPlayer]["count"] += 1; //aumento il numero di presenze come capitano
						$capCampionato[$i][$idPlayer]["end_season"] = $i;
						$capCampionato[$i][$idPlayer]["end_date"] = $matchDate;							
					}
					else
					{							
						$capCampionato[$i][$idPlayer] = array("count" => 1, "name" => $lineupPlayer->getName(),
																	"start_season" => $i, "start_date" => $matchDate, "end_season" => $i, "end_date" => $matchDate);
					}
				}
				else if($matchType == HS_COPPA)
				{
					if(isset($capCoppa[$i][$idPlayer])) //inserisco nella lista presenze
					{
						$capCoppa[$i][$idPlayer]["count"] += 1; //aumento il numero di presenze come capitano
						$capCoppa[$i][$idPlayer]["end_season"] = $i;
						$capCoppa[$i][$idPlayer]["end_date"] = $matchDate;							
					}
					else
					{							
						$capCoppa[$i][$idPlayer] = array("count" => 1, "name" => $lineupPlayer->getName(),
																	"start_season" => $i, "start_date" => $matchDate, "end_season" => $i, "end_date" => $matchDate);
					}
				}
				else if($matchType == HS_AMICHEVOLE_NORMALE || $matchType == HS_AMICHEVOLE_COPPA || $matchType == HS_INTERNAZIONALE_NORMALE || $matchType == HS_INTERNAZIONALE_COPPA)
				{
					if(isset($capAmichevole[$i][$idPlayer])) //inserisco nella lista presenze
					{
						$capAmichevole[$i][$idPlayer]["count"] += 1; //aumento il numero di presenze come capitano
						$capAmichevole[$i][$idPlayer]["end_season"] = $i;
						$capAmichevole[$i][$idPlayer]["end_date"] = $matchDate;							
					}
					else
					{							
						$capAmichevole[$i][$idPlayer] = array("count" => 1, "name" => $lineupPlayer->getName(),
																	"start_season" => $i, "start_date" => $matchDate, "end_season" => $i, "end_date" => $matchDate);
					}
				}
				else if($matchType == HS_MASTERS)
				{
					if(isset($capMasters[$i][$idPlayer])) //inserisco nella lista presenze
					{
						$capMasters[$i][$idPlayer]["count"] += 1; //aumento il numero di presenze come capitano
						$capMasters[$i][$idPlayer]["end_season"] = $i;
						$capMasters[$i][$idPlayer]["end_date"] = $matchDate;							
					}
					else
					{							
						$capMasters[$i][$idPlayer] = array("count" => 1, "name" => $lineupPlayer->getName(),
																	"start_season" => $i, "start_date" => $matchDate, "end_season" => $i, "end_date" => $matchDate);
					}
				}
				
				//il capitano è uno solo, quindi quando l'ho trovato posso breakare per risparmiare un po' di cicli
				break;
				
			} //end match lineup
						
		} //end analisi match
						
	} //end analisi stagione
	
	$db->query("DELETE FROM `matches` WHERE id = '".$userId."'"); //elimino la vecchia richiesta
	$db->query("INSERT INTO `matches`(`id`, `last_request`, `m_campionato`, `m_coppa`, `m_amichevole`, `m_masters`) VALUES
				('".$userId."','".date("Y-m-d")."','".mysqli_real_escape_string($db,json_encode($vitCampionato))."','".mysqli_real_escape_string($db,json_encode($vitCoppa))."',
				'".mysqli_real_escape_string($db,json_encode($vitAmichevole))."','".mysqli_real_escape_string($db,json_encode($vitMasters))."')");
	
	$db->query("DELETE FROM `captains` WHERE id = '".$userId."'"); //elimino la vecchia richiesta
	$db->query("INSERT INTO `captains`(`id`, `last_request`, `c_campionato`, `c_coppa`, `c_amichevole`, `c_masters`) VALUES
				('".$userId."','".date("Y-m-d")."','".mysqli_real_escape_string($db,json_encode($capCampionato))."','".mysqli_real_escape_string($db,json_encode($capCoppa))."',
				'".mysqli_real_escape_string($db,json_encode($capAmichevole))."','".mysqli_real_escape_string($db,json_encode($capMasters))."')");
				
		
	/*****************/
		
	//saving status
	$pollingFile = fopen("analyzer_assets/".$userCode.".txt", "w");
	fwrite($pollingFile, "SAVING DATA");
	fclose($pollingFile);
	
?>