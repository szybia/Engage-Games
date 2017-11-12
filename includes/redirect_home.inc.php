<?php
if (empty($_SESSION['email']))
{
    header("Location: index.php");
    exit();
}
