<?php
/**
 * SPA WordPress Theme functions
 */

function spa_theme_setup() {
  // Theme support
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_theme_support('html5', ['comment-list', 'comment-form', 'search-form']);
  
  // Register menus
  register_nav_menus([
    'primary' => 'Primary Menu'
  ]);
  
  // Include custom classes
  require_once get_template_directory() . '/includes/class-spa-api.php';
  require_once get_template_directory() . '/includes/class-spa-nav-walker.php';
  
  // Initialize API
  new SPA_API();
  
  // Enqueue scripts
  add_action('wp_enqueue_scripts', 'spa_enqueue_assets');
}
add_action('after_setup_theme', 'spa_theme_setup');

function spa_enqueue_assets() {
  // CSS
  wp_enqueue_style('spa-style', get_stylesheet_uri());
  
  // GSAP + Barba
  wp_enqueue_script('gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/gsap.min.js', [], '3.11.4', true);
  wp_enqueue_script('barba', 'https://cdnjs.cloudflare.com/ajax/libs/barba.js/1.0.0/barba.min.js', [], '1.0.0', true);
  
  // Theme scripts
  wp_enqueue_script('spa-acf-renderer', get_template_directory_uri() . '/assets/js/acf-renderer.js', [], '1.0', true);
  wp_enqueue_script('spa-transitions', get_template_directory_uri() . '/assets/js/transitions.js', ['gsap', 'barba'], '1.0', true);
  wp_enqueue_script('spa-app', get_template_directory_uri() . '/assets/js/app.js', ['spa-transitions'], '1.0', true);
  
  // Localize script
  wp_localize_script('spa-app', 'spaConfig', [
    'apiUrl' => home_url('/wp-json/spa/v1/'),
    'homePageId' => get_option('page_on_front') ?: 2,
    'siteUrl' => home_url('/')
  ]);
}

// Set permalinks on theme activation
function spa_theme_activation() {
  flush_rewrite_rules();
}
add_action('after_switch_theme', 'spa_theme_activation');

// Disables the block editor from managing widgets in the Gutenberg plugin.
add_filter( 'gutenberg_use_widgets_block_editor', '__return_false', 100 );
add_filter('use_block_editor_for_post', '__return_false', 10);

// Disables the block editor from managing widgets. renamed from wp_use_widgets_block_editor
add_filter( 'use_widgets_block_editor', '__return_false' );