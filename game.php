<?php
session_start();

//Include database connection
require_once('includes/db.inc.php');
//Include remember me login
require_once('includes/remember_cookie.inc.php');
//Include CSRFToken generator
require_once('includes/csrf_token.inc.php');
//Print function to avoid XSS
require_once('includes/xss.inc.php');

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

    <?php
        //Navbar
        require_once('includes/navbar.inc.php');
    ?>


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
                                        //Print game details
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
                                        <p class="hidden-id"><?php xss($_GET['q']); ?></p>
                                        <p><?php xss($developer); ?></p>
                                        <p>€<?php xss($price); ?></p>
                                        <div class="alert alert-danger hidden" role="alert">
                                          Please log-in.
                                        </div>
                                        <div class="alert alert-danger quantity-error" role="alert">
                                        </div>
                                        <input type="number" class="form-control bfh-number game-quantity-input" placeholder="Quantity" required>
                                        <button type="button" class="btn btn-outline-dark" data-toggle="modal" data-target="#addCart">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    if (!empty($_SESSION['email']))
    {
        ?>
        <div class="modal fade" id="addCart" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Added <?php xss($title); ?> to cart.</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-dark" data-dismiss="modal" aria-label="Close">Continue Shopping</button>
                <a href="shopping_cart.php">
                    <button type="button" class="btn btn-outline-dark">Go to shopping cart.</button>
                </a>
              </div>
            </div>
          </div>
        </div>
        <?php
    }
    ?>

    <?php
        //Print footer
        require_once('includes/footer.inc.php');
    ?>


    <!--  JQuery JavaScript     -->
	<script src="assets/js/jquery.min.js"></script>
    <!--  Popper JavaScript  -->
    <script src="assets/js/popper.min.js"></script>
    <!--  Bootstrap JavaScript  -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/custom.js"></script>
    <!-- AJAX adding to database -->
    <script src="assets/js/game_add.js"></script>
</body>
