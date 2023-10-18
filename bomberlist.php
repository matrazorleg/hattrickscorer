<?php

	//lato model dello script...ho lo sha1 trovo l'id...
	require_once 'engine/database.php';
	require_once 'engine/constant.php';
	require_once 'engine/PHT/autoload.php';
	
	if(isset($_COOKIE["HattrickScorerId"]))
		$userCode = $_COOKIE["HattrickScorerId"];
	else
		$userCode = "";
	$userId = -1;
	
	/*
		preferences
	*/
	
	define (DATE_FORMAT, "Y-m-d");
	
	/******/
	//Ricavo i dati dell'utente se è arrivato alla bomberlist dopo la dashboard 
	//Mi serve per mostrare i risultati della sua squadra
	$result = $db->query("SELECT id FROM `user` WHERE `user_code` = '".$userCode."'");
	if($result->num_rows == 1) 
	{
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$userId = $row["id"];		
	}
	
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
	
	<!-- data tables -->
	<link rel="stylesheet" href="css/datatables.css" />	
	<link rel="stylesheet" href="css/datatables.bootstrap.css" />	
	
	</head>
	<body>
				
		<header id="fh5co-header-section" class="sticky-banner">
			<div class="container">
				<div class="nav-header">
					<a href="#fh5co-primary-menu" onclick="showMenu()" class="js-fh5co-nav-toggle fh5co-nav-toggle dark"><i></i></a>
					<h1 id="fh5co-logo"><a href="index.php">Hattrick Scorer</a></h1>
					<!-- START #fh5co-menu-wrap -->
					<?php
					
					//il menu si deve comportare diversamente a seconda che l'utente sia loggato o meno
					if($userId != -1)
					{
						?>
						<nav id="fh5co-menu-wrap" role="navigation">
							<ul class="sf-menu" id="fh5co-primary-menu">
								<li class="active"><a href="bomberlist.php"><i class="fa fa-trophy"></i> BomberList</a></li>
								<li><a href="dashboard.php?id=<?php echo $userCode; ?>"><i class="fas fa-futbol"></i> Goals</a></li>
								<li><a href="appearance.php?id=<?php echo $userCode; ?>"><i class="far fa-sticky-note"></i> Appearance</a></li>
								<li><a href="global.php?id=<?php echo $userCode; ?>"><i class="far fa-clipboard"></i> General</a></li>
								<li><a href="#" onclick="unsetAndLogout()"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
							</ul>
						</nav>
						<?php
					}
					else
					{
						?>
						<nav id="fh5co-menu-wrap" role="navigation">
							<ul class="sf-menu" id="fh5co-primary-menu">
								<li><a href="index.php"><i class="fa fa-home"></i> Home</a></li>
								<li class="active"><a href="bomberlist.php"><i class="fa fa-trophy"></i> BomberList</a></li>
								<li><a href="about.php"><i class="far fa-question-circle"></i> About</a></li>
								<li><a href="contact.php"><i class="far fa-envelope"></i> Contact</a></li>
							</ul>
						</nav>
						<?php
					}				
					
					?>
				</div>
			</div>
		</header>
			
		<div id="top"></div>	
		<div id="fh5co-feature-product" class="fh5co-section-gray mg_dashboard bomber_list">
			<div class="container">
				
				<div class="bl_inst">
					<div class="bl_inst_title">BomberList <i class="fa fa-info-circle" aria-hidden="true" onclick="openTabBL()"></i></div>
					<div id="bl_inst_text">
						BomberList shows the best strikers of Hattrick Scorer users.<br/>
						For each registered user, the best scorer is showed for each type of competition. Only one player is part of this list (1 for every team in every competition played), so that everyone can take part without any advantage.<br/>
						<b>Entering BomberList is really easy: you have to log in to your Hattrick Scorer account.</b><br/>
						<br/>
						<i>Warning: The chart shows the player's goals and appearances exclusively with your team, and not globally in his career. If multiple players have scored the same number of goals, the second order rule is their appearances (realization average).</i><br/>
					</div>
				</div> 
				<div class="bl_wrong_calc">
					<div class="bl_wrong_calc_title" onclick="openTabWC()">Something wrong in goals calculation? Please click here!</div>
					<div id="bl_wrong_calc_text">
						I found a bug in Hattrick, confirmed by Game Masters that are also working on it.<br/>
						It consist in wrong calculation of goals reported in the player page when the player played and scored for other teams in the past and all them now are BOTS. This problem primarily involves players that are still "alive" and aged 35-40+.<br/>
						<b>For these players the stats reported by Hattrick Scorer are more reliable then Hattrick!</b><br/>
					</div>
				</div>
				
				<div class="controls">
					<div class="scorer_category">Global</div>
					<div class="scorer_control scorer_active" id="official_goals" onclick="showBombers(this,'ufficiali',1)">Official match goals</div>
					<div class="scorer_control" id="all_goals" onclick="showBombers(this,'tutti',1)">All match goals</div>
				</div>		
				<div class="controls">
					<div class="scorer_category">Competition</div>
					<div class="scorer_control" id="league_goals" onclick="showBombers(this,'campionato',1)">League match goals</div>
					<div class="scorer_control" id="cup_goals" onclick="showBombers(this,'coppa',1)">Cup match goals</div>
					<div class="scorer_control" id="friendly_goals" onclick="showBombers(this,'amichevole',1)">Friendly match goals</div>
					<div class="scorer_control" id="masters_goals" onclick="showBombers(this,'masters',1)">Hattrick Masters goals</div>
				</div>
				<div class="loading_gears" id="loading_gears">
					<img src="images/gears_inverted.svg" />
				</div>	
				
				<div id="bl_my_best_scorer">
					
				</div>
							
				<div class="scorer_tables" id="scorer_append">
					
					
				</div>
								
				<div id="goToTop"><a href="#top">^<br/>TOP</div></div>
				
			</div>
		</div>
				
		<?php 
			//footer
			include("footer.php"); 
		?>
	

	</div>
	<!-- END fh5co-page -->

	</div>
	<!-- END fh5co-wrapper -->

	<!-- jQuery -->


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
	<input type="hidden" id="current_active" value="official_goals" />
	<input type="hidden" id="current_request" value="ufficiali" />
	<input type="hidden" id="userCode" value="<?php echo $userCode; ?>" /> <?php //mi serve per il javascript ?>
	<script src='js/bomberlist.js'></script>
	
	<!-- data tables -->
	<script src="js/datatables.js"></script>

	</body>
</html>

