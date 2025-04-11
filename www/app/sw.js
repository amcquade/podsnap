// Service Worker Version
const CACHE_VERSION = 'v1';
const CACHE_NAME = `podcast-cache-${CACHE_VERSION}`;
const API_CACHE_NAME = `api-cache-${CACHE_VERSION}`;

// Files to cache on install
const PRE_CACHE = [
    '/',
    '/app/index.php',
    '/app/style.css',
    '/darkMode.js',
    '/app/icons/pwa-icon-256.png',
    '/app/icons/pwa-icon-512.png'
];

// Install Event - Cache essential files
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => cache.addAll(PRE_CACHE))
            .then(() => self.skipWaiting())
    );
});

// Activate Event - Clean up old caches
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cache => {
                    if (cache !== CACHE_NAME && cache !== API_CACHE_NAME) {
                        return caches.delete(cache);
                    }
                })
            );
        }).then(() => self.clients.claim())
    );
});

// Fetch Event - Handle requests
self.addEventListener('fetch', (event) => {
    const request = event.request;
    const url = new URL(request.url);

    // Handle audio files differently
    if (url.pathname.match(/\.(mp3|m4a|ogg|wav)$/i)) {
        event.respondWith(
            handleAudioRequest(event)
        );
        return;
    }

    // Default caching strategy for other files
    event.respondWith(
        cacheFirst(request, CACHE_NAME)
    );
});

// Special handler for audio files
async function handleAudioRequest(event) {
    const request = event.request;
    const cache = await caches.open(CACHE_NAME);

    try {
        // Try to get from cache first
        const cachedResponse = await cache.match(request);
        if (cachedResponse) return cachedResponse;

        // Create a CORS request
        const corsRequest = new Request(request.url, {
            mode: 'no-cors',
            headers: request.headers
        });

        // Fetch with no-cors
        const networkResponse = await fetch(corsRequest);

        // Clone the response because it can only be used once
        const responseToCache = networkResponse.clone();

        // Cache the opaque response (won't be readable but can be played)
        event.waitUntil(cache.put(request, responseToCache));

        return networkResponse;
    } catch (err) {
        console.error('Audio fetch failed:', err);
        return cache.match(request) || Response.error();
    }
}

// Cache First strategy
async function cacheFirst(request, cacheName) {
    const cache = await caches.open(cacheName);
    const cached = await cache.match(request);
    return cached || fetch(request);
}

// Cache then Network strategy for audio
async function cacheThenNetwork(request) {
    const cache = await caches.open(CACHE_NAME);

    try {
        // Try network first
        const networkResponse = await fetch(request);
        if (networkResponse.ok) {
            // Update cache in background
            event.waitUntil(cache.put(request, networkResponse.clone()));
        }
        return networkResponse;
    } catch (err) {
        // Fall back to cache
        const cached = await cache.match(request);
        return cached || Response.error();
    }
}

// Message handler for manual caching
self.addEventListener('message', (event) => {
    if (event.data.type === 'CACHE_AUDIO') {
        caches.open(CACHE_NAME)
            .then(cache => fetch(event.data.url)
                .then(response => cache.put(event.data.url, response))
                .catch(err => console.error('Cache error:', err)));
    }
});