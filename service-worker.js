importScripts('https://storage.googleapis.com/workbox-cdn/releases/5.1.2/workbox-sw.js');

if (workbox) {
    console.log('Workbox loaded');
    workbox.setConfig({
        debug: false
    });

    workbox.core.setCacheNameDetails({
        prefix: 'pwa'
    });

    // cache strategi untuk static assets
    workbox.routing.registerRoute(
        /\.(?:js|css|png|gif|jpg|jpeg|svg|woff2|woff)$/,
        new workbox.strategies.CacheFirst({
            cacheName: 'static-cache'
        })
    );

    // cache strategi untuk API requests
    workbox.routing.registerRoute(
        /api/,
        new workbox.strategies.NetworkFirst({
            cacheName: 'api-cache'
        })
    );
} else {
    console.log('Workbox not loaded');
}
