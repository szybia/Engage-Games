<?php
session_start();

function redirect($message)
{
    header("Location: ../profile.php?request=logout&q=$message");
    exit();
}

if (empty($_SESSION['email']))
{
    redirect("loggedin");
}
else
{

    if (isset($_COOKIE['remembermeengage']))
    {
        //Include database connection
        require_once('db.inc.php');

        $empty = null;

        $prepared_statement = $db->prepare("UPDATE USER
                                            SET remember_me_selector = ?,
                                                remember_me_verifier = ?
                                            WHERE EMAIL = ?");
        $prepared_statement->bind_param("sss", $empty, $empty, $_SESSION['email']);
        $prepared_statement->execute();
        $prepared_statement->close();

        setcookie(
          "remembermeengage",
          null,
          time() - 3600, "/"
        );
        unset($_COOKIE['remembermeengage']);
    }




    session_unset();
    session_destroy();

    header("Location: ../index.php");
    exit();
}
