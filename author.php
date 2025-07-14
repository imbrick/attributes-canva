<?php

/**
 * The template for displaying author archive pages.
 *
 * @package Attribute Canva
 *
 * Security check - prevent direct access
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header(); ?>

<main id="primary" class="site-main">
    <header class="author-header">
        <div class="author-avatar">
            <?php
            // Display the author's avatar.
            echo get_avatar(get_the_author_meta('ID'), 96); // 96px is the default size for avatars.
            ?>
        </div>
        <div class="author-info">
            <h1 class="author-title">
                <?php
                printf(
                    /* translators: %s: Author's name. */
                    esc_html__('Author: %s', 'attribute-canva'),
                    '<span>' . get_the_author() . '</span>'
                );
                ?>
            </h1>
            <p class="author-bio">
                <?php echo esc_html(get_the_author_meta('description')); ?>
            </p>
        </div>
    </header><!-- .author-header -->

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