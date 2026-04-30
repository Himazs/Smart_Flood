<?php
header('Content-Type: application/json');
include '../includes/config.php';
include '../includes/database.php';

try {
    $db = new Database();
    $conn = $db->connect();
    
    $stmt = $conn->prepare("SELECT * FROM safe_routes WHERE status = 'active' ORDER BY name");
    $stmt->execute();
    
    $routes = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true,
        'routes' => $routes
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>