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

// Get iTunes ID if available
$itunesId = !empty($showData['feed']['itunesId']) ? $showData['feed']['itunesId'] : null;
$applePodcastUrl = $itunesId ? "https://podcasts.apple.com/podcast/id{$itunesId}" : null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon"
          href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🎙️</text></svg>">
    <meta name="theme-color" content="#ffffff">
    <link rel="manifest" href="manifest.php?show_id=<?php echo intval($show_id) . "&title=" . urlencode($title); ?>">
    <title><?php echo $title; ?> - Podcast</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
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

        .podcast-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .podcast-artwork {
            max-width: 300px;
            width: 100%;
            height: auto;
            border-radius: 10px;
            margin: 0 auto 15px;
            display: block;
        }

        .podcast-title {
            margin-bottom: 15px;
        }

        .podcast-author {
            margin-bottom: 15px;
            font-size: 1.2rem;
            color: #6c757d;
        }

        .podcast-description {
            max-width: 800px;
            margin: 0 auto 30px;
            text-align: left;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        .podcast-meta {
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .podcast-platform-link {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 5px;
            transition: all 0.2s;
        }

        .rss-link {
            color: #ff6600;
            background-color: rgba(255, 102, 0, 0.1);
        }

        .rss-link:hover {
            color: white;
            background-color: #ff6600;
        }

        .apple-link {
            color: #FC3C44;
            background-color: rgba(252, 60, 68, 0.1);
        }

        .apple-link:hover {
            color: white;
            background-color: #FC3C44;
        }

        .episode-item {
            transition: background-color 0.2s ease;
        }

        @media (max-width: 768px) {
            .podcast-artwork {
                max-width: 200px;
            }

            .podcast-description {
                padding: 10px;
            }

            .podcast-meta {
                gap: 10px;
            }
        }

        footer {
            background-color: #f8f9fa;
            margin-bottom: 80px; /* Prevent overlap with audio player */
        }

        footer a {
            color: #6c757d;
            transition: color 0.2s;
        }

        footer a:hover {
            color: #0d6efd;
            text-decoration: none;
        }

        body {
            background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            min-height: 100vh;
        }

        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        .container {
            background-color: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(8px);
        }
    </style>
</head>

<body class="p-4">
<div class="container">
    <!-- Podcast Header with Artwork -->
    <div class="podcast-header p-3">
        <?php if (!empty($showData['feed']['artwork'])): ?>
            <img src="<?php echo $showData['feed']['artwork']; ?>" class="podcast-artwork" alt="Podcast Artwork">
        <?php endif; ?>
        <h1 class="podcast-title"><?php echo $title; ?></h1>

        <?php if (!empty($showData['feed']['author'])): ?>
            <div class="podcast-author">
                by <?php echo htmlspecialchars($showData['feed']['author']); ?>
            </div>
        <?php endif; ?>

        <!-- Podcast Platform Links -->
        <div class="podcast-meta">
            <?php if (!empty($showData['feed']['url'])): ?>
                <a href="<?php echo htmlspecialchars($showData['feed']['url']); ?>"
                   class="podcast-platform-link rss-link" target="_blank" title="Subscribe via RSS">
                    <i class="bi bi-rss-fill"></i> RSS Feed
                </a>
            <?php endif; ?>

            <?php if ($applePodcastUrl): ?>
                <a href="<?php echo $applePodcastUrl; ?>" class="podcast-platform-link apple-link" target="_blank"
                   title="Listen on Apple Podcasts">
                    <i class="bi bi-podcast"></i> Apple Podcasts
                </a>
            <?php endif; ?>
        </div>

        <!-- Podcast Description -->
        <?php if (!empty($showData['feed']['description'])): ?>
            <div class="podcast-description">
                <?php echo nl2br(htmlspecialchars($showData['feed']['description'])); ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Episode List -->
    <div class="episode-list p-3">
        <?php if (isset($data['items'])): ?>
            <h2 class="h4 mb-3">Episodes</h2>
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

<?php require_once 'footer.php'; ?>

</html>