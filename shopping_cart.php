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

//If user isn't logged in he cant see a shopping cart
if (empty($_SESSION['email']))
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

	<title>Engage Cart</title>

	<!-- Bootstrap core CSS -->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">

	<!-- Custom CSS -->
	<link href="assets/css/shopping_cart.css" rel="stylesheet">

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
                //Include navbar user shopping cart and profile
                require_once('includes/shopping_cart_navbar.inc.php');
            ?>
	    </div>
	</nav>

	<div class="alert alert-primary" role="alert">
		<button type="button" class="btn btn-outline-light">Undo</button>
	</div>

    <div class="main-body">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="catalogue">
                        <img class="sad-face" src="assets/img/sad.png" alt="Sad face">
						<h4 class="empty">Your shopping cart is empty &#58;&#40;</h4>
                        <h3 class="total"></h3>

                        <?php
                            //Acquire all shopping cart items
                            $prepared_statement = $db->prepare("select game_id, title, console, release_date, developer, price, cover_path, quantity from game
                                                                join shopping_cart using (game_id) where email = ?");
                            $prepared_statement->bind_param("s", $_SESSION['email']);
                            $prepared_statement->execute();
                            $prepared_statement->bind_result($game_id, $title, $console, $release_date, $developer, $price, $cover_path, $quantity);

                            //Iterate through cart items
                            while ($prepared_statement->fetch())
                            {
                            ?>
                                <div class="container wishlist-entry">
                                    <div class="row">
                                        <div class="col-sm-7">
                                            <img class="main-body-img" src="assets/img/games/<?php xss($cover_path); ?>" alt="The cover for <?php xss($title); ?>">
                                            <div class="container">
                                                <div class="row centered-form">
                                                    <div class="col-sm-12">
                                                        <p><?php xss($title); ?></p>
                                                        <p><?php xss($console); ?></p>
                                                        <p><?php xss(mb_substr($release_date, 0, 4)); ?></p>
                                                        <p><?php xss($developer); ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-5 text-align">
        									<h5 class="price">€<?php xss($price * $quantity); ?></h5>
        									<p class="base-price"><?php xss($price); ?></p>
                                            <p class="game-id"><?php xss($game_id); ?></p>
                                            <form class="quantity-form ">
                                              <div class="form-row align-items-center">
                                                <div class="col-auto center-quantity">
                                                  <select class="custom-select mb-2 mr-sm-2 mb-sm-0" id="inlineFormCustomSelect">
                                                      <?php
                                                      switch ($quantity)
                                                      {
                                                            case 1: ?>
                                                              <option selected value="1">1</option>
                                                              <option value="2">2</option>
                                                              <option value="3">3</option>
                                                              <option value="4">4</option>
                                                              <option value="5">5</option>
                                                              <option value="6">6</option>
                                                              <option value="7">7</option>
                                                              <option value="8">8</option>
                                                              <option value="9">9</option>
                                                            <?php break;
                                                            case 2: ?>
                                                                <option selected value="1">1</option>
                                                                <option selected value="2">2</option>
                                                                <option value="3">3</option>
                                                                <option value="4">4</option>
                                                                <option value="5">5</option>
                                                                <option value="6">6</option>
                                                                <option value="7">7</option>
                                                                <option value="8">8</option>
                                                                <option value="9">9</option>
                                                            <?php break;
                                                            case 3: ?>
                                                                <option selected value="1">1</option>
                                                                <option value="2">2</option>
                                                                <option selected value="3">3</option>
                                                                <option value="4">4</option>
                                                                <option value="5">5</option>
                                                                <option value="6">6</option>
                                                                <option value="7">7</option>
                                                                <option value="8">8</option>
                                                                <option value="9">9</option>
                                                            <?php break;
                                                            case 4: ?>
                                                                <option selected value="1">1</option>
                                                                <option value="2">2</option>
                                                                <option value="3">3</option>
                                                                <option selected value="4">4</option>
                                                                <option value="5">5</option>
                                                                <option value="6">6</option>
                                                                <option value="7">7</option>
                                                                <option value="8">8</option>
                                                                <option value="9">9</option>
                                                            <?php break;
                                                            case 5: ?>
                                                                <option selected value="1">1</option>
                                                                <option value="2">2</option>
                                                                <option value="3">3</option>
                                                                <option value="4">4</option>
                                                                <option selected value="5">5</option>
                                                                <option value="6">6</option>
                                                                <option value="7">7</option>
                                                                <option value="8">8</option>
                                                                <option value="9">9</option>
                                                            <?php break;
                                                            case 6: ?>
                                                                <option selected value="1">1</option>
                                                                <option value="2">2</option>
                                                                <option value="3">3</option>
                                                                <option value="4">4</option>
                                                                <option value="5">5</option>
                                                                <option selected value="6">6</option>
                                                                <option value="7">7</option>
                                                                <option value="8">8</option>
                                                                <option value="9">9</option>
                                                            <?php break;
                                                            case 7: ?>
                                                                <option selected value="1">1</option>
                                                                <option value="2">2</option>
                                                                <option value="3">3</option>
                                                                <option value="4">4</option>
                                                                <option value="5">5</option>
                                                                <option value="6">6</option>
                                                                <option selected value="7">7</option>
                                                                <option value="8">8</option>
                                                                <option value="9">9</option>
                                                            <?php break;
                                                            case 8: ?>
                                                                <option selected value="1">1</option>
                                                                <option value="2">2</option>
                                                                <option value="3">3</option>
                                                                <option value="4">4</option>
                                                                <option value="5">5</option>
                                                                <option value="6">6</option>
                                                                <option value="7">7</option>
                                                                <option selected value="8">8</option>
                                                                <option value="9">9</option>
                                                            <?php break;
                                                            case 9: ?>
                                                                <option  value="1">1</option>
                                                                <option value="2">2</option>
                                                                <option value="3">3</option>
                                                                <option value="4">4</option>
                                                                <option value="5">5</option>
                                                                <option value="6">6</option>
                                                                <option value="7">7</option>
                                                                <option value="8">8</option>
                                                                <option selected value="9">9</option>
                                                            <?php break;
                                                            default: ?>
                                                                <option value="1">1</option>
                                                                <option value="2">2</option>
                                                                <option value="3">3</option>
                                                                <option value="4">4</option>
                                                                <option value="5">5</option>
                                                                <option value="6">6</option>
                                                                <option value="7">7</option>
                                                                <option value="8">8</option>
                                                                <option selected value="<?php xss($quantity); ?>"><?php xss($quantity); ?></option>
                                                            <?php break;
                                                      } ?>
                                                  </select>
                                                </div>
                                              </div>
                                            </form>
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                            $prepared_statement->close();
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
    <!--  Popper JavaScript  -->
    <script src="assets/js/popper.min.js"></script>
    <!--  Bootstrap JavaScript  -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/custom.js"></script>
	<!-- Select Update Price -->
	<script src="assets/js/shopping_cart.js"></script>
</body>
