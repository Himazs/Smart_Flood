<?php
header('Content-Type: application/json');
include '../includes/config.php';
include '../includes/database.php';

try {
    $db = new Database();
    $conn = $db->connect();
    
    $stmt = $conn->prepare("SELECT * FROM government_messages WHERE target_audience IN ('public', 'all') ORDER BY created_at DESC");
    $stmt->execute();
    
    $news = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true,
        'news' => $news
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>