<?php

global $PageType, $PageName, $show_id, $show_title, $Env;
require_once 'functions.inc.php';
require_once 'version.php';
$Env = getEnvArray();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#ffffff">
    <title><?php echo $PageName ?? 'PodSnap'; ?></title>
    <script src="https://unpkg.com/htmx.org"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <?php

    // check if we have the gtag id and make sure were not on local
    if (!empty($Env['GTAG_ID']) && empty($Env['LOCAL'])): ?>

        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $Env['GTAG_ID']; ?>"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }

            gtag('js', new Date());

            gtag('config', '<?php echo $Env['GTAG_ID']; ?>');
        </script>

    <?php endif; ?>

    <?php

    if ($PageType == "home"): ?>
        <script src="darkMode.js"></script>
        <link rel="icon"
            href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🔍</text></svg>">
    <?php elseif ($PageType == "about"): ?>
        <script src="darkMode.js"></script>
        <link rel="icon"
            href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>📲</text></svg>">
    <?php elseif ($PageType == "app"): ?>
        <link rel="manifest"
            href="manifest.php?show_id=<?php echo intval($show_id) . "&title=" . urlencode($show_title) . "&v=" . ($version ?? time()); ?>">
        <link rel="icon"
            href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🎙️</text></svg>">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/howler/2.2.4/howler.core.min.js" integrity="sha512-d00Brs/+XQUUaO0Y9Uo8Vw63o7kS6ZcLM2P++17kALrI8oihAfL4pl1jQObeRBgv06j7xG0GHOhudAW0BdrycA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="../darkMode.js"></script>
    <?php else: ?>
        <link rel="icon"
            href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🎙️</text></svg>">
    <?php endif; ?>
</head>