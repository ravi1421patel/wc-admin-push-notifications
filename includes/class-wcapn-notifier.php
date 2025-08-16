<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class WCAPN_Notifier {

    public static function init() {
        // placeholder if needed later
    }

    public static function send_push( $title, $body, $url = '' ) {
        $serverKey = trim( (string) get_option( 'wcapn_fcm_server_key' ) );
        if ( empty( $serverKey ) ) return;

        // Get all admins + shop managers
        $recipients = get_users( [ 'role__in' => ['administrator', 'shop_manager'] ] );
        if ( empty( $recipients ) ) return;

        foreach ( $recipients as $user ) {
            $token = get_user_meta( $user->ID, 'wcapn_fcm_token', true );
            if ( empty( $token ) ) continue;

            $payload = [
                "to" => $token,
                "notification" => [
                    "title" => wp_strip_all_tags( $title ),
                    "body"  => wp_strip_all_tags( $body ),
                    "click_action" => $url ?: admin_url(),
                    "icon" => WCAPN_URL . "assets/icon.png"
                ]
            ];

            $args = [
                'headers' => [
                    'Authorization' => 'key=' . $serverKey,
                    'Content-Type'  => 'application/json'
                ],
                'body'    => wp_json_encode( $payload ),
                'timeout' => 15,
                'method'  => 'POST'
            ];

            wp_remote_post( 'https://fcm.googleapis.com/fcm/send', $args );
        }
    }
}
