<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - Podcast Search</title>
    <!-- Use the same styles as your main page -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .feature-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #6c5ce7;
        }
        .about-section {
            max-width: 800px;
            margin: 0 auto;
        }
    </style>
</head>
<body class="p-4">
<!-- Dark Mode Toggle -->
<button class="dark-mode-toggle" id="darkModeToggle" title="Toggle Dark Mode">
    <i class="bi bi-moon-fill"></i>
</button>

<div class="container">
    <div class="about-section">
        <h1 class="text-center mb-4">About Podcast Search</h1>

        <div class="card mb-4">
            <div class="card-body">
                <div class="text-center">
                    <i class="bi bi-search feature-icon"></i>
                </div>
                <h3 class="text-center">Podcast Search Engine</h3>
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
                    <li>Works offline for downloaded episodes</li>
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
<script>
    // Dark Mode Toggle (same as main page)
    const darkModeToggle = document.getElementById('darkModeToggle');
    const body = document.body;

    const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');
    const currentTheme = localStorage.getItem('theme');

    if (currentTheme === 'dark' || (!currentTheme && prefersDarkScheme.matches)) {
        body.classList.add('dark-mode');
        darkModeToggle.innerHTML = '<i class="bi bi-sun-fill"></i>';
    } else {
        darkModeToggle.innerHTML = '<i class="bi bi-moon-fill"></i>';
    }

    darkModeToggle.addEventListener('click', () => {
        body.classList.toggle('dark-mode');
        const theme = body.classList.contains('dark-mode') ? 'dark' : 'light';
        localStorage.setItem('theme', theme);

        if (body.classList.contains('dark-mode')) {
            darkModeToggle.innerHTML = '<i class="bi bi-sun-fill"></i>';
        } else {
            darkModeToggle.innerHTML = '<i class="bi bi-moon-fill"></i>';
        }
    });
</script>
</body>
</html>