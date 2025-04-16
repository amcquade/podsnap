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
        echo "<div class='col result-card'>";
        echo '<div class="card">';
        echo '<div class="card-body">';
        echo '<h5 class="card-title">' . htmlspecialchars($feed['title']) . '</h5>';
        echo '<p class="card-text text-muted">' . htmlspecialchars($feed['description']) . '</p>';

        // create app URL
        $id = urlencode($feed['id']);
        $url = "//{$id}.{$_SERVER['HTTP_HOST']}/app/?show_id={$id}";
        // $url = "/app/?show_id={$id}";
        echo '<a href="' . $url . '" class="btn btn-link p-0">View Episodes</a>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
} else {
    echo '<div class="alert alert-info">No podcasts found.</div>';
}