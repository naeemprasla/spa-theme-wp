<?php
/**
 * Custom REST API endpoints
 */

// Add custom endpoint for page data
add_action('rest_api_init', function() {
    register_rest_route('spa/v1', '/page/(?P<slug>[a-zA-Z0-9-]+)', [
        'methods' => 'GET',
        'callback' => 'spa_get_page_data',
        'permission_callback' => '__return_true'
    ]);
});

function spa_get_page_data($request) {
    $slug = $request['slug'];
    $page = get_page_by_path($slug);
    
    if (!$page) {
        return new WP_Error('not_found', 'Page not found', ['status' => 404]);
    }
    
    $controller = new WP_REST_Posts_Controller('page');
    $request = new WP_REST_Request('GET', "/wp/v2/pages/{$page->ID}");
    $request->set_url_params(['id' => $page->ID]);
    $response = $controller->get_item($request);
    
    return $response->data;
}

// Add menu endpoint
add_action('rest_api_init', function() {
    register_rest_route('spa/v1', '/menu/(?P<location>[a-zA-Z0-9-]+)', [
        'methods' => 'GET',
        'callback' => 'spa_get_menu',
        'permission_callback' => '__return_true'
    ]);
});

function spa_get_menu($request) {
    $location = $request['location'];
    $locations = get_nav_menu_locations();
    
    if (!isset($locations[$location])) {
        return new WP_Error('not_found', 'Menu location not found', ['status' => 404]);
    }
    
    $menu = wp_get_nav_menu_object($locations[$location]);
    $menu_items = wp_get_nav_menu_items($menu->term_id);
    
    return $menu_items ?: [];
}