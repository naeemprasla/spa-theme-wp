<?php get_header(); ?>

<div id="spa-wrapper">
  <div class="barba-container">
    <header id="spa-header">
      <div class="container">
        <?php if (has_nav_menu('primary')) : ?>
          <?php wp_nav_menu([
            'theme_location' => 'primary',
            'container' => false,
            'menu_class' => 'spa-nav',
            'walker' => new SPA_Nav_Walker()
          ]); ?>
        <?php endif; ?>
      </div>
    </header>

    <main id="spa-content">
      <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <article>
          <h1><?php the_title(); ?></h1>
          <?php the_content(); ?>
        </article>
      <?php endwhile; endif; ?>
    </main>

    <footer id="spa-footer">
      <div class="container">
        <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></p>
      </div>
    </footer>
  </div>
</div>

<?php get_footer(); ?>