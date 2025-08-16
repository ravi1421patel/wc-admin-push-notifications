# WC Admin Push Notifications

A WordPress plugin that enables real-time push notifications for WooCommerce store admins. This helps administrators get notified instantly about new orders, low stock alerts, and other store activities via Firebase Cloud Messaging (FCM).

---

## 🚀 Features
- Real-time push notifications for WooCommerce Admin.
- Notifications for:
  - New Orders
  - Order Status Updates
  - Low Stock Alerts
- Firebase Cloud Messaging (FCM) integration.
- Simple setup via plugin settings page in the WordPress Admin Dashboard.
- Lightweight and fast.

---

## 📦 Installation

1. Download or clone this repository.
2. Upload the plugin folder `wc-admin-push-notifications` to the `/wp-content/plugins/` directory.
3. Activate the plugin through the **Plugins** menu in WordPress.
4. Go to **Settings > WC Push Notifications** to configure.

---

## ⚙️ Configuration

This plugin requires Firebase setup to work with push notifications.

### Step 1: Create a Firebase Project
1. Go to [Firebase Console](https://console.firebase.google.com/).
2. Click **Add Project** and create a new project.
3. Once created, go to **Project Settings > Cloud Messaging**.

### Step 2: Get Your Firebase Credentials
1. In **Cloud Messaging**, locate your:
   - **Server Key**
   - **Sender ID**
2. Copy these values.

### Step 3: Configure Plugin Settings
1. Navigate to **WordPress Admin > Settings > WC Push Notifications**.
2. Enter your **Server Key** and **Sender ID**.
3. Save settings.

### Step 4: Add Firebase to Your App/Browser
- For browser push: Use Firebase SDK in your admin panel or custom PWA script.
- For mobile apps: Integrate Firebase SDK into your mobile client.

---

## 🔔 Notifications Supported
- **New Order Placed** – Instant alert when a customer places a new order.
- **Order Status Changed** – Get notified when the order status changes (Processing, Completed, etc.).
- **Low Stock Warning** – Be alerted when product stock goes below threshold.

---

## 📂 Folder Structure
wc-admin-push-notifications/
│── admin/
│ └── admin-settings.php
│── assets/
│ └── js/admin.js
│── includes/
│ └── class-wc-push-notification.php
│── wc-admin-push-notifications.php
│── readme.md

---

## 🛠️ Development Notes
- Uses **Firebase Cloud Messaging (FCM)** for push delivery.
- Built with **WordPress Settings API** for storing credentials.
- JavaScript (no jQuery dependency) for admin panel interactivity.

---

## 🤝 Contributing
1. Fork the repo
2. Create a new branch (`feature/new-feature`)
3. Commit your changes
4. Push and create a Pull Request

---

## 📜 License
This plugin is licensed under the **GPL-2.0+** license.

---

## 👨‍💻 Author
Developed by **Ravi Patel**  
GitHub: [ravi1421patel](https://github.com/ravi1421patel)