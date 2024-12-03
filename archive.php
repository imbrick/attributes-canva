<?php
/**
 * The template for displaying archive pages.
 *
 * @package Attribute Canva
 */

get_header(); ?>

<main id="primary" class="site-main">
    <header class="archive-header">
        <?php if (have_posts()) : ?>
            <h1 class="archive-title">
                <?php
                if (is_category()) {
                    single_cat_title(__('Category: ', 'attribute-canva'));
                } elseif (is_tag()) {
                    single_tag_title(__('Tag: ', 'attribute-canva'));
                } elseif (is_author()) {
                    printf(
                        __('Author: %s', 'attribute-canva'),
                        '<span class="vcard">' . get_the_author() . '</span>'
                    );
                } elseif (is_date()) {
                    echo get_the_date();
                } else {
                    _e('Archives', 'attribute-canva');
                }
                ?>
            </h1>
            <div class="archive-description">
                <?php
                if (is_category() || is_tag()) {
                    echo term_description();
                }
                ?>
            </div>
        <?php else : ?>
            <h1 class="archive-title"><?php _e('Nothing Found', 'attribute-canva'); ?></h1>
        <?php endif; ?>
    </header><!-- .archive-header -->

    <?php if (have_posts()) : ?>
        <div class="post-grid">
            <?php while (have_posts()) : the_post(); ?>
                <?php get_template_part('template-parts/content', get_post_type()); ?>
            <?php endwhile; ?>
        </div><!-- .post-grid -->

        <?php the_posts_pagination([
            'mid_size'  => 2,
            'prev_text' => __('&laquo; Previous', 'attribute-canva'),
            'next_text' => __('Next &raquo;', 'attribute-canva'),
        ]); ?>
    <?php else : ?>
        <section class="no-results not-found">
            <p><?php _e('It seems we can’t find any posts matching your query. Try searching:', 'attribute-canva'); ?></p>
            <?php get_search_form(); ?>
        </section><!-- .no-results -->
    <?php endif; ?>
</main><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
