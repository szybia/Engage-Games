<?php
//If file is being called directly exit
if(basename(__FILE__) == basename($_SERVER['PHP_SELF']))
{
    header("Location: ../index.php");
    exit();
}

if (empty($_SESSION['CSRFToken']))
{
    $_SESSION['CSRFToken'] = bin2hex(random_bytes(16));
}
