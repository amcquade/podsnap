<?php

/**
 * Defines $version
 * @var string $version
 */
require_once 'version.php';

?>

<footer>
    <div class="container text-center">
        <div class="row">
            <div class="text-md-end">
                <div class="footer-links">
                    <a href="/" class="footer-link"><i class="bi bi-house"></i> Home</a>
                    <a href="/about" class="footer-link"><i class="bi bi-info-circle"></i> About</a>
                    <a href="https://github.com/yourusername/yourrepo" class="footer-link" target="_blank">
                        <i class="bi bi-github"></i> GitHub
                    </a>
                    <a href="https://podcastindex.org/" class="footer-link" target="_blank">
                        <i class="bi bi-mic"></i> Podcast Index
                    </a>
                    <a class="footer-link">v<?php echo $version; ?></a>
                </div>
            </div>
        </div>
    </div>
</footer>