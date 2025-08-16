<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class WCAPN_Hooks {

    public static function init() {
        add_action( 'admin_enqueue_scripts', [ __CLASS__, 'enqueue_admin_scripts' ] );
        add_action( 'wp_ajax_wcapn_save_token', [ __CLASS__, 'save_fcm_token' ] );

        if ( get_option( 'wcapn_enable_new_order' ) ) {
            add_action( 'woocommerce_new_order', [ __CLASS__, 'on_new_order' ], 10, 1 );
        }
        if ( get_option( 'wcapn_enable_payment_complete' ) ) {
            add_action( 'woocommerce_order_status_completed', [ __CLASS__, 'on_payment_complete' ], 10, 1 );
        }
    }

    public static function enqueue_admin_scripts() {
        if ( current_user_can('manage_woocommerce') ) {
            // Use compat builds for simple namespaced API
            wp_enqueue_script( 'firebase-app', 'https://www.gstatic.com/firebasejs/10.12.0/firebase-app-compat.js', [], null, true );
            wp_enqueue_script( 'firebase-messaging', 'https://www.gstatic.com/firebasejs/10.12.0/firebase-messaging-compat.js', [], null, true );
            wp_enqueue_script( 'wcapn-frontend', WCAPN_URL . 'assets/js/wcapn-frontend.js', [ 'firebase-app', 'firebase-messaging', 'jquery' ], filemtime( WCAPN_DIR . 'assets/js/wcapn-frontend.js' ), true );

            $config = get_option('wcapn_firebase_config');
            $config_array = json_decode( $config, true );
            if ( ! is_array( $config_array ) ) $config_array = [];

            wp_localize_script( 'wcapn-frontend', 'wcapnData', [
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'firebaseConfig' => $config_array,
                'vapidKey' => (string) get_option('wcapn_vapid_key'),
                'userId' => get_current_user_id(),
                'swUrl'  => home_url('/firebase-messaging-sw.js'),
            ] );
        }
    }

    public static function save_fcm_token() {
        if ( ! current_user_can('manage_woocommerce') ) wp_die();
        $user_id = get_current_user_id();
        $token = isset($_POST['token']) ? sanitize_text_field(wp_unslash($_POST['token'])) : '';
        if ( $user_id && $token ) {
            update_user_meta( $user_id, 'wcapn_fcm_token', $token );
        }
        wp_die();
    }

    public static function on_new_order( $order_id ) {
        $order = wc_get_order( $order_id );
        if ( ! $order ) return;
        $title = sprintf( __('New Order #%d','wc-admin-push-notifications'), $order_id );
        $body  = sprintf( __('%s — %s','wc-admin-push-notifications'),
            $order->get_formatted_billing_full_name(),
            $order->get_formatted_order_total()
        );
        WCAPN_Notifier::send_push( $title, $body, admin_url( "post.php?post={$order_id}&action=edit" ) );
    }

    public static function on_payment_complete( $order_id ) {
        $order = wc_get_order( $order_id );
        if ( ! $order ) return;
        $title = sprintf( __('Payment Complete #%d','wc-admin-push-notifications'), $order_id );
        $body  = sprintf( __('%s — %s','wc-admin-push-notifications'),
            $order->get_formatted_billing_full_name(),
            $order->get_formatted_order_total()
        );
        WCAPN_Notifier::send_push( $title, $body, admin_url( "post.php?post={$order_id}&action=edit" ) );
    }
}
