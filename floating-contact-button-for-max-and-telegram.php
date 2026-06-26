<?php
/**
 * Plugin Name: Floating Contact Button for MAX and Telegram
 * Plugin URI:  https://cryptolamer.ru/support-the-plugin-floating-contact-button-for-max-and-telegram-eng/
 * Description: Floating Contact Button (Telegram, WhatsApp, Facebook Messenger, MAX). From Russia with love.
 * Version:     1.1.11
 * Author:      alexwp12
 * License:     GPLv2 or later
 * Text Domain: floating-contact-button-for-max-and-telegram
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'MAX_BUTTON_PLUGIN_FILE', __FILE__ );

// add settings
require_once plugin_dir_path( __FILE__ ) . 'includes/helpers.php';

// frontend (styles + render)
require_once plugin_dir_path( __FILE__ ) . 'includes/frontend.php';


// admin
require_once plugin_dir_path( __FILE__ ) . 'includes/admin.php';

/**
 * Add Settings link on Plugins page.
 */
add_filter(
    'plugin_action_links_' . plugin_basename( MAX_BUTTON_PLUGIN_FILE ),
    function ( $links ) {
        $settings_link = '<a href="' . admin_url( 'options-general.php?page=max-button' ) . '">' .
            esc_html__( 'Settings', 'floating-contact-button-for-max-and-telegram' ) .
        '</a>';

        array_unshift( $links, $settings_link );
        return $links;
    }
);

add_filter(
    'plugin_row_meta',
    function ( $links, $file ) {

        if ( $file !== plugin_basename( MAX_BUTTON_PLUGIN_FILE ) ) {
            return $links;
        }

        // Link to WP.org reviews
        $rating_link = '<a href="https://wordpress.org/support/plugin/floating-contact-button-for-max-and-telegram/reviews/" target="_blank" rel="noopener noreferrer">Plugin Rating</a>';

        $locale = get_locale();
        $is_ru  = ( strpos( $locale, 'ru_' ) === 0 );

        $pro_url = $is_ru
            ? 'https://cryptolamer.ru/airtheme-contact-button-pro/'
            : 'https://cryptolamer.ru/airtheme-contact-button-pro-eng/';

        // Link to PRO addon
        $pro_link = '<a href="' . esc_url( $pro_url ) . '" target="_blank" rel="noopener noreferrer">Contact Button PRO</a>';

        $links[] = $rating_link;
        $links[] = $pro_link;

        return $links;
    },
    10,
    2
);
