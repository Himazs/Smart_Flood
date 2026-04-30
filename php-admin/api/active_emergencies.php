<?php
header('Content-Type: application/json');
include '../includes/config.php';
include '../includes/database.php';
include '../includes/auth.php';

$payload = authenticate();

if (!in_array($payload['user_type'], ['volunteer', 'admin', 'government'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Access denied']);
    exit();
}

try {
    $db = new Database();
    $conn = $db->connect();
    
    $stmt = $conn->prepare("SELECT e.*, u.name as user_name FROM emergencies e JOIN users u ON e.user_id = u.id WHERE e.status = 'active' ORDER BY e.created_at DESC");
    $stmt->execute();
    
    $emergencies = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true,
        'emergencies' => $emergencies
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>