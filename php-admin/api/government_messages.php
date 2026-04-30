<?php
header('Content-Type: application/json');
include '../includes/config.php';
include '../includes/database.php';
include '../includes/auth.php';

$payload = authenticate();

if ($payload['user_type'] !== 'volunteer') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Access denied']);
    exit();
}

try {
    $db = new Database();
    $conn = $db->connect();
    
    $stmt = $conn->prepare("SELECT * FROM government_messages WHERE target_audience IN ('volunteer', 'all') ORDER BY created_at DESC");
    $stmt->execute();
    
    $messages = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true,
        'messages' => $messages
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>