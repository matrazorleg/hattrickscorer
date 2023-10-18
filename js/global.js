//funzioni di utilità della dashboard
function unsetAndLogout()
{
	var name = "HattrickScorerId";
	document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
	document.location = 'index.php';
}
//pulsanti match
function showResults(caller,request)
{
	var id = caller.id;
	var current = jQuery("#current_active_res").val();
	
	jQuery("#"+current).removeClass("scorer_active");
	jQuery("#"+id).addClass("scorer_active");
	
	jQuery("#table_"+current).css("display","none");
	jQuery("#table_"+id).css("display","block");
	
	jQuery("#current_active_res").val(id);
	jQuery("#current_request_res").val(request);
	
	appendResults(request);
}
//pulsanti capitani
function showCaptains(caller,request)
{
	var id = caller.id;
	var current = jQuery("#current_active_cap").val();
	
	jQuery("#"+current).removeClass("scorer_active");
	jQuery("#"+id).addClass("scorer_active");
	
	jQuery("#table_"+current).css("display","none");
	jQuery("#table_"+id).css("display","block");
	
	jQuery("#current_active_cap").val(id);
	jQuery("#current_request_cap").val(request);
	
	appendCaptains(request);
}

jQuery( document ).ready(function() {
    appendResults("ufficiali");
	appendCaptains("ufficiali");
});

function appendResults(request)
{
	//icona del caricamento
	jQuery("#loading_gears_1").css("display","block");
	jQuery("#result_append").empty();
		
	var userCode = jQuery("#userCode").val();
	
	//tabella di lookup dei nomi
	var tableTitles = new Array();
	
	tableTitles["campionato"] = "League match results";
	tableTitles["coppa"] = "Cup match results";
	tableTitles["amichevole"] = "Friendly match results";
	tableTitles["nazionale"] = "National match results";
	tableTitles["masters"] = "Hattrick Masters results";
	tableTitles["ufficiali"] = "Official match results";
	tableTitles["tutti"] = "All match results";
	
	//inoltro la richiesta al servizio
	//console.log("services/getResults.php?id="+userCode+"&request="+request);
	jQuery.get( "services/getResults.php?id="+userCode+"&request="+request, function( output ) {
		//console.log(output);
		output = jQuery.parseJSON(output);
		
		var vit = 0;
		var par = 0;
		var sco = 0;
		var seasons = Object.keys(output["data"]);
		var results = Object.values(output["data"]);
		var dates = output["seasons"];
		
		//custruisco la tabella	
		var echo = '<div class="container container-table table">';
				echo += '<div class="table_name">'+tableTitles[request]+'</div>';
				echo += '<div class="row">';
					
					//table la costruisco separatamente, per poterla appendere dopo i panels
					var table = '<div class="col-md-8">';
							table += '<table id="playersData" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">';
							table += '<thead>';
								table += '<tr>';
									table += '<th class="th-sm all">Season</th>';
									table += '<th class="th-sm all">Wins</th>';
									table += '<th class="th-sm">Draws</th>';
									table += '<th class="th-sm all">Defeats</th>';
								table += '</tr>';
							table += '</thead>';
							table += '<tbody id="playersDataAppend">';
								for(var i = 0; i<results.length; i++)
								{
									vit = vit + results[i][0];
									par = par + results[i][1];
									sco = sco + results[i][2];
									
									table += '<tr>';
										table += '<td><b>'+seasons[i]+'</b> <span class="res_seasons_dates">(f. '+dates[i]["date_start"]+'- t. '+dates[i]["date_end"]+')</span></td>';
										table += '<td class="text-center">'+results[i][0]+'</td>';
										table += '<td class="text-center">'+results[i][1]+'</td>';
										table += '<td class="text-center">'+results[i][2]+'</td>';
									table += '</tr>';
								}
							table += '</tbody>';
							table += '<tfoot>';
								table += '<tr>';
									table += '<th>Season</th>';
									table += '<th>Wins</th>';
									table += '<th>Draws</th>';
									table += '<th>Defeats</th>';
								table += '</tr>';
							table += '</tfoot>';
							table += '</table>';
						table += '</div>';
					
						//summary
						echo += '<div class="col-md-4 res_panels">';
							echo += '<div class="panel panel-default">';
								echo += '<div class="panel-heading"><h3 class="panel-title"><i class="fas fa-ring"></i> Played</h3></div>';
								echo += '<div class="panel-body"><span class="res_number">'+parseInt(vit+par+sco)+'</span></div>';
							echo += '</div>';
							
							echo += '<div class="panel panel-success">';
								echo += '<div class="panel-heading"><h3 class="panel-title"><i class="fas fa-check"></i> Won</h3></div>';
								echo += '<div class="panel-body"><span class="res_number">'+parseInt(vit)+'</span></div>';
							echo += '</div>';
							
							echo += '<div class="panel panel-warning">';
								echo += '<div class="panel-heading"><h3 class="panel-title"><i class="fas fa-equals"></i> Drawn</h3></div>';
								echo += '<div class="panel-body"><span class="res_number">'+parseInt(par)+'</span></div>';
							echo += '</div>';
							
							echo += '<div class="panel panel-danger">';
								echo += '<div class="panel-heading"><h3 class="panel-title"><i class="fas fa-times"></i></i> Lost</h3></div>';
								echo += '<div class="panel-body"><span class="res_number">'+parseInt(sco)+'</span></div>';
							echo += '</div>';
						echo += '</div>';
						
						//dovevo prima costruire la tabella per ottenere le variabili con i risultati compilati
						echo += table;
																
				echo += '</div>';
			echo += '</div>';
 	
		//appendo...	
		jQuery("#loading_gears_1").css("display","none");
		jQuery("#result_append").append(echo);
		
		//aziono datatables 
		jQuery('#playersData').DataTable({
			responsive:true,	
			"pageLength": 10,
			"order": [[ 0, "desc" ]], //ordinamento sulle stagioni
			"bSort": true
		});
		jQuery('.dataTables_length').addClass('bs-select');
		
	});
				
}

