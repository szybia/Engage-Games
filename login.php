<?php
session_start();

//Include database connection
require_once('includes/db.inc.php');
//Include remember me login
require_once('includes/remember_cookie.inc.php');
//Include CSRFToken generator
require_once('includes/csrf_token.inc.php');

//If user is logged in
if (!empty($_SESSION['logged_in'])) {
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

	<title>Engage Authentication</title>

	<!-- Bootstrap core CSS -->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">

	<!-- Custom CSS -->
	<link href="assets/css/login.css" rel="stylesheet">

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
	<a class="home" href="index.php">
		<h1 class="go-home">
			<i class="fa fa-long-arrow-left" aria-hidden="true"></i>
		Go Home.</h1>
	</a>
	<div class="main-logo-box">
        <div class="container">
            <div class="row">
                <div class="col-md-7 col-md-push-4"></div>
                <div class="col-md-4 col-md-pull-8">
                    <img class="main-logo" src="assets/img/logo.png" alt="Official Logo of Engage games">
                </div>
            </div>
        </div>
    </div>
    <div class="register-form">
        <div class="container">
            <div class="row">
                <div class="col-md-7 col-md-push-4"></div>
                <div class="col-md-4 col-md-pull-8">
                    <div class="register-box">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h3>Register</h3>
                                </div>
                            </div>
                        </div>

                        <?php
                        if (!empty($_GET['page']) && !empty($_GET['q']))
                        {
                            if ($_GET['page'] == 'register')
                            {
                                    switch ($_GET['q'])
                                    {
                                        case "empty":
                                            ?>
                                            <div class="alert alert-danger" role="alert">
                                              Please fill out all of the fields.
                                            </div>
                                            <?php
                                            break;
                                        case "invalid":
                                            ?>
                                            <div class="alert alert-danger" role="alert">
                                              Please do not enter any illegal characters.
                                            </div>
                                            <?php
                                            break;
                                        case "invalidemail":
                                            ?>
                                            <div class="alert alert-danger" role="alert">
                                              You have entered an invalid email.
                                            </div>
                                            <?php
                                            break;
                                        case "exists":
                                            ?>
                                            <div class="alert alert-danger" role="alert">
                                              This email is already taken.
                                            </div>
                                            <?php
                                            break;
                                        case "emaillenght":
                                            ?>
                                            <div class="alert alert-danger" role="alert">
                                              The email you have provided is too long.
                                            </div>
                                            <?php
                                            break;
                                        case "passwordlenght":
                                            ?>
                                            <div class="alert alert-danger" role="alert">
                                              The password you provided is too long.
                                            </div>
                                            <?php
                                            break;
                                        case "usernamelenght":
                                            ?>
                                            <div class="alert alert-danger" role="alert">
                                              The username you have provided is too long.
                                            </div>
                                            <?php
                                            break;
                                        case "nonmatching":
                                            ?>
                                            <div class="alert alert-danger" role="alert">
                                              Your passwords don't match.
                                            </div>
                                            <?php
                                            break;
                                        case "success":
                                            ?>
                                            <div class="alert alert-success" role="alert">
                                              You have successfully registered!
                                            </div>
                                            <?php
                                            break;
                                        case "database":
                                            ?>
                                            <div class="alert alert-danger" role="alert">
                                              Unable to connect to the database.
                                            </div>
                                            <?php
                                            break;
                                    }
                            }
                        }
                        ?>
                        <form class="register" action="includes/register.inc.php" method="post">
                            <input type="text" placeholder="Username" name="username" spellcheck="false" required>
                            <input type="email" placeholder="Email" name="email" spellcheck="false" required>
                            <input type="password" placeholder="Password" name="password" spellcheck="false" required>
                            <input type="password" placeholder="Confirm Password" name="password_check" spellcheck="false" required>
                            <input type="submit" name="register" value="Register Now">
                            <input type="hidden" name="CSRFToken"
                              value="<?php echo($_SESSION['CSRFToken']); ?>">
                        </form>
						<div class="login-switch">
							<p>Login</p>
						</div>
						<div class="register-switch-cur">
							<p>Register</p>
						</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<div class="login-form">
        <div class="container">
            <div class="row">
                <div class="col-md-7 col-md-push-4"></div>
                <div class="col-md-4 col-md-pull-8">
                    <div class="login-box">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h3>Login</h3>
                                </div>
                            </div>
                        </div>


                        <?php
                        if (!empty($_GET['page']) && !empty($_GET['q']))
                        {
                            if ($_GET['page'] == 'login')
                            {
                                    switch ($_GET['q'])
                                    {
                                        case "empty":
                                            ?>
                                            <div class="alert alert-danger" role="alert">
                                              Please fill out all of the fields.
                                            </div>
                                            <?php
                                            break;
                                        case "invalid":
                                            ?>
                                            <div class="alert alert-danger" role="alert">
                                              Illegal characters present in email.
                                            </div>
                                            <?php
                                            break;
                                        case "invalidemail":
                                            ?>
                                            <div class="alert alert-danger" role="alert">
                                              You have entered an invalid email.
                                            </div>
                                            <?php
                                            break;
                                        case "incorrect":
                                            ?>
                                            <div class="alert alert-danger" role="alert">
                                              You have entered an invalid email or password.
                                            </div>
                                            <?php
                                            break;
                                        case "success":
                                            ?>
                                            <div class="alert alert-success" role="alert">
                                              You have successfully logged in!
                                            </div>
                                            <?php
                                            break;
                                    }
                            }
                        }
                        ?>
                        <form class="login" action="includes/login.inc.php" method="post">
                            <input type="email" name="email" placeholder="Email" spellcheck="false" required>
                            <input type="password" name="password" placeholder="Password" spellcheck="false" required>
							<div class="remember-me">
                                <input class="remember-me-checkbox" type="checkbox" name="remember_me" value="">
								<div class="remember-me-button"></div>
								<div class="remember-me-text">Remember me</div>
							</div>
								<div class="forgot-pass">
									<div class="forgot-pass-button"></div>
									<div class="forgot-pass-text">Forgot Password?</div>
								</div>
                            <input type="hidden" name="CSRFToken"
                              value="<?php echo($_SESSION['CSRFToken']); ?>">
                            <input type="submit" name="login" value="Login">
                        </form>
						<div class="login-switch-cur">
							<p>Login</p>
						</div>
						<div class="register-switch">
							<p>Register</p>
						</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<div class="forgot-form">
        <div class="container">
            <div class="row">
                <div class="col-md-7 col-md-push-4"></div>
                <div class="col-md-4 col-md-pull-8">
                    <div class="forgot-box">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h3>Forgot Password</h3>
                                </div>
                            </div>
                        </div>
						<div class="container">
							<div class="row">
								<div class="col-sm-12">
									<a href="#">
										<p class="login-register-return"><i class="fa fa-arrow-left" aria-hidden="true"></i>Login/Register</p>
									</a>
								</div>
							</div>
						</div>
                        <form class="forgot" action="index.php" method="post">
                            <input type="email" name="email" placeholder="Email" spellcheck="false" required>
                            <input type="hidden" name="CSRFToken"
                              value="<?php echo($_SESSION['CSRFToken']); ?>">
                            <input type="submit" value="Send Email">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!--  JQuery JavaScript     -->
    <script src="assets/js/jquery.min.js"></script>
    <!--  Bootstrap JavaScript  -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/custom.js"></script>
    <!-- Custom Login Box -->
    <script src="assets/js/login.js"></script>
    </body>
</html>
