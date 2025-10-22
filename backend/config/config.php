<?php
// Configuration and Helper Functions

// CORS Configuration
function setCorsHeaders() {
    header("Access-Control-Allow-Origin: http://localhost:5173");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Allow-Credentials: true");
    header("Content-Type: application/json; charset=UTF-8");

    // Handle preflight OPTIONS request
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }
}

// Data file paths
define('USERS_FILE', __DIR__ . '/../data/users.json');
define('BOOKINGS_FILE', __DIR__ . '/../data/bookings.json');

// Read JSON file
function readJsonFile($filePath) {
    if (!file_exists($filePath)) {
        return [];
    }
    $content = file_get_contents($filePath);
    return json_decode($content, true) ?: [];
}

// Write JSON file
function writeJsonFile($filePath, $data) {
    $json = json_encode($data, JSON_PRETTY_PRINT);
    return file_put_contents($filePath, $json);
}

// Send JSON response
function sendResponse($success, $message, $data = null, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit();
}

// Generate unique ID
function generateId() {
    return uniqid('', true);
}

// Get current timestamp
function getCurrentTimestamp() {
    return date('Y-m-d H:i:s');
}

// Validate email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Validate phone number (basic validation)
function validatePhone($phone) {
    return preg_match('/^[0-9+\-\s()]{7,20}$/', $phone);
}

// Get JSON input from request body
function getJsonInput() {
    $input = file_get_contents('php://input');
    return json_decode($input, true);
}

// Sanitize string input
function sanitizeString($string) {
    return htmlspecialchars(strip_tags(trim($string)));
}
