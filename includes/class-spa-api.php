<?php
class SPA_API {
  public function __construct() {
    add_action('rest_api_init', [$this, 'register_routes']);
  }

  public function register_routes() {
    // Page by ID
    register_rest_route('spa/v1', '/page/(?P<id>\d+)', [
      'methods' => 'GET',
      'callback' => [$this, 'get_page'],
      'permission_callback' => '__return_true'
    ]);

    // Page by slug
    register_rest_route('spa/v1', '/page-by-slug/(?P<slug>[a-zA-Z0-9-]+)', [
      'methods' => 'GET',
      'callback' => [$this, 'get_page_by_slug'],
      'permission_callback' => '__return_true'
    ]);
  }

  public function get_page($request) {
    $page_id = $request['id'];
    return $this->prepare_page_response($page_id);
  }

  public function get_page_by_slug($request) {
    $page = get_page_by_path($request['slug'], OBJECT, ['page', 'post']);
    
    if (!$page || $page->post_status !== 'publish') {
      $home_id = get_option('page_on_front');
      if ($home_id) {
        return $this->prepare_page_response($home_id);
      }
      return new WP_Error('not_found', 'Page not found', ['status' => 404]);
    }

    return $this->prepare_page_response($page->ID);
  }

  private function prepare_page_response($page_id) {
    $page = get_post($page_id);
    if (!$page) {
      return new WP_Error('not_found', 'Page not found', ['status' => 404]);
    }

    $response = [
      'title' => $page->post_title,
      'content' => apply_filters('the_content', $page->post_content),
      'slug' => $page->post_name,
      'id' => $page->ID
    ];

    if (function_exists('get_fields')) {
      $response['acf'] = get_fields($page_id);
    }

    return $response;
  }
}