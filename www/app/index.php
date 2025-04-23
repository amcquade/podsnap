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
                            data-audio-url="<?php echo htmlspecialchars($episode['enclosureUrl']); ?>" data-duration=<?php echo gmdate("H:i:s", $episode['duration']);  ?>>
                            <a href="#" class="text-decoration-none play-episode">
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

                <div id="audio-player">
                    <button id="play-pause">
                        <i id="play-pause-icon" class="bi bi-play-fill"></i>
                    </button>
                    <div id="progress-container">
                        <div id="progress"></div>
                    </div>
                    <span id="current-time">0:00</span> / <span id="duration">0:00</span>
                </div>
            </div>
        </div>
    </div>



    <script>
        // Audio Player Functionality
        const audioPlayer = document.getElementById('audioPlayer');
        const audioPlayerContainer = document.getElementById('audioPlayerContainer');
        const nowPlayingTitle = document.getElementById('nowPlayingTitle');
        const playerArtwork = document.getElementById('playerArtwork');
        const episodeItems = document.querySelectorAll('.episode-item');

        const playPauseButton = document.getElementById('play-pause');
        const playPauseIcon = document.getElementById('play-pause-icon');
        const progressContainer = document.getElementById('progress-container');
        const progressBar = document.getElementById('progress');
        const currentTimeDisplay = document.getElementById('current-time');
        const durationDisplay = document.getElementById('duration');



        var sound;

        // Play/Pause Toggle
        playPauseButton.addEventListener('click', () => {
            if (sound.playing()) {
                sound.pause();
                playPauseIcon.className = 'bi bi-play-fill'; // Change to play icon
            } else {
                sound.play();
                playPauseIcon.className = 'bi bi-pause-fill'; // Change to pause icon
        }

        });

        // Update Progress Bar and Time
        function updateProgress() {
            const seek = sound.seek() || 0; // Get current playback position
            const duration = sound.duration();
            progressBar.style.width = `${(seek / duration) * 100}%`;
            currentTimeDisplay.textContent = formatTime(seek);
            if (sound.playing()) {
                requestAnimationFrame(updateProgress); // Smooth updates
            }
        }

        // Update Duration on Load
        function updateDuration() {
            durationDisplay.textContent = formatTime(sound.duration());
        }

        // Seek on Progress Bar Click
        progressContainer.addEventListener('click', (event) => {
            const {
                offsetX,
                currentTarget
            } = event;
            const width = currentTarget.offsetWidth;
            const clickPosition = offsetX / width;
            sound.seek(sound.duration() * clickPosition);
            updateProgress();
        });

        // Format Time Helper
        function formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const secs = Math.floor(seconds % 60).toString().padStart(2, '0');
            return `${minutes}:${secs}`;
        }

        // Handle episode clicks
        document.querySelectorAll('.play-episode').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const listItem = this.closest('.episode-item');
                const audioUrl = listItem.getAttribute('data-audio-url');
                const episodeTitle = this.textContent;

                // Update player
                // audioPlayer.src = audioUrl;
                nowPlayingTitle.textContent = episodeTitle;
                audioPlayerContainer.classList.remove('d-none');

                // Highlight current episode
                episodeItems.forEach(item => item.classList.remove('now-playing'));
                listItem.classList.add('now-playing');

                // Play the audio
                // audioPlayer.play().catch(e => console.log('Auto-play prevented:', e));

                if (sound) {
                    sound.unload(); // Unload the previous instance
                    console.log("Unloading sound");
                }
                sound = new Howl({
                    src: [audioUrl],
                    html5: true,
                    onplay: updateProgress, // Start progress updates when playing
                    onload: updateDuration // Update duration when file loads
                });
                if (sound.play()) {
                    console.log("Playing howler");
                    playPauseIcon.className = 'bi bi-pause-fill'; // Change to pause icon
                }
            });
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
                <?php if (isset($Env) && empty($Env['LOCAL']) && !empty($Env['GTAG_ID'])): ?>
                    // Track that the app is installable
                    gtag('event', 'pwa_installable', {
                        'event_category': 'PWA',
                        'event_label': 'PWA Install Prompt Displayed'
                    });
                <?php endif; ?>
            }
        });

        installButton.addEventListener('click', async () => {
            if (!deferredPrompt) return;

            deferredPrompt.prompt();
            const {
                outcome
            } = await deferredPrompt.userChoice;
            console.log(`User response: ${outcome}`);

            <?php if (isset($Env) && empty($Env['LOCAL']) && !empty($Env['GTAG_ID'])): ?>
                if (outcome === 'accepted') {
                    // Track PWA install success
                    gtag('event', 'pwa_installed', {
                        'event_category': 'PWA',
                        'event_label': 'PWA Installed'
                    });
                } else {
                    // Track PWA install rejection
                    gtag('event', 'pwa_install_rejected', {
                        'event_category': 'PWA',
                        'event_label': 'PWA Install Rejected'
                    });
                }
            <?php endif; ?>
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

        let showId = new URL(location).searchParams.get('show_id');

        // push notification
        const publicVapidKey = '<?php echo $Env['VAPID_PUBLIC_KEY'] ?? ''; ?>';

        // Register Service Worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register(`/app/sw.js?show_id=${showId}`)
                    .then(registration => {
                        console.log('ServiceWorker registration successful:', registration);

                        // Ensure pushManager is supported
                        if (!registration.pushManager) {
                            throw new Error('PushManager is not supported.');
                        }

                        return registration.pushManager.subscribe({
                            userVisibleOnly: true,
                            applicationServerKey: urlBase64ToUint8Array(publicVapidKey)
                        });
                    })
                    .then(subscription => {
                        console.log('Push subscription successful:', subscription);

                        // Convert subscription to a plain object that can be cloned
                        const subscriptionData = {
                            endpoint: subscription.endpoint,
                            keys: {
                                p256dh: Array.from(new Uint8Array(subscription.getKey('p256dh'))),
                                auth: Array.from(new Uint8Array(subscription.getKey('auth')))
                            }
                        };

                        // Send subscription to the service worker
                        navigator.serviceWorker.ready.then(serviceWorker => {
                            serviceWorker.active?.postMessage({
                                type: 'STORE_SUBSCRIPTION',
                                subscriptionData
                            });
                        });

                        // Send subscription to the server
                        return fetch('/api/subscribe.php', {
                            method: 'POST',
                            body: JSON.stringify(subscriptionData),
                            headers: { 'Content-Type': 'application/json' }
                        });
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to subscribe on the server.');
                        }
                        console.log('Subscription saved on server:', response);
                    })
                    .catch(err => {
                        console.error('Error during service worker or subscription process:', err);
                    });
            });
        }

        function urlBase64ToUint8Array(base64String) {
            const padding = '='.repeat((4 - base64String.length % 4) % 4);
            const base64 = (base64String + padding)
                .replace(/-/g, '+')
                .replace(/_/g, '/');
            const rawData = atob(base64);
            const outputArray = new Uint8Array(rawData.length);
            for (let i = 0; i < rawData.length; ++i) {
                outputArray[i] = rawData.charCodeAt(i);
            }
            return outputArray;
        }
    </script>
</body>

</html>