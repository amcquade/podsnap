const CACHE_NAME = 'podcast-episodes-v1';
const API_CACHE_NAME = 'api-cache-v1';

// Install event - cache essential files
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => cache.addAll([
                '/',
                '/index.php',
                '/style.css',
                '/icons/pwa-icon-256.png',
                '/icons/pwa-icon-512.png',
            ]))
    );
});

// Fetch event - cache played episodes
self.addEventListener('fetch', (event) => {
    const url = new URL(event.request.url);

    // Cache audio files
    if (url.pathname.match(/\.(mp3|m4a|ogg|wav)$/i)) {
        event.respondWith(
            cacheThenNetwork(event.request)
        );
    }
});

// Strategies
async function cacheFirst(request, cacheName) {
    const cache = await caches.open(cacheName);
    const cached = await cache.match(request);
    return cached || fetch(request);
}

async function cacheThenNetwork(request) {
    const cache = await caches.open(CACHE_NAME);

    // Try cache first
    const cached = await cache.match(request);
    if (cached) return cached;

    // Fetch and cache
    const response = await fetch(request);
    if (response.ok) {
        cache.put(request, response.clone());
    }
    return response;
}

// Clean up old caches
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then(keys => Promise.all(
            keys.map(key => {
                if (key !== CACHE_NAME && key !== API_CACHE_NAME) {
                    return caches.delete(key);
                }
            })
        ))
    );
});

self.addEventListener('message', (event) => {
    if (event.data.type === 'CACHE_AUDIO') {
        caches.open(CACHE_NAME)
            .then(cache => fetch(event.data.url).then(response => cache.put(event.data.url, response)));
    }
});
