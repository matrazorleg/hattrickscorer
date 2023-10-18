//funzioni di utilità della dashboard
function unsetAndLogout()
{
	var name = "HattrickScorerId";
	document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
	document.location = 'index.php';
}

function openTabBL()
{
	if(jQuery("#bl_inst_text").css("display") == "none")
		jQuery("#bl_inst_text").css("display","block");
	else
		jQuery("#bl_inst_text").css("display","none");
}

function openTabWC()
{
	if(jQuery("#bl_wrong_calc_text").css("display") == "none")
		jQuery("#bl_wrong_calc_text").css("display","block");
	else
		jQuery("#bl_wrong_calc_text").css("display","none");
}

function showBombers(caller,request,pagination)
{
	var id = caller.id;
	var current = jQuery("#current_active").val();
	
	jQuery("#"+current).removeClass("scorer_active");
	jQuery("#"+id).addClass("scorer_active");
	
	jQuery("#table_"+current).css("display","none");
	jQuery("#table_"+id).css("display","block");
	
	jQuery("#current_active").val(id);
	jQuery("#current_request").val(request);
	
	appendTable(request,pagination);
	calculatePagination(request); //spenta la paginazione ora la fa in automatico la tabella
	userPlayer(request);
}

jQuery( document ).ready(function() {
    appendTable("ufficiali",1);
	calculatePagination("ufficiali"); //spenta la paginazione ora la fa in automatico la tabella
	userPlayer("ufficiali");
});

function appendTable(request,pagination)
{
	//icona del caricamento
	jQuery("#loading_gears").css("display","block");
	jQuery("#scorer_append").empty();
			
	//tabella di lookup dei nomi
	var tableTitles = new Array();
	
	tableTitles["campionato"] = "League match goals";
	tableTitles["coppa"] = "Cup match goals";
	tableTitles["amichevole"] = "Friendly match goals";
	tableTitles["nazionale"] = "National match goals";
	tableTitles["masters"] = "Hattrick Masters goals";
	tableTitles["ufficiali"] = "Official match goals";
	tableTitles["tutti"] = "All match goals";
	
	//inoltro la richiesta al servizio
	//console.log("services/getBomberList.php?request="+request+"&pagination="+pagination);
	jQuery.get( "services/getBomberList.php?request="+request+"&pagination="+pagination, function( output ) {
		//console.log(output);
		output = jQuery.parseJSON(output);
		
		//custruisco la tabella	
		var echo = '<div class="container container-table table bomber_list_table">';
				echo += '<div class="table_name">'+tableTitles[request]+'</div>';
				echo += '<div class="row">';
					echo += '<div class="col-md-12">';
						echo += '<table id="playersData" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">';
						echo += '<thead>';
							echo += '<tr>';
								echo += '<th class="th-sm">Rank</th>';
								echo += '<th class="th-sm">Team</th>';
								echo += '<th class="th-sm all">Player</th>';
								echo += '<th class="th-sm all">Goals</th>';
								echo += '<th class="th-sm">Appearances</th>';
								echo += '<th class="th-sm">History</th>';
							echo += '</tr>';
						echo += '</thead>';
						echo += '<tbody id="playersDataAppend">'
							for(var i = 0; i<output.length; i++)
							{
								var hist = jQuery.parseJSON(output[i]["history"]);
								var flagOffset = (20*output[i]["team_country"]);
																
								if(output[i]["in_team"] == 1)
									echo += '<tr class="strong-row">';
								else
									echo += '<tr>';
									
									echo += '<td class="text-center">'+(i+1)+'</td>';
									echo += '<td>';
										echo += '<div class="flag" style="background:transparent url(\'images/flags.gif\') no-repeat -'+flagOffset+'px 0;" ></div>';
										echo += '<a target="_blank" href="https://www.hattrick.org/goto.ashx?path=/Club/?TeamID='+output[i]["team_id"]+'">'+output[i]["team_name"]+'</a>';
									echo += '</td>';
									echo += '<td><a target="_blank" href="https://www.hattrick.org/goto.ashx?path=/Club/Players/Player.aspx?playerId='+output[i]["player_id"]+'">'+output[i]["player_name"]+'</a></td>';
									echo += '<td class="text-center">'+output[i]["goals"]+'</td>';
									echo += '<td class="text-center">'+output[i]["appearance"]+'</td>';
									echo += '<td class="text-center history_text">'+hist["start_date"]+' (s. '+hist["start_season"]+')<br/>'+hist["end_date"]+' (s. '+hist["end_season"]+')</td>';								
								echo += '</tr>';
							}
						echo += '</tbody>';
						echo += '<tfoot>';
							echo += '<tr>';
								echo += '<th>Rank</th>';
								echo += '<th>Team</th>';
								echo += '<th>Player</th>';
								echo += '<th>Goals</th>';
								echo += '<th>Appearances</th>';
								echo += '<th>History</th>';
							echo += '</tr>';
						echo += '</tfoot>';
						echo += '</table>';
					echo += '</div>';
				echo += '</div>';
			echo += '</div>';
		
		//vecchia implementazione
		/*
		var echo = '<div class="bomber_list_table" id="table_league_goals">';
						echo += '<div class="table_name">'+tableTitles[request]+'</div>';
						echo += '<div class="table_values">';
							echo += '<div class="row_val int">';
								echo += '<div class="rank">Rank</div>';
								echo += '<div class="team_name">Team</div>';
								echo += '<div class="player">Player</div>';
								echo += '<div class="goals">Goals</div>';
								echo += '<div class="presenze">Appearances</div>';
								echo += '<div class="desktop big_history">Goal History</div>';
							echo += '</div>';
						
								if(output.length == 0)
								{
									echo += '<div class="row_val val0">';
										echo += '<div class="no_player">Scorers not found for this competition</div>';
									echo += '</div>';
								}
								else
								{
									for(var i = 0; i < output.length; i++)
									{
										var hist = jQuery.parseJSON(output[i]["history"]);
										var flagOffset = (20*output[i]["team_country"]);
										
										echo += '<div class="row_val val'+(i % 2)+'">';
											echo += '<div class="rank">'+((pagination-1)*100 + (i+1))+'</div>';
											echo += '<div class="team_name">';
												echo += '<div class="flag" style="background:transparent url(\'images/flags.gif\') no-repeat -'+flagOffset+'px 0;" ></div>';
												echo += '<a target="_blank" href="https://www.hattrick.org/goto.ashx?path=/Club/?TeamID='+output[i]["team_id"]+'">'+output[i]["team_name"]+'</a>';
											echo += '</div>';
											echo += '<div class="player inteam'+output[i]["in_team"]+'">';
												echo += '<a target="_blank" href="https://www.hattrick.org/goto.ashx?path=/Club/Players/Player.aspx?playerId='+output[i]["player_id"]+'">'+output[i]["player_name"]+'</a>';
											echo += '</div>';
											echo += '<div class="goals">'+output[i]["goals"]+'</div>';
											echo += '<div class="presenze">'+output[i]["appearance"]+'</div>';
											echo += '<div class="desktop big_history">'+hist["start_date"]+' / '+hist["end_date"]+'</div>';
										echo += '</div>';
									}
								}
								
						echo += '</div>';
		echo += '</div>';
		*/
		
		//appendo...	
		jQuery("#loading_gears").css("display","none");
		jQuery("#scorer_append").append(echo);
		window.scrollTo(0,0);
		
		//aziono datatables
		jQuery('#playersData').DataTable({
			responsive:true,	
			"pageLength": 100,
			"order": [[ 0, "asc" ]], //ordinamento sul ranking
			"bSort": true
		});
		jQuery('.dataTables_length').addClass('bs-select');
		
	});
}


