<?php
$dbServerName = "localhost";
$dbUsername  = "root";
$dbPassword = "";
$dbName = "engage";

$db = mysqli_connect($dbServerName, $dbUsername, $dbPassword, $dbName);

if (mysqli_connect_errno())
{
    echo "Unable to connect to database.";
    exit(0);
}
