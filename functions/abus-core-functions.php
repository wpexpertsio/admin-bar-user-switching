<?php

defined('ABSPATH') || exit;

/**
 * Determine the URL of the currently viewed page - will return array if $parse set to true.
 *
 * @see https://github.com/scottsweb/null/blob/master/functions.php
 *
 * @return string|array
 */
function abus_get_current_url($parse = false )
{
    $port = ( $_SERVER[ 'SERVER_PORT' ] == '80') ? '' : ( ":".$_SERVER[ 'SERVER_PORT' ] );

    $url = set_url_scheme( 'https://' . $_SERVER[ 'HTTP_HOST' ] . $port . $_SERVER[ 'REQUEST_URI' ] );
    
    if ( $parse ) {
        return parse_url( $url );
    }

    return apply_filters('abus_get_current_url', $url );
}
