<?php
session_start();

function redirect($message)
{
    header("Location: ../login.php?page=register&q=$message");
    exit();
}

//If user is logged in her cant register
if (empty($_SESSION['logged_in']))
{
    //Check if user is registering
    if (!empty($_POST['register']))
    {
        if ($_SESSION['CSRFToken'] != $_POST['CSRFToken'])
        {
            redirect("empty");
        }
        else
        {
            //Include database connection
            require_once('db.inc.php');

            //Escape all inputs
            $username       =   mysqli_real_escape_string($db, $_POST['username']);
            $email          =   mysqli_real_escape_string($db, $_POST['email']);
            $password       =   mysqli_real_escape_string($db, $_POST['password']);
            $password_check =   mysqli_real_escape_string($db, $_POST['password_check']);

            //Check for empty inputs
            if (empty($username) || empty($email) || empty($password) || empty($password_check))
            {
                redirect("empty");
            }
            else
            {
                //Ensure username and email only have accepted characters
                if (!preg_match("/^[a-zA-Z0-9]*$/", $username) ||
                    !preg_match("/^[a-zA-Z0-9@.]*$/", $email))
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
                        //Check if email already exists
                        $prepared_statement = $db->prepare("SELECT count(*) from user where email like ?");
                        $prepared_statement->bind_param("s", $email);
                        $prepared_statement->execute();
                        $prepared_statement->bind_result($new_var);
                        $prepared_statement->fetch();
                        $prepared_statement->close();
                        if ($new_var > 0)
                        {
                            redirect("exists");
                        }
                        else
                        {
                            if (strlen($email) > 60)
                            {
                                redirect("emaillenght");
                            }
                            elseif (strlen($password) > 72)
                            {
                                redirect("passwordlenght");
                            }
                            elseif (strlen($username) > 50)
                            {
                                redirect("usernamelenght");
                            }
                            else
                            {

                                if ($password != $password_check)
                                {
                                    redirect("nonmatching");
                                }
                                else
                                {
                                    //Hash password
                                    $password =  password_hash($password, PASSWORD_BCRYPT);

                                    $prepared_statement = $db->prepare("INSERT INTO USER (email, username, password) VALUES (?, ?, ?)");
                                    $prepared_statement->bind_param("sss", $email, $username, $password);
                                    $prepared_statement->execute();
                                    $prepared_statement->close();

                                    redirect("success");
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
