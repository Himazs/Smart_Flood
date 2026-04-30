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

if (!in_array($payload['user_type'], ['public', 'volunteer'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Only public users and volunteers can submit flood reports']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['title']) || !isset($data['latitude']) || !isset($data['longitude']) || !isset($data['description'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Title, latitude, longitude and description are required']);
    exit();
}

$title = sanitizeInput($data['title']);
$latitude = sanitizeInput($data['latitude']);
$longitude = sanitizeInput($data['longitude']);
$description = sanitizeInput($data['description']);
$severity = isset($data['severity']) ? sanitizeInput($data['severity']) : 'medium';
$user_id = $payload['user_id'];

if (!in_array($severity, ['low', 'medium', 'high'])) {
    $severity = 'medium';
}

try {
    $db = new Database();
    $conn = $db->connect();
    
    $stmt = $conn->prepare("INSERT INTO flood_reports (user_id, title, latitude, longitude, severity, description) VALUES (:user_id, :title, :latitude, :longitude, :severity, :description)");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':latitude', $latitude);
    $stmt->bindParam(':longitude', $longitude);
    $stmt->bindParam(':severity', $severity);
    $stmt->bindParam(':description', $description);
    
    if ($stmt->execute()) {
        logAction('Flood report submitted', "User $user_id submitted flood report: $title");
        
        echo json_encode([
            'success' => true,
            'message' => 'Flood report submitted successfully'
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to submit flood report']);
    }
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>