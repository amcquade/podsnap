<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#ffffff">
    <link rel="manifest" href="/manifest.json">
    <title>Podcast Listing</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>

<body class="p-6">

    <?php

    if (!isset($_GET['show_id'])) {
        echo '<p class="text-red-500">No show ID provided.</p>';
        exit;
    }

    require_once dirname(dirname(__FILE__)) . '/functions.inc.php';

    $show_id = urlencode($_GET['show_id']);

    $uri = "episodes/byfeedid?id={$show_id}&pretty";
    $data = makeApiCall($uri);

    // TODO: take a look at API for this stuff.....
    if (isset($data['items'])) {
        echo '<h1 class="text-xl font-bold">Episodes for ' . htmlspecialchars($show_id) . '</h1>';
        echo '<ul class="list-disc pl-6">';
        
        // TODO: player URL
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