<?php
session_start();

function redirect($message)
{
    header("Location: ../profile.php?request=delete&q=$message");
    exit();
}

if (empty($_SESSION['email']))
{
    redirect("loggedin");
}
else
{
    if (empty($_POST['CSRFToken']))
    {
        redirect("empty");
    }
    else
    {
        if ($_POST['CSRFToken'] != $_SESSION['CSRFToken'])
        {
            redirect("empty");
        }
        else
        {
            if (empty($_POST['email'])      ||
                empty($_POST['password'])   ||
                empty($_POST['g-recaptcha-response']))
            {
                redirect("empty");
            }
            else
            {
                require_once('recaptcha.inc.php');

                $siteKey = "6LdafTMUAAAAAEjOROjGfi4qCGu6UOeABrjGIWRw";
                $secret = "6LdafTMUAAAAAGP-gquib1Ghz0M1o7aSqlHnh5Uf";
                $lang = "en";
                $resp = null;
                $error = null;
                $reCaptcha = new ReCaptcha($secret);
                $resp = $reCaptcha->verifyResponse(
                    $_SERVER["REMOTE_ADDR"],
                    $_POST["g-recaptcha-response"]
                );

                if ($resp == null || !$resp->success)
                {
                    redirect("bot");
                }
                else
                {
                    if (!preg_match("/^[a-zA-Z0-9\@\.]*$/", $_POST['email']))
                    {
                        redirect("invalid");
                    }
                    else
                    {
                        if (strlen($_POST['email']) > 60 || strlen($_POST['password']) > 72)
                        {
                            redirect("length");
                        }
                        else
                        {
                            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
                            {
                                redirect("invalid");
                            }
                            else
                            {
                                //Include database connection
                                require_once('db.inc.php');

                                $email = mysqli_real_escape_string($db, $_POST['email']);

                                if ($email != $_SESSION['email'])
                                {
                                    redirect("email");
                                }
                                else
                                {
                                    $prepared_statement = $db->prepare("SELECT password FROM user WHERE email = ?");
                                    $prepared_statement->bind_param("s", $email);
                                    $prepared_statement->execute();
                                    $prepared_statement->store_result();
                                    $num_of_rows = $prepared_statement->num_rows();

                                    if ($num_of_rows < 1)
                                    {
                                        redirect("nouser");
                                    }
                                    else
                                    {
                                        $prepared_statement->bind_result($d_password);
                                        $prepared_statement->fetch();
                                        $prepared_statement->close();

                                        $password = mysqli_real_escape_string($db, $_POST['password']);

                                        if (!password_verify($password, $d_password))
                                        {
                                            redirect("password");
                                        }
                                        else
                                        {
                                            $path = "../assets/img/users/";

                                            $prepared_statement = $db->prepare("DELETE FROM USER WHERE EMAIL = ?");
                                            $prepared_statement->bind_param("s", $email);
                                            $prepared_statement->execute();
                                            $prepared_statement->close();

                                            $prepared_statement = $db->prepare("DELETE FROM SHOPPING_CART WHERE EMAIL = ?");
                                            $prepared_statement->bind_param("s", $email);
                                            $prepared_statement->execute();
                                            $prepared_statement->close();

                                            if (isset($_COOKIE['remembermeengage']))
                                            {
                                                setcookie(
                                                  "remembermeengage",
                                                  null,
                                                  time() - 3600, "/"
                                                );
                                                unset($_COOKIE['remembermeengage']);
                                            }

                                            if ($_SESSION['user_image_path'] != "default.png")
                                            {
                                                //Delete old profile picture
                                                if (is_writable($path . $_SESSION['user_image_path']))
                                                {
                                                    unlink($path . $_SESSION['user_image_path']);
                                                }
                                            }


                                            session_unset();
                                            session_destroy();

                                            header("Location: ../index.php");
                                            exit();
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
