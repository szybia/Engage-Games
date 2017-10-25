<?php
session_start();

//Include database connection
require_once('includes/db.inc.php');
//Include remember me login
require_once('includes/remember_cookie.inc.php');
//Include CSRFToken generator
require_once('includes/csrf_token.inc.php');

//Print function to avoid XSS
function xss($message)
{
    echo(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));
}

//If no game specified or isn't a number go home
if (empty($_GET['q']) || !is_numeric($_GET['q']))
{
    header("Location: index.php");
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="The Official Page of Engage Games">
	<meta name="author" content="Szymon Bialkowski &amp; Shane Doyle">
	<link rel="icon" href="assets/img/favicon.ico">

	<title>Engage Game</title>

	<!-- Bootstrap core CSS -->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">

	<!-- Custom CSS -->
	<link href="assets/css/game.css" rel="stylesheet">

	<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Lato:300,300i,400,700" rel="stylesheet">

	<!-- HTML5 shiv and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>

<body>
    <div id="loading"></div>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
	    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".dual-collapse">
	        <span class="navbar-toggler-icon"></span>
	    </button>
		<a href="index.php">
			<img class="nav-logo" src="assets/img/logo-black.png" alt="Official Logo of Engage Games">
		</a>
		<div class="container-fluid">
			<div class="row nav-center">
				<div class="col-sm-12 nav-center">
					<form class="navbar-form align-middle" action="search.php" role="search">
			        <div class="input-group nav-search">
			            <input type="text" class="form-control" placeholder="Search" name="q">
			            <div class="input-group-btn">
			                <button class="btn btn-default" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
			            </div>
			        </div>
			        </form>
				</div>
			</div>
		</div>

	    <div class="navbar-collapse navbar-toggleable-md collapse dual-collapse">
	        <ul class="navbar-nav ml-auto">
	            <li class="nav-item first">
	                <a class="nav-link text-black nav-item-bold" href="index.php">HOME</a>

	            </li>
	            <li class="nav-item second">
	                <a class="nav-link text-black nav-item-bold" href="catalogue.php">CATALOGUE</a>
	            </li>
	            <li class="nav-item third">
	                <a class="nav-link text-black nav-item-bold" target="_blank" href="https://github.com/SzymonB7/EngageGames">ABOUT</a>
	            </li>

	            <hr class="navbar-underline">
	        </ul>
	        <hr class="vertical-hr">

            <?php
            if (isset($_SESSION['logged_in']))
            {    ?>
                <div class="logged-in">
                  <a href="shopping_cart.php">
                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                    <div class="numberCircle">
                        <?php
                            if (!empty($_SESSION['email']))
                            {
                                //Number of items in shopping cart
                                $prepared_statement = $db->prepare("select count(*) from user join shopping_cart using (email) where email = ?");
                                $prepared_statement->bind_param("s", $_SESSION['email']);
                                $prepared_statement->execute();
                                $prepared_statement->bind_result($shopping_cart_num);
                                $prepared_statement->fetch();
                                $prepared_statement->close();
                                xss($shopping_cart_num);
                            }
                        ?>
                    </div>
                  </a>
                  <a href="profile.php">
                    <img class="logged-in-img" src="assets/img/users/<?php xss($_SESSION['user_image_path']); ?>" alt="Profile picture of <?php xss($_SESSION['username']); ?>">
                  </a>
                </div>
            <?php
            }
            else
            { ?>
    	        <div class="login">
    	            <i class="fa fa-unlock" aria-hidden="true"></i>
    	            <a class="text-black nav-item-bold" href="login.php">LOGIN</a>
    	        </div>
    	        <div class="register">
    	            <i class="fa fa-user-plus" aria-hidden="true"></i>
    	            <a class="text-black nav-item-bold" href="login.php">REGISTER</a>
    	        </div>
            <?php
            } ?>
	    </div>
	</nav>


    <div class="main-body">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="catalogue">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="game-details">
                                        <?php

                                        //Print number of items in shopping cart
                                        $prepared_statement = $db->prepare("select title, console, release_date, price, developer, cover_path from game where game_id = ?");
                                        $prepared_statement->bind_param("i", $_GET['q']);
                                        $prepared_statement->execute();
                                        $prepared_statement->bind_result($title, $console, $release_date, $price, $developer, $cover_path);
                                        $prepared_statement->fetch();
                                        $prepared_statement->close();   ?>
                                        <img class="main-body-img" src="assets/img/games/<?php xss($cover_path); ?>" alt="The cover for <?php xss($title); ?>">
                                        <p><?php xss($title); ?></p>
                                        <p><?php xss($console); ?></p>
                                        <p><?php xss(mb_substr($release_date, 0, 4)); ?></p>
                                        <p><?php xss($developer); ?></p>
                                        <p>€<?php xss($price); ?></p>
                                        <button type="button" class="btn btn-outline-dark">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <footer>
	  <div class="container footer-container">
	  	<div class="row align-items-center">
	  		<div class="col-sm-4">
				<a href="#">
	  				<img class="nav-logo" src="assets/img/logo.png" alt="Official Logo of Engage Games">
				</a>
	  		</div>
	  		<div class="col-sm-4 align-center">
					<a href="https://github.com/BialkowskiSz" class="footer-badge" target="_blank">
						<i class="fa fa-github"></i>
					</a>
					<a href="https://www.linkedin.com/in/szymonbialkowski" class="footer-badge" target="_blank">
						<i class="fa fa-linkedin"></i>
					</a>
					<a href="https://www.linkedin.com/in/szymonbialkowski" class="footer-badge" target="_blank">
						<i class="fa fa-linkedin"></i>
					</a>
	  		</div>
	  		<div class="col-sm-4">
	  			<h3 class="footer-copyright">©Engage 2017.
                                                All rights reserved.</h3>
	  		</div>
	  	</div>
	  </div>
	</footer>


    <!--  JQuery JavaScript     -->
	<script src="assets/js/jquery.min.js"></script>
    <!--  Bootstrap JavaScript  -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/custom.js"></script>
</body>
