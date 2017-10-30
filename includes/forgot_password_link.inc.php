<?php
session_start();

//Include CSRFToken generator
require_once('csrf_token.inc.php');

function redirect($message)
{
    header("Location: ../login.php?page=forgot&q=$message");
    exit();
}

//Print function to avoid XSS
function xss($message)
{
    echo(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));
}

if (empty($_GET['key']) || empty($_GET['email']))
{
    redirect("empty");
}
else
{
    if (strlen($_GET['key']) > 70 || strlen($_GET['email']) > 60)
    {
        redirect("length");
    }
    else
    {
        if (!preg_match("/^[a-zA-Z0-9\@\.]*$/", $_GET['email']) ||
            !preg_match("/^[a-f0-9]*$/", $_GET['key']))
        {
            redirect("invalid");
        }
        else
        {
            //Ensure email is valid
            if (!filter_var($_GET['email'], FILTER_VALIDATE_EMAIL))
            {
                redirect("invalid");
            }
            else
            {
                //Include database connection
                require_once('db.inc.php');

                $email  = mysqli_real_escape_string($db, $_GET['email']);
                $key    = mysqli_real_escape_string($db, $_GET['key']);

                $prepared_statement = $db->prepare("SELECT forgot_password_date from user where email = ? AND
                                                                                 forgot_password_hash = ?");
                $prepared_statement->bind_param("ss", $email, $key);
                $prepared_statement->execute();
                $prepared_statement->store_result();
                $count = $prepared_statement->num_rows();

                if ($count < 1)
                {
                    redirect("none");
                }
                else
                {
                    $prepared_statement->bind_result($d_forgot_password_date);
                    $prepared_statement->fetch();
                    $prepared_statement->close();


                    $now        = strtotime(date('Y-m-d H:i:s'));
                    $link_date  = strtotime($d_forgot_password_date);
                    $minutes  = abs($link_date - $now);
                    $minutes   = round($minutes / 60);

                    if ($minutes > 15)
                    {
                        redirect("expired");
                    }
                    else
                    {
                        ?>
                        <head>
                            <title>Engage Reset</title>
                            <link rel="icon" href="../assets/img/favicon.ico">
                        </head>
                        <!-- Google Fonts -->
                    	<link href="https://fonts.googleapis.com/css?family=Lato:300,300i,400,700" rel="stylesheet">
                        <!-- Custom CSS -->
                    	<link href="../assets/css/forgot_password_link.css" rel="stylesheet">
                        <div class="login-form">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-7 col-md-push-4"></div>
                                    <div class="col-md-4 col-md-pull-8">
                                        <div class="login-box">
                                            <div class="container">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <h3>Reset Password.</h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <form class="login" action="forgot_password_authenticate.php" method="post">
                                                <input type="password" name="password" placeholder="Password" spellcheck="false" required>
                                                <input type="password" name="password_check" placeholder="Confirm Password" spellcheck="false" required>
                                                <input type="submit" name="submit" value="Reset Password">
                                                <input type="hidden" name="CSRFToken"
                                                  value="<?php xss($_SESSION['CSRFToken']); ?>">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php

                        $_SESSION['reset_check']                = true;
                        $_SESSION['reset_check_email']          = $email;
                        exit();
                    }
                }
            }
        }
    }
}
?>
