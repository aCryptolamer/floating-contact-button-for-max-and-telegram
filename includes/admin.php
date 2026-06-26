<?php
// includes/admin.php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add settings page.
 */
function max_button_add_menu() {
    add_options_page(
        __( 'Floating Contact Button Settings', 'floating-contact-button-for-max-and-telegram' ),
        __( 'Contact Button', 'floating-contact-button-for-max-and-telegram' ),
        'manage_options',
        'max-button',
        'max_button_render_settings'
    );
}
add_action( 'admin_menu', 'max_button_add_menu' );

/**
 * Enqueue admin assets.
 */
function max_button_admin_assets( $hook ) {
    if ( $hook !== 'settings_page_max-button' ) {
        return;
    }

    wp_enqueue_style(
        'max-button-admin',
        plugin_dir_url( __FILE__ ) . '../assets/css/admin.css',
        array(),
        '1.1'
    );

    wp_enqueue_script(
        'max-button-admin-sortable',
        plugin_dir_url( __FILE__ ) . '../assets/js/admin-sortable.js',
        array( 'jquery', 'jquery-ui-sortable' ),
        '1.0',
        true
    );
}
add_action( 'admin_enqueue_scripts', 'max_button_admin_assets' );

/**
 * Render settings page.
 */
function max_button_render_settings() {

    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $options = get_option( 'max_button_settings', array() );
    $options = is_array( $options ) ? $options : array();
    ?>

    <div class="wrap max-button-admin">

        <h1><?php esc_html_e( 'Floating Contact Button Settings', 'floating-contact-button-for-max-and-telegram' ); ?></h1>

        <form method="post" action="options.php">
            <?php settings_fields( 'max_button_settings_group' ); ?>

            <input type="hidden"
                   id="max_buttons_order"
                   name="max_button_settings[buttons_order]"
                   value="<?php echo esc_attr( $options['buttons_order'] ?? 'telegram,whatsapp,max,pro1,pro2,pro3' ); ?>">

            <h2><?php esc_html_e( 'Buttons', 'floating-contact-button-for-max-and-telegram' ); ?></h2>

            <?php
            $default_order = array( 'telegram', 'whatsapp', 'messenger', 'max', 'pro1', 'pro2', 'pro3' );

            $order = isset( $options['buttons_order'] )
                ? explode( ',', $options['buttons_order'] )
                : $default_order;

            $order = array_values(
                array_unique(
                    array_merge( $order, $default_order )
                )
            );

            $locale = get_locale();
            $is_ru  = ( strpos( $locale, 'ru_' ) === 0 );

            $pro_url = $is_ru
                ? 'https://cryptolamer.ru/airtheme-contact-button-pro/'
                : 'https://cryptolamer.ru/airtheme-contact-button-pro-eng/';

            $cards = apply_filters(
                'max_button_admin_cards',
                array(

                    'telegram' => '
                    <div class="max-button-card" data-button="telegram">
                        <details>
                            <summary>Telegram</summary>
                            <div class="max-button-card-content">
                                <label>
                                    <input type="checkbox" name="max_button_settings[enable_telegram]" value="1" ' . checked( 1, $options['enable_telegram'] ?? 0, false ) . ' />
                                    ' . esc_html__( 'Enable', 'floating-contact-button-for-max-and-telegram' ) . '
                                </label>
                                <p>
                                    <input type="text" class="regular-text"
                                           name="max_button_settings[telegram_link]"
                                           value="' . esc_attr( $options['telegram_link'] ?? '' ) . '" />
                                </p>
                            </div>
                        </details>
                    </div>',

                    'whatsapp' => '
                    <div class="max-button-card" data-button="whatsapp">
                        <details>
                            <summary>WhatsApp</summary>
                            <div class="max-button-card-content">
                                <label>
                                    <input type="checkbox" name="max_button_settings[enable_whatsapp]" value="1" ' . checked( 1, $options['enable_whatsapp'] ?? 0, false ) . ' />
                                    ' . esc_html__( 'Enable', 'floating-contact-button-for-max-and-telegram' ) . '
                                </label>
                                <p>
                                    <input type="text" class="regular-text"
                                           name="max_button_settings[whatsapp_link]"
                                           value="' . esc_attr( $options['whatsapp_link'] ?? '' ) . '" />
                                </p>
                            </div>
                        </details>
                    </div>',

                    'messenger' => '
<div class="max-button-card" data-button="messenger">
    <details>
        <summary>Facebook Messenger</summary>
        <div class="max-button-card-content">
            <label>
                <input type="checkbox" name="max_button_settings[enable_messenger]" value="1" ' . checked( 1, $options['enable_messenger'] ?? 0, false ) . ' />
                ' . esc_html__( 'Enable', 'floating-contact-button-for-max-and-telegram' ) . '
            </label>
            <p>
                <input type="text" class="regular-text"
                       name="max_button_settings[messenger_link]"
                       value="' . esc_attr( $options['messenger_link'] ?? '' ) . '"
                       placeholder="https://m.me/username" />
            </p>
        </div>
    </details>
</div>',

                    'max' => '
                    <div class="max-button-card" data-button="max">
                        <details>
                            <summary>MAX</summary>
                            <div class="max-button-card-content">
                                <label>
                                    <input type="checkbox" name="max_button_settings[enable_max]" value="1" ' . checked( 1, $options['enable_max'] ?? 0, false ) . ' />
                                    ' . esc_html__( 'Enable', 'floating-contact-button-for-max-and-telegram' ) . '
                                </label>
                                <p>
                                    <input type="text" class="regular-text"
                                           name="max_button_settings[max_link]"
                                           value="' . esc_attr( $options['max_link'] ?? '' ) . '" />
                                </p>
                            </div>
                        </details>
                    </div>',

                    'pro1' => '
                    <div class="max-button-card max-pro-anchor" data-button="pro1">
                        <details open>
        <summary>' . esc_html__( 'Add Any Custom Button (PRO)', 'floating-contact-button-for-max-and-telegram' ) . '</summary>
        <div class="max-button-card-content">
             <a href="' . esc_url( $pro_url ) . '"
               target="_blank"
               rel="noopener noreferrer">
                ' . esc_html__( 'Add your buttons now →', 'floating-contact-button-for-max-and-telegram' ) . '
            </a></div>
                        </details>
                    </div>',
                ),
                $options
            );
            ?>

            <div id="max-buttons-sortable" class="max-buttons-list">
                <?php
                foreach ( $order as $key ) {
                    if ( isset( $cards[ $key ] ) ) {
                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Card HTML is composed from trusted plugin callbacks.
                        echo $cards[ $key ];
                    }
                }
                ?>
            </div>

            <!-- ================= HINT ================= -->

<h2><?php esc_html_e( 'Chat hint', 'floating-contact-button-for-max-and-telegram' ); ?></h2>

<p>
    <label>
        <input type="checkbox"
               name="max_button_settings[enable_hint]"
               value="1"
            <?php checked( 1, $options['enable_hint'] ?? 0 ); ?> />
        <?php esc_html_e( 'Enable hint text near main button', 'floating-contact-button-for-max-and-telegram' ); ?>
    </label>
</p>

<p>
    <input type="text"
           class="regular-text"
           name="max_button_settings[hint_text]"
           value="<?php echo esc_attr( $options['hint_text'] ?? 'Hi!' ); ?>"
           placeholder="Hi!" />
</p>

            <!-- ================= INDICATOR ================= -->

            <h2><?php esc_html_e( 'Indicator color', 'floating-contact-button-for-max-and-telegram' ); ?></h2>

            <div class="max-indicator-standalone">
                <label class="max-indicator-toggle">
                    <input type="checkbox"
                           name="max_button_settings[indicator_toggle]"
                           value="1"
                        <?php checked( 1, $options['indicator_toggle'] ?? 0 ); ?> />
                    <span class="indicator-ui">
                        <span class="dot red"></span>
                        <span class="dot green"></span>
                    </span>
                </label>
            </div>

            <!-- ================= POSITION ================= -->

            <h2><?php esc_html_e( 'Button position (px)', 'floating-contact-button-for-max-and-telegram' ); ?></h2>

            <p>
                <label>
                    <?php esc_html_e( 'Top', 'floating-contact-button-for-max-and-telegram' ); ?>:
                    <input type="number"
                           name="max_button_settings[offset_top]"
                           value="<?php echo esc_attr( $options['offset_top'] ?? '' ); ?>" />
                </label>
            </p>

            <p>
                <label>
                    <?php esc_html_e( 'Right', 'floating-contact-button-for-max-and-telegram' ); ?>:
                    <input type="number"
                           name="max_button_settings[offset_right]"
                           value="<?php echo esc_attr( $options['offset_right'] ?? '' ); ?>" />
                </label>
            </p>

            <p>
                <label>
                    <?php esc_html_e( 'Bottom', 'floating-contact-button-for-max-and-telegram' ); ?>:
                    <input type="number"
                           name="max_button_settings[offset_bottom]"
                           value="<?php echo esc_attr( $options['offset_bottom'] ?? '' ); ?>" />
                </label>
            </p>

            <!-- ================= VISIBILITY ================= -->

<h2><?php esc_html_e( 'Visibility', 'floating-contact-button-for-max-and-telegram' ); ?></h2>

<p>
    <label>
        <input type="hidden" name="max_button_settings[show_desktop]" value="0" />
        <input type="checkbox"
               name="max_button_settings[show_desktop]"
               value="1"
            <?php checked( 1, $options['show_desktop'] ?? 0 ); ?> />
        <?php esc_html_e( 'Show on Desktop', 'floating-contact-button-for-max-and-telegram' ); ?>
    </label>
</p>

<p>
    <label>
        <input type="hidden" name="max_button_settings[show_mobile]" value="0" />
        <input type="checkbox"
               name="max_button_settings[show_mobile]"
               value="1"
            <?php checked( 1, $options['show_mobile'] ?? 0 ); ?> />
        <?php esc_html_e( 'Show on Mobile', 'floating-contact-button-for-max-and-telegram' ); ?>
    </label>
</p>

            <!-- ================= RECOMMENDED THEME ================= -->

            <h2><?php esc_html_e( 'Recommended Theme', 'floating-contact-button-for-max-and-telegram' ); ?></h2>

            <p>
                <?php esc_html_e( 'AirTheme is a modern, fast and free WordPress theme with a convenient Customizer.', 'floating-contact-button-for-max-and-telegram' ); ?>
            </p>

            <p>
                <a href="https://wordpress.org/themes/airtheme/"
                   target="_blank"
                   rel="noopener noreferrer">
                    <?php esc_html_e( 'View and download AirTheme on WordPress.org →', 'floating-contact-button-for-max-and-telegram' ); ?>
                </a>
            </p>

            <?php submit_button(); ?>

        </form>
        <?php
$locale = get_locale();

$donate_url = ( strpos( $locale, 'ru_' ) === 0 )
    ? 'https://cryptolamer.ru/support_wp_plugin-floating-contact-button-for-max-and-telegram/'
    : 'https://buymeacoffee.com/cryptolamer';
?>

<div class="max-plugin-support">
    <p>
        ❤️ <?php esc_html_e( 'If the plugin was helpful, you can support its development:', 'floating-contact-button-for-max-and-telegram' ); ?>
    </p>
    <a href="<?php echo esc_url( $donate_url ); ?>"
       target="_blank"
       rel="noopener noreferrer"
       class="button button-secondary">
        ☕ <?php esc_html_e( 'Buy me a coffee', 'floating-contact-button-for-max-and-telegram' ); ?>
    </a>
</div>
    </div>
<?php
}

