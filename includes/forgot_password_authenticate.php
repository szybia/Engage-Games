<?php
session_start();

//Include CSRFToken generator
require_once('csrf_token.inc.php');

function redirect($message)
{
    unset($_SESSION['reset_check']);
    header("Location: ../login.php?page=forgot&q=$message");
    exit();
}

if (empty($_POST['password'])               ||
    empty($_POST['password_check'])         ||
    empty($_POST['submit'])                 ||
    empty($_POST['CSRFToken'])              ||
    empty($_SESSION['reset_check_email']))
{
    redirect("empty");
}
else
{
    if ($_POST['password'] != $_POST['password_check'])
    {
        redirect("nonmatching");
    }
    else
    {
        if (strlen($_POST['password']) > 72)
        {
            redirect("length");
        }
        else
        {
            if ($_POST['CSRFToken'] != $_SESSION['CSRFToken'])
            {
                redirect("empty");
            }
            else
            {
                //Include database connection
                require_once('db.inc.php');

                $password = mysqli_real_escape_string($db, $_POST['password']);
                $empty = null;

                //Hash password
                $password =  password_hash($password, PASSWORD_BCRYPT);

                $prepared_statement = $db->prepare("UPDATE USER
                                                    SET PASSWORD = ?,
                                                        forgot_password_hash = ?,
                                                        forgot_password_date = ?
                                                    WHERE EMAIL = ?");
                $prepared_statement->bind_param("ssss", $password, $empty, $empty, $_SESSION['reset_check_email']);
                $prepared_statement->execute();
                $prepared_statement->close();

                redirect("success");
            }
        }

    }
}
?>
