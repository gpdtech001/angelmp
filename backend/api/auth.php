<?php
require_once '../config/config.php';

setCorsHeaders();

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(false, 'Method not allowed', null, 405);
}

// Get input data
$input = getJsonInput();

// Validate required fields
if (!isset($input['email']) || !isset($input['name']) || !isset($input['contact'])) {
    sendResponse(false, 'Email, name, and contact are required', null, 400);
}

$email = sanitizeString($input['email']);
$name = sanitizeString($input['name']);
$contact = sanitizeString($input['contact']);

// Validate email format
if (!validateEmail($email)) {
    sendResponse(false, 'Invalid email format', null, 400);
}

// Validate phone number
if (!validatePhone($contact)) {
    sendResponse(false, 'Invalid contact number format', null, 400);
}

// Validate name (should not be empty)
if (empty($name)) {
    sendResponse(false, 'Name cannot be empty', null, 400);
}

// Read users from file
$users = readJsonFile(USERS_FILE);

// Check if user exists
$userExists = false;
$userId = null;

foreach ($users as &$user) {
    if ($user['email'] === $email) {
        $userExists = true;
        $userId = $user['id'];

        // Update user info and last login
        $user['name'] = $name;
        $user['contact'] = $contact;
        $user['lastLogin'] = getCurrentTimestamp();

        break;
    }
}

// If user doesn't exist, create new user
if (!$userExists) {
    $userId = generateId();
    $newUser = [
        'id' => $userId,
        'email' => $email,
        'name' => $name,
        'contact' => $contact,
        'createdAt' => getCurrentTimestamp(),
        'lastLogin' => getCurrentTimestamp()
    ];

    $users[] = $newUser;
}

// Save users to file
if (writeJsonFile(USERS_FILE, $users)) {
    // Find and return the current user
    foreach ($users as $user) {
        if ($user['email'] === $email) {
            sendResponse(true, $userExists ? 'Login successful' : 'Account created successfully', $user, 200);
        }
    }
} else {
    sendResponse(false, 'Failed to save user data', null, 500);
}
