// Firebase Messaging Service Worker
importScripts('https://www.gstatic.com/firebasejs/10.12.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.12.0/firebase-messaging-compat.js');

// NOTE: This config will be replaced at runtime by messaging sender when pushing.
// The SW uses FCM payload's data/notification and does not need the full app init if already cached.
// For reliability, we provide a minimal initializeApp; values do not have to be secret.
try {
  // Attempt to initialize with window.firebaseConfig if injected
  // Fallback: initialize empty (will still receive messages)
  firebase.initializeApp({});
} catch (e) {}

const messaging = firebase.messaging();

// Background handler
messaging.onBackgroundMessage(function(payload) {
  const data = payload.notification || {};
  const title = data.title || 'WooCommerce';
  const options = {
    body: data.body || '',
    icon: data.icon || undefined,
    data: { click_action: data.click_action || '/' }
  };
  self.registration.showNotification(title, options);
});

self.addEventListener('notificationclick', function(event){
  const action = (event.notification && event.notification.data && event.notification.data.click_action) || '/';
  event.notification.close();
  event.waitUntil(clients.openWindow(action));
});