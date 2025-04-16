<?php

$show_id = intval($_GET['show_id']);
if (empty($show_id)) {
    http_response_code(404);
    header('Content-Type: application/json');
    echo json_encode(['error' => true, 'msg' => 'Missing show id']);
    exit();
}

// build icon paths
$icon_256 = "icons/pwa-icon-256.png?show_id={$show_id}";
$icon_512 = "icons/pwa-icon-512.png?show_id={$show_id}";

$protocol = !empty($_SERVER['HTTP_X_FORWARDED_PROTO']) 
    ? $_SERVER['HTTP_X_FORWARDED_PROTO'] 
    : ($_SERVER['REQUEST_SCHEME'] ?? 'https');

$scope = "/app/?show_id={$show_id}";
$start_url = $protocol . "://" . htmlspecialchars($_SERVER['HTTP_HOST']) . $scope . "&standalone=true";
$header_color = '#ffffff';
$background_color = '#ffffff';
$theme_color = '#000000';

$name = htmlspecialchars(urldecode($_GET['title'] ?? 'Podcast'));
$description = "Podcast App for the '{$name}' show";

$manifest = [
    "name" => $name,
    "short_name" => $name,
    "description" => $description,
    "start_url" => $start_url,
    "display" => "standalone",
    "orientation" => "any",
    "scope" => $scope,
    "id" => $start_url,
    "background_color" => $header_color,
    "theme_color" => $theme_color,
    "icons" => [
        [
            "src" => $icon_256,
            "sizes" => "256x256",
            "type" => "image/png",
        ],
        [
            "src" => $icon_512,
            "sizes" => "512x512",
            "type" => "image/png",
        ],
    ],
    "prefer_related_applications" => false
];


header('Content-Type: application/json');
echo json_encode($manifest);
