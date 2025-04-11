// Service Worker with Proxy Support
const CACHE_VERSION = 'v3';
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

// Install Event
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => cache.addAll(PRE_CACHE))
            .then(() => self.skipWaiting())
    );
});

// Activate Event
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

// Fetch Event Handler
self.addEventListener('fetch', (event) => {
    const request = event.request;
    const url = new URL(request.url);

    // Handle API requests
    if (url.pathname.includes('/api/')) {
        event.respondWith(cacheFirst(request, API_CACHE_NAME));
        return;
    }

    // Handle audio files through proxy
    if (url.pathname.match(/\.(mp3|m4a|ogg|wav)$/i)) {
        event.respondWith(handleAudioRequest(event));
        return;
    }

    // Default cache strategy
    event.respondWith(cacheFirst(request, CACHE_NAME));
});

// Audio Request Handler with Proxy
async function handleAudioRequest(event) {
    const request = event.request;
    const cache = await caches.open(CACHE_NAME);

    // Try cache first
    const cachedResponse = await cache.match(request);
    if (cachedResponse) return cachedResponse;

    try {
        // Create proxy URL for external audio
        const proxyUrl = `/app/proxy-audio.php?url=${encodeURIComponent(request.url)}`;
        const proxyRequest = new Request(proxyUrl, {
            headers: request.headers,
            mode: 'cors'
        });

        const networkResponse = await fetch(proxyRequest);

        // Verify response is audio
        const contentType = networkResponse.headers.get('content-type');
        if (!contentType || !contentType.startsWith('audio/')) {
            throw new Error('Invalid audio response');
        }

        // Clone response for caching
        const responseToCache = networkResponse.clone();

        // Cache the response
        event.waitUntil(
            cache.put(request, responseToCache).catch(err => {
                console.error('Cache put error:', err);
            })
        );

        return networkResponse;
    } catch (err) {
        console.error('Audio fetch failed:', err);
        return Response.error();
    }
}

// Cache First Strategy
async function cacheFirst(request, cacheName) {
    const cache = await caches.open(cacheName);
    const cached = await cache.match(request);
    return cached || fetch(request);
}

// Message Handler for Manual Caching
self.addEventListener('message', (event) => {
    if (event.data.type === 'CACHE_AUDIO') {
        const audioUrl = event.data.url;
        const proxyUrl = `/proxy-audio.php?url=${encodeURIComponent(audioUrl)}`;

        caches.open(CACHE_NAME)
            .then(cache => fetch(proxyUrl))
            .then(response => {
                if (response.ok) {
                    return caches.open(CACHE_NAME)
                        .then(cache => cache.put(audioUrl, response));
                }
            })
            .catch(err => console.error('Cache error:', err));
    }
});

// Range Request Support
self.addEventListener('fetch', (event) => {
    if (event.request.headers.get('range')) {
        const url = new URL(event.request.url);
        if (url.pathname.match(/\.(mp3|m4a|ogg|wav)$/i)) {
            event.respondWith(
                handleRangeRequest(event.request)
            );
        }
    }
});

async function handleRangeRequest(request) {
    const cache = await caches.open(CACHE_NAME);
    const cachedResponse = await cache.match(request.url);

    if (cachedResponse) {
        return new Response(
            cachedResponse.body,
            {
                status: 206,
                statusText: 'Partial Content',
                headers: cachedResponse.headers
            }
        );
    }

    return fetch(request);
}