<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Safe options getter.
 */
function max_button_get_options() {
    $opts = get_option( 'max_button_settings' );
    return is_array( $opts ) ? $opts : array();
}

/**
 * Returns true if at least one channel is enabled and has a non-empty link.
 */
function max_button_enabled() {
    $o = max_button_get_options();

    $has_telegram  = ! empty( $o['enable_telegram'] )  && ! empty( $o['telegram_link'] );
    $has_max       = ! empty( $o['enable_max'] )       && ! empty( $o['max_link'] );
    $has_whatsapp  = ! empty( $o['enable_whatsapp'] )  && ! empty( $o['whatsapp_link'] );
    $has_messenger = ! empty( $o['enable_messenger'] ) && ! empty( $o['messenger_link'] );

    return ( $has_telegram || $has_max || $has_whatsapp || $has_messenger );
}