<?php
header('Content-Type: application/json');

if (!isset($_POST['query'])) {
    echo '<p class="text-red-500">No query provided.</p>';
    exit;
}

$query = urlencode($_POST['query']);
$api_url = "https://podcastindex.org/api/1.0/search/byterm?q={$query}";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'User-Agent: PodcastApp/1.0',
    'Authorization: Bearer YOUR_API_KEY_HERE'
]);

$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo '<p class="text-red-500">Curl error: ' . curl_error($ch) . '</p>';
    curl_close($ch);
    exit;
}

curl_close($ch);
$data = json_decode($response, true);

if (isset($data['feeds'])) {
    foreach ($data['feeds'] as $feed) {
        echo '<div class="rounded-lg shadow-md p-4">';
        echo '<h2 class="text-lg font-semibold">' . htmlspecialchars($feed['title']) . '</h2>';
        echo '<p class="text-sm text-gray-600">' . htmlspecialchars($feed['description']) . '</p>';
        echo '<a href="' . htmlspecialchars($feed['url']) . '" target="_blank" rel="noopener noreferrer" class="text-blue-600 underline mt-2 inline-block">Visit Podcast</a>';
        echo '</div>';
    }
} else {
    echo '<p class="text-gray-500">No podcasts found.</p>';
}