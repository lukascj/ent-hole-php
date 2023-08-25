<?php

$servername = "localhost:2890";
$dbusername = "root";
$dbpwd = "";
$dbname = "entdb";

$conn = mysqli_connect($servername, $dbusername, $dbpwd, $dbname);

if(!$conn) {
    die("Connection failed: ".mysqli_connect_error());
}