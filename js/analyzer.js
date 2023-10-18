var userCode;
var returnedData;
var bar1;
var bar2;
var bar3;

jQuery( document ).ready(function() {
	
	//progressbar
	bar1 = defineProgressBar("#progress-bar-1");
	bar2 = defineProgressBar("#progress-bar-2");
	bar3 = defineProgressBar("#progress-bar-3");	
	
    //analizzo periodicamente il file di interscambio tramite il poller (servizio)
	userCode = jQuery("#userCode").val();
	setInterval("callPoller()",3*1000); //ogni 3 secondi (parametro in millisecondi)
		
});

function callPoller()
{
	jQuery.get('analyzer_assets/poller.php?userCode='+userCode, function(data) {
		console.log(data);
		//controllo i dati ritornati, ed eventualmente se siamo alla fase di salvataggio dei dati posso procedere alla dashboard
		if(data == "SAVING DATA")
			window.location.href = 'dashboard.php?id='+userCode;
		else
		{
			var steps = data.split("-");
			//data è nel formato: tipo di polling - step corrente - step max
			
			//per far si che la barra non sia es 68/70 e quindi parta già da 97% applico una trasformazione (68-68)+1 / (70-68)+1
			steps[1] = (steps[1]-steps[1])+1;
			steps[2] = (steps[2]-steps[1])+1;
			
			//goals e appearance
			if(data.indexOf("G") > -1)
			{
				bar1.set(parseFloat(steps[1]/steps[2]));
			}
			//bomber list
			if(data.indexOf("B") > -1)
			{
				bar1.set(1.0);
				bar2.set(parseFloat(steps[1]/steps[2]));
			}
			//matches e captains
			if(data.indexOf("M") > -1)
			{
				bar1.set(1.0);
				bar2.set(1.0);
				bar3.set(parseFloat(steps[1]/steps[2]));
			}
		}
		
	});	
}

function defineProgressBar(container)
{
	return new ProgressBar.Line(container, {
	  strokeWidth: 4,
	  easing: 'easeInOut',
	  duration: 1400,
	  color: '#FFEA82',
	  trailColor: '#eee',
	  trailWidth: 1,
	  svgStyle: {width: '100%', height: '100%'},
	  text: {
		style: {
			color:'#000000',
            position: 'relative',
            left: '50%',
            top: '10px',
            padding: 0,
            margin: 0,
            transform: {
                prefix: true,
                value: 'translate(-50%, -50%)'
            }
		},
		autoStyleContainer: false
	  },
	  from: {color: '#e6b018'},
	  to: {color: '#216b2f'},
	  step: (state, bar) => {
		bar.path.setAttribute('stroke', state.color);
		bar.setText(Math.round(bar.value() * 100) + ' %');
	  }
	});
}
