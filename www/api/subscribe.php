<?php
header('Content-Type: application/json');

// Read input
$input = json_decode(file_get_contents('php://input'), true);

// Validate
if (!isset($input['endpoint']) || !isset($input['keys']['p256dh']) || !isset($input['keys']['auth'])) {
    http_response_code(400);
    die(json_encode(['error' => 'Invalid subscription data']));
}

// Store subscription in file (only one needed)
file_put_contents('subscription.json', json_encode([
    'endpoint' => $input['endpoint'],
    'publicKey' => $input['keys']['p256dh'],
    'authToken' => $input['keys']['auth']
]));

echo json_encode(['success' => true]);