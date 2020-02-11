<?php


namespace AdminBarUserSwitching\Admin;

defined('ABSPATH') || exit;

class Notice
{
    /**
     * Notice constructor.
     */
    public function __construct()
    {
        add_action( 'admin_notices', array( $this, 'error' ) );
    }

    /**
     * Deactivates the plugin and throws and error message when User Switching plugin not active
     *
     * @return void
     */
    public function error()
    {
        if ( ! class_exists( 'user_switching' ) ) {
            deactivate_plugins(ABUS_PLUGIN_SLUG . '/' . ABUS_PLUGIN_SLUG . '.php', ABUS_PLUGIN_SLUG . '.php');

            $html = '
                <div class="error">
                    <p>' . __( 'The <strong>User Switching in Admin Bar</strong> plugin has been <strong>deactivated</strong>. The reason for this, is that it
                    requires the "User Switching" plugin by John Blackbourn in order to work. Please install the
                    "User Switching" plugin, then activate this plugin again. <strong>Please ignore the Plugin
                    Activated message below</strong>.', 'abus' ) . '</p>
                </div>
            ';

            echo $html;
        }
    }
}
