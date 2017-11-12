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

    <?php
        //Navbar
        require_once('includes/navbar.inc.php');
    ?>

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


    <?php
        //Print footer
        require_once('includes/footer.inc.php');
    ?>


    <!--  JQuery JavaScript     -->
	<script src="assets/js/jquery.min.js"></script>
    <!--  Bootstrap JavaScript  -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/custom.js"></script>
</body>
