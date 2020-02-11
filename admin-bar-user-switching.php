<?php
/**
 * Plugin Name: User Switching in Admin Bar
 * Plugin URI: https://www.bebic.at
 * Description: Originally developed by <a href="https://markwilkinson.me/">Mark Wilkinson</a>, this plugin builds upon the <a href="https://wordpress.org/plugins/user-switching/">User Switching</a> plugin by John Blackbourn and adds a dropdown list of users in the WordPress admin bar with a link to switch to that user, then providing a switch back link in the admin bar as well.
 * Version: 1.2
 * Author: Dražen Bebić
 * Author URI: https://www.bebic.at
 * Text Domain: abus
 * Domain Path: /i18n/languages/
 * Requires at least: 3.1
 * Tested up to: 5.3
 * Requires PHP: 5.6
 */

use AdminBarUserSwitching\Plugin as AdminBarUserSwitchingPlugin;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/functions/abus-core-functions.php';

// Define ABUS_PLUGIN_FILE
if (!defined('ABUS_PLUGIN_FILE')) {
    define('ABUS_PLUGIN_FILE', __FILE__);
}

// Define ABUS_PLUGIN_DIR
if (!defined('ABUS_PLUGIN_DIR')) {
    define('ABUS_PLUGIN_DIR', __DIR__);
}

// Define ABUS_PLUGIN_URL
if (!defined('ABUS_PLUGIN_URL')) {
    define('ABUS_PLUGIN_URL', plugins_url('', __FILE__) . '/');
}

// Define ABUS_PLUGIN_VERSION
if (!defined('ABUS_PLUGIN_VERSION')) {
    define('ABUS_PLUGIN_VERSION', '1.2');
}

// Define ABUS_PLUGIN_SLUG
if (!defined('ABUS_PLUGIN_SLUG')) {
    define('ABUS_PLUGIN_SLUG', 'admin-bar-user-switching');
}

/**
 * Main instance of AdminBarUserSwitching\.
 *
 * @return AdminBarUserSwitchingPlugin
 */
function AdminBarUserSwitching() {
    return AdminBarUserSwitchingPlugin::instance();
}

// Global for backwards compatibility.
$GLOBALS[ABUS_PLUGIN_SLUG] = AdminBarUserSwitching();
