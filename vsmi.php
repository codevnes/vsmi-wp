<?php
/**
 * Plugin Name: VSMI
 * Plugin URI: https://danhtrong.com
 * Description: WordPress REST API plugin for posts and categories
 * Version: 1.0.0
 * Author: Trần Danh Trọng
 * Author URI: https://danhtrong.com
 * Text Domain: vsmi
 */

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

// Include main plugin class
require_once plugin_dir_path(__FILE__) . 'includes/class-vsmi-plugin.php';

/**
 * Returns the main instance of VSMI Plugin
 *
 * @return VSMI_Plugin
 */
function VSMI() {
    return VSMI_Plugin::instance();
}

// Initialize the plugin
VSMI();
