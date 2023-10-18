
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
		
	<!-- captcha -->
	<script type='text/javascript'>
    var captchaContainer = null;
    var loadCaptcha = function() {
      captchaContainer = grecaptcha.render('captcha_container', {
        'sitekey' : '6LerAxYUAAAAAICwy8-jrN7B6dPJpfyU1GppL3Na',
        'callback' : function(response) {
			$("#captcha_response").val(response);	
			$("#form_button2").prop("disabled", false);
        }
      });
    };
    </script>
	
	
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
							<li class="active"><a href="index.php"><i class="fa fa-home"></i> Home</a></li>
							<li><a href="bomberlist.php"><i class="fa fa-trophy"></i> BomberList</a></li>
							<li><a href="about.php"><i class="far fa-question-circle"></i> About</a></li>
							<li><a href="contact.php"><i class="far fa-envelope"></i> Contact</a></li>
						</ul>
					</nav>
				</div>
			</div>
		</header>
		
		<div class="container">
			<div class="row alert alert-warning" role="alert" style="text-align:center;font-weight:bold;font-size:14px;">
				<div class="col-sm-12">
					<i class="fa fa-exclamation-triangle" aria-hidden="true"></i><br/>
					<b>Hattrick Scorer will be discontinued on 2024-02-28</b> - The domain now is hattrickscorer.work.gd<br/><br/>
					<div style="text-align:left">
						Dear HS User, <br/><br/>
						Unfortunately the adventure is coming to an end. Due to lack of time and high costs of hosting and domain maintenance the tool will be closed.<br/>
						In these six years it has been an honor and a source of happiness for me to have created this tool for you.<br/>
						There were many users who appreciated it and gave me many suggestions for improvement.<br/>
						My presence on Hattrick has also decreased a lot and the passion is no longer what it was when I requested my very first team in the game in 2006.<br/>
						I will publish the source code of the site soon and if someone wants to continue the project I will be happy to share with him all the data I have collected.<br/>
						In recent times there have been some problems for CHPP products, now are resolved so you can fully enjoy Hattrick Scorer for another year.<br/>
						<br/>
						<i>Thank you for being an Hattrick Scorer user.</i><br/>
						<i>With estimates, Soichiro Arima aka Matteo</i>
						<div style="margin-top:10px; font-size:12px;color:red;">
							The domain has moved to work.gd and some browsers thinks that is not safe, because the original name was hattrickscorer.com. <b style="color:black">Don't worry it's always the same HS and it is totally SAFE</b>
						</div>
					</div>
				</div>				
			</div>
		</div>
		
		<div class="intro_no_border">
			<div id="fh5co-feature-product" class="fh5co-section-gray">
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<p class="intro_text">For so long you have searched for a tool that analyzes your games and says who are your best strikers ... <b>now you find it!</b></p>
							<div class="col-md-3 feature-text">
								<h3>Strikers and Appearances</h3>
								<span>A simple tool to discover your team best strikers. The goals are divided according to the competition.</span>
							</div>
							<div class="col-md-3 feature-text">
								<h3>Results</h3>
								<span>With this tool you will see all the goals scored by your team, both official and unofficial, but also your captains history and match results.</span>
							</div>
							<div class="col-md-3 feature-text">
								<h3>Fast</h3>
								<span>Through a rapid registration system you will have access to faster analysis when you will come back.</span>
							</div>
							<div class="col-md-3 feature-text">
								<h3>BomberList</h3>
								<span>Compare your stats with other Hattrick Scorer users.</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="container mg_container">
		<?php
			//verifico se esiste il cookie
			//se esiste vado direttamente alla dashboard dello scorer, perchè ho tutti i dati che mi servono per fare la richiesta
			//altrimenti propongo login e registrazione
		?>
		
			<div id="mg_login">	
				<?php 
				//handle errors
				if(isset($_GET["code"]) && $_GET["code"] == "0")
				{
				?>
					<div class="row error_box" id="error_box" style="display:block">
						reCaptcha not valid. Please try again.
					</div>
				<?php
				}
				else if(isset($_GET["code"]) && $_GET["code"] == "1")
				{
				?>
					<div class="row error_box" id="error_box" style="display:block">
						This email already exists in our database. Please try again.
					</div>
				<?php
				}
				else if(isset($_GET["code"]) && $_GET["code"] == "2")
				{
				?>
					<div class="row error_box" id="error_box" style="display:block">
						This username already exists in our database. Please try again.
					</div>
				<?php
				}
				else if(isset($_GET["code"]) && $_GET["code"] == "e")
				{
				?>
					<div class="row error_box" id="error_box" style="display:block">
						Please type your username and password in the login form.
					</div>
				<?php
				}
				else if(isset($_GET["code"]) && $_GET["code"] == "n")
				{
				?>
					<div class="row error_box" id="error_box" style="display:block">
						Incorrect username or password. Please try again.
					</div>
				<?php
				}
				else if(isset($_GET["code"]) && $_GET["code"] == "3")
				{
				?>
					<div class="row success_box" id="success_box" style="display:block">
						Registration successfull. Please log in your account to use Hattrick Scorer.
					</div>
					<div class="row error_box" id="error_box" style="display:none">
					</div>
				<?php
				}
				else
				{
				?>
					<div class="row error_box" id="error_box" style="display:none">
					</div>
				<?php
				}
				?>
				<div class="row">
					<div class="col-md-4">
					<?php 
						if(!isset($_COOKIE["HattrickScorerId"])) //se manca il cookie serve il login
						{
					?>
							<form id="login-form" method="post" action="login.php">
								<div class="input_row input_row100"><div class="title">LOGIN</div></div>
								<div class="input_row input_row100"><div class="subtitle">Access to view your team best strikers</div></div>
								<div class="input_row input_row100"><div class="labelf">Username</div><div class="control"><input type="text" name="login_username" id="login_username" value="" placeholder="username" onclick="whiteBackground(this.id);" /></div></div>
								<div class="input_row input_row100"><div class="labelf">Password</div><div class="control"><input type="password" name="login_password" id="login_password" value="" placeholder="password" onclick="whiteBackground(this.id);" /></div></div>
								<div class="input_row input_row50"><a href="restorepwd.php" class="restorepwd">Forgot password?</a></div>
								<div class="input_row input_row50"><input type="submit" id="form_button" class="form_button" value="Login" /></div>
							</form>
					<?php
						}
						else //quicklogin
						{
					?>		<div class="input_row input_row100"><div class="title">LOGIN</div></div>
							<div class="input_row input_row100"><div class="subtitle">Access to view your team best strikers</div></div>
							<div class="input_row input_row100"><a class="quickbutton" href="analyzer.php?id=<?php echo $_COOKIE["HattrickScorerId"]; ?>">Go to your dashboard</a></div>
					<?php
						}						
					?>
					</div>
					<div class="col-md-8 border_box">
						<form id="register-form" method="post" action="register.php" onsubmit="return validateRegistration()">
							<div class="input_row input_row100"><div class="title">REGISTER</div></div>
							<div class="input_row input_row100"><div class="subtitle">Create an account to use Hattrick Scorer. Check the <b>reCaptcha</b> to enable the registration button.</div></div>
							<div class="input_row input_row50"><div class="labelf">Username</div><div class="control"><input type="text" name="register_username" id="register_username" value="" placeholder="username" onclick="whiteBackground(this.id);" /></div></div>
							<div class="input_row input_row50"><div class="labelf">Email</div><div class="control"><input type="text" name="register_email" id="register_email" value="" placeholder="email" onclick="whiteBackground(this.id);" /></div></div>
							<div class="input_row input_row50"><div class="labelf">Password</div><div class="control"><input type="password" name="register_password" id="register_password" value="" placeholder="password" onclick="whiteBackground(this.id);" /></div></div>
							<div class="input_row input_row50"><div class="labelf">Re-Password</div><div class="control"><input type="password" name="register_re_password" id="register_re_password" value="" placeholder="re-password" onclick="whiteBackground(this.id);" /></div></div>
							<input type="hidden" id="captcha_response" value="" />
							<div class="input_row input_row_50" id="captcha_container"></div>
							<div class="input_row input_row50"><input type="submit" id="form_button2" class="form_button2" value="Register" disabled="true"/></div>							
						</form>
						<!-- captcha -->
						<script src="https://www.google.com/recaptcha/api.js?onload=loadCaptcha&render=explicit" async defer></script>
					</div>
				</div>			
			</div>
		</div>
		
		<div id="fh5co-feature-product" class="fh5co-section-gray">
			<div class="container">
				
				<div class="row">
					<div class="col-md-4">
						<div class="feature-text">
							<h3>Register</h3>
							<p>Registration is required to use the tool to make fast analysis, and save your data for the next time you will use Hattrick Scorer.</p>
						</div>
					</div>
					<div class="col-md-4">
						<div class="feature-text">
							<h3>Login</h3>
							<p>With the credentials provided in registration log into your account to start the analysis. The analysis time depends on the number of games.</p>
						</div>
					</div>
					<div class="col-md-4">
						<div class="feature-text">
							<h3>Authorize CHPP</h3>
							<p>The first time you log into your account you will be redirect to the Hattrick CHPP page to authorize Hattrick Scorer.</p>
						</div>
					</div>
				</div>
				<div class="row row_distance">
					<div class="col-md-6">
						<div class="feature-text">
							<h3>Analysis time</h3>
							<p>Analyze all the matches of a team takes about 15-20 seconds for each season played. The process may be long the first time. The next time will be only analyzed the current season.</p>
						</div>
					</div>
					<div class="col-md-6">
						<div class="feature-text">
							<h3>Why we need registration?</h3>
							<p>Registration is required to save your data in order to make the process faster. Also by registering an account on Hattrick Scorer you will need to certify the product only the first time.</p>
						</div>
					</div>
				</div>
				<div class="row row_distance">
					<div class="col-md-4">
						<div class="feature-text">
							<?php include "fragments/paypal.php"; ?>
						</div>
					</div>
					<div class="col-md-8">
						<div class="feature-text">
							<p class="small_text_donation">
							Hattrick Scorer is FREE and always will be.<br/>
							If you appreciate this tool and you would like to contribute to maintaining the site you can make a donation.<br/>
							Thank you!
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
	
	<!-- funzioni di utilità -->
	<script src='js/functions.js'></script>
	
	</body>
</html>

