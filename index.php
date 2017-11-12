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

$num_of_slides = 3; //Dynamic number of slides for home page

?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="The Official Page of Engage Games">
	<meta name="author" content="Szymon Bialkowski &amp; Shane Doyle">
	<link rel="icon" href="assets/img/favicon.ico">

	<title>Engage</title>

	<!-- Bootstrap core CSS -->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">

	<!-- Custom CSS -->
	<link href="assets/css/style.css" rel="stylesheet">

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


	<section class="main-body">
		<div class="container">
			<div class="row">
				<div class="col-sm-5">
					<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
						<ol class="carousel-indicators">
                            <?php for ($i=0; $i < $num_of_slides; $i++) { ?>
                                <?php if ($i == 0): ?>
                                    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                                <?php else: ?>
                                    <li data-target="#carouselExampleIndicators" data-slide-to="<?php xss($i); ?>"></li>
                                <?php endif; ?>
                            <?php }//end of for loop ?>
						</ol>


						<div class="carousel-inner" role="listbox">
                            <?php

    					   	$result = mysqli_query($db, "SELECT game_id, title, cover_path FROM game where deal like 1 order by RAND() limit $num_of_slides");
                            for ($i = 0; $i < $num_of_slides; $i++) {
                                $row = mysqli_fetch_row($result); ?>
                                <?php if ($i == 0): ?>
                                    <div class="carousel-item active">
                                <?php else: ?>
                                    <div class="carousel-item">
                                <?php endif; ?>
    								<a href="game.php?q=<?php xss($row[0]); ?>">
    								<img class="d-block img-fluid" src="assets/img/games/<?php xss($row[2]);  ?>" alt="The cover image of <?php xss($row[1]);  ?>">
    								<div class="carousel-caption  d-md-block">
    								    <h3><?php xss($row[1]); ?></h3>
    							    </div>
    								</a>
    							</div>

                            <?php   } //end for loop   ?>
						</div>
						<a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
							<span class="carousel-control-prev-icon" aria-hidden="true"></span>
							<span class="sr-only">Previous</span>
						</a>
						<a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
							<span class="carousel-control-next-icon" aria-hidden="true"></span>
							<span class="sr-only">Next</span>
						</a>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="main-body-first-col">
						<h3>Under $5</h3>
                        <?php

                            $result = mysqli_query($db, "SELECT game_id, title, price, cover_path FROM game where price < 5 order by RAND() limit 2");
                            for ($i = 0; $i < 2; $i++)
                            {
                                $row = mysqli_fetch_row($result);
                        ?>

                            <div class="main-body-white-col">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <a href="game.php?q=<?php xss ($row[0]); ?>">
                                                <img class="main-body-img" src="assets/img/games/<?php xss ($row[3]); ?>" alt="The cover for <?php xss ($row[1]); ?>">
                                            </a>
                                            <h3 class="text-black main-body-white-col-h3"><?php xss ($row[1]); ?></h3>
                                            <p class="main-body-white-col-p">€<?php xss ($row[2]); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php
                            } //end for loop
                        ?>

					</div>
				</div>
				<div class="col-sm-3">
					<div class="main-body-first-col">
						<h3>New</h3>
                        <?php

                        $result = mysqli_query($db, "SELECT game_id, title, price, cover_path FROM game order by -release_date limit 2");
                        for ($i = 0; $i < 2; $i++) {
                            $row = mysqli_fetch_row($result); ?>

                            <div class="main-body-white-col">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <a href="game.php?q=<?php xss ($row[0]); ?>">
                                                <img class="main-body-img" src="assets/img/games/<?php xss ($row[3]); ?>" alt="The cover for <?php xss ($row[1]); ?>">
                                            </a>
                                            <h3 class="text-black main-body-white-col-h3"><?php xss ($row[1]); ?></h3>
                                            <p class="main-body-white-col-p">€<?php xss ($row[2]); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>




                        <?php   } //end for loop   ?>
					</div>
				</div>
			</div>
		</div>
	</section>

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
