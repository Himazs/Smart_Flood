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

if (!isset($data['name']) || !isset($data['email']) || !isset($data['password'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Name, email and password are required']);
    exit();
}

$name = sanitizeInput($data['name']);
$email = sanitizeInput($data['email']);
$password = sanitizeInput($data['password']);
$phone = isset($data['phone']) ? sanitizeInput($data['phone']) : null;
$address = isset($data['address']) ? sanitizeInput($data['address']) : null;
$user_type = isset($data['user_type']) ? sanitizeInput($data['user_type']) : 'volunteer';

if (!validateEmail($email)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid email format']);
    exit();
}

if ($phone && !validatePhone($phone)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid phone number format']);
    exit();
}

try {
    $db = new Database();
    $conn = $db->connect();
    
    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        http_response_code(409);
        echo json_encode(['success' => false, 'message' => 'Email already exists']);
        exit();
    }
    
    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, user_type, phone, address) VALUES (:name, :email, :password, :user_type, :phone, :address)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':user_type', $user_type);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':address', $address);
    
    if ($stmt->execute()) {
        $user_id = $conn->lastInsertId();
        
        // Get the created user
        $stmt = $conn->prepare("SELECT id, name, email, user_type, phone, address, profile_image, created_at FROM users WHERE id = :id");
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();
        $user = $stmt->fetch();
        
        echo json_encode([
            'success' => true,
            'message' => 'User registered successfully',
            'user' => $user
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to register user']);
    }
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>