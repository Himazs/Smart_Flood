<?php
header('Content-Type: application/json');
include '../includes/config.php';
include '../includes/database.php';
include '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$category = isset($data['category']) ? sanitizeInput($data['category']) : 'flood';

try {
    $db = new Database();
    $conn = $db->connect();
    
    $stmt = $conn->prepare("SELECT * FROM safety_tips WHERE category = :category ORDER BY id");
    $stmt->bindParam(':category', $category);
    $stmt->execute();
    
    $tips = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true,
        'tips' => $tips
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>