function appendCaptains(request)
{
	//icona del caricamento
	jQuery("#loading_gears_2").css("display","block");
	jQuery("#captains_append").empty();
		
	var userCode = jQuery("#userCode").val();
	
	//tabella di lookup dei nomi
	var tableTitles = new Array();
	
	tableTitles["campionato"] = "League match captains";
	tableTitles["coppa"] = "Cup match captains";
	tableTitles["amichevole"] = "Friendly match captains";
	tableTitles["nazionale"] = "National match captains";
	tableTitles["masters"] = "Hattrick Masters captains";
	tableTitles["ufficiali"] = "Official match captains";
	tableTitles["tutti"] = "All match captains";
	
	//inoltro la richiesta al servizio
	//console.log("services/getCaptains.php?id="+userCode+"&request="+request);
	jQuery.get( "services/getCaptains.php?id="+userCode+"&request="+request, function( output ) {
		//console.log(output);
		output = jQuery.parseJSON(output);
		
		var echo = '<div class="container container-table table">';
				echo += '<div class="table_name">'+tableTitles[request]+'</div>';
				echo += '<div class="row">';
					echo += '<div class="col-md-12">';
						echo += '<table id="captainsData" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">';
						echo += '<thead>';
							echo += '<tr>';
								echo += '<th class="th-sm all">Name</th>';
								echo += '<th class="th-sm all">Captain appearances</th>';
								echo += '<th class="th-sm">History</th>';
							echo += '</tr>';
						echo += '</thead>';
						echo += '<tbody id="playersDataAppend">';
							for(var i = 0; i<output.length; i++)
							{
								if(output[i]["in_team"] == 1)
									echo += '<tr class="strong-row">';
								else
									echo += '<tr>';
							
									echo += '<td>'+output[i]["name"]+'<br/><span class="player_id"><a target="_blank" href="https://www.hattrick.org/goto.ashx?path=/Club/Players/Player.aspx?playerId='+output[i]["id"]+'" >'+output[i]["id"]+' <i class="fa fa-external-link" aria-hidden="true"></i></a></span></td>';
									echo += '<td class="text-center">'+output[i]["count"]+'</td>';
									echo += '<td class="text-center history_text">'+output[i]["start_date"]+' (s. '+output[i]["start_season"]+')<br/>'+output[i]["end_date"]+' (s. '+output[i]["end_season"]+')</td>';								
								echo += '</tr>';
							}
						echo += '</tbody>';
						echo += '<tfoot>';
							echo += '<tr>';
								echo += '<th>Name</th>';
								echo += '<th>Captain appearances</th>';
								echo += '<th>History</th>';
							echo += '</tr>';
						echo += '</tfoot>';
						echo += '</table>';
					echo += '</div>';
				echo += '</div>';
			echo += '</div>';
			
		//appendo...	
		jQuery("#loading_gears_2").css("display","none");
		jQuery("#captains_append").append(echo);
		
		//aziono datatables 
		jQuery('#captainsData').DataTable({
			responsive:true,	
			"pageLength": 25,
			"order": [[ 1, "desc" ]], //ordinamento sulle presenza da capitano
			"bSort": true
		});
		jQuery('.dataTables_length').addClass('bs-select');
		
	});
				
}