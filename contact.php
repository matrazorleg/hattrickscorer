
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
	<meta name="author" content="Soichiro Arima" />

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
							<li><a href="about.php"><i class="far fa-question-circle"></i> About</a></li>
							<li class="active"><a href="contact.php"><i class="far fa-envelope"></i> Contact</a></li>
						</ul>
					</nav>
				</div>
			</div>
		</header>
				
		<div id="fh5co-contact" class="animate-box">
			<div class="container mg_container mg_contact">
				<?php 
				//handle errors
				if(isset($_GET["e"]) && $_GET["e"] == "1")
				{
				?>
					<div class="row error_box" id="error_box" style="display:block">
						Invalid data provided. Please try again.
					</div>
				<?php
				}
				else if(isset($_GET["e"]) && $_GET["e"] == "2")
				{
				?>
					<div class="row error_box" id="error_box" style="display:block">
						Incomplete data. Please try again.
					</div>
				<?php
				}
				else if(isset($_GET["e"]) && $_GET["e"] == "3")
				{
				?>
					<div class="row error_box" id="error_box" style="display:block">
						It is impossible to send an email now. Please try again later.
					</div>
				<?php
				}
				else if(isset($_GET["s"]) && $_GET["s"] == "1")
				{
				?>
					<div class="row success_box" id="success_box" style="display:block">
						Mail successfully sent. Thanks for your interest in Hattrick Scorer.
					</div>
				<?php
				}
				?>
							
					<div class="row">
						<div class="col-md-6">
							<h3 class="section-title">Find me!</h3>
							<p>Hattrick Scorer Headquarter: between green and cities.</p>
							<ul class="contact-info">
								<li><i class="icon-user-tie"></i><a target="_blank" href="https://www.hattrick.org/Club/Manager/?userId=7674438">SoichiroArima - Manager of Real Cotechino</a></li>
								<li><i class="icon-mail"></i><a href="mailto:htscorer@gmail.com" target="_blank" >htscorer@gmail.com</a></li>
								<li><i class="icon-globe2"></i><a href="http://www.hattrickscorer.com" target="_blank" >www.hattrickscorer.com</a></li>
							</ul>
						</div>
						<div class="col-md-6">
						<form id="contactForm" action="mailer.php" method="post">
							<div class="row">
								<h4>Contact me for any problem or just to send suggestions!<br/>Thank you for using Hattrick Scorer!</h4>
								<div class="col-md-6">
									<div class="form-group">
										<input type="text" class="form-control" placeholder="Name" name="name">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<input type="text" class="form-control" placeholder="Email" name="email">
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<textarea name="message" class="form-control" id="" cols="30" rows="7" placeholder="Message"></textarea>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<input type="submit" value="Send Message" class="btn btn-primary">
									</div>
								</div>
							</div>
						</form>
						</div>
					</div>
			</div>
		</div>
		<!-- END fh5co-contact -->
		<div id="map" class="map" class="fh5co-map">
			<img src="images/map.jpg" alt="Hattrick Scorer Headquarter">
		</div>
		<!-- END map -->

		
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

