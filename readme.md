WC Admin Push Notifications

A WordPress plugin that enables real-time push notifications for WooCommerce store administrators using Firebase Cloud Messaging (FCM).

🚀 Features

Instant push notifications for WooCommerce events (new order, low stock, etc.).

Firebase Cloud Messaging (FCM) integration.

Works in background and foreground.

Easy configuration from WordPress admin.

📦 Installation

Download or clone the plugin into your WordPress wp-content/plugins/ directory:

git clone https://github.com/yourusername/wc-admin-push-notifications.git


Activate the plugin from WordPress Admin > Plugins.

🔑 Firebase Setup

Go to Firebase Console.

Create a new Firebase project.

Navigate to Project Settings > General > Your apps.

Add a Web App.

Copy the apiKey, authDomain, projectId, storageBucket, messagingSenderId, and appId.

In Firebase, go to Project Settings > Cloud Messaging and note down the Server Key.

⚙️ Plugin Configuration

In WordPress, go to Settings > WC Push Notifications.

Enter your Firebase project credentials:

API Key

Auth Domain

Project ID

Messaging Sender ID

App ID

VAPID Key (optional)

Save settings.

📂 Firebase Service Worker Setup

⚠️ Important: Firebase requires a firebase-messaging-sw.js file to be placed in the root directory of your website.

Create a file in your WordPress root (same place as wp-config.php):

/public_html/
  ├── wp-admin/
  ├── wp-content/
  ├── wp-includes/
  ├── firebase-messaging-sw.js   ✅
  └── wp-config.php


Add the following code inside firebase-messaging-sw.js:

// Import Firebase
importScripts('https://www.gstatic.com/firebasejs/9.6.1/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/9.6.1/firebase-messaging.js');

// Your Firebase config (must match plugin settings)
firebase.initializeApp({
  apiKey: "YOUR_API_KEY",
  authDomain: "YOUR_PROJECT_ID.firebaseapp.com",
  projectId: "YOUR_PROJECT_ID",
  storageBucket: "YOUR_PROJECT_ID.appspot.com",
  messagingSenderId: "YOUR_MESSAGING_SENDER_ID",
  appId: "YOUR_APP_ID",
});

// Retrieve messaging instance
const messaging = firebase.messaging();

messaging.onBackgroundMessage(function(payload) {
  console.log('[firebase-messaging-sw.js] Received background message ', payload);

  const notificationTitle = payload.notification.title;
  const notificationOptions = {
    body: payload.notification.body,
    icon: '/wp-content/plugins/wc-admin-push-notifications/assets/icon.png'
  };

  self.registration.showNotification(notificationTitle, notificationOptions);
});

🔔 Usage

After setup, your WooCommerce store will automatically send push notifications to admins when:

New orders are placed.

Stock is low.

Order status changes.

📸 Screenshots

(Add your screenshots in /assets and reference them here)
Example:


🤝 Contributing

Pull requests are welcome! Please open an issue first to discuss major changes.

📄 License

This project is licensed under the MIT License.
