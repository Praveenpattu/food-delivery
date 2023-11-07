<?php
session_start();
$servername = "localhost";
$server_user = "vetri";
$server_pass = "V3t6!v3!";
$dbname = "food";
//$name = $_SESSION['name'];
//$role = $_SESSION['role'];
$con = new mysqli($servername, $server_user, $server_pass, $dbname);
?>