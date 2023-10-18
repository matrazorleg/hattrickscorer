<?php

	//lato model dello script...ho lo sha1 trovo l'id...
	require_once 'engine/database.php';
	require_once 'engine/constant.php';
	require_once 'engine/PHT/autoload.php';
		
	$userCode = $_GET["id"];
	$userId = -1;
	
	$result = $db->query("SELECT id,last_visit FROM `user` WHERE `user_code` = '".$userCode."'");
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
	
	/* 
	questo è il controller dell'analisi, usato per prevenire il problema del Gateway Timeout che sopraggiungeva nel hosting
	l'obiettivo del controller è lanciare in background l'analisi e tenere traccia dello status della stessa...
	*/
	
	
	//parametri per l'accesso CHPP e dati basilari della squadra
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
							  
	
	//lancio l'analizzatore dei dati
	//produzione
	exec("nohup php /var/www/html/hattrickscorer/calculator.php ".$userCode." > /dev/null &"); //usati i parametri argv, exec non può passare parametri in get/post, l'& finale serve per eseguire il comando in background
	//il controller si occuperà di analizzare il file di scambio per aggiornare la pagina
	
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
	<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Hattrick Scorer</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Scorer tool for Hattrick online game" />
	<meta name="keywords" content="hattrick, scorer, stats, strikers, goleador" />
	<meta name="author" content="SoichiroArima" />

  	<!-- Facebook and Twitter integration -->
	<meta property="og:title" content=""/>
	<meta property="og:image" content=""/>
	<meta property="og:url" content=""/>
	<meta property="og:site_name" content=""/>
	<meta property="og:description" content=""/>
	<meta name="twitter:title" content="" />
	<meta name="twitter:image" content="" />
	<meta name="twitter:url" content="" />
	<meta name="twitter:card" content="" />

	<!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
	<link rel="shortcut icon" href="favicon.ico">

	<!-- <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,300' rel='stylesheet' type='text/css'> -->
	
	<!-- Animate.css -->
	<link rel="stylesheet" href="css/animate.css">
	<!-- Icomoon Icon Fonts-->
	<link rel="stylesheet" href="css/icomoon.css">
	<!-- Bootstrap  -->
	<link rel="stylesheet" href="css/bootstrap.css">
	<!-- Superfish -->
	<link rel="stylesheet" href="css/superfish.css">

	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/responsive.css">


	<!-- Modernizr JS -->
	<script src="js/modernizr-2.6.2.min.js"></script>
	<!-- FOR IE9 below -->
	<!--[if lt IE 9]>
	<script src="js/respond.min.js"></script>
	<![endif]-->
	
	<!-- font awesome -->
	<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">	
	
	</head>
	<body>
	
		<header id="fh5co-header-section" class="sticky-banner">
			<div class="container">
				<div class="nav-header">
					<a href="#fh5co-primary-menu" class="js-fh5co-nav-toggle fh5co-nav-toggle dark"><i></i></a>
					<h1 id="fh5co-logo"><a href="index.php">Hattrick Scorer</a></h1>
				</div>
			</div>
		</header>
		
		<div id="fh5co-feature-product" class="fh5co-section-gray mg_dashboard analyzer_margin">
			<div class="container">
	
				<?php include_once("analytics/analyticstracking.php"); ?>	
							
				<div class="an_left col-md-6">
					<div class="an_welcome">Welcome <b><?php echo $user->getName(); ?></b></div>
					<div class="an_last_visit">Starting time: <?php echo date("Y-m-d H:i:s"); ?></div>
				</div>
				<input type="hidden" name="userCode" id="userCode" value="<?php echo $userCode; ?>" /> <?php //necessario al js per interrogare il servizio...?>
				<div class="an_right col-md-6">
					<div class="an_logo"><img src="<?php echo $logoUrl; ?>" /></div>
					<div class="an_team_name"><?php echo $teamName; ?></div>
				</div>						
				<div class="an_action col-md-12">				
					<div class="an_status">
							<div class="season_status">
								Inspecting data <br/> <i class="fas fa-download"></i>
							</div>
							
							<div class="row mt-4 progress_bars">
								<div class="col-md-4">
									<p class="progress-text">
										Goals and Appearances
									</p>
									<div id="progress-bar-1" class="progress_bar"></div>
								</div>
								<div class="col-md-4">
									<p class="progress-text">
										Bomber List
									</p>
									<div id="progress-bar-2" class="progress_bar"></div>
								</div>
								<div class="col-md-4">
									<p class="progress-text">
										Scores and Captains
									</p>
									<div id="progress-bar-3" class="progress_bar"></div>
								</div>
							</div>
							
					</div>
					<div class="an_loading">
						<img src="images/gears_inverted.svg" />
					</div>
					<div class="an_disclaimer">
						The analyzer is inspecting your team matches to create Hattrick Scorer stats.<br/>
						The statistical calculation time depends on the season you had play in Hattrick. <b>It takes about 30-40 seconds for each single season played.</b><br/>
						Don't close this window until the analysis is finished, you will be redirect to your dashboard after the end of the calculation.<br/>
						The analysis require <b>Javascript enabled</b> on your browser. Enjoy!
					</div>
				</div>	
			</div>
		</div>
		
		<?php 
			//footer
			include("footer.php"); 
		?>
		
	</body>

	<script src="js/jquery.min.js"></script>
	<!-- jQuery Easing -->
	<script src="js/jquery.easing.1.3.js"></script>
	<!-- Bootstrap -->
	<script src="js/bootstrap.min.js"></script>
	<!-- Waypoints -->
	<script src="js/jquery.waypoints.min.js"></script>
	<script src="js/sticky.js"></script>

	<!-- Stellar -->
	<script src="js/jquery.stellar.min.js"></script>
	<!-- Superfish -->
	<script src="js/hoverIntent.js"></script>
	<script src="js/superfish.js"></script>
	
	<!-- Main JS -->
	<script src="js/main.js"></script>
	<script src="js/menu.js"></script>
	
	<!-- funzioni di utilità -->
    <script src="js/progressbar.js"></script>
	<script src='js/analyzer.js'></script>
	
	</body>
</html>
	
	