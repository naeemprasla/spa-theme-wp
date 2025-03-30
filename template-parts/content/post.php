<article id="post-<?php the_ID(); ?>" <?php post_class('spa-post-content'); ?>>
    <header class="entry-header">
        <h1 class="entry-title"><?php the_title(); ?></h1>
        
        <div class="entry-meta">
            <span class="posted-on">
                <?php echo esc_html(get_the_date()); ?>
            </span>
            <span class="byline">
                <?php esc_html_e('By', 'spa-theme'); ?> 
                <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" data-spa-link>
                    <?php echo esc_html(get_the_author()); ?>
                </a>
            </span>
        </div>
    </header>

    <?php if (has_post_thumbnail()) : ?>
        <div class="post-thumbnail">
            <?php the_post_thumbnail('full'); ?>
        </div>
    <?php endif; ?>

    <div class="entry-content">
        <?php the_content(); ?>
    </div>

    <footer class="entry-footer">
        <?php if (has_category()) : ?>
            <div class="cat-links">
                <?php esc_html_e('Categories:', 'spa-theme'); ?> 
                <?php the_category(', '); ?>
            </div>
        <?php endif; ?>

        <?php if (has_tag()) : ?>
            <div class="tags-links">
                <?php esc_html_e('Tags:', 'spa-theme'); ?> 
                <?php the_tags('', ', '); ?>
            </div>
        <?php endif; ?>
    </footer>
</article>