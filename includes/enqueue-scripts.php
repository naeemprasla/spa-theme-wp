<?php
/**
 * Handle all script and style enqueuing
 */

add_action('wp_enqueue_scripts', 'spa_enqueue_assets');
function spa_enqueue_assets() {
    // Main styles
    wp_enqueue_style('spa-style', get_stylesheet_uri());
    wp_enqueue_style('spa-main', get_template_directory_uri() . '/assets/css/main.css');
    
    // GSAP Animation Library
    wp_enqueue_script('gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/gsap.min.js', [], '3.11.4', true);
    wp_enqueue_script('gsap-scrolltrigger', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/ScrollTrigger.min.js', ['gsap'], '3.11.4', true);
    
    // Theme scripts
    wp_enqueue_script('spa-router', get_template_directory_uri() . '/assets/js/components/spa-router.js', [], '1.0', true);
    wp_enqueue_script('spa-content-loader', get_template_directory_uri() . '/assets/js/components/content-loader.js', [], '1.0', true);
    wp_enqueue_script('spa-section-renderer', get_template_directory_uri() . '/assets/js/components/section-renderer.js', [], '1.0', true);
    wp_enqueue_script('spa-app', get_template_directory_uri() . '/assets/js/app.js', ['spa-router', 'spa-content-loader', 'spa-section-renderer'], '1.0', true);
    
    // Localize script
    wp_localize_script('spa-app', 'spaSettings', [
        'restUrl' => rest_url(),
        'homeUrl' => home_url('/'),
        'nonce' => wp_create_nonce('wp_rest'),
        'isAuthenticated' => is_user_logged_in()
    ]);
}

// Add inline script to handle no-JS detection
add_action('wp_head', 'spa_no_js_detection');
function spa_no_js_detection() {
    echo '<script>document.documentElement.className = document.documentElement.className.replace("no-js","js");</script>';
}