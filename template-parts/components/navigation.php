<nav id="spa-dynamic-navigation" class="spa-navigation">
    <?php
    wp_nav_menu([
        'theme_location' => 'primary',
        'container' => false,
        'menu_class' => 'spa-nav-menu',
        'walker' => new SPA_Nav_Walker()
    ]);
    ?>
</nav>