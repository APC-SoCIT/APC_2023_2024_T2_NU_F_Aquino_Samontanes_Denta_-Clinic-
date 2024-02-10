<?php
$sname= "localhost";
$userN= "root";
$pw = "";

$db_name = "teethtitan";

$conn = mysqli_connect($sname, $userN, $pw, $db_name);

if (!$conn) {
    echo "Connection failed!";
}