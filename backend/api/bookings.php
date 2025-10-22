<?php
require_once '../config/config.php';

setCorsHeaders();

$method = $_SERVER['REQUEST_METHOD'];

// Define available time slots
$availableTimeSlots = [
    '09:00 - 10:00',
    '10:00 - 11:00',
    '11:00 - 12:00',
    '12:00 - 13:00',
    '13:00 - 14:00',
    '14:00 - 15:00',
    '15:00 - 16:00',
    '16:00 - 17:00',
    '17:00 - 18:00'
];

// GET - Retrieve bookings
if ($method === 'GET') {
    $bookings = readJsonFile(BOOKINGS_FILE);

    // Filter by email if provided
    if (isset($_GET['email'])) {
        $email = sanitizeString($_GET['email']);
        $bookings = array_filter($bookings, function($booking) use ($email) {
            return $booking['userEmail'] === $email;
        });
        $bookings = array_values($bookings); // Re-index array
    }

    // Filter by date if provided
    if (isset($_GET['date'])) {
        $date = sanitizeString($_GET['date']);
        $bookings = array_filter($bookings, function($booking) use ($date) {
            return $booking['date'] === $date;
        });
        $bookings = array_values($bookings); // Re-index array
    }

    sendResponse(true, 'Bookings retrieved successfully', $bookings, 200);
}

// POST - Create new booking
if ($method === 'POST') {
    $input = getJsonInput();

    // Validate required fields
    if (!isset($input['userEmail']) || !isset($input['date']) || !isset($input['timeSlot'])) {
        sendResponse(false, 'User email, date, and time slot are required', null, 400);
    }

    $userEmail = sanitizeString($input['userEmail']);
    $date = sanitizeString($input['date']);
    $timeSlot = sanitizeString($input['timeSlot']);
    $guestName = isset($input['guestName']) ? sanitizeString($input['guestName']) : '';
    $purpose = isset($input['purpose']) ? sanitizeString($input['purpose']) : '';

    // Validate email
    if (!validateEmail($userEmail)) {
        sendResponse(false, 'Invalid email format', null, 400);
    }

    // Validate time slot
    if (!in_array($timeSlot, $availableTimeSlots)) {
        sendResponse(false, 'Invalid time slot', null, 400);
    }

    // Validate date (must be today or future)
    $bookingDate = strtotime($date);
    $today = strtotime(date('Y-m-d'));
    if ($bookingDate < $today) {
        sendResponse(false, 'Cannot book for past dates', null, 400);
    }

    // Check for double-booking
    $bookings = readJsonFile(BOOKINGS_FILE);
    foreach ($bookings as $booking) {
        if ($booking['date'] === $date &&
            $booking['timeSlot'] === $timeSlot &&
            $booking['status'] === 'confirmed') {
            sendResponse(false, 'This time slot is already booked', null, 400);
        }
    }

    // Create new booking
    $newBooking = [
        'id' => generateId(),
        'userEmail' => $userEmail,
        'date' => $date,
        'timeSlot' => $timeSlot,
        'guestName' => $guestName,
        'purpose' => $purpose,
        'status' => 'confirmed',
        'createdAt' => getCurrentTimestamp()
    ];

    $bookings[] = $newBooking;

    // Save bookings
    if (writeJsonFile(BOOKINGS_FILE, $bookings)) {
        sendResponse(true, 'Booking created successfully', $newBooking, 201);
    } else {
        sendResponse(false, 'Failed to create booking', null, 500);
    }
}

// PUT - Update booking
if ($method === 'PUT') {
    $input = getJsonInput();

    // Validate required fields
    if (!isset($input['id'])) {
        sendResponse(false, 'Booking ID is required', null, 400);
    }

    $bookingId = sanitizeString($input['id']);
    $bookings = readJsonFile(BOOKINGS_FILE);

    $bookingFound = false;
    foreach ($bookings as &$booking) {
        if ($booking['id'] === $bookingId) {
            $bookingFound = true;

            // Update allowed fields
            if (isset($input['date'])) {
                $date = sanitizeString($input['date']);
                // Validate date
                $bookingDate = strtotime($date);
                $today = strtotime(date('Y-m-d'));
                if ($bookingDate < $today) {
                    sendResponse(false, 'Cannot book for past dates', null, 400);
                }

                // Check for double-booking if changing date or time
                if (isset($input['timeSlot'])) {
                    $timeSlot = sanitizeString($input['timeSlot']);
                    if (!in_array($timeSlot, $availableTimeSlots)) {
                        sendResponse(false, 'Invalid time slot', null, 400);
                    }

                    // Check if new slot is available
                    foreach ($bookings as $b) {
                        if ($b['id'] !== $bookingId &&
                            $b['date'] === $date &&
                            $b['timeSlot'] === $timeSlot &&
                            $b['status'] === 'confirmed') {
                            sendResponse(false, 'This time slot is already booked', null, 400);
                        }
                    }
                    $booking['timeSlot'] = $timeSlot;
                }

                $booking['date'] = $date;
            }

            if (isset($input['timeSlot']) && !isset($input['date'])) {
                $timeSlot = sanitizeString($input['timeSlot']);
                if (!in_array($timeSlot, $availableTimeSlots)) {
                    sendResponse(false, 'Invalid time slot', null, 400);
                }

                // Check if new slot is available
                foreach ($bookings as $b) {
                    if ($b['id'] !== $bookingId &&
                        $b['date'] === $booking['date'] &&
                        $b['timeSlot'] === $timeSlot &&
                        $b['status'] === 'confirmed') {
                        sendResponse(false, 'This time slot is already booked', null, 400);
                    }
                }
                $booking['timeSlot'] = $timeSlot;
            }

            if (isset($input['guestName'])) {
                $booking['guestName'] = sanitizeString($input['guestName']);
            }

            if (isset($input['purpose'])) {
                $booking['purpose'] = sanitizeString($input['purpose']);
            }

            break;
        }
    }

    if (!$bookingFound) {
        sendResponse(false, 'Booking not found', null, 404);
    }

    // Save bookings
    if (writeJsonFile(BOOKINGS_FILE, $bookings)) {
        // Return updated booking
        foreach ($bookings as $booking) {
            if ($booking['id'] === $bookingId) {
                sendResponse(true, 'Booking updated successfully', $booking, 200);
            }
        }
    } else {
        sendResponse(false, 'Failed to update booking', null, 500);
    }
}

// DELETE - Cancel booking (soft delete)
if ($method === 'DELETE') {
    // Get booking ID from query string
    if (!isset($_GET['id'])) {
        sendResponse(false, 'Booking ID is required', null, 400);
    }

    $bookingId = sanitizeString($_GET['id']);
    $bookings = readJsonFile(BOOKINGS_FILE);

    $bookingFound = false;
    foreach ($bookings as &$booking) {
        if ($booking['id'] === $bookingId) {
            $bookingFound = true;
            $booking['status'] = 'cancelled';
            break;
        }
    }

    if (!$bookingFound) {
        sendResponse(false, 'Booking not found', null, 404);
    }

    // Save bookings
    if (writeJsonFile(BOOKINGS_FILE, $bookings)) {
        sendResponse(true, 'Booking cancelled successfully', null, 200);
    } else {
        sendResponse(false, 'Failed to cancel booking', null, 500);
    }
}

// If we get here, method is not supported
sendResponse(false, 'Method not allowed', null, 405);
