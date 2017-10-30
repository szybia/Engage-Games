<?php
$dbServerName = "localhost";
$dbUsername  = "root";
$dbPassword = "";
$dbName = "engage";

//If file is being called directly exit
if(basename(__FILE__) == basename($_SERVER['PHP_SELF']))
{
    header("Location: ../index.php");
    exit();
}

$db = mysqli_connect($dbServerName, $dbUsername, $dbPassword, $dbName);

if (mysqli_connect_errno())
{
    die("Unable to connect to database.");
}
