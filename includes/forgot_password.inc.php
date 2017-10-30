<?php
session_start();

function redirect($message)
{
    header("Location: ../login.php?page=forgot&q=$message");
    exit();
}

//If user is logged in he can't forget password
if (!empty($_SESSION['email']))
{
    redirect("");
}
else
{
    //Check token
    if (empty($_POST['CSRFToken']) ||
        empty($_POST['email']))
    {
        redirect("empty");
    }
    else
    {
        if ($_SESSION['CSRFToken'] != $_POST['CSRFToken'])
        {
            redirect("empty");
        }
        else
        {
            if (strlen($_POST['email']) > 60)
            {
                redirect("lenght");
            }
            else
            {
                //Ensure email is valid
                if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
                {
                    redirect("invalid");
                }
                else
                {
                    if (!preg_match("/^[a-zA-Z0-9\@\.]*$/", $_POST['email']))
                    {
                        redirect("invalid");
                    }
                    else
                    {
                        //Include database connection
                        require_once('db.inc.php');

                        $email = mysqli_real_escape_string($db, $_POST['email']);

                        //Check if email already exists
                        $prepared_statement = $db->prepare("SELECT count(*) from user where email like ?");
                        $prepared_statement->bind_param("s", $email);
                        $prepared_statement->execute();
                        $prepared_statement->bind_result($count);
                        $prepared_statement->fetch();
                        $prepared_statement->close();

                        if ($count < 1)
                        {
                            redirect("nouser");
                        }
                        else
                        {
                            $forgot_password_hash = bin2hex(random_bytes(35));
                            $datetime = date('Y-m-d H:i:s');

                            $prepared_statement = $db->prepare("UPDATE USER SET FORGOT_PASSWORD_HASH = ?,
                                                                                FORGOT_PASSWORD_DATE = ?
                                                                                WHERE email = ?");
                            $prepared_statement->bind_param("sss", $forgot_password_hash, $datetime, $email);
                            $prepared_statement->execute();
                            $prepared_statement->close();

                            $to = $email;
                            $subject = "Recover Password";
                            $message = "<!DOCTYPE html>
                            <html>
                                <head>
                                    <meta charset=\"utf-8\">
                                    <title>Recover Password</title>
                                    <style media=\"screen\">
                                        * {
                                            font-family: Lato;
                                            text-align: center;
                                            color: #333;
                                        }
                                        button {
                                            padding: 16px 32px;
                                            text-align: center;
                                            text-decoration: none;
                                            display: inline-block;
                                            font-size: 16px;
                                            margin: 4px 2px;
                                            border-radius: 5px;
                                            -webkit-transition-duration: 0.4s; /* Safari */
                                            transition-duration: 0.4s;
                                            cursor: pointer;
                                            background-color: white;
                                            color: black;
                                            border: 2px solid #333;
                                        }

                                        button:hover {
                                            background-color: #333;
                                            color: white;
                                        }

                                        h1 {
                                            font-weight: 900;
                                            font-size: 50px;
                                        }
                                    </style>
                                </head>
                                <body>
                                    <h1>ENGAGE</h1>
                                    <h2>You've requested to reset your password.</h2>
                                    <h3>Please click the button below.</h3>
                                    <h4>This link expires in 15 minutes.</h4>
                                    <a href=\"localhost/Engage/includes/forgot_password_link.inc.php?key=$forgot_password_hash&amp;email=$email\">
                                        <button class=\"button button5\">Reset password.</button>
                                    </a>
                                </body>
                            </html>
                            ";
                            $headers  = 'MIME-Version: 1.0' . "\r\n";
                            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                            mail($to,$subject,$message, $headers);

                            redirect("success");
                        }
                    }
                }
            }
        }
    }
}
?>
