<?php
$servername = "mysql-container";
$username = "user";
$pwd = "userpassword";
$dbname = "mydatabase";

$conn = new mysqli($servername, $username, $pwd, $dbname);

if($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// echo "Connected successfully to MySQL!";