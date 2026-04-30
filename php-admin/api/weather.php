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

if (!isset($data['latitude']) || !isset($data['longitude'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Latitude and longitude are required']);
    exit();
}

$latitude = sanitizeInput($data['latitude']);
$longitude = sanitizeInput($data['longitude']);

// Check if we have cached weather data
try {
    $db = new Database();
    $conn = $db->connect();
    
    $stmt = $conn->prepare("SELECT * FROM weather_cache WHERE location = :location AND expires_at > NOW() ORDER BY created_at DESC LIMIT 1");
    $location = $latitude . ',' . $longitude;
    $stmt->bindParam(':location', $location);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $cachedData = $stmt->fetch();
        echo $cachedData['data'];
        exit();
    }
} catch (PDOException $e) {
    // Continue to fetch from API if cache check fails
}

// Fetch from OpenWeatherMap API
$apiKey = WEATHER_API_KEY;
$url = "https://api.openweathermap.org/data/2.5/weather?lat=$latitude&lon=$longitude&appid=$apiKey&units=metric";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    $weatherData = json_decode($response, true);
    
    // Cache the response for 30 minutes
    try {
        $expiresAt = date('Y-m-d H:i:s', strtotime('+30 minutes'));
        $data = json_encode([
            'success' => true,
            'weather' => $weatherData
        ]);
        
        $stmt = $conn->prepare("INSERT INTO weather_cache (location, data, expires_at) VALUES (:location, :data, :expires_at)");
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':data', $data);
        $stmt->bindParam(':expires_at', $expiresAt);
        $stmt->execute();
    } catch (PDOException $e) {
        // Silently fail caching
    }
    
    echo $data;
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to fetch weather data']);
}
?>