<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#ffffff">
    <link rel="manifest" href="/manifest.json">
    <title>Podcast Search</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>

<body class="p-6">



    <?php

    if (!isset($_GET['show_id'])) {
        echo '<p class="text-red-500">No show ID provided.</p>';
        exit;
    }

    require_once dirname(dirname(__DIR__)) . '/config/config.php';
    if (!isset($ApiKey) || empty($ApiKey) || !isset($ApiSecret) || empty($ApiSecret)) {
        echo '<p class="text-red-500">Auth Error...</p>';
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

    $show_id = urlencode($_GET['show_id']);
    $api_url = "https://api.podcastindex.org/api/1.0/episodes/byfeedid?id={$show_id}&pretty";


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

    // TODO: take a look at API for this stuff.....
    if (isset($data['items'])) {
        echo '<h1 class="text-xl font-bold">Episodes for ' . htmlspecialchars($show_id) . '</h1>';
        echo '<ul class="list-disc pl-6">';
        foreach ($data['items'] as $episode) {
            echo '<li><a href="' . htmlspecialchars($episode['enclosureUrl']) . '" target="_blank" class="text-blue-600">' . htmlspecialchars($episode['title']) . '</a></li>';
        }
        echo '</ul>';
    } else {
        echo '<p class="text-gray-500">No episodes found.</p>';
    }
    ?>

</body>

</html>