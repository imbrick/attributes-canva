<?php
/**
 * The template for displaying search results pages.
 *
 * @package Attribute Canva
 */

get_header(); ?>

<main id="primary" class="site-main">
    <header class="search-header">
        <h1 class="search-title">
            <?php
            printf(
                /* translators: %s: search query. */
                esc_html__('Search Results for: %s', 'attribute-canva'),
                '<span>' . get_search_query() . '</span>'
            );
            ?>
        </h1>
    </header><!-- .search-header -->

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
        <?php get_template_part('template-parts/content', 'none'); ?>
    <?php endif; ?>
</main><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
