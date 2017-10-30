<?php
session_start();

function redirect($message)
{
    header("Location: ../login.php?page=login&q=$message");
    exit();
}

//If user is logged in her cant login
if (empty($_SESSION['email']))
{
    //Check if user is logging in
    if (!empty($_POST['login']))
    {

        //Cross Site Request Forgery
        if (empty($_POST['CSRFToken'])      ||
            empty($_SESSION['CSRFToken'])   ||
            $_SESSION['CSRFToken'] != $_POST['CSRFToken'])
        {
            redirect("empty");
        }
        else
        {
            //Include database connection
            require_once('db.inc.php');

            //Escape all inputs
            $email          =   mysqli_real_escape_string($db, $_POST['email']);
            $password       =   mysqli_real_escape_string($db, $_POST['password']);

            //Check for empty inputs
            if (empty($email) || empty($password))
            {
                redirect("empty");
            }
            else
            {
                //Ensure email only has accepted characters
                if (!preg_match("/^[a-zA-Z0-9@.]*$/", $email))
                {
                    redirect("invalid");
                }
                else
                {
                    //Ensure email is valid
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
                    {
                        redirect("invalidemail");
                    }
                    else
                    {
                        //Check if email exists
                        $prepared_statement = $db->prepare("SELECT email, username, password, user_image_path from user where email like ?");
                        $prepared_statement->bind_param("s", $email);
                        $prepared_statement->execute();
                        $prepared_statement->store_result();
                        $num_of_rows = $prepared_statement->num_rows();
                        if ($num_of_rows < 1)
                        {
                            redirect("incorrect");
                        }
                        else
                        {
                            $prepared_statement->bind_result($d_email, $d_username, $d_password, $d_user_image_path);
                            $prepared_statement->fetch();
                            $prepared_statement->close();

                            if (!password_verify($password, $d_password))
                            {
                                redirect("incorrect");
                            }
                            else
                            {
                                if (isset($_POST['remember_me']))
                                {
                                    //Set session variables
                                    $_SESSION['user_image_path'] = empty($d_user_image_path) ? "default.png" : $d_user_image_path;
                                    $_SESSION['username'] = $d_username;
                                    $_SESSION['email'] = $d_email;

                                    //Create remember me cookie
                                    $selector = bin2hex(random_bytes(16));
                                    $verifier = hash("sha384", bin2hex(random_bytes(16)));
                                    $combined = $selector . "." . $verifier;

                                    $prepared_statement = $db->prepare("UPDATE user set remember_me_selector = ?, remember_me_verifier = ? where email = ?");
                                    $prepared_statement->bind_param("sss", $selector, $verifier, $_SESSION['email']);
                                    $prepared_statement->execute();
                                    $prepared_statement->close();

                                    // Set cookie for 1 year
                                    setcookie(
                                      "remembermeengage",
                                      $combined,
                                      time() + (1 * 365 * 24 * 60 * 60), "/"
                                    );

                                    header("Location: ../index.php");
                                    exit();

                                }
                                else
                                {
                                    $_SESSION['user_image_path'] = empty($d_user_image_path) ? "default.png" : $d_user_image_path;
                                    $_SESSION['username'] = $d_username;
                                    $_SESSION['email'] = $d_email;
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
    else
    {
        header("Location: ../login.php");
        exit();
    }
}
else
{
    header("Location: ../index.php");
    exit();
}
