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
//If user isn't logged in he cant see a shopping cart
require_once('includes/redirect_home.inc.php');

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

	<?php
		//Navbar
		require_once('includes/navbar.inc.php');
	?>

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
	<!-- Select Update Price -->
	<script src="assets/js/shopping_cart.js"></script>
</body>
