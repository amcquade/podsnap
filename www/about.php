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
                <h3 class="text-center">PodSnap</h3>
                <p>
                    This is a specialized search engine powered by the Podcast Index,
                    the largest open podcast database. It allows you to discover and
                    explore podcasts from across the web.
                </p>
                <p>
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
                <h3 class="text-center">Install Any Podcast as an App</h3>
                <p>
                    Our unique feature allows you to install any podcast as a standalone
                    Progressive Web App (PWA) on your device. This means:
                </p>
                <ul>
                    <li>One-tap access from your home screen</li>
                    <li>No app store required</li>
                    <li>Lightweight and fast</li>
                </ul>
                <p>
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
                <h3 class="text-center">Open & Transparent</h3>
                <p>
                    This project is open source and built on open standards. We believe in:
                </p>
                <ul>
                    <li>Open access to podcasting</li>
                    <li>No tracking or user profiling</li>
                    <li>Respect for podcasters' content</li>
                </ul>
                <p class="text-center mt-4">
                    <a href="https://github.com/yourusername/yourrepo" class="btn btn-primary" target="_blank">
                        <i class="bi bi-github"></i> View on GitHub
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>

<!-- Same scripts as your main page for consistency -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="darkMode.js"></script>
</body>
</html>