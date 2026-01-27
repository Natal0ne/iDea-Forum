<?php
// includes/db_connect.php

$host = "localhost";
$port = "5432";
$dbname = "gruppo03";
// $dbname = "forum"; // Fallback if gruppo03 is incorrect, but sticking to existing config
$user = "www";      
$password = "www";  

try {
    // DSN String for PostgreSQL
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
    
    // Create PDO instance
    $conn = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
    // echo "Connected successfully"; 
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>