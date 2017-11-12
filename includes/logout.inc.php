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
