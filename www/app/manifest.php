<?php

// TODO: make api call to get feed data....

// build icon paths
$icon_256 = "icons/pwa-icon-256.png";
$icon_512 = "icons/pwa-icon-512.png";

$protocol = "https";
if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
    $protocol = $_SERVER['HTTP_X_FORWARDED_PROTO'];
} elseif (!empty($_SERVER['REQUEST_SCHEME'])) {
    $protocol = $_SERVER['REQUEST_SCHEME'];
}
$start_url = $protocol . "://" . htmlspecialchars($_SERVER['HTTP_HOST']) . "/app/?show_id=" . intval($_GET['show_id']);
$header_color = '#ffffff';
$background_color = '#ffffff';
$theme_color = '#ffffff';

$name = 'Podcast';
if (isset($_GET['title'])) {
    $name = htmlspecialchars(urldecode($_GET['title']));
}
$description = "Podcast App for the '{$name}' show";

$manifest = [
    "name" => "$name",
    "short_name" => "$name",
    "description" => $description,
    "start_url" => $start_url,
    "display" => "standalone",
    "orientation" => "any",
    "background_color" => $header_color,
    "theme_color" => $theme_color,
    "icons" => [
        [
            "src" => $icon_256,
            "sizes" => "256x256",
            "type" => "image/png"
        ],
        [
            "src" => $icon_512,
            "sizes" => "512x512",
            "type" => "image/png"
        ]
    ]
];


header('Content-Type: application/json');
echo json_encode($manifest);
