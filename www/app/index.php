<?php
if (!isset($_GET['show_id'])) {
    echo '<div class="alert alert-danger">No show ID provided.</div>';
    exit;
}

require_once dirname(dirname(__FILE__)) . '/functions.inc.php';

$show_id = urlencode($_GET['show_id']);

$uri = "episodes/byfeedid?id={$show_id}&pretty";
$data = makeApiCall($uri);

$uri = "podcasts/byfeedid?id={$show_id}&pretty";
$showData = makeApiCall($uri);

$title = 'Podcast';
if (!empty($showData['feed']['title'])) {
    $title = htmlspecialchars($showData['feed']['title']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#ffffff">
    <link rel="manifest" href="manifest.php?show_id=<?php echo intval($show_id) . "&title=" . urlencode($title); ?>">
    <title>Podcast Listing</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">
    <div class="container">
        <div class="row align-items-center">
            <?php if (!empty($showData['feed']['artwork'])): ?>
                <div class="col-md-4 mb-4">
                    <img src="<?php echo $showData['feed']['artwork']; ?>" class="img-fluid rounded" alt="Podcast Artwork" id="show-artwork">
                </div>
            <?php endif; ?>

            <div class="col-md-8">
                <?php if (isset($data['items'])): ?>
                    <h1 class="h3 mb-3">Episodes for "<?php echo $title; ?>"</h1>
                    <ul class="list-group">
                        <?php foreach ($data['items'] as $episode): ?>
                            <li class="list-group-item">
                                <a href="<?php echo htmlspecialchars($episode['enclosureUrl']); ?>"
                                    target="_blank"
                                    class="text-decoration-none">
                                    <?php echo htmlspecialchars($episode['title']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <div class="alert alert-info">No episodes found.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>