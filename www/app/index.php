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

$show_title = 'Podcast';
if (!empty($showData['feed']['title'])) {
    $show_title = htmlspecialchars($showData['feed']['title']);
}

// Get iTunes ID if available
$itunesId = !empty($showData['feed']['itunesId']) ? $showData['feed']['itunesId'] : null;
$applePodcastUrl = $itunesId ? "https://podcasts.apple.com/podcast/id{$itunesId}" : null;

global $PageName, $PageType;
$PageType = "app";
$PageName = "$show_title - PodSnap";

require_once dirname(dirname(__FILE__)) . '/header.php';
?>

<body class="p-4">
<!-- Dark Mode Toggle -->
<button class="dark-mode-toggle" id="darkModeToggle" title="Toggle Dark Mode">
    <i class="bi bi-moon-fill"></i>
</button>

<div class="container">
    <!-- Podcast Header with Artwork -->
    <div class="podcast-header p-3 pb-0">
        <?php if (!empty($showData['feed']['artwork'])): ?>
            <img src="<?php echo $showData['feed']['artwork']; ?>" class="podcast-artwork" alt="Podcast Artwork">
        <?php endif; ?>
        <h1 class="podcast-title"><?php echo $show_title; ?></h1>

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

            <?php if (!empty($showData['feed']['link'])): ?>
                <a href="<?php echo htmlspecialchars($showData['feed']['link']); ?>"
                   class="podcast-platform-link website-link" title="Podcast website" target="_blank">
                    <i class="bi bi-globe"></i> Website
                </a>
            <?php endif; ?>
            <!-- Install App Button - will be hidden if already installed -->
            <button id="installButton" class="podcast-platform-link install-link d-none" title="Install App">
                <i class="bi bi-download"></i> Install App
            </button>
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
                        <!--                        <div class="text-muted small mt-1">-->
                        <!--                            --><?php //if (!empty($episode['duration'])): ?>
                        <!--                                Duration: --><?php //echo gmdate("H:i:s", $episode['duration']); ?>
                        <!--                            --><?php //endif; ?>
                        <!--                        </div>-->
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <div class="alert alert-info">No episodes found.</div>
        <?php endif; ?>
    </div>
</div>

<?php require_once dirname(dirname(__FILE__)) . '/footer.php'; ?>

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

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../darkMode.js"></script>
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
        // TODO: Auto-play next episode
    });

    // PWA installation prompt
    let deferredPrompt;
    const installButton = document.getElementById('installButton');

    window.addEventListener('beforeinstallprompt', (e) => {
        // Only show install button if not already installed
        if (!window.matchMedia('(display-mode: standalone)').matches) {
            e.preventDefault();
            deferredPrompt = e;
            installButton.classList.remove('d-none');
        }
    });

    installButton.addEventListener('click', async () => {
        if (!deferredPrompt) return;

        deferredPrompt.prompt();
        const {outcome} = await deferredPrompt.userChoice;
        console.log(`User response: ${outcome}`);
        deferredPrompt = null;
        installButton.classList.add('d-none');
    });

    // Hide install button if already installed
    window.addEventListener('load', () => {
        if (window.matchMedia('(display-mode: standalone)').matches) {
            installButton.classList.add('d-none');
        }
    });

    window.addEventListener('appinstalled', () => {
        installButton.classList.add('d-none');
    });

    // Register Service Worker
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function () {
            navigator.serviceWorker.register('/app/sw.js')
                .then(registration => {
                    console.log('ServiceWorker registration successful');
                })
                .catch(err => console.log('ServiceWorker registration failed: ', err));
        });
    }
</script>
</body>
</html>