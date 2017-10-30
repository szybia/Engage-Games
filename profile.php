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

//If user isn't logged in her can't access the profile
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

	<title>Engage Profile</title>

	<!-- Bootstrap core CSS -->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">

	<!-- Custom CSS -->
	<link href="assets/css/profile.css" rel="stylesheet">

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
                            <div class="row">
                                <div class="col-sm-12">
                                    <img type="button"
data-toggle="modal" data-target="#changePhoto" class="profile-picture" src="assets\img\users\<?php xss($_SESSION['user_image_path']); ?>" alt="The profile picture of <?php xss($_SESSION['username']); ?>">
                                    <h3 class="name text-white"><?php xss($_SESSION['username']); ?></h3>
                                    <h5 class="text-white"><?php xss($_SESSION['email']); ?></h5>
                                    <?php
                                    if (!empty($_GET['request']))
                                    {
                                        switch ($_GET['request'])
                                        {
                                            case 'password':
                                                if (!empty($_GET['q']))
                                                {
                                                    switch ($_GET['q'])
                                                    {
                                                        case 'empty':
                                                            ?>
                                                            <div class="alert alert-danger" role="alert">
                                                                <h4>Change Password</h4>
                                                                Please fill out all the fields.
                                                            </div>
                                                            <?php
                                                            break;
                                                        case 'lenght':
                                                            ?>
                                                            <div class="alert alert-danger" role="alert">
                                                                <h4>Change Password</h4>
                                                                Your password is too long. Max 72
                                                            </div>
                                                            <?php
                                                            break;
                                                        case 'nonmatching':
                                                            ?>
                                                            <div class="alert alert-danger" role="alert">
                                                                <h4>Change Password</h4>
                                                                Your passwords did not match.
                                                            </div>
                                                            <?php
                                                            break;
                                                        case 'incorrect':
                                                            ?>
                                                            <div class="alert alert-danger" role="alert">
                                                                <h4>Change Password</h4>
                                                                Incorrect current password.
                                                            </div>
                                                            <?php
                                                            break;
                                                        case 'success':
                                                            ?>
                                                            <div class="alert alert-success" role="alert">
                                                                <h4>Change Password</h4>
                                                                You've successfully changed your password.
                                                            </div>
                                                            <?php
                                                            break;
                                                        default:
                                                            break;
                                                    }
                                                }
                                                break;

                                            case 'name':
                                                if (!empty($_GET['q']))
                                                {
                                                    switch ($_GET['q'])
                                                    {
                                                        case 'empty':
                                                            ?>
                                                            <div class="alert alert-danger" role="alert">
                                                                <h4>Change Name</h4>
                                                                Please fill out all the fields.
                                                            </div>
                                                            <?php
                                                            break;
                                                        case 'invalid':
                                                            ?>
                                                            <div class="alert alert-danger" role="alert">
                                                                <h4>Change Name</h4>
                                                                Please only enter numbers and letters.
                                                            </div>
                                                            <?php
                                                            break;
                                                        case 'lenght':
                                                            ?>
                                                            <div class="alert alert-danger" role="alert">
                                                                <h4>Change Name</h4>
                                                                Input is too long.
                                                            </div>
                                                            <?php
                                                            break;
                                                        case 'nouser':
                                                            ?>
                                                            <div class="alert alert-danger" role="alert">
                                                                <h4>Change Name</h4>
                                                                This user doesn't exist.
                                                            </div>
                                                            <?php
                                                            break;
                                                        case 'incorrect':
                                                            ?>
                                                            <div class="alert alert-danger" role="alert">
                                                                <h4>Change Name</h4>
                                                                Incorrect password.
                                                            </div>
                                                            <?php
                                                            break;
                                                        case 'success':
                                                            ?>
                                                            <div class="alert alert-success" role="alert">
                                                                <h4>Change Name</h4>
                                                                You've successfully changed your name.
                                                            </div>
                                                            <?php
                                                            break;
                                                        default:
                                                            break;
                                                    }
                                                }
                                                break;

                                            case 'email':
                                                if (!empty($_GET['q']))
                                                {
                                                    switch ($_GET['q'])
                                                    {
                                                        case 'empty':
                                                            ?>
                                                            <div class="alert alert-danger" role="alert">
                                                                <h4>Change Email</h4>
                                                                Please fill out all the fields.
                                                            </div>
                                                            <?php
                                                            break;
                                                        case 'invalid':
                                                            ?>
                                                            <div class="alert alert-danger" role="alert">
                                                                <h4>Change Email</h4>
                                                                Invalid characters in email.
                                                            </div>
                                                            <?php
                                                            break;
                                                        case 'lenght':
                                                            ?>
                                                            <div class="alert alert-danger" role="alert">
                                                                <h4>Change Email</h4>
                                                                Input is too long.
                                                            </div>
                                                            <?php
                                                            break;
                                                        case 'nouser':
                                                            ?>
                                                            <div class="alert alert-danger" role="alert">
                                                                <h4>Change Email</h4>
                                                                This user doesn't exist.
                                                            </div>
                                                            <?php
                                                            break;
                                                        case 'incorrect':
                                                            ?>
                                                            <div class="alert alert-danger" role="alert">
                                                                <h4>Change Email</h4>
                                                                Incorrect password.
                                                            </div>
                                                            <?php
                                                            break;
                                                        case 'exists':
                                                            ?>
                                                            <div class="alert alert-danger" role="alert">
                                                                <h4>Change Email</h4>
                                                                User with this email already exists.
                                                            </div>
                                                            <?php
                                                            break;
                                                        case 'success':
                                                            ?>
                                                            <div class="alert alert-success" role="alert">
                                                                <h4>Change Email</h4>
                                                                You've successfully changed your email.
                                                            </div>
                                                            <?php
                                                            break;
                                                        default:
                                                            break;
                                                    }
                                                }
                                                break;

                                                case 'picture':
                                                    if (!empty($_GET['q']))
                                                    {
                                                        switch ($_GET['q'])
                                                        {
                                                            case 'empty':
                                                                ?>
                                                                <div class="alert alert-danger" role="alert">
                                                                    <h4>Change Photo</h4>
                                                                    Empty photo sent.
                                                                </div>
                                                                <?php
                                                                break;
                                                            case 'ext':
                                                                ?>
                                                                <div class="alert alert-danger" role="alert">
                                                                    <h4>Change Photo</h4>
                                                                    Invalid extension.
                                                                </div>
                                                                <?php
                                                                break;
                                                            case 'size':
                                                                ?>
                                                                <div class="alert alert-danger" role="alert">
                                                                    <h4>Change Photo</h4>
                                                                    Photo is too large.
                                                                </div>
                                                                <?php
                                                                break;
                                                            case 'invalidimage':
                                                                ?>
                                                                <div class="alert alert-danger" role="alert">
                                                                    <h4>Change Photo</h4>
                                                                    Invalid image.
                                                                </div>
                                                                <?php
                                                                break;
                                                            case 'servererror':
                                                                ?>
                                                                <div class="alert alert-danger" role="alert">
                                                                    <h4>Change Photo</h4>
                                                                    Server has encountered an error.
                                                                </div>
                                                                <?php
                                                                break;
                                                            default:
                                                                break;
                                                        }
                                                    }
                                                    break;

                                            default:
                                                break;
                                        }
                                    }

                                    ?>
                                    <a href="shopping_cart.php">
                                        <button type="button" class="btn btn-light middle middle-first">Shopping cart</button>
                                    </a>
                                    <button type="button" class="btn btn-light middle middle" data-toggle="modal" data-target="#changePass">
                                        Change Password
                                    </button>
                                    <button type="button" class="btn btn-light middle" data-toggle="modal" data-target="#changeName">
                                        Change Name
                                    </button>
                                    <button type="button" class="btn btn-light middle" data-toggle="modal" data-target="#changeEmail">
                                        Change Email
                                    </button>
                                    <button type="button" class="btn btn-light middle" data-toggle="modal" data-target="#deleteAcc">
                                        Delete Account
                                    </button>
                                    <a href="includes/logout.inc.php">
                                        <button type="button" class="btn btn-light middle">Logout</button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="changePhoto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Change Profile Picture</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="includes/change_photo.inc.php" method="post" enctype="multipart/form-data">
                      <div class="form-group">
                          <label class="custom-file">
                            <input type="file" name="profile_picture" id="file_input" class="custom-file-input">
                            <span class="custom-file-control" id="photo_name"></span>
                            <input type="hidden" name="CSRFToken" value="<?php xss($_SESSION['CSRFToken']); ?>">
                          </label>
                      </div>
                      <button type="submit" class="btn btn-dark modal-submit">Change Photo</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="changePass" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="includes/change_password.inc.php" method="post">
                      <div class="form-group">
                        <label for="exampleInputPassword1">Current Password*</label>
                        <input type="password" class="form-control" name="current_password"  placeholder="Password" required>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputPassword1">New Password*</label>
                        <input type="password" class="form-control" name="new_password" placeholder=" New Password" required>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputPassword1">Confirm New Password*</label>
                        <input type="password" class="form-control" name="new_password_check"  placeholder="Confirm Password" required>
                        <input type="hidden" name="CSRFToken" value="<?php xss($_SESSION['CSRFToken']); ?>">
                      </div>
                      <button type="submit" class="btn btn-dark modal-submit">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="changeName" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Change Name</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="delete_account" action="includes/change_name.inc.php" method="post">
                      <div class="form-group">
                        <label for="exampleInputPassword1">New Name</label>
                        <input type="text" class="form-control" placeholder="Name" name="new_name" required>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputPassword1">Password</label>
                        <input type="password" class="form-control"  placeholder="Password" name="password" required>
                        <input type="hidden" name="CSRFToken" value="<?php xss($_SESSION['CSRFToken']); ?>">
                      </div>
                      <button type="submit" class="btn btn-dark modal-submit">Change Name</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="changeEmail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Change Email</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="includes/change_email.inc.php" method="post">
                      <div class="form-group">
                        <label for="exampleInputPassword1">New Email</label>
                        <input type="email" class="form-control" name="new_email" placeholder="New Email" required>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputPassword1">Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                        <input type="hidden" name="CSRFToken" value="<?php xss($_SESSION['CSRFToken']); ?>">
                      </div>
                      <button type="submit" class="btn btn-dark modal-submit">Change Email</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteAcc" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Account</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body delete-account-first">
                    <p>Are you sure you want to permanently delete your account?</p>
                </div>
                <div class="modal-body">
                    <form action="includes/delete_account.inc.php" method="post">
                        <div class="alert alert-danger hidden-tick" role="alert">
                          Please tick the reCaptcha box.
                        </div>
                      <div class="form-group first-form-group">
                        <label for="exampleInputPassword1">Current Email</label>
                        <input type="email" class="form-control" name="email" placeholder="Email" required>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputPassword1">Current Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Confirm Password" required>
                        <input type="hidden" name="CSRFToken" value="<?php xss($_SESSION['CSRFToken']); ?>">
                      </div>
                      <div class="g-recaptcha" data-sitekey="6LdafTMUAAAAAEjOROjGfi4qCGu6UOeABrjGIWRw">
                      </div>
                      <button type="submit" class="btn btn-danger modal-submit">DELETE ACCOUNT</button>
                    </form>
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
    <script src="assets/js/file_input.js"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <!--  JQuery JavaScript     -->
	<script src="assets/js/jquery.min.js"></script>
    <!--  Popper JavaScript  -->
    <script src="assets/js/popper.min.js"></script>
    <!--  Bootstrap JavaScript  -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/custom.js"></script>
    <!-- Profile JS -->
    <script src="assets/js/profile.js"></script>
</body>
