<?php


namespace AdminBarUserSwitching\Controllers;

use user_switching;
use WP_User;
use WP_User_Query;

defined('ABSPATH') || exit;

class AdminBar
{
    /**
     * AdminBar constructor.
     */
    public function __construct()
    {
        add_action( 'wp_ajax_abus_user_search', array( $this, 'userSearch' ) );
    }

    /**
     * Searches for the required user depending what was entered into the search box in the admin bar.
     */
    public function userSearch()
    {
        // Get the posted query search, current url and nonce
        $q     = esc_attr( $_POST[ 'query' ] );
        $url   = esc_url( $_POST[ 'currentUrl' ] );
        $nonce = esc_attr( $_POST[ 'nonce' ] );
        $html  = '';

        // Check whether the nonce passes for intent
        if ( ! wp_verify_nonce( $nonce, 'abus_nonce' ) ) {
            exit();
        }

        $args = apply_filters(
            'abus_user_search_args',
            array(
                'search' => is_numeric( $q ) ? $q : '*' . $q . '*',
            )
        );

        // Query the users
        $userQuery = new WP_User_Query( $args );

        $html .= '<div class="abus_user_results">';

        // Check whether we have results returned
        if ( ! empty( $userQuery->results ) ) {
            /**
             * Loop through each returned user
             *
             * @var WP_User $user
             */
            foreach ( $userQuery->results as $user ) {
                // If this user is the current user - skip to next user
                if ( $user->ID == get_current_user_id() ) {
                    continue;
                }

                if ( $link = user_switching::maybe_switch_url( $user ) ) {
                    $link = add_query_arg( 'redirect_to', apply_filters( 'abus_switch_to_url', $url ), $link );
                    $html .= '
                        <p class="result">
                            <a href="' . esc_url( $link, $user ) . '">' . $user->display_name . '</a>
                        </p>
                    ';
                }

            }
        } else {
            $html .= '<p class="result">' . __( 'No users found.', 'abus' ) . '</p>';
        }

        $html .= '</div>';

        wp_send_json($html);
    }
}
