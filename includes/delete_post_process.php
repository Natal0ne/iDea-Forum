<?php
require_once 'config.php';
header('Content-Type: application/json');

if ($_SESSION['role'] === 'admin' && isset($_POST['post_id'])) {
    $conn = connect_db();
    $post_id = intval($_POST['post_id']);

    // Controllo se è un OP
    $check_query = "SELECT parent_id FROM public.posts WHERE id = $1";
    $check_res = pg_query_params($conn, $check_query, array($post_id));
    $post_data = pg_fetch_assoc($check_res);

    // È l'OP se parent_id è NULL
    $is_op = ($post_data['parent_id'] === null);

    // Faccio la Soft Delete
    $query = "UPDATE public.posts SET deleted_at = CURRENT_TIMESTAMP WHERE id = $1";
    $result = pg_query_params($conn, $query, array($post_id));

    if ($result) {
        echo json_encode([
            'success' => true,
            'is_op' => $is_op // Inviamo questa informazione al JS
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'DB Error']);
    }
    pg_close($conn);
} else {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
}
