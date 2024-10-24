<?php
/**
 * Plugin Name: My Custom Calendar
 * Description: A custom calendar view using OOP style.
 * Version: 1.0
 * Author: Tarikul Islam
 * Author URI: https://yourwebsite.com
 * Text Domain: my-custom-calendar
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define constants for plugin path and URL
define('MY_CALENDAR_PLUGIN_URL', plugin_dir_url(__FILE__));
define('MY_CALENDAR_PLUGIN_PATH', plugin_dir_path(__FILE__));

// Include the main plugin class
require_once MY_CALENDAR_PLUGIN_PATH . 'includes/class-main.php';

// Initialize the plugin
function my_custom_calendar()
{
    $plugin = new Main();
}
my_custom_calendar();
