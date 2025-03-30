<?php
/**
 * Theme Name: SPA REST API Theme
 * Description: WordPress SPA using REST API and ACF Repeater fields
 */

if (!defined('ABSPATH')) exit;

// Load theme files
require_once get_template_directory() . '/includes/theme-setup.php';
require_once get_template_directory() . '/includes/enqueue-scripts.php';
require_once get_template_directory() . '/includes/rest-api.php';

// Initialize theme
add_action('after_setup_theme', 'spa_theme_setup');