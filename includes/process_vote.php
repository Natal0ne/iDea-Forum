<?php
session_start();
require_once 'db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = $_POST['post_id'] ?? null;
    $vote_value = $_POST['vote_type'] ?? null; // 1 or -1
    $user_id = $_SESSION['user_id'];

    if (!$post_id || !in_array($vote_value, [1, -1])) {
        echo json_encode(['success' => false, 'error' => 'Invalid input']);
        exit;
    }

    try {
        // Check existing vote
        $checkSql = "SELECT vote_value FROM post_votes WHERE post_id = :post_id AND user_id = :user_id";
        $stmt = $conn->prepare($checkSql);
        $stmt->execute([':post_id' => $post_id, ':user_id' => $user_id]);
        $existing = $stmt->fetch();

        if ($existing) {
            if ((int)$existing['vote_value'] === (int)$vote_value) {
                // Remove vote (Toggle)
                $delSql = "DELETE FROM post_votes WHERE post_id = :post_id AND user_id = :user_id";
                $delStmt = $conn->prepare($delSql);
                $delStmt->execute([':post_id' => $post_id, ':user_id' => $user_id]);
            } else {
                // Change vote
                $updSql = "UPDATE post_votes SET vote_value = :vote_value WHERE post_id = :post_id AND user_id = :user_id";
                $updStmt = $conn->prepare($updSql);
                $updStmt->execute([':vote_value' => $vote_value, ':post_id' => $post_id, ':user_id' => $user_id]);
            }
        } else {
            // New vote
            $insSql = "INSERT INTO post_votes (post_id, user_id, vote_value) VALUES (:post_id, :user_id, :vote_value)";
            $insStmt = $conn->prepare($insSql);
            $insStmt->execute([':post_id' => $post_id, ':user_id' => $user_id, ':vote_value' => $vote_value]);
        }

        // Return new count
        $countSql = "SELECT COALESCE(SUM(vote_value), 0) as score FROM post_votes WHERE post_id = :post_id";
        $countStmt = $conn->prepare($countSql);
        $countStmt->execute([':post_id' => $post_id]);
        $score = $countStmt->fetchColumn();

        echo json_encode(['success' => true, 'new_score' => $score]);

    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
?>
