self.addEventListener('install', (event) => {
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  event.waitUntil(clients.claim());
});

self.addEventListener('fetch', (event) => {
  // Network-only or standard fetching without local storage of files to device as requested
  event.respondWith(fetch(event.request));
});
