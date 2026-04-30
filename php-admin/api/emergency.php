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

if ($payload['user_type'] !== 'public') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Only public users can report emergencies']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['latitude']) || !isset($data['longitude']) || !isset($data['name'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Latitude, longitude and name are required']);
    exit();
}

$latitude = sanitizeInput($data['latitude']);
$longitude = sanitizeInput($data['longitude']);
$name = sanitizeInput($data['name']);
$user_id = $payload['user_id'];

try {
    $db = new Database();
    $conn = $db->connect();
    
    $stmt = $conn->prepare("INSERT INTO emergencies (user_id, name, latitude, longitude) VALUES (:user_id, :name, :latitude, :longitude)");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':latitude', $latitude);
    $stmt->bindParam(':longitude', $longitude);
    
    if ($stmt->execute()) {
        logAction('Emergency reported', "User $user_id reported emergency at $latitude, $longitude");
        
        echo json_encode([
            'success' => true,
            'message' => 'Emergency reported successfully'
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to report emergency']);
    }
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>