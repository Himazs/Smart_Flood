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
$user_id = $payload['user_id'];

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['name']) || !isset($data['email'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Name and email are required']);
    exit();
}

$name = sanitizeInput($data['name']);
$email = sanitizeInput($data['email']);
$phone = isset($data['phone']) ? sanitizeInput($data['phone']) : null;
$address = isset($data['address']) ? sanitizeInput($data['address']) : null;
$profile_image = isset($data['profile_image']) ? sanitizeInput($data['profile_image']) : null;

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
    
    // Check if email already exists for another user
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email AND id != :id");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        http_response_code(409);
        echo json_encode(['success' => false, 'message' => 'Email already exists']);
        exit();
    }
    
    // Update user profile
    $sql = "UPDATE users SET name = :name, email = :email, phone = :phone, address = :address";
    $params = [
        ':name' => $name,
        ':email' => $email,
        ':phone' => $phone,
        ':address' => $address,
        ':id' => $user_id
    ];
    
    if ($profile_image) {
        $sql .= ", profile_image = :profile_image";
        $params[':profile_image'] = $profile_image;
    }
    
    $sql .= " WHERE id = :id";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    
    if ($stmt->rowCount() > 0) {
        // Get updated user data
        $stmt = $conn->prepare("SELECT id, name, email, user_type, phone, address, profile_image, created_at FROM users WHERE id = :id");
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();
        $user = $stmt->fetch();
        
        logAction('Profile updated', "User $user_id updated their profile");
        
        echo json_encode([
            'success' => true,
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'message' => 'No changes made to profile'
        ]);
    }
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>