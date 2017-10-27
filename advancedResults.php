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

	<title>Engage Results</title>

	<!-- Bootstrap core CSS -->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">

	<!-- Custom CSS -->
	<link href="assets/css/advanced_results.css" rel="stylesheet">

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
                        <div class="search-result">
                            <p>Advanced Search Results.</p>
                            <p>Incorrect result? Try <a href="advanced.php">Advanced search.</a></p>
                        </div>
                        <?php
                        //If all search entries empty
                        if (empty($_GET['game_title'])
                            && empty($_GET['console'])
                            && empty($_GET['genre'])
                            && empty($_GET['age'])
                            && empty($_GET['release_year'])
                            && $_GET['min_price'] == 0
                            && $_GET['max_price'] == 0
                            //If max price is set and min_price bigger than max_price (Impossible)
                            || ($_GET['max_price'] != 0   &&  $_GET['min_price'] > $_GET['max_price'])
                            )
                        {
                        ?>
                            <img class="sad-face" src="assets/img/sad.png" alt="Sad face" style="display: block;">
    						<h4 class="empty visible">Invalid search, try again &#58;&#40;</h4>
                        <?php
                        }
                        else
                        {
                            //Title
                            if (!empty($_GET['game_title']))
                            {
                                $_GET['game_title'] = preg_replace("/[^0-9a-zA-Z\s]/", "", $_GET['game_title']);
                                $_GET['game_title'] = "%" . $_GET['game_title'] . "%";

                                //Console
                                if (!empty($_GET['console']))
                                {
                                    $_GET['console'] = preg_replace("/[^0-9A-Z]/", "", $_GET['console']);

                                    //Genre
                                    if (!empty($_GET['genre']))
                                    {

                                        $_GET['genre'] = preg_replace("/[^A-Za-z\s\-]/", "", $_GET['genre']);

                                        //Age
                                        if (!empty($_GET['age']))
                                        {

                                            $_GET['age'] = preg_replace("/[^0-9]/", "", $_GET['age']);

                                            //Release Year
                                            if (!empty($_GET['release_year']))
                                            {

                                                $_GET['release_year'] = preg_replace("/[^0-9]/", "", $_GET['release_year']);
                                                $_GET['release_year'] = "%" . $_GET['release_year'] . "%";

                                                //Minimum Price
                                                if (!empty($_GET['min_price']) && $_GET['min_price'] != 0)
                                                {

                                                    $_GET['min_price'] = preg_replace("/[^0-9]/", "", $_GET['min_price']);

                                                    //Maximum Price
                                                    if (!empty($_GET['max_price']) && $_GET['max_price'] != 0)
                                                    {
                                                        $_GET['max_price'] = preg_replace("/[^0-9]/", "", $_GET['max_price']);

                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            console like ? AND
                                                                                            genre like ? AND
                                                                                            age <= ? AND
                                                                                            release_date like ? AND
                                                                                            price > ? AND
                                                                                            price < ?");
                                                        $prepared_statement->bind_param("sssisii", $_GET['game_title'], $_GET['console'], $_GET['genre'], $_GET['age'],  $_GET['release_year'],  $_GET['min_price'], $_GET['max_price']);
                                                    }
                                                    else
                                                    {
                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            console like ? AND
                                                                                            genre like ? AND
                                                                                            age <= ? AND
                                                                                            release_date like ? AND
                                                                                            price > ?");
                                                        $prepared_statement->bind_param("sssisi", $_GET['game_title'], $_GET['console'], $_GET['genre'], $_GET['age'],  $_GET['release_year'],  $_GET['min_price']);
                                                    }
                                                }
                                                else
                                                {
                                                    if (!empty($_GET['max_price']) && $_GET['max_price'] != 0)
                                                    {
                                                        $_GET['max_price'] = preg_replace("/[^0-9]/", "", $_GET['max_price']);

                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            console like ? AND
                                                                                            genre like ? AND
                                                                                            age <= ? AND
                                                                                            release_date like ? AND
                                                                                            price <= ?");
                                                        $prepared_statement->bind_param("sssisi", $_GET['game_title'], $_GET['console'], $_GET['genre'], $_GET['age'],  $_GET['release_year'],  $_GET['max_price']);
                                                    }
                                                    else
                                                    {
                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            console like ? AND
                                                                                            genre like ? AND
                                                                                            age <= ? AND
                                                                                            release_date like ?");
                                                        $prepared_statement->bind_param("sssis", $_GET['game_title'], $_GET['console'], $_GET['genre'], $_GET['age'],  $_GET['release_year']);
                                                    }
                                                }
                                            }
                                            else
                                            {
                                                //Minimum Price
                                                if (!empty($_GET['min_price']) && $_GET['min_price'] != 0)
                                                {

                                                    $_GET['min_price'] = preg_replace("/[^0-9]/", "", $_GET['min_price']);

                                                    //Maximum Price
                                                    if (!empty($_GET['max_price']) && $_GET['max_price'] != 0)
                                                    {
                                                        $_GET['max_price'] = preg_replace("/[^0-9]/", "", $_GET['max_price']);

                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            console like ? AND
                                                                                            genre like ? AND
                                                                                            age <= ? AND
                                                                                            price > ? AND
                                                                                            price < ?");
                                                        $prepared_statement->bind_param("sssiii", $_GET['game_title'], $_GET['console'], $_GET['genre'], $_GET['age'], $_GET['min_price'], $_GET['max_price']);
                                                    }
                                                    else
                                                    {
                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            console like ? AND
                                                                                            genre like ? AND
                                                                                            age <= ? AND
                                                                                            price > ?");
                                                        $prepared_statement->bind_param("sssii", $_GET['game_title'], $_GET['console'], $_GET['genre'], $_GET['age'],  $_GET['min_price']);
                                                    }
                                                }
                                                else
                                                {
                                                    if (!empty($_GET['max_price']) && $_GET['max_price'] != 0)
                                                    {
                                                        $_GET['max_price'] = preg_replace("/[^0-9]/", "", $_GET['max_price']);

                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            console like ? AND
                                                                                            genre like ? AND
                                                                                            age <= ? AND
                                                                                            price <= ?");
                                                        $prepared_statement->bind_param("sssii", $_GET['game_title'], $_GET['console'], $_GET['genre'], $_GET['age'],  $_GET['max_price']);
                                                    }
                                                    else
                                                    {
                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            console like ? AND
                                                                                            genre like ? AND
                                                                                            age <= ?");
                                                        $prepared_statement->bind_param("sssi", $_GET['game_title'], $_GET['console'], $_GET['genre'], $_GET['age']);
                                                    }
                                                }
                                            }
                                        }
                                        else
                                        {
                                            //Release Year
                                            if (!empty($_GET['release_year']))
                                            {

                                                $_GET['release_year'] = preg_replace("/[^0-9]/", "", $_GET['release_year']);
                                                $_GET['release_year'] = "%" . $_GET['release_year'] . "%";

                                                //Minimum Price
                                                if (!empty($_GET['min_price']) && $_GET['min_price'] != 0)
                                                {

                                                    $_GET['min_price'] = preg_replace("/[^0-9]/", "", $_GET['min_price']);

                                                    //Maximum Price
                                                    if (!empty($_GET['max_price']) && $_GET['max_price'] != 0)
                                                    {
                                                        $_GET['max_price'] = preg_replace("/[^0-9]/", "", $_GET['max_price']);

                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            console like ? AND
                                                                                            genre like ? AND
                                                                                            release_date like ? AND
                                                                                            price > ? AND
                                                                                            price < ?");
                                                        $prepared_statement->bind_param("ssssii", $_GET['game_title'], $_GET['console'], $_GET['genre'],  $_GET['release_year'],  $_GET['min_price'], $_GET['max_price']);
                                                    }
                                                    else
                                                    {
                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            console like ? AND
                                                                                            genre like ? AND
                                                                                            release_date like ? AND
                                                                                            price > ?");
                                                        $prepared_statement->bind_param("ssssi", $_GET['game_title'], $_GET['console'], $_GET['genre'],  $_GET['release_year'],  $_GET['min_price']);
                                                    }
                                                }
                                                else
                                                {
                                                    if (!empty($_GET['max_price']) && $_GET['max_price'] != 0)
                                                    {
                                                        $_GET['max_price'] = preg_replace("/[^0-9]/", "", $_GET['max_price']);

                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            console like ? AND
                                                                                            genre like ? AND
                                                                                            release_date like ? AND
                                                                                            price <= ?");
                                                        $prepared_statement->bind_param("ssssi", $_GET['game_title'], $_GET['console'], $_GET['genre'],  $_GET['release_year'],  $_GET['max_price']);
                                                    }
                                                    else
                                                    {
                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            console like ? AND
                                                                                            genre like ? AND
                                                                                            release_date like ?");
                                                        $prepared_statement->bind_param("ssss", $_GET['game_title'], $_GET['console'], $_GET['genre'],  $_GET['release_year']);
                                                    }
                                                }
                                            }
                                            else
                                            {
                                                //Minimum Price
                                                if (!empty($_GET['min_price']) && $_GET['min_price'] != 0)
                                                {

                                                    $_GET['min_price'] = preg_replace("/[^0-9]/", "", $_GET['min_price']);

                                                    //Maximum Price
                                                    if (!empty($_GET['max_price']) && $_GET['max_price'] != 0)
                                                    {
                                                        $_GET['max_price'] = preg_replace("/[^0-9]/", "", $_GET['max_price']);

                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            console like ? AND
                                                                                            genre like ? AND
                                                                                            price > ? AND
                                                                                            price < ?");
                                                        $prepared_statement->bind_param("sssii", $_GET['game_title'], $_GET['console'], $_GET['genre'], $_GET['min_price'], $_GET['max_price']);
                                                    }
                                                    else
                                                    {
                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            console like ? AND
                                                                                            genre like ? AND
                                                                                            price > ?");
                                                        $prepared_statement->bind_param("sssi", $_GET['game_title'], $_GET['console'], $_GET['genre'],  $_GET['min_price']);
                                                    }
                                                }
                                                else
                                                {
                                                    if (!empty($_GET['max_price']) && $_GET['max_price'] != 0)
                                                    {
                                                        $_GET['max_price'] = preg_replace("/[^0-9]/", "", $_GET['max_price']);

                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            console like ? AND
                                                                                            genre like ? AND
                                                                                            price <= ?");
                                                        $prepared_statement->bind_param("sssi", $_GET['game_title'], $_GET['console'], $_GET['genre'],  $_GET['max_price']);
                                                    }
                                                    else
                                                    {
                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            console like ? AND
                                                                                            genre like ?");
                                                        $prepared_statement->bind_param("sss", $_GET['game_title'], $_GET['console'], $_GET['genre']);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    else
                                    {
                                        //Age
                                        if (!empty($_GET['age']))
                                        {

                                            $_GET['age'] = preg_replace("/[^0-9]/", "", $_GET['age']);

                                            //Release Year
                                            if (!empty($_GET['release_year']))
                                            {

                                                $_GET['release_year'] = preg_replace("/[^0-9]/", "", $_GET['release_year']);
                                                $_GET['release_year'] = "%" . $_GET['release_year'] . "%";

                                                //Minimum Price
                                                if (!empty($_GET['min_price']) && $_GET['min_price'] != 0)
                                                {

                                                    $_GET['min_price'] = preg_replace("/[^0-9]/", "", $_GET['min_price']);

                                                    //Maximum Price
                                                    if (!empty($_GET['max_price']) && $_GET['max_price'] != 0)
                                                    {
                                                        $_GET['max_price'] = preg_replace("/[^0-9]/", "", $_GET['max_price']);

                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            console like ? AND
                                                                                            age <= ? AND
                                                                                            release_date like ? AND
                                                                                            price > ? AND
                                                                                            price < ?");
                                                        $prepared_statement->bind_param("ssisii", $_GET['game_title'], $_GET['console'], $_GET['age'],  $_GET['release_year'],  $_GET['min_price'], $_GET['max_price']);
                                                    }
                                                    else
                                                    {
                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            console like ? AND
                                                                                            age <= ? AND
                                                                                            release_date like ? AND
                                                                                            price > ?");
                                                        $prepared_statement->bind_param("ssisi", $_GET['game_title'], $_GET['console'], $_GET['age'],  $_GET['release_year'],  $_GET['min_price']);
                                                    }
                                                }
                                                else
                                                {
                                                    if (!empty($_GET['max_price']) && $_GET['max_price'] != 0)
                                                    {
                                                        $_GET['max_price'] = preg_replace("/[^0-9]/", "", $_GET['max_price']);

                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            console like ? AND
                                                                                            age <= ? AND
                                                                                            release_date like ? AND
                                                                                            price <= ?");
                                                        $prepared_statement->bind_param("ssisi", $_GET['game_title'], $_GET['console'], $_GET['age'],  $_GET['release_year'],  $_GET['max_price']);
                                                    }
                                                    else
                                                    {
                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            console like ? AND
                                                                                            age <= ? AND
                                                                                            release_date like ?");
                                                        $prepared_statement->bind_param("ssis", $_GET['game_title'], $_GET['console'], $_GET['age'],  $_GET['release_year']);
                                                    }
                                                }
                                            }
                                            else
                                            {
                                                //Minimum Price
                                                if (!empty($_GET['min_price']) && $_GET['min_price'] != 0)
                                                {

                                                    $_GET['min_price'] = preg_replace("/[^0-9]/", "", $_GET['min_price']);

                                                    //Maximum Price
                                                    if (!empty($_GET['max_price']) && $_GET['max_price'] != 0)
                                                    {
                                                        $_GET['max_price'] = preg_replace("/[^0-9]/", "", $_GET['max_price']);

                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            console like ? AND
                                                                                            age <= ? AND
                                                                                            price > ? AND
                                                                                            price < ?");
                                                        $prepared_statement->bind_param("ssiii", $_GET['game_title'], $_GET['console'], $_GET['age'], $_GET['min_price'], $_GET['max_price']);
                                                    }
                                                    else
                                                    {
                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            console like ? AND
                                                                                            age <= ? AND
                                                                                            price > ?");
                                                        $prepared_statement->bind_param("ssii", $_GET['game_title'], $_GET['console'], $_GET['age'],  $_GET['min_price']);
                                                    }
                                                }
                                                else
                                                {
                                                    if (!empty($_GET['max_price']) && $_GET['max_price'] != 0)
                                                    {
                                                        $_GET['max_price'] = preg_replace("/[^0-9]/", "", $_GET['max_price']);

                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            console like ? AND
                                                                                            age <= ? AND
                                                                                            price <= ?");
                                                        $prepared_statement->bind_param("ssii", $_GET['game_title'], $_GET['console'], $_GET['age'],  $_GET['max_price']);
                                                    }
                                                    else
                                                    {
                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            console like ? AND
                                                                                            age <= ?");
                                                        $prepared_statement->bind_param("ssi", $_GET['game_title'], $_GET['console'], $_GET['age']);
                                                    }
                                                }
                                            }
                                        }
                                        else
                                        {
                                            //Release Year
                                            if (!empty($_GET['release_year']))
                                            {

                                                $_GET['release_year'] = preg_replace("/[^0-9]/", "", $_GET['release_year']);
                                                $_GET['release_year'] = "%" . $_GET['release_year'] . "%";

                                                //Minimum Price
                                                if (!empty($_GET['min_price']) && $_GET['min_price'] != 0)
                                                {

                                                    $_GET['min_price'] = preg_replace("/[^0-9]/", "", $_GET['min_price']);

                                                    //Maximum Price
                                                    if (!empty($_GET['max_price']) && $_GET['max_price'] != 0)
                                                    {
                                                        $_GET['max_price'] = preg_replace("/[^0-9]/", "", $_GET['max_price']);

                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            console like ? AND
                                                                                            release_date like ? AND
                                                                                            price > ? AND
                                                                                            price < ?");
                                                        $prepared_statement->bind_param("sssii", $_GET['game_title'], $_GET['console'],  $_GET['release_year'],  $_GET['min_price'], $_GET['max_price']);
                                                    }
                                                    else
                                                    {
                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            console like ? AND
                                                                                            release_date like ? AND
                                                                                            price > ?");
                                                        $prepared_statement->bind_param("sssi", $_GET['game_title'], $_GET['console'], $_GET['release_year'],  $_GET['min_price']);
                                                    }
                                                }
                                                else
                                                {
                                                    if (!empty($_GET['max_price']) && $_GET['max_price'] != 0)
                                                    {
                                                        $_GET['max_price'] = preg_replace("/[^0-9]/", "", $_GET['max_price']);

                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            console like ? AND
                                                                                            release_date like ? AND
                                                                                            price <= ?");
                                                        $prepared_statement->bind_param("sssi", $_GET['game_title'], $_GET['console'], $_GET['release_year'],  $_GET['max_price']);
                                                    }
                                                    else
                                                    {
                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            console like ? AND
                                                                                            release_date like ?");
                                                        $prepared_statement->bind_param("sss", $_GET['game_title'], $_GET['console'],  $_GET['release_year']);
                                                    }
                                                }
                                            }
                                            else
                                            {
                                                //Minimum Price
                                                if (!empty($_GET['min_price']) && $_GET['min_price'] != 0)
                                                {

                                                    $_GET['min_price'] = preg_replace("/[^0-9]/", "", $_GET['min_price']);

                                                    //Maximum Price
                                                    if (!empty($_GET['max_price']) && $_GET['max_price'] != 0)
                                                    {
                                                        $_GET['max_price'] = preg_replace("/[^0-9]/", "", $_GET['max_price']);

                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            console like ? AND
                                                                                            price > ? AND
                                                                                            price < ?");
                                                        $prepared_statement->bind_param("ssii", $_GET['game_title'], $_GET['console'], $_GET['min_price'], $_GET['max_price']);
                                                    }
                                                    else
                                                    {
                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            console like ? AND
                                                                                            price > ?");
                                                        $prepared_statement->bind_param("ssi", $_GET['game_title'], $_GET['console'], $_GET['min_price']);
                                                    }
                                                }
                                                else
                                                {
                                                    if (!empty($_GET['max_price']) && $_GET['max_price'] != 0)
                                                    {
                                                        $_GET['max_price'] = preg_replace("/[^0-9]/", "", $_GET['max_price']);

                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            console like ? AND
                                                                                            price <= ?");
                                                        $prepared_statement->bind_param("ssi", $_GET['game_title'], $_GET['console'],  $_GET['max_price']);
                                                    }
                                                    else
                                                    {
                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            console like ?");
                                                        $prepared_statement->bind_param("ss", $_GET['game_title'], $_GET['console']);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                else
                                {
                                    //Genre
                                    if (!empty($_GET['genre']))
                                    {

                                        $_GET['genre'] = preg_replace("/[^A-Za-z\s\-]/", "", $_GET['genre']);

                                        //Age
                                        if (!empty($_GET['age']))
                                        {

                                            $_GET['age'] = preg_replace("/[^0-9]/", "", $_GET['age']);

                                            //Release Year
                                            if (!empty($_GET['release_year']))
                                            {

                                                $_GET['release_year'] = preg_replace("/[^0-9]/", "", $_GET['release_year']);
                                                $_GET['release_year'] = "%" . $_GET['release_year'] . "%";

                                                //Minimum Price
                                                if (!empty($_GET['min_price']) && $_GET['min_price'] != 0)
                                                {

                                                    $_GET['min_price'] = preg_replace("/[^0-9]/", "", $_GET['min_price']);

                                                    //Maximum Price
                                                    if (!empty($_GET['max_price']) && $_GET['max_price'] != 0)
                                                    {
                                                        $_GET['max_price'] = preg_replace("/[^0-9]/", "", $_GET['max_price']);

                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            genre like ? AND
                                                                                            age <= ? AND
                                                                                            release_date like ? AND
                                                                                            price > ? AND
                                                                                            price < ?");
                                                        $prepared_statement->bind_param("ssisii", $_GET['game_title'], $_GET['genre'], $_GET['age'],  $_GET['release_year'],  $_GET['min_price'], $_GET['max_price']);
                                                    }
                                                    else
                                                    {
                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            genre like ? AND
                                                                                            age <= ? AND
                                                                                            release_date like ? AND
                                                                                            price > ?");
                                                        $prepared_statement->bind_param("ssisi", $_GET['game_title'], $_GET['genre'], $_GET['age'],  $_GET['release_year'],  $_GET['min_price']);
                                                    }
                                                }
                                                else
                                                {
                                                    if (!empty($_GET['max_price']) && $_GET['max_price'] != 0)
                                                    {
                                                        $_GET['max_price'] = preg_replace("/[^0-9]/", "", $_GET['max_price']);

                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            genre like ? AND
                                                                                            age <= ? AND
                                                                                            release_date like ? AND
                                                                                            price <= ?");
                                                        $prepared_statement->bind_param("ssisi", $_GET['game_title'], $_GET['genre'], $_GET['age'],  $_GET['release_year'],  $_GET['max_price']);
                                                    }
                                                    else
                                                    {
                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            genre like ? AND
                                                                                            age <= ? AND
                                                                                            release_date like ?");
                                                        $prepared_statement->bind_param("ssis", $_GET['game_title'], $_GET['genre'], $_GET['age'],  $_GET['release_year']);
                                                    }
                                                }
                                            }
                                            else
                                            {
                                                //Minimum Price
                                                if (!empty($_GET['min_price']) && $_GET['min_price'] != 0)
                                                {

                                                    $_GET['min_price'] = preg_replace("/[^0-9]/", "", $_GET['min_price']);

                                                    //Maximum Price
                                                    if (!empty($_GET['max_price']) && $_GET['max_price'] != 0)
                                                    {
                                                        $_GET['max_price'] = preg_replace("/[^0-9]/", "", $_GET['max_price']);

                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            genre like ? AND
                                                                                            age <= ? AND
                                                                                            price > ? AND
                                                                                            price < ?");
                                                        $prepared_statement->bind_param("ssiii", $_GET['game_title'], $_GET['genre'], $_GET['age'], $_GET['min_price'], $_GET['max_price']);
                                                    }
                                                    else
                                                    {
                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            genre like ? AND
                                                                                            age <= ? AND
                                                                                            price > ?");
                                                        $prepared_statement->bind_param("ssii", $_GET['game_title'], $_GET['genre'], $_GET['age'],  $_GET['min_price']);
                                                    }
                                                }
                                                else
                                                {
                                                    if (!empty($_GET['max_price']) && $_GET['max_price'] != 0)
                                                    {
                                                        $_GET['max_price'] = preg_replace("/[^0-9]/", "", $_GET['max_price']);

                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            genre like ? AND
                                                                                            age <= ? AND
                                                                                            price <= ?");
                                                        $prepared_statement->bind_param("ssii", $_GET['game_title'], $_GET['genre'], $_GET['age'],  $_GET['max_price']);
                                                    }
                                                    else
                                                    {
                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            genre like ? AND
                                                                                            age <= ?");
                                                        $prepared_statement->bind_param("ssi", $_GET['game_title'], $_GET['genre'], $_GET['age']);
                                                    }
                                                }
                                            }
                                        }
                                        else
                                        {
                                            //Release Year
                                            if (!empty($_GET['release_year']))
                                            {

                                                $_GET['release_year'] = preg_replace("/[^0-9]/", "", $_GET['release_year']);
                                                $_GET['release_year'] = "%" . $_GET['release_year'] . "%";

                                                //Minimum Price
                                                if (!empty($_GET['min_price']) && $_GET['min_price'] != 0)
                                                {

                                                    $_GET['min_price'] = preg_replace("/[^0-9]/", "", $_GET['min_price']);

                                                    //Maximum Price
                                                    if (!empty($_GET['max_price']) && $_GET['max_price'] != 0)
                                                    {
                                                        $_GET['max_price'] = preg_replace("/[^0-9]/", "", $_GET['max_price']);

                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            genre like ? AND
                                                                                            release_date like ? AND
                                                                                            price > ? AND
                                                                                            price < ?");
                                                        $prepared_statement->bind_param("sssii", $_GET['game_title'], $_GET['genre'],  $_GET['release_year'],  $_GET['min_price'], $_GET['max_price']);
                                                    }
                                                    else
                                                    {
                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            genre like ? AND
                                                                                            release_date like ? AND
                                                                                            price > ?");
                                                        $prepared_statement->bind_param("sssi", $_GET['game_title'], $_GET['genre'],  $_GET['release_year'],  $_GET['min_price']);
                                                    }
                                                }
                                                else
                                                {
                                                    if (!empty($_GET['max_price']) && $_GET['max_price'] != 0)
                                                    {
                                                        $_GET['max_price'] = preg_replace("/[^0-9]/", "", $_GET['max_price']);

                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            genre like ? AND
                                                                                            release_date like ? AND
                                                                                            price <= ?");
                                                        $prepared_statement->bind_param("sssi", $_GET['game_title'], $_GET['genre'],  $_GET['release_year'],  $_GET['max_price']);
                                                    }
                                                    else
                                                    {
                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            genre like ? AND
                                                                                            release_date like ?");
                                                        $prepared_statement->bind_param("sss", $_GET['game_title'], $_GET['genre'],  $_GET['release_year']);
                                                    }
                                                }
                                            }
                                            else
                                            {
                                                //Minimum Price
                                                if (!empty($_GET['min_price']) && $_GET['min_price'] != 0)
                                                {

                                                    $_GET['min_price'] = preg_replace("/[^0-9]/", "", $_GET['min_price']);

                                                    //Maximum Price
                                                    if (!empty($_GET['max_price']) && $_GET['max_price'] != 0)
                                                    {
                                                        $_GET['max_price'] = preg_replace("/[^0-9]/", "", $_GET['max_price']);

                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            genre like ? AND
                                                                                            price > ? AND
                                                                                            price < ?");
                                                        $prepared_statement->bind_param("ssii", $_GET['game_title'], $_GET['genre'], $_GET['min_price'], $_GET['max_price']);
                                                    }
                                                    else
                                                    {
                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            genre like ? AND
                                                                                            price > ?");
                                                        $prepared_statement->bind_param("ssi", $_GET['game_title'], $_GET['genre'],  $_GET['min_price']);
                                                    }
                                                }
                                                else
                                                {
                                                    if (!empty($_GET['max_price']) && $_GET['max_price'] != 0)
                                                    {
                                                        $_GET['max_price'] = preg_replace("/[^0-9]/", "", $_GET['max_price']);

                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            genre like ? AND
                                                                                            price <= ?");
                                                        $prepared_statement->bind_param("ssi", $_GET['game_title'], $_GET['genre'],  $_GET['max_price']);
                                                    }
                                                    else
                                                    {
                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            genre like ?");
                                                        $prepared_statement->bind_param("ss", $_GET['game_title'],  $_GET['genre']);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    else
                                    {
                                        //Age
                                        if (!empty($_GET['age']))
                                        {

                                            $_GET['age'] = preg_replace("/[^0-9]/", "", $_GET['age']);

                                            //Release Year
                                            if (!empty($_GET['release_year']))
                                            {

                                                $_GET['release_year'] = preg_replace("/[^0-9]/", "", $_GET['release_year']);
                                                $_GET['release_year'] = "%" . $_GET['release_year'] . "%";

                                                //Minimum Price
                                                if (!empty($_GET['min_price']) && $_GET['min_price'] != 0)
                                                {

                                                    $_GET['min_price'] = preg_replace("/[^0-9]/", "", $_GET['min_price']);

                                                    //Maximum Price
                                                    if (!empty($_GET['max_price']) && $_GET['max_price'] != 0)
                                                    {
                                                        $_GET['max_price'] = preg_replace("/[^0-9]/", "", $_GET['max_price']);

                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            age <= ? AND
                                                                                            release_date like ? AND
                                                                                            price > ? AND
                                                                                            price < ?");
                                                        $prepared_statement->bind_param("sisii", $_GET['game_title'], $_GET['age'],  $_GET['release_year'],  $_GET['min_price'], $_GET['max_price']);
                                                    }
                                                    else
                                                    {
                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            age <= ? AND
                                                                                            release_date like ? AND
                                                                                            price > ?");
                                                        $prepared_statement->bind_param("sisi", $_GET['game_title'], $_GET['age'],  $_GET['release_year'],  $_GET['min_price']);
                                                    }
                                                }
                                                else
                                                {
                                                    if (!empty($_GET['max_price']) && $_GET['max_price'] != 0)
                                                    {
                                                        $_GET['max_price'] = preg_replace("/[^0-9]/", "", $_GET['max_price']);

                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            age <= ? AND
                                                                                            release_date like ? AND
                                                                                            price <= ?");
                                                        $prepared_statement->bind_param("sisi", $_GET['game_title'], $_GET['age'],  $_GET['release_year'],  $_GET['max_price']);
                                                    }
                                                    else
                                                    {
                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            age <= ? AND
                                                                                            release_date like ?");
                                                        $prepared_statement->bind_param("sis", $_GET['game_title'], $_GET['age'],  $_GET['release_year']);
                                                    }
                                                }
                                            }
                                            else
                                            {
                                                //Minimum Price
                                                if (!empty($_GET['min_price']) && $_GET['min_price'] != 0)
                                                {

                                                    $_GET['min_price'] = preg_replace("/[^0-9]/", "", $_GET['min_price']);

                                                    //Maximum Price
                                                    if (!empty($_GET['max_price']) && $_GET['max_price'] != 0)
                                                    {
                                                        $_GET['max_price'] = preg_replace("/[^0-9]/", "", $_GET['max_price']);

                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            age <= ? AND
                                                                                            price > ? AND
                                                                                            price < ?");
                                                        $prepared_statement->bind_param("siii", $_GET['game_title'], $_GET['age'], $_GET['min_price'], $_GET['max_price']);
                                                    }
                                                    else
                                                    {
                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            age <= ? AND
                                                                                            price > ?");
                                                        $prepared_statement->bind_param("sii", $_GET['game_title'], $_GET['age'],  $_GET['min_price']);
                                                    }
                                                }
                                                else
                                                {
                                                    if (!empty($_GET['max_price']) && $_GET['max_price'] != 0)
                                                    {
                                                        $_GET['max_price'] = preg_replace("/[^0-9]/", "", $_GET['max_price']);

                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            age <= ? AND
                                                                                            price <= ?");
                                                        $prepared_statement->bind_param("sii", $_GET['game_title'], $_GET['age'],  $_GET['max_price']);
                                                    }
                                                    else
                                                    {
                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            age <= ?");
                                                        $prepared_statement->bind_param("si", $_GET['game_title'], $_GET['age']);
                                                    }
                                                }
                                            }
                                        }
                                        else
                                        {
                                            //Release Year
                                            if (!empty($_GET['release_year']))
                                            {

                                                $_GET['release_year'] = preg_replace("/[^0-9]/", "", $_GET['release_year']);
                                                $_GET['release_year'] = "%" . $_GET['release_year'] . "%";

                                                //Minimum Price
                                                if (!empty($_GET['min_price']) && $_GET['min_price'] != 0)
                                                {

                                                    $_GET['min_price'] = preg_replace("/[^0-9]/", "", $_GET['min_price']);

                                                    //Maximum Price
                                                    if (!empty($_GET['max_price']) && $_GET['max_price'] != 0)
                                                    {
                                                        $_GET['max_price'] = preg_replace("/[^0-9]/", "", $_GET['max_price']);

                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            release_date like ? AND
                                                                                            price > ? AND
                                                                                            price < ?");
                                                        $prepared_statement->bind_param("ssii", $_GET['game_title'],  $_GET['release_year'],  $_GET['min_price'], $_GET['max_price']);
                                                    }
                                                    else
                                                    {
                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            release_date like ? AND
                                                                                            price > ?");
                                                        $prepared_statement->bind_param("ssi", $_GET['game_title'], $_GET['release_year'],  $_GET['min_price']);
                                                    }
                                                }
                                                else
                                                {
                                                    if (!empty($_GET['max_price']) && $_GET['max_price'] != 0)
                                                    {
                                                        $_GET['max_price'] = preg_replace("/[^0-9]/", "", $_GET['max_price']);

                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            release_date like ? AND
                                                                                            price <= ?");
                                                        $prepared_statement->bind_param("ssi", $_GET['game_title'], $_GET['release_year'],  $_GET['max_price']);
                                                    }
                                                    else
                                                    {
                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            release_date like ?");
                                                        $prepared_statement->bind_param("ss", $_GET['game_title'], $_GET['release_year']);
                                                    }
                                                }
                                            }
                                            else
                                            {
                                                //Minimum Price
                                                if (!empty($_GET['min_price']) && $_GET['min_price'] != 0)
                                                {

                                                    $_GET['min_price'] = preg_replace("/[^0-9]/", "", $_GET['min_price']);

                                                    //Maximum Price
                                                    if (!empty($_GET['max_price']) && $_GET['max_price'] != 0)
                                                    {
                                                        $_GET['max_price'] = preg_replace("/[^0-9]/", "", $_GET['max_price']);

                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            price > ? AND
                                                                                            price < ?");
                                                        $prepared_statement->bind_param("sii", $_GET['game_title'], $_GET['min_price'], $_GET['max_price']);
                                                    }
                                                    else
                                                    {
                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            price > ?");
                                                        $prepared_statement->bind_param("si", $_GET['game_title'], $_GET['min_price']);
                                                    }
                                                }
                                                else
                                                {
                                                    if (!empty($_GET['max_price']) && $_GET['max_price'] != 0)
                                                    {
                                                        $_GET['max_price'] = preg_replace("/[^0-9]/", "", $_GET['max_price']);

                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ? AND
                                                                                            price <= ?");
                                                        $prepared_statement->bind_param("si", $_GET['game_title'], $_GET['max_price']);
                                                    }
                                                    else
                                                    {
                                                        $prepared_statement = $db->prepare("SELECT game_id, title, console, price, cover_path FROM game WHERE
                                                                                            title like ?");
                                                        $prepared_statement->bind_param("s", $_GET['game_title']);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            else
                            {

                            }


                            $prepared_statement->execute();
                            $prepared_statement->bind_result($game_id, $title, $console, $price, $cover_path);

                            while($prepared_statement->fetch())
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
