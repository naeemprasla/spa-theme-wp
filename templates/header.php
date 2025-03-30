<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <div class="no-js-warning">
        For the best experience, please enable JavaScript in your browser.
    </div>
    
    <div id="app">
        <header class="site-header">
            <div class="site-branding">
                <?php if (has_custom_logo()) : ?>
                    <div class="site-logo"><?php the_custom_logo(); ?></div>
                <?php else : ?>
                    <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" data-spa-link><?php bloginfo('name'); ?></a></h1>
                <?php endif; ?>
            </div>
            
            <nav class="main-navigation spa-enhanced">
                <?php wp_nav_menu([
                    'theme_location' => 'primary',
                    'container' => false,
                    'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                    'walker' => new SPA_Nav_Walker()
                ]); ?>
            </nav>
        </header>

        <main id="content" class="site-content">
            <div id="spa-content" class="spa-enhanced">
                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <?php the_content(); ?>
                <?php endwhile; endif; ?>
            </div>
        </main>