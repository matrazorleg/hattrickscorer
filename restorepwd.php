
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

	<!-- font-awesome -->
	<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
	
	<!-- Modernizr JS -->
	<script src="js/modernizr-2.6.2.min.js"></script>
	<!-- FOR IE9 below -->
	<!--[if lt IE 9]>
	<script src="js/respond.min.js"></script>
	<![endif]-->
	
	<!-- funzioni di utilità -->
	<script src='js/restorepwd.js'></script>
	
	</head>
	<body>
		<?php include_once("analytics/analyticstracking.php"); ?>
				
		<header id="fh5co-header-section" class="sticky-banner">
			<div class="container">
				<div class="nav-header">
					<a href="#fh5co-primary-menu" onclick="showMenu()" class="js-fh5co-nav-toggle fh5co-nav-toggle dark"><i></i></a>
					<h1 id="fh5co-logo"><a href="index.php">Hattrick Scorer</a></h1>
					<!-- START #fh5co-menu-wrap -->
					<nav id="fh5co-menu-wrap" role="navigation">
						<ul class="sf-menu" id="fh5co-primary-menu">
							<li><a href="index.php"><i class="fa fa-home"></i> Home</a></li>
						</ul>
					</nav>
				</div>
			</div>
		</header>
			
		<div class="container mg_container">
			<div id="mg_login">	
				
					<div class="row success_box" id="success_box" style="display:none">
					</div>
					<div class="row error_box" id="error_box" style="display:none">
					</div>
				
				<div class="row">
					<div class="col-md-12">
						<form>
							<div class="input_row input_row100"><div class="title">RESTORE PASSWORD</div></div>
							<div class="input_row input_row100"><div class="subtitle">Insert USERNAME and the EMAIL used to register.</div></div>
							<div class="input_row input_row100"><div class="labelf">Username</div><div class="control"><input type="text" name="register_username" id="register_username" value="" placeholder="username" onclick="whiteBackground(this.id);" /></div></div>
							<div class="input_row input_row100"><div class="labelf">Email</div><div class="control"><input type="text" name="register_email" id="register_email" value="" placeholder="email" onclick="whiteBackground(this.id);" /></div></div>
							<div class="input_row input_row100"><div id="form_button" class="form_button_restore" onclick="checkRestore();">Restore</div></div>
						</form>
					</div>
				</div>			
			</div>
		</div>
		
		<div id="fh5co-feature-product" class="fh5co-section-gray">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="feature-text">
							<h3>Password Restore</h3>
							<p>
							By clicking "Restore" button the system will send a new password to the address used to register.<br/>
							You can use the new password to access your account.<br/>
							If you don't remeber your username or the email address used to register please send and email in Contact page. Our team will answer as soon as possible.<br/>
							<br/>
							<b>If you don't receive the email check the SPAM folder of your email account.</b>
							</p>
						</div>
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

