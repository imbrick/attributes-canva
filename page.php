<?php

/**
 * The template for displaying all single pages.
 * Enhanced for clean canvas design
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
    <div class="container">
        <?php
        while (have_posts()) :
            the_post();
        ?>

            <!-- Page Content -->
            <article id="page-<?php the_ID(); ?>" <?php post_class('card'); ?>>

                <!-- Featured Image -->
                <?php if (has_post_thumbnail()) : ?>
                    <div class="page-thumbnail">
                        <?php the_post_thumbnail('large', array('loading' => 'eager')); ?>
                    </div>
                <?php endif; ?>

                <!-- Entry Header -->
                <header class="entry-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>

                    <?php if (get_the_excerpt()) : ?>
                        <div class="page-excerpt">
                            <p><?php echo wp_kses_post(get_the_excerpt()); ?></p>
                        </div>
                    <?php endif; ?>
                </header><!-- .entry-header -->

                <!-- Entry Content -->
                <div class="entry-content">
                    <?php
                    the_content();

                    wp_link_pages([
                        'before' => '<div class="page-links">' . __('Pages:', 'attribute-canva'),
                        'after'  => '</div>',
                        'link_before' => '<span class="btn btn-outline btn-small">',
                        'link_after' => '</span>',
                    ]);
                    ?>
                </div><!-- .entry-content -->

                <!-- Entry Footer -->
                <?php if (current_user_can('edit_posts')) : ?>
                    <footer class="entry-footer">
                        <?php
                        edit_post_link(
                            __('Edit Page', 'attribute-canva'),
                            '<div class="edit-link"><span class="btn btn-secondary btn-small">',
                            '</span></div>'
                        );
                        ?>
                    </footer><!-- .entry-footer -->
                <?php endif; ?>
            </article><!-- #page-<?php the_ID(); ?> -->

            <!-- Comments Section (if enabled) -->
            <?php if (comments_open() || get_comments_number()) : ?>
                <div class="comments-wrapper">
                    <?php comments_template(); ?>
                </div>
            <?php endif; ?>

        <?php endwhile; ?>
    </div><!-- .container -->
</main><!-- #primary -->

<?php get_footer(); ?>