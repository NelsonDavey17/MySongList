<?php // src/actions/toggle_favorit.php

session_start();
header('Content-Type: application/json'); // Respon berupa JSON

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions/user_song_functions.php';

// Terima input JSON dari JavaScript
$input = json_decode(file_get_contents('php://input'), true);
$laguId = $input['lagu_id'] ?? null;
$userId = $_SESSION['user_id'];

if ($laguId && filter_var($laguId, FILTER_VALIDATE_INT)) {
    try {
        // Panggil fungsi toggle yang tadi dibuat
        $status = toggleFavorite($conn, $userId, $laguId);
        
        echo json_encode([
            'success' => true,
            'status' => $status, // 'added' atau 'removed'
            'message' => ($status === 'added') ? 'Ditambahkan ke Favorit' : 'Dihapus dari Favorit'
        ]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid Data']);
}

if ($conn) mysqli_close($conn);
?>