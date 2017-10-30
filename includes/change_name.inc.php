<?php
session_start();

function redirect($message)
{
    header("Location: ../profile.php?request=name&q=$message");
    exit();
}

if (empty($_SESSION['email']))
{
    redirect("");
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
            if (empty($_POST['new_name'])   ||
                empty($_POST['password']))
            {
                redirect("empty");
            }
            else
            {
                //Ensure username and email only have accepted characters
                if (!preg_match("/^[a-zA-Z0-9]*$/", $_POST['new_name']))
                {
                    redirect("invalid");
                }
                else
                {
                    if (strlen($_POST['new_name']) > 50 || strlen($_POST['password']) > 72)
                    {
                        redirect("lenght");
                    }
                    else
                    {
                        //Include database connection
                        require_once('db.inc.php');

                        $new_name               =   mysqli_real_escape_string($db, $_POST['new_name']);
                        $password               =   mysqli_real_escape_string($db, $_POST['password']);

                        $prepared_statement = $db->prepare("SELECT password FROM user WHERE email = ?");
                        $prepared_statement->bind_param("s", $_SESSION['email']);
                        $prepared_statement->execute();
                        $prepared_statement->store_result();
                        $num_of_rows = $prepared_statement->num_rows();
                        $prepared_statement->bind_result($d_password);
                        $prepared_statement->fetch();
                        $prepared_statement->close();

                        if ($num_of_rows < 0)
                        {
                            redirect("nouser");
                        }
                        else
                        {
                            if (!password_verify($password, $d_password))
                            {
                                redirect("incorrect");
                            }
                            else
                            {
                                $prepared_statement = $db->prepare("UPDATE user SET username = ?
                                                                    WHERE email = ?;");
                                $prepared_statement->bind_param("ss", $new_name, $_SESSION['email']);
                                $prepared_statement->execute();
                                $prepared_statement->close();

                                $_SESSION['username'] = $new_name;

                                redirect("success");
                            }
                        }
                    }
                }
            }
        }
    }
}
