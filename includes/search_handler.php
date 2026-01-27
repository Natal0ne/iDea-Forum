<?php
// includes/search_handler.php
require_once 'db_connect.php';

header('Content-Type: application/json');

if (isset($_GET['q'])) {
    $query = $_GET['q'];
    try {
        // Search for threads with titles matching the query (case-insensitive)
        $stmt = $conn->prepare("SELECT id, title, slug FROM threads WHERE title ILIKE :query LIMIT 5");
        $stmt->execute(['query' => '%' . $query . '%']);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($results);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode([]);
}
?>
