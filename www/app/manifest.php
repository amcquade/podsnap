<?php

// TODO: make api call to get feed data....


// build icon paths
$icon_256 = "/icon-256.png";
$icon_512 = "/icon-512.png";

$start_url = 'url/appidbs';
$header_color = '#ffffff';
$background_color = '#ffffff';
$theme_color = '#ffffff';



$description = "Pod web";
$name = 'Name of podcast';

$manifest = [
    "name" => "$description",
    "short_name" => "$name - PWA",
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
    ],
    "screenshots" => [
        [
            "src" => $icon_256,
            "sizes" => "256x256",
            "type" => "image/png",
            "form_factor" => "narrow",
            "label" => "Application Icon"
        ],
        [
            "src" => $icon_512,
            "sizes" => "512x512",
            "type" => "image/png",
            "form_factor" => "wide",
            "label" => "Application Icon"
        ]
    ]
];


header('Content-Type: application/json');
echo json_encode($manifest);
