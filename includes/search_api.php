<?php
require_once 'config.php';

$conn = connect_db();

// Se searchInput non c'è ritorna una stringa vuota
$searchString = $_GET['q'] ?? '';

// se la ricerca è di meno di due caratteri non ritorna nulla, per ottimizzare le query 
if(strlen($searchString) < 2) {
    echo json_encode([]);
    exit;
}

// query di selezione (mostrando solo i primi 5 risultati)
$query = "SELECT title, slug FROM threads WHERE title ILIKE $1";

// eseguo la query
$res = pg_query_params( $conn, $query, array('%' . $searchString . '%') );

// prendo i risultati come array associativo
// operatore elvis ?: assicura che se non ci sono risultati la variabile è un array vuoto invece di FALSE
$results = pg_fetch_all($res) ?: [];

// comunico al browser che il contenuto del file è un JSON e non un HTML
header('Content-Type: application/json');
// trasformo l'array php in una stringa che supporta JSON e la stampo a video
echo json_encode($results);
pg_close( $conn );
?>