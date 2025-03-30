<?php
header('Content-Type: application/json');

if (!isset($_POST['query'])) {
    echo '<div class="alert alert-danger">No query provided.</div>';
    exit;
}

$maxResults = urlencode($_POST['maxResults'] ?? 10);
$query = urlencode($_POST['query']);

$uri = "search/byterm?q={$query}&max={$maxResults}";

require_once dirname(__DIR__) . '/functions.inc.php';
$data = makeApiCall($uri);

if (isset($data['feeds'])) {
    foreach ($data['feeds'] as $feed) {
        echo '<div class="card mb-3">';
        echo '<div class="card-body">';
        echo '<h5 class="card-title">' . htmlspecialchars($feed['title']) . '</h5>';
        echo '<p class="card-text text-muted">' . htmlspecialchars($feed['description']) . '</p>';
        echo '<a href="/app?show_id=' . urlencode($feed['id']) . '" class="btn btn-link p-0">View Episodes</a>';
        echo '</div>';
        echo '</div>';
    }
} else {
    echo '<div class="alert alert-info">No podcasts found.</div>';
}