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
    <style>
        #installContainer {
            position: fixed;
            bottom: 80px;
            right: 20px;
            z-index: 1000;
            display: none;
        }

        #installButton {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        #audioPlayerContainer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 10px;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            z-index: 999;
        }

        .now-playing {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        body {
            padding-bottom: 80px; /* Make space for audio player */
        }
    </style>
</head>

<body class="p-4">
<div class="container">
    <div class="row align-items-center">
        <?php if (!empty($showData['feed']['artwork'])): ?>
            <div class="col-md-4 mb-4">
                <img src="<?php echo $showData['feed']['artwork']; ?>" class="img-fluid rounded" alt="Podcast Artwork"
                     id="show-artwork">
            </div>
        <?php endif; ?>

        <div class="col-md-8">
            <?php if (isset($data['items'])): ?>
                <h1 class="h3 mb-3">Episodes for "<?php echo $title; ?>"</h1>
                <ul class="list-group" id="episodesList">
                    <?php foreach ($data['items'] as $episode): ?>
                        <li class="list-group-item episode-item"
                            data-audio-url="<?php echo htmlspecialchars($episode['enclosureUrl']); ?>">
                            <a href="#" class="text-decoration-none play-episode">
                                <?php echo htmlspecialchars($episode['title']); ?>
                            </a>
                            <div class="text-muted small mt-1">
                                <?php if (!empty($episode['duration'])): ?>
                                    Duration: <?php echo gmdate("H:i:s", $episode['duration']); ?>
                                <?php endif; ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <div class="alert alert-info">No episodes found.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Audio Player -->
<div id="audioPlayerContainer" class="d-none">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-1 d-none d-md-block">
                <img src="<?php echo !empty($showData['feed']['artwork']) ? $showData['feed']['artwork'] : ''; ?>"
                     id="playerArtwork" class="img-fluid rounded" style="max-height: 50px;">
            </div>
            <div class="col-md-5">
                <div id="nowPlayingTitle" class="text-truncate">No episode selected</div>
            </div>
            <div class="col-md-6">
                <audio id="audioPlayer" controls class="w-100"></audio>
            </div>
        </div>
    </div>
</div>

<!-- Install PWA Button -->
<div id="installContainer">
    <button id="installButton" class="btn btn-primary btn-lg rounded-pill">
        Install App
    </button>
</div>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Audio Player Functionality
    const audioPlayer = document.getElementById('audioPlayer');
    const audioPlayerContainer = document.getElementById('audioPlayerContainer');
    const nowPlayingTitle = document.getElementById('nowPlayingTitle');
    const playerArtwork = document.getElementById('playerArtwork');
    const episodeItems = document.querySelectorAll('.episode-item');

    // Handle episode clicks
    document.querySelectorAll('.play-episode').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const listItem = this.closest('.episode-item');
            const audioUrl = listItem.getAttribute('data-audio-url');
            const episodeTitle = this.textContent;

            // Update player
            audioPlayer.src = audioUrl;
            nowPlayingTitle.textContent = episodeTitle;
            audioPlayerContainer.classList.remove('d-none');

            // Highlight current episode
            episodeItems.forEach(item => item.classList.remove('now-playing'));
            listItem.classList.add('now-playing');

            // Play the audio
            audioPlayer.play().catch(e => console.log('Auto-play prevented:', e));
        });
    });

    // Handle audio player events
    audioPlayer.addEventListener('play', function () {
        audioPlayerContainer.classList.remove('d-none');
    });

    audioPlayer.addEventListener('ended', function () {
        // Optional: Auto-play next episode
    });

    // PWA installation prompt
    let deferredPrompt;
    const installContainer = document.getElementById('installContainer');
    const installButton = document.getElementById('installButton');

    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
        installContainer.style.display = 'block';

        setTimeout(() => {
            if (installContainer.style.display !== 'none') {
                installContainer.style.display = 'none';
            }
        }, 30000);
    });

    installButton.addEventListener('click', async () => {
        if (!deferredPrompt) return;

        deferredPrompt.prompt();
        const {outcome} = await deferredPrompt.userChoice;
        console.log(`User response to the install prompt: ${outcome}`);
        deferredPrompt = null;
        installContainer.style.display = 'none';
    });

    window.addEventListener('appinstalled', () => {
        installContainer.style.display = 'none';
        deferredPrompt = null;
        console.log('PWA was installed');
    });

    window.addEventListener('load', () => {
        if (window.matchMedia('(display-mode: standalone)').matches) {
            installContainer.style.display = 'none';
        }
    });
</script>
</body>

</html>