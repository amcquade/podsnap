<?php
/**
 * Variables defined in app/index.php
 * @var string $title - Title of podcast
 * @var array $showData - array of podcast data from pci api
 */

?>

<!-- Footer -->
<footer class="mt-5 py-4 border-top">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <p class="mb-1">© <?php echo date('Y'); ?> <?php echo htmlspecialchars($title); ?></p>
                <?php if (!empty($showData['feed']['author'])): ?>
                    <p class="text-muted small">Created by <?php echo htmlspecialchars($showData['feed']['author']); ?></p>
                <?php endif; ?>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="mb-2">
                    <?php if (!empty($showData['feed']['link'])): ?>
                        <a href="<?php echo htmlspecialchars($showData['feed']['link']); ?>" class="text-decoration-none me-3" target="_blank">
                            <i class="bi bi-globe"></i> Website
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($showData['feed']['email'])): ?>
                        <a href="mailto:<?php echo htmlspecialchars($showData['feed']['email']); ?>" class="text-decoration-none">
                            <i class="bi bi-envelope"></i> Contact
                        </a>
                    <?php endif; ?>
                </div>
                <p class="text-muted small mb-0">
                    <a href="https://podcastindex.org/">Powered by Podcast Index</a>
                </p>
            </div>
        </div>
    </div>
</footer>