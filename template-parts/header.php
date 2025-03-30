<div class="site-branding">
    <a href="<?php echo esc_url(home_url('/')); ?>" data-spa-link rel="home">
        <?php if (has_custom_logo()) : ?>
            <?php the_custom_logo(); ?>
        <?php else : ?>
            <h1 class="site-title"><?php bloginfo('name'); ?></h1>
        <?php endif; ?>
    </a>
    <?php if (get_bloginfo('description')) : ?>
        <p class="site-description"><?php bloginfo('description'); ?></p>
    <?php endif; ?>
</div>

<nav id="site-navigation" class="main-navigation">
    <?php if (has_nav_menu('primary')) : ?>
        <?php wp_nav_menu([
            'theme_location' => 'primary',
            'menu_class' => 'spa-menu',
            'container' => false,
            'walker' => new SPA_Nav_Walker()
        ]); ?>
    <?php else : ?>
        <div id="spa-navigation"></div>
    <?php endif; ?>
</nav>