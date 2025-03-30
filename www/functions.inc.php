<?php
require_once dirname(__DIR__) . '/config/config.php';


function makeApiCall($uri) {
    global $ApiKey, $ApiSecret;
    
    if (!isset($ApiKey) || empty($ApiKey) || !isset($ApiSecret) || empty($ApiSecret)) {
        echo '<p class="text-red-500">Auth Error...1</p>';
        exit;
    }

    if (empty($uri)) {
        echo '<p class="text-red-500">Auth Error...2</p>';
        exit;
    }

    $apiHeaderTime = time();

    // Hash them to get the Authorization token
    $hash = sha1($ApiKey . $ApiSecret . $apiHeaderTime);

    $headers = [
        "User-Agent: ppwa/0.1",
        "X-Auth-Key: $ApiKey",
        "X-Auth-Date: $apiHeaderTime",
        "Authorization: $hash"
    ];

    $api_url = "https://api.podcastindex.org/api/1.0/$uri";


    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);


    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        echo '<p class="text-red-500">Curl error: ' . curl_error($ch) . '</p>';
        curl_close($ch);
        exit;
    }

    curl_close($ch);
    $data = json_decode($response, true);
    
    return $data;
}