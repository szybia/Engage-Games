<?php
session_start();

require_once('include_only.inc.php');

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
                if ($_POST['CSRFToken'] != $_SESSION['CSRFToken'])
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

                    }
                }
            }
        }
    }
}
