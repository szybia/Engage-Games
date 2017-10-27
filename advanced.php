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

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="The Official Page of Engage Games">
	<meta name="author" content="Szymon Bialkowski &amp; Shane Doyle">
	<link rel="icon" href="assets/img/favicon.ico">

	<title>Engage Advanced Search</title>

	<!-- Bootstrap core CSS -->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">

	<!-- Custom CSS -->
	<link href="assets/css/advanced.css" rel="stylesheet">

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
					<form class="navbar-form align-middle" role="search">
			        <div class="input-group nav-search">
			            <input type="text" class="form-control" placeholder="FIFA 18" name="q">
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
                //Include navbar user shopping cart and profile
                require_once('includes/shopping_cart_navbar.inc.php');
            ?>
	    </div>
	</nav>

    <div class="main-body">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="catalogue">
                        <div class="container">
                            <form class="main-form" method="get" action="advancedResults.php">
                                <h3>Advanced Search.</h3>
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-2 col-form-label">Title</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="game_title" placeholder="Name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Console</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="console" id="exampleFormControlSelect1">
                                            <option selected="" disabled>Console</option>
                                            <option value="PC">PC</option>
                                            <option value="PS4">Playstation 4</option>
                                            <option value="XB1">Xbox One</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Genre</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="genre" id="exampleFormControlSelect1">
                                            <option selected="" disabled>Genre</option>
                                            <option>Action</option>
                                            <option>Action role-playing</option>
                                            <option>Action-Adventure</option>
                                            <option>Fighting</option>
                                            <option>FPS</option>
                                            <option>Shooter</option>
                                            <option>Sports</option>
                                            <option>Stealth</option>
                                            <option>Survival Horror</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Age</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="age" id="exampleFormControlSelect1">
                                            <option selected="" disabled>Age</option>
                                            <option>18+</option>
                                            <option>16+</option>
                                            <option>12+</option>
                                            <option>7+</option>
                                            <option>3+</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-2 col-form-label">Year</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" name="release_year" placeholder="Release Year">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-2 col-form-label">Min Price</label>
                                    <div class="col-sm-10">
                                        <p class="slider-p" id="slider_output">0</p>
                                        <input type="range" name="min_price" value="0" id="my_range" min="0" max="100">
                                        </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-2 col-form-label">Max Price</label>
                                    <div class="col-sm-10">
                                        <p class="slider-p" id="slider_output_2">0</p>
                                        <input type="range" name="max_price" value="0" id="my_range_2" min="0" max="100">
                                        </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                    </div>
                                </div>
                            </form>
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
    <!-- Custom Slider JS -->
    <script src="assets/js/advanced_search.js"></script>
</body>
