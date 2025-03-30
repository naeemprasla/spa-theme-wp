<div class="site-info">
    <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.</p>
    <?php if (has_nav_menu('footer')) : ?>
        <nav class="footer-navigation">
            <?php wp_nav_menu([
                'theme_location' => 'footer',
                'menu_class' => 'footer-menu',
                'depth' => 1
            ]); ?>
        </nav>
    <?php endif; ?>
</div>