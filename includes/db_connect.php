<?php
function connect_db() {
    $host = "localhost";
    $port = "5432";
    $dbname = "gruppo03";
    $user = "www";
    $password = "www";

    $conn_string = "host=$host port=$port dbname=$dbname user=$user password=$password";

    $conn = pg_connect($conn_string)
        or die('connection failed: ' . pg_last_error()); // commentare pg_last_error() per il deploy

    return $conn;
}
?>