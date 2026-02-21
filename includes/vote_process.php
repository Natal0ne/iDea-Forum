<?php
require_once 'config.php';
header('Content-Type: application/json');

if (!isset($_SESSION["user_id"])) {
    echo json_encode(['success' => false, 'message' => 'Please login']);
    exit;
}

$user_id = $_SESSION['user_id'];
$post_id = intval($_POST['post_id']);
$vote_value = intval($_POST['vote_value']); // 1 o -1

$conn = connect_db();

// 1. Controlla se esiste già un voto
$query = "SELECT vote_value FROM post_votes WHERE user_id = $1 AND post_id = $2";
$res = pg_query_params($conn, $query, [$user_id, $post_id]);
$existing = pg_fetch_assoc($res);

if ($existing) {
    if ($existing['vote_value'] == $vote_value) {
        // Se riclicca lo stesso voto, lo rimuoviamo (Toggle off)
        $delete = "DELETE FROM post_votes WHERE user_id = $1 AND post_id = $2";
        pg_query_params($conn, $delete, [$user_id, $post_id]);
        $current_vote = 0;
    } else {
        // Se cambia da up a down o viceversa, aggiorniamo
        $update = "UPDATE post_votes SET vote_value = $3 WHERE user_id = $1 AND post_id = $2";
        pg_query_params($conn, $update, [$user_id, $post_id, $vote_value]);
        $current_vote = $vote_value;
    }
} else {
    // Nuovo voto
    $insert = "INSERT INTO post_votes (user_id, post_id, vote_value) VALUES ($1, $2, $3)";
    pg_query_params($conn, $insert, [$user_id, $post_id, $vote_value]);
    $current_vote = $vote_value;
}

// 2. Calcola il nuovo totale per il post
$score_query = "SELECT SUM(vote_value) as total FROM post_votes WHERE post_id = $1";
$score_res = pg_query_params($conn, $score_query, [$post_id]);
$new_score = pg_fetch_assoc($score_res)['total'] ?? 0;

// 3. (Opzionale) Aggiorna reputazione dell'autore del post
$author_query = "UPDATE users SET reputation = (SELECT SUM(pv.vote_value) FROM post_votes pv JOIN posts p ON pv.post_id = p.id WHERE p.user_id = users.id)
                 WHERE id = (SELECT user_id FROM posts WHERE id = $1)";
pg_query_params($conn, $author_query, [$post_id]);

echo json_encode([
    'success' => true,
    'new_score' => $new_score,
    'user_vote' => $current_vote
]);
