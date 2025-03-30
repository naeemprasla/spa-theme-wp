<?php
class SPA_Renderer {
    public function __construct() {
        add_action('wp_footer', [$this, 'render_templates']);
        add_filter('body_class', [$this, 'add_body_classes']);
    }

    public function add_body_classes($classes) {
        if (is_front_page()) {
            $classes[] = 'spa-home';
        } else if (is_single()) {
            $classes[] = 'spa-single';
        } else if (is_page()) {
            $classes[] = 'spa-page';
        }
        return $classes;
    }

    public function render_templates() {
        ?>
        <script type="text/template" id="spa-navigation-template">
            <div id="spa-navigation"></div>
        </script>

        <script type="text/template" id="spa-loading-template">
            <div class="spa-loading">
                <div class="spinner"></div>
            </div>
        </script>
        <?php
    }
}