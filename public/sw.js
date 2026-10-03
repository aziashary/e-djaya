const CACHE_NAME = 'edjaya-pwa-v4';
const PRECACHE_ASSETS = [
  '/',
  '/assets/css/auth.css',
  '/assets/css/edjaya-ui.css',
  '/assets/js/edjaya-ui.js',
  '/assets/vendor/css/core.css',
  '/assets/img/favicon/favicon-192x192.png',
  '/assets/img/favicon/favicon-512x512.png',
  '/assets/img/favicon/site.webmanifest',
  '/manifest.json'
];

self.addEventListener('install', (event) => {
  self.skipWaiting();
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => cache.addAll(PRECACHE_ASSETS).catch(() => {}))
  );
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) => {
      // Purge all old caches completely
      return Promise.all(
        keys.map((key) => caches.delete(key))
      );
    }).then(() => self.clients.claim())
  );
});

self.addEventListener('fetch', (event) => {
  if (event.request.method !== 'GET') {
    return;
  }

  const url = new URL(event.request.url);

  // For HTML page navigation: network first, fallback to cached '/' if offline
  if (event.request.mode === 'navigate') {
    event.respondWith(
      fetch(event.request)
        .then((response) => {
          if (response && response.status === 200) {
            const copy = response.clone();
            caches.open(CACHE_NAME).then((cache) => cache.put(event.request, copy));
          }
          return response;
        })
        .catch(() => caches.match(event.request).then((cached) => cached || caches.match('/')))
    );
    return;
  }

  // For static assets (CSS, JS, images, fonts): ALWAYS NETWORK FIRST, update cache.
  // NEVER fallback to HTML '/' for assets!
  event.respondWith(
    fetch(event.request)
      .then((networkResponse) => {
        if (
          networkResponse &&
          networkResponse.status === 200 &&
          (url.pathname.startsWith('/assets/') || url.pathname.startsWith('/build/'))
        ) {
          const copy = networkResponse.clone();
          caches.open(CACHE_NAME).then((cache) => cache.put(event.request, copy));
        }
        return networkResponse;
      })
      .catch(() => {
        // Only return cached asset matching the exact URL, NEVER return HTML '/'
        return caches.match(event.request);
      })
  );
});
