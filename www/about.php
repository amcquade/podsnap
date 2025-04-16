<?php
global $PageName, $PageType;
$PageType = "about";
$PageName = "About";

require_once 'header.php';

?>

<body class="p-4">
<!-- Dark Mode Toggle -->
<button class="dark-mode-toggle" id="darkModeToggle" title="Toggle Dark Mode">
    <i class="bi bi-moon-fill"></i>
</button>

<div class="container">
    <div class="about-section">
        <h1 class="text-center mb-4">About PodSnap</h1>

        <div class="card mb-4">
            <div class="card-body">
                <div class="text-center">
                    <i class="bi bi-search feature-icon"></i>
                </div>
                <h3 class="card-title text-center">PodSnap</h3>
                <p class="card-text">
                    This is a specialized search engine powered by the Podcast Index,
                    the largest open podcast database. It allows you to discover and
                    explore podcasts from across the web.
                </p>
                <p class="card-text">
                    The Podcast Index provides comprehensive metadata and episode information,
                    giving you access to podcasts that may not be available in commercial directories.
                </p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <div class="text-center">
                    <i class="bi bi-phone feature-icon"></i>
                </div>
                <h3 class="card-title text-center">Install Any Podcast as an App</h3>
                <p class="card-text">
                    Our unique feature allows you to install any podcast as a standalone
                    Progressive Web App (PWA) on your device. This means:
                </p>
                <ul class="card-text">
                    <li>One-tap access from your home screen</li>
                    <li>No app store required</li>
                    <li>Lightweight and fast</li>
                </ul>
                <p class="card-text">
                    When you find a podcast you love, simply use the "Install App" button
                    to add it to your device like a native application.
                </p>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="text-center">
                    <i class="bi bi-code-slash feature-icon"></i>
                </div>
                <h3 class="card-title text-center">Open & Transparent</h3>
                <p class="card-text">
                    This project is open source and built on open standards. We believe in:
                </p>
                <ul class="card-text">
                    <li>Open access to podcasting</li>
                    <li>No tracking or user profiling</li>
                    <li>Respect for podcasters' content</li>
                </ul>
                <p class="card-text text-center mt-4">
                    <a href="https://github.com/yourusername/yourrepo" class="btn btn-primary" target="_blank">
                        <i class="bi bi-github"></i> View on GitHub
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>

</body>
</html>