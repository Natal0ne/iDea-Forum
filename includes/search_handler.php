<?php
include 'db_connect.php';

header('Content-Type: application/json');

$response = [];

if (isset($_GET['q'])) {
    $search = $_GET['q'];

    // Per sicurezza
    $safe_search = pg_escape_string($conn, $search);

    // Query per cercare i thread (limitata a 5 risultati)
    $sql = "SELECT id, title, slug FROM threads WHERE title ILIKE '%$safe_search%' LIMIT 5";
    $result = pg_query($conn, $sql);
    
    // Se la query ha successo
    if($result) {
        // Ciclo per ogni riga della query
        while($row = pg_fetch_assoc($result)) {
            // Aggiungo il thread alla risposta
            $response[] = [
                'id' => $row['id'],
                'title' => $row['title'],
            ];
        }
    }
    
    // Invio la risposta codificata in JSON
    echo json_encode($response);
}
    
?>
