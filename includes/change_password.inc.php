<?php
session_start();

function redirect($message)
{
    header("Location: ../profile.php?request=password&q=$message");
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
            if (empty($_POST['current_password'])   ||
                empty($_POST['new_password'])       ||
                empty($_POST['new_password_check']) ||
                empty($_POST['CSRFToken']))
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
                    if (strlen($_POST['new_password'])      > 72 ||
                        strlen($_POST['current_password'])  > 72 ||
                        strlen($_POST['new_password_check']) > 72)
                    {
                        redirect("lenght");
                    }
                    else
                    {
                        //Include database connection
                        require_once('db.inc.php');

                        $current_password          =   mysqli_real_escape_string($db, $_POST['current_password']);
                        $new_password               =   mysqli_real_escape_string($db, $_POST['new_password']);
                        $new_password_check         =   mysqli_real_escape_string($db, $_POST['new_password_check']);

                        if ($new_password != $new_password_check)
                        {
                            redirect("nonmatching");
                        }
                        else
                        {
                            $prepared_statement = $db->prepare("SELECT password FROM user WHERE email = ?");
                            $prepared_statement->bind_param("s", $_SESSION['email']);
                            $prepared_statement->execute();
                            $prepared_statement->bind_result($d_password);
                            $prepared_statement->fetch();
                            $prepared_statement->close();

                            if (!password_verify($current_password, $d_password))
                            {
                                redirect("incorrect");
                            }
                            else
                            {
                                $new_password =  password_hash($new_password, PASSWORD_BCRYPT);

                                $prepared_statement = $db->prepare("UPDATE USER SET password = ?
                                                                    where email = ?");
                                $prepared_statement->bind_param("ss", $new_password, $_SESSION['email']);
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
