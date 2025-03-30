<?php
/**
 * The template for displaying page content
 * 
 * Works with both traditional WordPress rendering and SPA JavaScript loading
 */

// Get the ACF fields for this page
$sections = get_field('sections');

// Check if we're in SPA mode (header already sent)
$is_spa_request = (defined('DOING_AJAX') && DOING_AJAX) || wp_doing_ajax();

if (!$is_spa_request) : 
    // Traditional WordPress rendering
    ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <?php if (empty($sections)) : ?>
            <header class="entry-header">
                <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
            </header>
        <?php endif; ?>

        <div class="entry-content">
            <?php
            if (!empty($sections)) {
                // Output sections for initial page load
                foreach ($sections as $section) {
                    switch ($section['acf_fc_layout']) {
                        case 'hero_section':
                            ?>
                            <section class="hero-section" style="background-image: url(<?php echo esc_url($section['background_image']['url']); ?>)">
                                <div class="container">
                                    <h1><?php echo esc_html($section['title']); ?></h1>
                                    <?php if ($section['subtitle']) : ?>
                                        <p><?php echo esc_html($section['subtitle']); ?></p>
                                    <?php endif; ?>
                                    <?php if ($section['button_link']) : ?>
                                        <a href="<?php echo esc_url($section['button_link']['url']); ?>" 
                                           class="button" 
                                           data-spa-link
                                           target="<?php echo esc_attr($section['button_link']['target'] ?: '_self'); ?>">
                                            <?php echo esc_html($section['button_link']['title']); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </section>
                            <?php
                            break;
                            
                        case 'content_section':
                            ?>
                            <section class="content-section">
                                <div class="container">
                                    <?php if ($section['title']) : ?>
                                        <h2><?php echo esc_html($section['title']); ?></h2>
                                    <?php endif; ?>
                                    <div class="content">
                                        <?php echo wp_kses_post($section['content']); ?>
                                    </div>
                                </div>
                            </section>
                            <?php
                            break;
                            
                        case 'card_grid':
                            ?>
                            <section class="card-grid-section">
                                <div class="container">
                                    <?php if ($section['title']) : ?>
                                        <h2><?php echo esc_html($section['title']); ?></h2>
                                    <?php endif; ?>
                                    <div class="cards">
                                        <?php foreach ($section['cards'] as $card) : ?>
                                            <div class="card">
                                                <?php if ($card['title']) : ?>
                                                    <h3><?php echo esc_html($card['title']); ?></h3>
                                                <?php endif; ?>
                                                <?php if ($card['content']) : ?>
                                                    <p><?php echo esc_html($card['content']); ?></p>
                                                <?php endif; ?>
                                                <?php if ($card['link']) : ?>
                                                    <a href="<?php echo esc_url($card['link']['url']); ?>" 
                                                       data-spa-link
                                                       target="<?php echo esc_attr($card['link']['target'] ?: '_self'); ?>">
                                                        <?php echo esc_html($card['link']['title']); ?>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </section>
                            <?php
                            break;
                    }
                }
            } else {
                // Fallback to standard content
                the_content();
            }
            ?>
        </div>

        <?php if (get_edit_post_link()) : ?>
            <footer class="entry-footer">
                <?php
                edit_post_link(
                    sprintf(
                        wp_kses(
                            __('Edit <span class="screen-reader-text">%s</span>', 'spa-theme'),
                            array(
                                'span' => array(
                                    'class' => array(),
                                ),
                            )
                        ),
                        wp_kses_post(get_the_title())
                    ),
                    '<span class="edit-link">',
                    '</span>'
                );
                ?>
            </footer>
        <?php endif; ?>
    </article>
    <?php
else :
    // SPA AJAX response - minimal wrapper
    ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <?php if (empty($sections)) : ?>
            <header class="entry-header">
                <h1 class="entry-title"><?php the_title(); ?></h1>
            </header>
        <?php endif; ?>
        
        <div class="entry-content">
            <?php 
            if (!empty($sections)) {
                // Output just the raw content for SPA to handle
                the_content();
            } else {
                the_content();
            }
            ?>
        </div>
    </article>
    <?php
endif;