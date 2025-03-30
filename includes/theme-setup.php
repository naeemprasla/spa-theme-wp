<?php
/**
 * Theme setup and configuration
 */

function spa_theme_setup() {
    // Theme supports
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);
    
    // Register menus
    register_nav_menus([
        'primary' => __('Primary Menu', 'spa-theme'),
    ]);
    
    // ACF JSON save point
    add_filter('acf/settings/save_json', function($path) {
        return get_template_directory() . '/includes/acf-json';
    });
    
    // ACF JSON load point
    add_filter('acf/settings/load_json', function($paths) {
        $paths[] = get_template_directory() . '/includes/acf-json';
        return $paths;
    });
    
    // Enable ACF options page
    if (function_exists('acf_add_options_page')) {
        acf_add_options_page([
            'page_title' => 'Theme Options',
            'menu_title' => 'Theme Options',
            'menu_slug'  => 'theme-options',
            'capability' => 'edit_posts',
            'redirect'   => false
        ]);
    }
}

// Register ACF fields in REST API
add_action('rest_api_init', 'spa_register_acf_fields_in_rest');
function spa_register_acf_fields_in_rest() {
    $post_types = get_post_types(['public' => true]);
    
    foreach ($post_types as $post_type) {
        register_rest_field($post_type, 'acf', [
            'get_callback' => 'spa_get_acf_fields_for_api',
            'schema' => null,
        ]);
    }
}

function spa_get_acf_fields_for_api($object) {
    $post_id = $object['id'];
    return get_fields($post_id);
}