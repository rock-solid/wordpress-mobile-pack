/**
 * Welcome to your Workbox-powered service worker!
 *
 * You'll need to register this file in your web app and you should
 * disable HTTP caching for this file too.
 * See https://goo.gl/nhQhGp
 *
 * The rest of the code is auto-generated. Please don't update this file
 * directly; instead, make changes to your Workbox build configuration
 * and re-run your build process.
 * See https://goo.gl/2aRDsh
 */

importScripts("https://pwa-cdn.baobabsuite.com/static/workbox/workbox-v3.6.3/workbox-sw.js", "https://pwa-cdn.baobabsuite.com/static/workbox/workbox-v3.6.3/workbox-google-analytics.prod.js");
  //"/static/workbox/workbox-v3.4.1/workbox-google-analytics.prod.js"

workbox.skipWaiting();
workbox.clientsClaim();

self.__precacheManifest = [].concat(self.__precacheManifest || []);
workbox.precaching.suppressWarnings();
workbox.precaching.precacheAndRoute(self.__precacheManifest, {});

workbox.routing.registerRoute(/^https?.*/, workbox.strategies.networkFirst(), 'GET');
workbox.googleAnalytics.initialize();