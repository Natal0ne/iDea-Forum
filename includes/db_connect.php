<?php
// includes/db_connect.php

$host = "localhost";
$port = "5432";
$dbname = "gruppo03";
$user = "www";      // Uguale per tutti
$password = "www";  // Uguale per tutti

// Stringa di connessione per PostgreSQL
$connection_string = "host=$host port=$port dbname=$dbname user=$user password=$password";

// Tenta la connessione
$db = pg_connect($connection_string);

if (!$db) {
    // In fase di sviluppo stampiamo l'errore per capire cosa non va
    die("Errore di connessione al Database: " . pg_last_error());
} else {
    echo "Connessione riuscita con utente www!";
}
?>