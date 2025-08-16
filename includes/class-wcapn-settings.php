<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class WCAPN_Settings {

    public static function init() {
        add_action( 'admin_menu', [ __CLASS__, 'add_menu' ] );
        add_action( 'admin_init', [ __CLASS__, 'register_settings' ] );
        add_action( 'admin_post_wcapn_send_test', [ __CLASS__, 'handle_send_test' ] );
    }

    public static function add_menu() {
        add_options_page(
            __('WC Admin Push Notifications','wc-admin-push-notifications'),
            __('WC Push Notifications','wc-admin-push-notifications'),
            'manage_woocommerce',
            'wcapn-settings',
            [ __CLASS__, 'settings_page' ]
        );
    }

    public static function register_settings() {
        register_setting( 'wcapn_options_group', 'wcapn_fcm_server_key' );
        register_setting( 'wcapn_options_group', 'wcapn_vapid_key' );
        register_setting( 'wcapn_options_group', 'wcapn_firebase_config' );
        register_setting( 'wcapn_options_group', 'wcapn_enable_new_order' );
        register_setting( 'wcapn_options_group', 'wcapn_enable_payment_complete' );
    }

    public static function settings_page() {
        $firebase_json = get_option('wcapn_firebase_config');
        ?>
        <div class="wrap">
            <h1><?php _e('WooCommerce Admin Push Notifications', 'wc-admin-push-notifications'); ?></h1>
            <p><?php _e('Configure Firebase and choose which WooCommerce events will trigger push notifications to admins/shop managers.', 'wc-admin-push-notifications'); ?></p>

            <form method="post" action="options.php">
                <?php settings_fields( 'wcapn_options_group' ); ?>
                <?php do_settings_sections( 'wcapn_options_group' ); ?>

                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><label for="wcapn_fcm_server_key"><?php _e('FCM Server Key','wc-admin-push-notifications'); ?></label></th>
                        <td><input type="text" id="wcapn_fcm_server_key" name="wcapn_fcm_server_key" value="<?php echo esc_attr( get_option('wcapn_fcm_server_key') ); ?>" size="70" />
                        <p class="description"><?php _e('Firebase Cloud Messaging Server key (Project settings → Cloud Messaging).', 'wc-admin-push-notifications'); ?></p></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wcapn_vapid_key"><?php _e('Web Push VAPID Key (Public)','wc-admin-push-notifications'); ?></label></th>
                        <td><input type="text" id="wcapn_vapid_key" name="wcapn_vapid_key" value="<?php echo esc_attr( get_option('wcapn_vapid_key') ); ?>" size="70" />
                        <p class="description"><?php _e('Paste your Web Push certificate key (public). Used by messaging.getToken().', 'wc-admin-push-notifications'); ?></p></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="wcapn_firebase_config"><?php _e('Firebase Config JSON','wc-admin-push-notifications'); ?></label></th>
                        <td>
                            <textarea id="wcapn_firebase_config" name="wcapn_firebase_config" rows="8" cols="70" placeholder='{"apiKey":"","authDomain":"","projectId":"","storageBucket":"","messagingSenderId":"","appId":""}'><?php echo esc_textarea( $firebase_json ); ?></textarea>
                            <p class="description"><?php _e('Paste your Firebase web app config JSON.', 'wc-admin-push-notifications'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Enable New Order Notification','wc-admin-push-notifications'); ?></th>
                        <td><label><input type="checkbox" name="wcapn_enable_new_order" value="1" <?php checked( get_option('wcapn_enable_new_order'), 1 ); ?> /> <?php _e('Enable','wc-admin-push-notifications'); ?></label></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Enable Payment Complete Notification','wc-admin-push-notifications'); ?></th>
                        <td><label><input type="checkbox" name="wcapn_enable_payment_complete" value="1" <?php checked( get_option('wcapn_enable_payment_complete'), 1 ); ?> /> <?php _e('Enable','wc-admin-push-notifications'); ?></label></td>
                    </tr>
                </table>

                <?php submit_button(); ?>
            </form>

            <hr/>
            <h2><?php _e('Send Test Notification','wc-admin-push-notifications'); ?></h2>
            <p><?php _e('Make sure you have allowed notifications in your browser (while logged in as admin/shop manager) so your token is saved. Then send a test notification:', 'wc-admin-push-notifications'); ?></p>
            <form method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">
                <input type="hidden" name="action" value="wcapn_send_test" />
                <?php wp_nonce_field('wcapn_send_test_nonce'); ?>
                <p><label><?php _e('Title','wc-admin-push-notifications'); ?> <input type="text" name="title" value="Test push from WC" size="40"></label></p>
                <p><label><?php _e('Message','wc-admin-push-notifications'); ?> <input type="text" name="body" value="It works! 🎉" size="60"></label></p>
                <p><button class="button button-primary"><?php _e('Send Test','wc-admin-push-notifications'); ?></button></p>
            </form>
        </div>
        <?php
    }

    public static function handle_send_test() {
        if ( ! current_user_can('manage_woocommerce') || ! check_admin_referer('wcapn_send_test_nonce') ) {
            wp_die( __('Permission denied','wc-admin-push-notifications') );
        }
        $title = sanitize_text_field($_POST['title'] ?? 'Test');
        $body  = sanitize_text_field($_POST['body'] ?? 'Hello');
        WCAPN_Notifier::send_push( $title, $body, admin_url() );
        wp_redirect( wp_get_referer() ?: admin_url('options-general.php?page=wcapn-settings') );
        exit;
    }
}
