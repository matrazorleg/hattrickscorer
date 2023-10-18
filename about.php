
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
	<meta name="author" content="MatRazorleg" />

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
	
	<!-- font-awesome -->
	<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
	
	<!-- timeline -->
	<link rel="stylesheet" href="css/timeline.css">

	<!-- Modernizr JS -->
	<script src="js/modernizr-2.6.2.min.js"></script>
	<!-- FOR IE9 below -->
	<!--[if lt IE 9]>
	<script src="js/respond.min.js"></script>
	<![endif]-->

	</head>
	<body>
		<?php include_once("analytics/analyticstracking.php") ?>
		
		<header id="fh5co-header-section" class="sticky-banner">
			<div class="container">
				<div class="nav-header">
					<a href="#fh5co-primary-menu" onclick="showMenu()" class="js-fh5co-nav-toggle fh5co-nav-toggle dark"><i></i></a>
					<h1 id="fh5co-logo"><a href="index.php">Hattrick Scorer</a></h1>
					<!-- START #fh5co-menu-wrap -->
					<nav id="fh5co-menu-wrap" role="navigation">
						<ul class="sf-menu" id="fh5co-primary-menu">
							<li><a href="index.php"><i class="fa fa-home"></i> Home</a></li>
							<li><a href="bomberlist.php"><i class="fa fa-trophy"></i> BomberList</a></li>
							<li class="active"><a href="about.php"><i class="far fa-question-circle"></i> About</a></li>
							<li><a href="contact.php"><i class="far fa-envelope"></i> Contact</a></li>
						</ul>
					</nav>
				</div>
			</div>
		</header>
		
		

		<div id="fh5co-content-section" class="fh5co-section-gray">
			<div class="container">
				<div class="row">
					<div class="col-md-8 col-md-offset-2 text-center heading-section animate-box">
						<h3>About Hattrick Scorer</h3>
						<p>Me, this tool and future development</p>
					</div>
				</div>
			</div>
			<div class="container mg_feature">
				<div class="row">
					<div class="col-md-4">
						<div class="fh5co-team text-center animate-box">
							<figure>
								<img src="images/avatar.png" alt="SoichiroArima">
							</figure>

							<div class="mg_padding_top">
								<h3>SoichiroArima</h3>
								<p><span>Founder and Developer</span></p>
								<p class="responsive_just">I am an Italian computer engineer.<br/>
								My work consist in software creation and computer systems management.<br/>
								I play Hattrick since 2006 and I always wanted to make a tool that would be useful to me and perhaps to some other game user. I hope I have succeeded in my objective.</p>
							</div>
							
						</div>
					</div>
					<div class="col-md-8">
						<div class="feature-text animate-box">						
							<h3>My history in Hattrick</h3>
							<p class="responsive_just">
							It all started in 2006, when still a student I joined Hattrick not knowing it would be the game that I played for the longest time in my entire life.<br/>
							For reasons of time I had to abandon the original team in early 2011 and then re-establish the glorious "Real Cotechino" in 2012, from then on my passion for the game is always increased and many were the satisfaction and fun (certainly also some disappointment, but it's part of the game!).<br/>
							In the last year I thought very about Hattrick Scorer because every sale of a player's "historic" of my team I was wondering how it was important for the team.<br/>
							And what is more important to score lots of goals and be compared with other big players of the team?<br/>
							From this idea I started developing this tool, which I hope will be useful or at least fun to use.<br/>
							There is still a long way to go, but I am convinced that this can be a good start!<br/>
							I wish good Hattrick to all!
							</p>
							<h3>Credits</h3>
							<p>
							Special thanks to <a href="https://github.com/jetwitaussi/pht" target="_blank">PHT</a> created by <a href="https://www.hattrick.org/Club/Manager/?userId=653581" target="_blank">CHPP-teles</a><br/>
							It is the PHP Framework for Hattrick CHPP applications that is used in Hattrick Scorer tool.
							</p>
							<p>
							Current version: <b>0.5.1</b>
							</p>
						</div>
					</div>
				</div>
				<div class="row row_distance">
					<div class="col-md-12">
						<h3>History and Changelog</h3>
						<!-- The Timeline -->
						<ul class="timeline">

							<!-- Item -->
							<li>
								<div class="direction-r">
									<div class="flag-wrapper">
										<span class="flag">16 September 2020</span>
										<span class="time-wrapper"><span class="time">0.5.1</span></span>
									</div>
									<div class="desc">
									<p>
									> Added new seasons starting from 76.
									</p>
									</div>
								</div>
							</li>
							
							<li>
								<div class="direction-l">
									<div class="flag-wrapper">
										<span class="flag">25 January 2019</span>
										<span class="time-wrapper"><span class="time">0.5.0</span></span>
									</div>
									<div class="desc">
									<p>
									> Renewed graphics.<br/>
									> Mobile responsive improvements.<br/>
									> Introduced stats about matches and captains. <br/>
									</p>
									</div>
								</div>
							</li>
							
							<li>
								<div class="direction-r">
									<div class="flag-wrapper">
										<span class="flag">8 August 2017</span>
										<span class="time-wrapper"><span class="time">0.4.0</span></span>
									</div>
									<div class="desc">
									<p>
									> Fixed Hattrick Masters goal calculation.<br/>
									> Fixed appearances for red carded players.<br/>
									> Segnalation to Hattrick Staff of a bug in goals stats. <br/>
									> Hided NT stats, will come back after improving the calculation system. <br/>
									</p>
									</div>
								</div>
							</li>
							
							<li>
								<div class="direction-l">
									<div class="flag-wrapper">
										<span class="flag">19 June 2017</span>
										<span class="time-wrapper"><span class="time">0.3.3</span></span>
									</div>
									<div class="desc">
									<p>
									> Fixed bug to appearance calculation.
									</p>
									</div>
								</div>
							</li>
							
							<li>
								<div class="direction-r">
									<div class="flag-wrapper">
										<span class="flag">8 May 2017</span>
										<span class="time-wrapper"><span class="time">0.3.2</span></span>
									</div>
									<div class="desc">
									<p>
									> Fixed season bug for global users.
									</p>
									</div>
								</div>
							</li>
							
							<li>
								<div class="direction-l">
									<div class="flag-wrapper">
										<span class="flag">25 April 2017</span>
										<span class="time-wrapper"><span class="time">0.3.1</span></span>
									</div>
									<div class="desc">
									<p>
									> Introduced password recovery.<br/>
									> Migration on new server.
									</p>
									</div>
								</div>
							</li>
							
							<li>
								<div class="direction-r">
									<div class="flag-wrapper">
										<span class="flag">17 April 2017</span>
										<span class="time-wrapper"><span class="time">0.3.0</span></span>
									</div>
									<div class="desc">
									<p>
									> Login system and analysis time improved.<br/>
									> Introduced new features and appearances section.
									</p>
									</div>
								</div>
							</li>
							
							<li>
								<div class="direction-l">
									<div class="flag-wrapper">
										<span class="flag">18 March 2017</span>
										<span class="time-wrapper"><span class="time">0.2.1</span></span>
									</div>
									<div class="desc">
									<p>
									> Minor bugfixing.
									</p>
									</div>
								</div>
							</li>
														
							<li>
								<div class="direction-r">
									<div class="flag-wrapper">
										<span class="flag">7 March 2017</span>
										<span class="time-wrapper"><span class="time">0.2.0</span></span>
									</div>
									<div class="desc">
									<p>
									> Launched Hattrick Scorer.
									</p>
									</div>
								</div>
							</li>
							
							<li>
								<div class="direction-l">
									<div class="flag-wrapper">
										<span class="flag">20 October 2016</span>
										<span class="time-wrapper"><span class="time">0.1.0</span></span>
									</div>
									<div class="desc">
									<p>
									> Started developing Hattrick Scorer.
									</p>
									</div>
								</div>
							</li>
							
							
										  
						</ul>
					</div>
				
				</div>
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

	</body>
</html>

