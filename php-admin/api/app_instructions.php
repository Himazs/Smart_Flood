<?php
header('Content-Type: application/json');
include '../includes/config.php';
include '../includes/database.php';

try {
    $db = new Database();
    $conn = $db->connect();
    
    $stmt = $conn->prepare("SELECT * FROM app_instructions ORDER BY step_number");
    $stmt->execute();
    
    $instructions = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true,
        'instructions' => $instructions
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>