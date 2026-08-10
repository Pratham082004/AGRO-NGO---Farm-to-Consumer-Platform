<?php

$url = "localhost";
$username = "root";
$password = "";

$conn = mysqli_connect($url, $username, $password, "tms2");

if (!$conn) {
    die("Could not Connect MySQL: " . mysqli_connect_error());
}

?>