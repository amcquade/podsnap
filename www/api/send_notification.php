<?php
header('Content-Type: application/json');
require_once dirname(__DIR__) . '/functions.inc.php';
$Env = getEnvArray();

// VAPID keys (replace with your own)
$vapidKeys = [
    'publicKey' => $Env['VAPID_PUBLIC_KEY'],
    'privateKey' => $Env['VAPID_PRIVATE_KEY'],
];

// Load the single subscription
if (!file_exists('subscription.json')) {
    http_response_code(404);
    die(json_encode(['error' => 'No subscription found']));
}

$subscription = json_decode(file_get_contents('subscription.json'), true);

// Prepare payload
$payload = json_encode([
    'title' => $_POST['title'] ?? 'New Notification',
    'body' => $_POST['body'] ?? 'You have a new message',
    'icon' => $_POST['icon'] ?? '/icon.png',
    'url' => $_POST['url'] ?? '/'
]);

// Create encryption headers
$auth = base64_encode(random_bytes(16));
$p256dh = $subscription['publicKey'];
$endpoint = $subscription['endpoint'];

// Create payload encryption
$curve = openssl_pkey_new([
    'curve_name' => 'prime256v1',
    'private_key_type' => OPENSSL_KEYTYPE_EC
]);
$details = openssl_pkey_get_details($curve);
$localPublicKey = base64url_encode($details['ec']['public_key']);

// Encrypt payload (simplified - real implementation needs proper encryption)
$cipherText = openssl_encrypt(
    $payload,
    'aes-128-gcm',
    random_bytes(16),
    0,
    random_bytes(12),
    $tag
);

// Send push notification
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $endpoint,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        'Authorization: vapid t='.base64url_encode(json_encode([
            'sub' => "mailto:{$Env['VAPID_MAIL_TO']}",
            'aud' => parse_url($endpoint, PHP_URL_HOST),
            'exp' => time() + 43200 // 12 hours
        ])).', k='.$vapidKeys['publicKey'],
        'Crypto-Key: p256ecdsa='.$vapidKeys['publicKey'],
        'Encryption: salt='.base64url_encode(random_bytes(16)),
        'Content-Encoding: aesgcm',
        'TTL: 60'
    ],
    CURLOPT_POSTFIELDS => $cipherText,
    CURLOPT_RETURNTRANSFER => true
]);

$response = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo json_encode([
    'success' => $status === 201,
    'status' => $status
]);

// Helper function
function base64url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}