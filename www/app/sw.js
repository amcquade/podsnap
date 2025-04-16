// Service Worker with Proxy Support
const CACHE_VERSION = 'v3';
const SHOW_ID = new URL(location).searchParams.get('show_id');
const HOST = location.hostname;
const CACHE_ID_VERSION = `${CACHE_VERSION}-${SHOW_ID}`;
const CACHE_NAME = `podsnap-cache-${CACHE_ID_VERSION}`;
const API_CACHE_NAME = `api-cache-${CACHE_ID_VERSION}`;

// Files to cache on install
const PRE_CACHE = [
    '/',
    `/app/?show_id=${SHOW_ID}`,
    '/app/style.css',
    '/darkMode.js',
    `/app/icons/pwa-icon-256.png?${SHOW_ID}`,
    `/app/icons/pwa-icon-512.png?${SHOW_ID}`
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

    // Default cache strategy
    event.respondWith(cacheFirst(request, CACHE_NAME));
});

// Cache First Strategy
async function cacheFirst(request, cacheName) {
    const cache = await caches.open(cacheName);
    const cached = await cache.match(request);
    return cached || fetch(request);
}
