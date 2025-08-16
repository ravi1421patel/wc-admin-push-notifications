=== WooCommerce Admin Push Notifications ===
Contributors: casey
Tags: woocommerce, push, notifications, admin, firebase
Requires at least: 5.8
Tested up to: 6.6
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Send browser push notifications to Administrators and Shop Managers for WooCommerce events (new order, payment complete) using Firebase Cloud Messaging.

== Description ==
- Choose events (new order, payment completed).
- Works with Firebase Cloud Messaging (copy your Server Key, VAPID public key, and Web App config JSON).
- Saves admin FCM tokens when they allow notifications in the WP Admin.
- Test button to send a sample push.

== Installation ==
1. Upload the plugin folder to `/wp-content/plugins/` or install the ZIP from Plugins → Add New → Upload.
2. Activate the plugin.
3. Visit **Settings → WC Push Notifications** and paste:
   - FCM Server Key
   - VAPID Public Key
   - Firebase Web App Config JSON
4. While logged in as admin, accept the browser notification prompt in WP Admin (this saves your token).
5. Click **Send Test**.

== Notes ==
- On activation, the plugin attempts to copy `firebase-messaging-sw.js` to your site root for widest scope. If it fails, copy it manually from the plugin folder to your WordPress root (same folder as wp-config.php).

== Changelog ==
= 1.0.0 =
* Initial release.
