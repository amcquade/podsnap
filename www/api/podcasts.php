<?php
header('Content-Type: application/json');

if (!isset($_POST['query'])) {
    echo '<p class="text-red-500">No query provided.</p>';
    exit;
}

$maxResults = urlencode($_POST['maxResults'] ?? 10);
$query = urlencode($_POST['query']);

$uri = "search/byterm?q={$query}&max={$maxResults}";

require_once dirname(__DIR__) . '/functions.inc.php';
$data = makeApiCall($uri);

if (isset($data['feeds'])) {
    foreach ($data['feeds'] as $feed) {
        echo '<div class="rounded-lg shadow-md p-4">';
        echo '<h2 class="text-lg font-semibold">' . htmlspecialchars($feed['title']) . '</h2>';
        echo '<p class="text-sm text-gray-600">' . htmlspecialchars($feed['description']) . '</p>';
        echo '<a href="/app?show_id=' . urlencode($feed['id']) . '" class="text-blue-600 underline mt-2 inline-block">View Episodes</a>';
        echo '</div>';
    }
} else {
    echo '<p class="text-gray-500">No podcasts found.</p>';
}
