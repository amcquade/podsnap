<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🔍</text></svg>">
    <meta name="theme-color" content="#ffffff">
    <title>PodSnap - Search</title>
    <script src="https://unpkg.com/htmx.org"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="p-4">
<!-- Dark Mode Toggle -->
<button class="dark-mode-toggle" id="darkModeToggle" title="Toggle Dark Mode">
    <i class="bi bi-moon-fill"></i>
</button>

<div class="container">
    <h1 class="h1 fw-bold">PodSnap</h1>
    <h2 class="h5 mb-4 fw-bold">Search</h2>

    <form hx-post="/api/podcasts.php" hx-target="#results" hx-swap="innerHTML" class="row g-2 mb-4">
        <div class="col">
            <input type="text" name="query" class="form-control form-control-lg" placeholder="Search for podcasts..." required>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="bi bi-search"></i> Search
            </button>
        </div>
    </form>

    <div id="results" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 justify-content-center">
        <!-- Results will be injected here -->
        <div class="col result-card">
            <div class="card">
                <div class="card-body">
                    <div class="card-content">
                        <i class="bi bi-mic" style="font-size: 3rem; color: #6c757d; margin-bottom: 1rem;"></i>
                        <h5 class="card-title">Search for podcasts</h5>
                        <p class="card-text">Enter a term above to discover new podcasts</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="darkMode.js"></script>
<script>
    // Enhance HTMX requests
    document.body.addEventListener('htmx:beforeRequest', function() {
        // Show loading state
        const results = document.getElementById('results');
        results.innerHTML = `
                <div class="col-12 text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `;
    });
</script>
<?php require_once 'footer.php'; ?>
</body>
</html>