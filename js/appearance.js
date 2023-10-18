//funzioni di utilità della dashboard
function unsetAndLogout()
{
	var name = "HattrickScorerId";
	document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
	document.location = 'index.php';
}

function showAppearance(caller,request,field,order)
{
	var id = caller.id;
	var current = jQuery("#current_active").val();
	
	jQuery("#"+current).removeClass("scorer_active");
	jQuery("#"+id).addClass("scorer_active");
	
	jQuery("#table_"+current).css("display","none");
	jQuery("#table_"+id).css("display","block");
	
	jQuery("#current_active").val(id);
	jQuery("#current_request").val(request);
	
	appendTable(request,field,order);
}

jQuery( document ).ready(function() {
    appendTable("ufficiali","presenze","desc");
});

function orderTable(field,order)
{
	var request = jQuery("#current_request").val();
	appendTable(request,field,order);
}

function appendTable(request,field,order)
{
	//icona del caricamento
	jQuery("#loading_gears").css("display","block");
	jQuery("#scorer_append").empty();
		
	var userCode = jQuery("#userCode").val();
	
	//tabella di lookup dei nomi
	var tableTitles = new Array();
	
	tableTitles["campionato"] = "League appearance";
	tableTitles["coppa"] = "Cup appearance";
	tableTitles["amichevole"] = "Friendly appearance";
	tableTitles["nazionale"] = "National appearance";
	tableTitles["masters"] = "Hattrick Masters appearance";
	tableTitles["ufficiali"] = "Official appearance";
	tableTitles["tutti"] = "All appearance";
	//inoltro la richiesta al servizio
	jQuery.get( "services/getAppearance.php?id="+userCode+"&request="+request+"&field="+field+"&order="+order, function( output ) {
		//console.log(output);
		output = jQuery.parseJSON(output);
		//custruisco la tabella		
		
		var echo = '<div class="container container-table table">';
				echo += '<div class="table_name">'+tableTitles[request]+'</div>';
				echo += '<div class="row">';
					echo += '<div class="col-md-12">';
						echo += '<table id="playersData" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">';
						echo += '<thead>';
							echo += '<tr>';
								echo += '<th class="th-sm all">Name</th>';
								echo += '<th class="th-sm all">Appearances</th>';
								echo += '<th class="th-sm">Best rating</th>';
								echo += '<th class="th-sm">History</th>';
							echo += '</tr>';
						echo += '</thead>';
						echo += '<tbody id="playersDataAppend">'
							for(var i = 0; i<output.length; i++)
							{
								if(output[i]["in_team"] == 1)
									echo += '<tr class="strong-row">';
								else
									echo += '<tr>';
								
									echo += '<td>'+output[i]["name"]+'<br/><span class="player_id"><a target="_blank" href="https://www.hattrick.org/goto.ashx?path=/Club/Players/Player.aspx?playerId='+output[i]["id"]+'" >'+output[i]["id"]+' <i class="fa fa-external-link" aria-hidden="true"></i></a></span></td>';
									echo += '<td class="text-center">'+output[i]["presenze"]+'</td>';
									echo += '<td class="text-center">'+output[i]["valutazione"]+' <i class="fa fa-star" aria-hidden="true"></i></td>';
									echo += '<td class="text-center history_text">'+output[i]["start_date"]+' (s. '+output[i]["start_season"]+')<br/>'+output[i]["end_date"]+' (s. '+output[i]["end_season"]+')</td>';								
								echo += '</tr>';
							}
						echo += '</tbody>';
						echo += '<tfoot>';
							echo += '<tr>';
								echo += '<th>Name</th>';
								echo += '<th>Appearances</th>';
								echo += '<th>Best rating</th>';
								echo += '<th>History</th>';
							echo += '</tr>';
						echo += '</tfoot>';
						echo += '</table>';
					echo += '</div>';
				echo += '</div>';
			echo += '</div>';
		
		//vecchia implementazione
		/*
		var echo = '<div class="table" id="table_league_goals">';
						echo += '<div class="table_name">'+tableTitles[request]+'</div>';
						echo += '<div class="table_values">';
							echo += '<div class="row_val int">';
								echo += '<div class="big_id">ID</div>';
								echo += '<div class="big_player">Player</div>';
								echo += '<div class="appearance">';
									echo += '<div class="cell_name">Appearance</div>';
									echo += '<div class="cell_order">';
										echo += '<i class="fa fa-arrow-up" aria-hidden="true" onclick="orderTable(\'presenze\',\'asc\')"></i>';
										echo += '<i class="fa fa-arrow-down" aria-hidden="true" onclick="orderTable(\'presenze\',\'desc\')"></i>';
									echo += '</div>';
								echo += '</div>';
								echo += '<div class="rating">';
									echo += '<div class="cell_name">Best rating</div>';
									echo += '<div class="cell_order">';
										echo += '<i class="fa fa-arrow-up" aria-hidden="true" onclick="orderTable(\'valutazione\',\'asc\')"></i>';
										echo += '<i class="fa fa-arrow-down" aria-hidden="true" onclick="orderTable(\'valutazione\',\'desc\')"></i>';
									echo += '</div>';
								echo += '</div>';
								echo += '<div class="desktop big_history">App. History</div>';
							echo += '</div>';
						
								if(output.length == 0)
								{
									echo += '<div class="row_val val0">';
										echo += '<div class="no_player">Appearances not found for this competition</div>';
									echo += '</div>';
								}
								else
								{
									for(var i = 0; i < output.length; i++)
									{
										echo += '<div class="row_val val'+(i % 2)+'">';
											echo += '<div class="id">(<a target="_blank" href="https://www.hattrick.org/goto.ashx?path=/Club/Players/Player.aspx?playerId='+output[i]["id"]+'" >'+output[i]["id"]+'</a>)</div>';
											echo += '<div class="player inteam'+output[i]["in_team"]+'">'+output[i]["name"]+'</div>';
											echo += '<div class="appearance">'+output[i]["presenze"]+'</div>';
											echo += '<div class="rating">'+output[i]["valutazione"]+'</div>'; 
											echo += '<div class="desktop app_history">';
													echo += '<div class="history_clear"><div class="innerseason">s. '+output[i]["start_season"]+'</div><div class="innerdate">('+output[i]["start_date"]+')</div></div>';
													echo += '<div class="history_clear"><div class="innerseason">s. '+output[i]["end_season"]+'</div><div class="innerdate">('+output[i]["end_date"]+')</div></div>';
											echo += '</div>';
										echo += '</div>';
									}
								}
								
						echo += '</div>';
		echo += '</div>';
		*/
		
		//appendo...	
		jQuery("#loading_gears").css("display","none");
		jQuery("#scorer_append").append(echo);
		
		//aziono datatables
		jQuery('#playersData').DataTable({
			responsive:true,	
			"pageLength": 100,
			"order": [[ 1, "desc" ]], //ordinamento su appearances
			"bSort": true
		});
		jQuery('.dataTables_length').addClass('bs-select');
		
	});
				
}