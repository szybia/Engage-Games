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

//Huge input, go back to home page
if (strlen((string)$_GET['q'] ) > 1000)
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

	<title>Engage Search</title>

	<!-- Bootstrap core CSS -->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">

	<!-- Custom CSS -->
	<link href="assets/css/search.css" rel="stylesheet">

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
                        <?php
                        if (empty($_GET['q']))
                        {
                        ?>
                            <img class="sad-face" src="assets/img/sad.png" alt="Sad face" style="display: block;">
    						<h4 class="empty visible">Empty search &#58;&#40;</h4>
                        <?php
                        }
                        else
                        {
                            ?>
                            <div class="search-result">
                                <p>You searched for: <?php xss($_GET['q']); ?></p>
                                <p>Incorrect result? Try <a href="advanced.php">Advanced search.</a></p>
                            </div>
                            <?php
                                //Counter for results
                                $i = 0;

                                //Replace everything which isn't a letter / number or space
                                $_GET['q'] = preg_replace("/[^a-zA-Z0-9\s]/", "", $_GET['q']);
                                $_GET['q'] = "%" . $_GET['q'] . "%";

                                //Acquire all relevant search items
                                $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path
                                                                    FROM game WHERE title like ? OR genre like ? or console like ? OR developer like ?
                                                                    OR release_date like ?");
                                $prepared_statement->bind_param("sssss", $_GET['q'], $_GET['q'], $_GET['q'], $_GET['q'], $_GET['q']);
                                $prepared_statement->execute();
                                $prepared_statement->bind_result($game_id, $title, $console, $price, $cover_path);

                                //Iterate through cart items
                                while ($prepared_statement->fetch())
                                {
                                    ?>
                                    <div class="main-body-white-col">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <a href="game.php?q=<?php xss($game_id); ?>">
                                                        <img class="main-body-img" src="assets/img/games/<?php xss($cover_path); ?>" alt="The cover for <?php xss($title); ?>">
                                                    </a>
                                                    <h3 class="text-black main-body-white-col-h3"><?php xss($title); ?></h3>
                                                    <p class="margin-0"><?php xss($console); ?></p>
                                                    <p class="main-body-white-col-p margin-0">€<?php xss($price); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    $i++;
                                }

                                if ($i == 0)
                                {
                                    ?>
                                        <img class="sad-face" src="assets/img/sad.png" alt="Sad face" style="display: block;">
                						<h4 class="empty visible">No results &#58;&#40;</h4>
                                    <?php
                                }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <footer>
	  <div class="container footer-container">
	  	<div class="row align-items-center">
	  		<div class="col-sm-4">
				<a href="index.php">
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