/**
 * Register settings.
 */
function max_button_register_settings() {
    register_setting(
        'max_button_settings_group',
        'max_button_settings',
        'max_button_sanitize_settings'
    );
}
add_action( 'admin_init', 'max_button_register_settings' );

/**
 * Sanitize settings.
 */
function max_button_sanitize_settings( $input ) {

    $output = array();

    // --- enable flags ---
    $output['enable_telegram'] = isset( $input['enable_telegram'] ) ? 1 : 0;
    $output['enable_whatsapp'] = isset( $input['enable_whatsapp'] ) ? 1 : 0;
    $output['enable_max']       = isset( $input['enable_max'] ) ? 1 : 0;
    $output['enable_messenger'] = isset( $input['enable_messenger'] ) ? 1 : 0;

    // --- visibaility ---

    $output['show_desktop'] = isset( $input['show_desktop'] ) ? 1 : 0;
    $output['show_mobile']  = isset( $input['show_mobile'] ) ? 1 : 0;

    // --- indicator ---
    $output['indicator_toggle'] = isset( $input['indicator_toggle'] ) ? 1 : 0;

    // --- links ---
    $output['telegram_link'] = isset( $input['telegram_link'] ) ? esc_url_raw( $input['telegram_link'] ) : '';
    $output['whatsapp_link'] = isset( $input['whatsapp_link'] ) ? esc_url_raw( $input['whatsapp_link'] ) : '';
    $output['max_link']      = isset( $input['max_link'] ) ? esc_url_raw( $input['max_link'] ) : '';
    $output['messenger_link'] = isset( $input['messenger_link'] ) ? esc_url_raw( $input['messenger_link'] ) : '';

    // --- position ---
    $output['offset_top']     = isset( $input['offset_top'] ) ? intval( $input['offset_top'] ) : '';
    $output['offset_right']  = isset( $input['offset_right'] ) ? intval( $input['offset_right'] ) : '';
    $output['offset_bottom'] = isset( $input['offset_bottom'] ) ? intval( $input['offset_bottom'] ) : '';

    // --- buttons order ---
    if ( isset( $input['buttons_order'] ) ) {
        $order = array_map( 'sanitize_key', explode( ',', $input['buttons_order'] ) );
        $allowed = array( 'telegram', 'whatsapp', 'messenger', 'max', 'pro1', 'pro2', 'pro3' );
        $order = array_values( array_intersect( $order, $allowed ) );
        $output['buttons_order'] = implode( ',', $order );
    }

    // 🔥 КЛЮЧЕВОЕ: даём PRO-плагину сохранить свои данные
    $output = apply_filters( 'max_button_sanitize_input', $input, $output );

    // --- FIX: гарантируем сохранение hint после PRO ---
    $output['enable_hint'] = isset( $input['enable_hint'] ) ? 1 : 0;
    $output['hint_text']   = isset( $input['hint_text'] ) ? sanitize_text_field( $input['hint_text'] ) : '';

    return $output;
}
