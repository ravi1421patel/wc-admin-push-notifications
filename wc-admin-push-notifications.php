<?php
/*
Plugin Name: WooCommerce Admin Push Notifications
Description: Sends browser push notifications to admins/managers for WooCommerce events like new orders and payment completion using Firebase Cloud Messaging (FCM).
Version: 1.0.0
Author: Casey (Ravi)
Text Domain: wc-admin-push-notifications
*/

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'WCAPN_FILE', __FILE__ );
define( 'WCAPN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WCAPN_URL', plugin_dir_url( __FILE__ ) );

require_once WCAPN_DIR . 'includes/class-wcapn-settings.php';
require_once WCAPN_DIR . 'includes/class-wcapn-notifier.php';
require_once WCAPN_DIR . 'includes/class-wcapn-hooks.php';

/**
 * Try to copy service worker to web root on activation for site-wide scope.
 */
register_activation_hook( __FILE__, function(){
    $src = WCAPN_DIR . 'firebase-messaging-sw.js';
    $dest = ABSPATH . 'firebase-messaging-sw.js';
    if ( file_exists( $src ) ) {
        // Try to copy if writable
        @copy( $src, $dest );
    }
});

add_action( 'plugins_loaded', function() {
    // WooCommerce check
    if ( ! class_exists( 'WooCommerce' ) ) {
        add_action( 'admin_notices', function(){
            echo '<div class="notice notice-error"><p><strong>WC Admin Push Notifications:</strong> WooCommerce is required.</p></div>';
        });
        return;
    }
    WCAPN_Settings::init();
    WCAPN_Notifier::init();
    WCAPN_Hooks::init();
});
