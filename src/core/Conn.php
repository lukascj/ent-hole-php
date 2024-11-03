<?php
function create_conn() {
    $conn = new mysqli(
        $_ENV['DB_CONTAINER'], 
        $_ENV['DB_USER'], 
        $_ENV['DB_PASS'], 
        $_ENV['DB_NAME']
    );
    if($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } else {
        // echo "Connected successfully to MySQL!";
        return $conn;
    }
}
