<?php
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validatePhone($phone) {
    return preg_match('/^[0-9]{10}$/', $phone);
}

function formatDate($date, $format = 'Y-m-d H:i:s') {
    return date($format, strtotime($date));
}

function getStatusBadge($status) {
    switch ($status) {
        case 'active':
        case 'verified':
        case 'received':
            return '<span class="badge bg-success">' . ucfirst($status) . '</span>';
        case 'pending':
            return '<span class="badge bg-warning">' . ucfirst($status) . '</span>';
        case 'resolved':
        case 'distributed':
            return '<span class="badge bg-info">' . ucfirst($status) . '</span>';
        case 'false':
        case 'inactive':
            return '<span class="badge bg-danger">' . ucfirst($status) . '</span>';
        default:
            return '<span class="badge bg-secondary">' . ucfirst($status) . '</span>';
    }
}

function getSeverityBadge($severity) {
    switch ($severity) {
        case 'high':
            return '<span class="badge bg-danger">' . ucfirst($severity) . '</span>';
        case 'medium':
            return '<span class="badge bg-warning">' . ucfirst($severity) . '</span>';
        case 'low':
            return '<span class="badge bg-success">' . ucfirst($severity) . '</span>';
        default:
            return '<span class="badge bg-secondary">' . ucfirst($severity) . '</span>';
    }
}

function logAction($action, $details = '') {
    $db = new Database();
    $conn = $db->connect();
    
    $user_id = $_SESSION['user_id'] ?? null;
    $user_type = $_SESSION['user_type'] ?? 'unknown';
    
    $stmt = $conn->prepare("INSERT INTO activity_log (user_id, user_type, action, details) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $user_type, $action, $details]);
}
?>