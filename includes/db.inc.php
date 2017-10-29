<?php
$dbServerName = "localhost";
$dbUsername  = "root";
$dbPassword = "";
$dbName = "engage";

require_once('include_only.inc.php');

$db = mysqli_connect($dbServerName, $dbUsername, $dbPassword, $dbName);

if (mysqli_connect_errno())
{
    die("Unable to connect to database.");
}
