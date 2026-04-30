<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'smart_flood_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Start session
session_start();

// Base URL
define('BASE_URL', 'http://localhost/php-admin');

// JWT Secret Key
define('JWT_SECRET', 'smart_flood_secret_key_2023');

// Weather API Key
define('WEATHER_API_KEY', 'your_openweather_api_key_here');

// Allowed origins for CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization, Accept,charset,boundary,Content-Length');

// Set timezone
date_default_timezone_set('Asia/Colombo');

// Error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>