<?php
header('Content-Type: application/json');
include '../includes/config.php';
include '../includes/database.php';
include '../includes/auth.php';
include '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

$payload = authenticate();

if ($payload['user_type'] !== 'volunteer') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Only volunteers can submit donations']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['name']) || !isset($data['contact']) || !isset($data['description'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Name, contact and description are required']);
    exit();
}

$name = sanitizeInput($data['name']);
$contact = sanitizeInput($data['contact']);
$description = sanitizeInput($data['description']);
$user_id = $payload['user_id'];

try {
    $db = new Database();
    $conn = $db->connect();
    
    $stmt = $conn->prepare("INSERT INTO donations (user_id, name, contact, description) VALUES (:user_id, :name, :contact, :description)");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':contact', $contact);
    $stmt->bindParam(':description', $description);
    
    if ($stmt->execute()) {
        logAction('Donation submitted', "User $user_id submitted donation: $name");
        
        echo json_encode([
            'success' => true,
            'message' => 'Donation submitted successfully'
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to submit donation']);
    }
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>