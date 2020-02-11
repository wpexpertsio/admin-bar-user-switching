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
    $s = empty( $_SERVER[ 'HTTPS' ] ) ? '' : ( $_SERVER[ 'HTTPS' ] == 'on' ) ? 's' : '';
    $protocol = substr( strtolower( $_SERVER[ 'SERVER_PROTOCOL' ] ), 0, strpos( strtolower( $_SERVER[ 'SERVER_PROTOCOL' ] ), '/' ) ) . $s;
    $port = ( $_SERVER[ 'SERVER_PORT' ] == '80') ? '' : ( ":".$_SERVER[ 'SERVER_PORT' ] );

    if ( $parse ) {
        return parse_url( $protocol . "://" . $_SERVER[ 'HTTP_HOST' ] . $port . $_SERVER[ 'REQUEST_URI' ] );
    }

    return $protocol . "://" . $_SERVER[ 'HTTP_HOST' ] . $port . $_SERVER[ 'REQUEST_URI' ];
}
