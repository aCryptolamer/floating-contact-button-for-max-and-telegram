<?php
// includes/frontend.php — floating contact buttons (updated with mother button)

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enqueue frontend styles.
 */
function max_button_enqueue_front() {
    if ( is_admin() || ! function_exists( 'max_button_enabled' ) || ! max_button_enabled() ) {
        return;
    }

    $rel = 'assets/css/style.css';
    $abs = plugin_dir_path( MAX_BUTTON_PLUGIN_FILE ) . $rel;
    $ver = file_exists( $abs ) ? filemtime( $abs ) : '1.0.1';

    wp_enqueue_style(
        'max-button-front',
        plugins_url( $rel, MAX_BUTTON_PLUGIN_FILE ),
        array(),
        $ver
    );
    
    // JS
    wp_enqueue_script(
        'max-button-front-js',
        plugins_url( 'assets/js/front.js', MAX_BUTTON_PLUGIN_FILE ),
        array(),
        $ver,
        true
    );
    
}

add_action( 'wp_enqueue_scripts', 'max_button_enqueue_front', 20 );

/**
 * Render floating buttons.
 */
function max_button_footer_render() {
    if ( ! max_button_enabled() ) {
        return;
    }

    $options = max_button_get_options();
// ----------------------
// Visibility logic
// ----------------------
$show_desktop = isset($options['show_desktop']) ? (int)$options['show_desktop'] : 1;
$show_mobile  = isset($options['show_mobile']) ? (int)$options['show_mobile'] : 1;

$classes = array();

if (!$show_desktop && $show_mobile) {
    $classes[] = 'max-hide-desktop';
} elseif ($show_desktop && !$show_mobile) {
    $classes[] = 'max-hide-mobile';
} elseif (!$show_desktop && !$show_mobile) {
    $classes[] = 'max-hide-all';
}

$wrapper_class = 'max-button-scope';
if ( ! empty( $classes ) ) {
    $wrapper_class .= ' ' . implode( ' ', $classes );
}

// ----------------------
// Button position styles
// ----------------------
$style = '';

if ( ! empty( $options['offset_top'] ) ) {
    $style .= 'top:' . intval( $options['offset_top'] ) . 'px;';
}

if ( ! empty( $options['offset_right'] ) ) {
    $style .= 'right:' . intval( $options['offset_right'] ) . 'px;';
}

if ( ! empty( $options['offset_bottom'] ) ) {
    $style .= 'bottom:' . intval( $options['offset_bottom'] ) . 'px;';
}

// ————————————————
// We check if there is at least one active button
// (base + extensions)
// ————————————————
$has_any = false;

// base buttons
$has_any = $has_any || ( ! empty( $options['enable_telegram'] ) && ! empty( $options['telegram_link'] ) );
$has_any = $has_any || ( ! empty( $options['enable_max'] ) && ! empty( $options['max_link'] ) );
$has_any = $has_any || ( ! empty( $options['enable_whatsapp'] ) && ! empty( $options['whatsapp_link'] ) );
$has_any = $has_any || ( ! empty( $options['enable_messenger'] ) && ! empty( $options['messenger_link'] ) );

// extensions (PRO, addons)
$has_any = apply_filters( 'max_button_has_any', $has_any, $options );

if ( ! $has_any ) {
    return;
}

// ————————————————
// Start of container
// ————————————————
echo "\n<!-- Wordpress plugin: Floating Contact Button for MAX and Telegram -->\n";

echo '<div id="max-button-wrapper" class="' . esc_attr( $wrapper_class ) . '"';
if ( $style ) {
    echo ' style="' . esc_attr( $style ) . '"';
}
echo '>';

// ————————————————
// Common column (SINGLE flex + gap)
// ————————————————
echo '<div class="max-button-column">';

// ————————————————
// Child Button Container - OPEN TOP
// ————————————————
echo '<div class="max-button-bubble with-bubble">';

$order = isset( $options['buttons_order'] )
    ? explode( ',', $options['buttons_order'] )
    : array( 'telegram', 'whatsapp', 'messenger', 'max' );

$buttons = apply_filters(
    'max_button_front_buttons',
    array(

    'telegram' => function () use ( $options ) {
        if ( empty( $options['enable_telegram'] ) || empty( $options['telegram_link'] ) ) return;
        echo '<a href="' . esc_url( $options['telegram_link'] ) . '" target="_blank" rel="noopener noreferrer" class="max-button-item tg-icon">
                <img src="' . esc_url( plugins_url( 'assets/img/telegram.png', MAX_BUTTON_PLUGIN_FILE ) ) . '" alt="Telegram">
              </a>';
    },

    'whatsapp' => function () use ( $options ) {
        if ( empty( $options['enable_whatsapp'] ) || empty( $options['whatsapp_link'] ) ) return;
        echo '<a href="' . esc_url( $options['whatsapp_link'] ) . '" target="_blank" rel="noopener noreferrer" class="max-button-item wa-icon">
                <img src="' . esc_url( plugins_url( 'assets/img/whatsapp.png', MAX_BUTTON_PLUGIN_FILE ) ) . '" alt="WhatsApp">
              </a>';
    },

    'max' => function () use ( $options ) {
        if ( empty( $options['enable_max'] ) || empty( $options['max_link'] ) ) return;
        echo '<a href="' . esc_url( $options['max_link'] ) . '" target="_blank" rel="noopener noreferrer" class="max-button-item max-icon">
                <img src="' . esc_url( plugins_url( 'assets/img/max.png', MAX_BUTTON_PLUGIN_FILE ) ) . '" alt="MAX">
              </a>';
    },
    'messenger' => function () use ( $options ) {
    if ( empty( $options['enable_messenger'] ) || empty( $options['messenger_link'] ) ) return;
    echo '<a href="' . esc_url( $options['messenger_link'] ) . '" target="_blank" rel="noopener noreferrer" class="max-button-item messenger-icon">
            <img src="' . esc_url( plugins_url( 'assets/img/messenger.png', MAX_BUTTON_PLUGIN_FILE ) ) . '" alt="Messenger">
          </a>';
    },

        ),
    $options
);

foreach ( $order as $key ) {
    if ( isset( $buttons[ $key ] ) ) {
        $buttons[ $key ]();
    }
}




echo '</div>'; // end of the bubble panel

// ————————————————
// Mother button - IN THE SAME COLUMN (with indicator toggle)
// ————————————————
$indicator_enabled = ! empty( $options['indicator_toggle'] );

$indicator_class = ! empty( $indicator_enabled ) ? ' indicator-active' : '';

// --- hint ---
$hint_enabled = ! empty( $options['enable_hint'] );
$hint_text    = ! empty( $options['hint_text'] ) ? $options['hint_text'] : 'Hi!';

echo '<div class="max-button-mother-wrapper">';

// hint bubble
if ( $hint_enabled ) {
    echo '<div class="max-button-hint">' . esc_html( $hint_text ) . '</div>';
}

// mother button
echo '<div class="max-button-item max-button-mother' . esc_attr( $indicator_class ) . '">
        <img src="' . esc_url( plugins_url( 'assets/img/mother.png', MAX_BUTTON_PLUGIN_FILE ) ) . '" alt="Menu">
      </div>';

echo '</div>';
      
echo '</div>'; // end of max-button-column
echo '</div>'; // end of wrapper
}
add_action( 'wp_footer', 'max_button_footer_render', 99 );
