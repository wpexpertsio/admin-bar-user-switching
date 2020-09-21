<?php


namespace AdminBarUserSwitching\Integrations\WordPress;

use user_switching;
use WP_Admin_Bar;

defined('ABSPATH') || exit;

class AdminBar
{
    /**
     * AdminBar constructor.
     */
    public function __construct()
    {
        add_action( 'wp_before_admin_bar_render', array( $this, 'output' ), 1 );
        // Frontend hook - most themes will implement this
        add_action( 'wp_footer', array( $this, 'footer' ), 1);
        // Admin footer hook
        add_action( 'admin_footer', array( $this, 'footer' ), 1);
        add_thickbox();
    }

    /**
     * Outputs the admin bar markup for the user search box.
     *
     * @return void
     */
    public function output()
    {
        // If user switching is not active or the admin bar is not showing - go no further!
        if ( ! class_exists( 'user_switching' ) || ! is_admin_bar_showing() ) {
            return;
        }

        /** @var user_switching $user_switching */
        global $user_switching;

        /** @var WP_Admin_Bar $wp_admin_bar */
        global $wp_admin_bar;

        // Check whether the current user can edit users - cap is filterable
        if ( current_user_can( apply_filters( 'abus_switch_to_capability', 'edit_users' ) ) ) {

            // Add admin bar menu for switching to a user
            $wp_admin_bar->add_menu(
                array(
                    'id'    => 'abus_switch_to_user',
                    'title' => apply_filters( 'abus_switch_to_text', __( 'Switch to user', 'abus' ) ),
                    'href'  => '#TB_inline?height=600&width=390&inlineId=abus_wrapper',
                )
            );

        }

        // Check if there is an old user stored i.e. this logged in user is through switching
        if ( $user_switching->get_old_user() ) {

            // Build the switch back url
            $switchBackUrl = $user_switching->switch_back_url( $user_switching->get_old_user() );

            /* we are logged in through switching so add admin bar menu to create the switch back link */
            $wp_admin_bar->add_menu(
                array(
                    'id'    => 'switch_back',
                    'title' => apply_filters( 'abus_switch_back_text', __( 'Switch back', 'abus' ) ),
                    'href'  => esc_url( add_query_arg( array( 'redirect_to' => abus_get_current_url() ), $switchBackUrl ) )
                )
            );
        }
    }

    public function footer()
    {
        // Don't display if the user doesn't have capability to switch users
        if ( !current_user_can( apply_filters( 'abus_switch_to_capability', 'edit_users' ) ) ) {
            return '';
        }

        /** @var user_switching $user_switching */
        global $user_switching;

        // Build the user search form markup
        ?>
            <div id="abus_wrapper">
                <form method="post" action="abus_user_search">
                    <input id="abus_search_text"
                           class=""
                           name="abus_search_text"
                           type="text"
                           placeholder="<?=__( 'Enter a username', 'abus' ); ?>"/>

                    <input id="abus_search_submit"
                           class="button"
                           name="abus_search_submit"
                           type="submit" value="<?=__( 'Search', 'abus' ); ?>"/>

                    <input name="abus_current_url"
                           type="hidden"
                           value="<?=esc_url( abus_get_current_url() ); ?>"/>

                    <input name="abus_nonce"
                           type="hidden"
                           value="<?=wp_create_nonce( 'abus_nonce' ); ?>" />
                </form>
                <p class="result">
                    <a href="<?=$user_switching->switch_off_url( wp_get_current_user() ); ?>">Guest User / Switch Off</a>
                </p>
                <div id="abus_result"></div>
            </div>
        <?php
    }
}
