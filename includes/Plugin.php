<?php


namespace AdminBarUserSwitching;

use AdminBarUserSwitching\Abstracts\Singleton;

defined('ABSPATH') || exit;

final class Plugin extends Singleton

{
    /**
     * Main constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->defineConstants();

        add_action( 'init',                  array( $this, 'init' ) );
        add_action( 'init',                  array( $this, 'loadPluginTextDomain' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueueScripts' ) );
        add_action( 'wp_enqueue_scripts',    array( $this, 'enqueueScripts' ) );
        add_action( 'wp_head',               array( $this, 'enqueueStyles' ) );
        add_action( 'admin_head',            array( $this, 'enqueueStyles' ) );
        add_filter( 'plugin_row_meta',       array( $this, 'pluginRowMeta' ) , 10, 2);
    }

    /**
     * Define plugin constants.
     *
     * @return void
     */
    private function defineConstants()
    {
        if (!defined('ABSPATH_LENGTH')) {
            define('ABSPATH_LENGTH', strlen(ABSPATH));
        }

        define('ABUS_ABSPATH',         dirname(ABUS_PLUGIN_FILE) . '/');
        define('ABUS_PLUGIN_BASENAME', plugin_basename(ABUS_PLUGIN_FILE));

        // URL's
        define('ABUS_ASSETS_URL', ABUS_PLUGIN_URL . 'assets/');
        define('ABUS_JS_URL',     ABUS_ASSETS_URL . 'js/');
    }

    /**
     * Initialize the plugin when WordPress Initialises.
     *
     * @return void
     */
    public function init()
    {
        // Admin
        new Admin\Notice();

        // Controllers
        new Controllers\AdminBar();

        // Integrations
        new Integrations\WordPress\AdminBar();
    }

    /**
     * Adds the i18n translations to the plugin.
     *
     * @return void
     */
    public function loadPluginTextDomain()
    {
        if ( function_exists( 'determine_locale' ) ) {
            $locale = determine_locale();
        } else {
            $locale = is_admin() ? get_user_locale() : get_locale();
        }

        $locale = apply_filters( 'plugin_locale', $locale, 'abus' );

        unload_textdomain( 'abus' );

        load_textdomain( 'abus', WP_LANG_DIR . '/plugins/' . ABUS_PLUGIN_SLUG . '-' . $locale . '.mo' );

        load_plugin_textdomain(
            'abus',
            false,
            plugin_basename(dirname(ABUS_PLUGIN_FILE)) . '/i18n/languages'
        );
    }

    /**
     * Enqueues plugin-related JS files.
     *
     * @param string $hook
     *
     * @return void
     */
    public function enqueueScripts($hook)
    {
        wp_register_script(
            'abus_script',
            ABUS_JS_URL . 'abus_script.js',
            array( 'jquery' ),
            ABUS_PLUGIN_VERSION
        );

        $args = array(
            'ajaxurl'   => admin_url( 'admin-ajax.php' ),
            'magicWord' => '',
        );

        $args = apply_filters( 'abus_ajax_args', $args );

        wp_localize_script(
            'abus_script',
            'abus_ajax',
            $args
        );

        if ( is_user_logged_in() ) {
            wp_enqueue_script( 'abus_script' );
        }
    }

    /**
     * Enqueues plugin-related stylesheets
     */
    public function enqueueStyles()
    {
        $styles = '
            <style type="text/css">
                #wpadminbar .quicklinks #wp-admin-bar-abus_switch_to_user ul li .ab-item {
                    height: auto;
                }
                #abus_search_text {
                    width: 280px;
                    margin: 0;
                    padding: 0 8px;
                    line-height: 2;
                    min-height: 30px;
                    box-shadow: 0 0 0 transparent;
                    border-radius: 4px;
                    border: 1px solid #7e8993;
                    background-color: #ffffff;
                    color: #32373c;
                    font-size: 14px;
                    box-sizing: border-box;
                    vertical-align: top;
                }
                #abus_search_text:focus {
                    border-color: #007cba;
                    box-shadow: 0 0 0 1px #007cba;
                    outline: 2px solid transparent;
                }
                #abus_search_submit {
                    font-size: 13px;
                    padding: 0 10px;
                    min-height: 30px;
                    border-width: 1px;
                    border-radius: 3px;
                    color: #0071a1;
                    border-color: #0071a1;
                    background-color: #f3f5f6;
                    line-height: 2;
                    box-sizing: border-box;
                    vertical-align: top;
                 }
                 #abus_search_submit:hover {
                    background: #f1f1f1;
                    border-color: #016087;
                    color: #016087;
                 }
            </style>
        ';

        echo apply_filters( 'abus_styles', $styles );

    }

    /**
     * Add additional links to the plugin row meta.
     *
     * @param array  $links Array of already present links
     * @param string $file  File name
     *
     * @return array
     */
    public function pluginRowMeta($links, $file)
    {
        if ( strpos( $file, ABUS_PLUGIN_SLUG . '.php') !== false ) {
            $newLinks = array(
                'github' => sprintf(
                    '<a href="%s" target="_blank">%s</a>',
                    'https://github.com/drazenbebic/admin-bar-user-switching',
                    'GitHub'
                ),
                'donate' => sprintf(
                    '<a href="%s" target="_blank">%s</a>',
                    'https://www.paypal.me/drazenbebic/',
                    __( 'Donate', 'abus' )
                )
            );

            $links = array_merge( $links, $newLinks );
        }

        return $links;
    }
}