function calculatePagination(request)
{
	//console.log("services/getBomberListPagination.php?request="+request);
	jQuery.get( "services/getBomberListPagination.php?request="+request, function( output ) {
		var echo = "";
		for(var i = 1; i <= output; i++)
		{
			echo += '<div class="page" onclick="appendTable(\''+request+'\','+i+')">'+((i-1)*100 + 1)+' - '+((i)*100)+'</div>';
		}
		jQuery("#pagination").empty();
		jQuery("#pagination").append(echo);
	});
}


function userPlayer(request)
{
	var userCode = jQuery("#userCode").val();
	if(userCode != null && userCode != "") //utente loggato, mostro il suo giocatore
	{
		jQuery("#bl_my_best_scorer").empty();
		//console.log("services/getBomberListSingle.php?request="+request+"&id="+userCode);
		jQuery.get( "services/getBomberListSingle.php?request="+request+"&id="+userCode, function( output ) {
			output = jQuery.parseJSON(output);
			var echo = "";
			
			echo += '<div class="label_my"><i class="fas fa-award"></i> My team best scorer</div>';
			echo += '<div class="player">';
				echo += '<div class="name"><a href="https://www.hattrick.org/goto.ashx?path=/Club/Players/Player.aspx?playerId='+output["player_id"]+'">'+output["player_name"]+'</a></div>';
				echo += '<div class="goals">goals <span class="bolded_element">'+output["goals"]+'</span> appearances <span class="bolded_element">'+output["appearance"]+'</span></div>';
			echo += '</div>';
			
			jQuery("#bl_my_best_scorer").append(echo);
			
		});
	}
}