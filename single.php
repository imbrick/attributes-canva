<?php

/**
 * The template for displaying single posts.
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

            <!-- Post Content -->
            <article id="post-<?php the_ID(); ?>" <?php post_class('card'); ?>>

                <!-- Featured Image -->
                <?php if (has_post_thumbnail()) : ?>
                    <div class="post-thumbnail">
                        <?php the_post_thumbnail('large', array('loading' => 'eager')); ?>
                    </div>
                <?php endif; ?>

                <!-- Entry Header -->
                <header class="entry-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>

                    <div class="entry-meta">
                        <span class="posted-on">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z" />
                            </svg>
                            <?php echo get_the_date(); ?>
                        </span>

                        <span class="byline">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                            </svg>
                            <?php the_author_posts_link(); ?>
                        </span>

                        <?php if (has_category()) : ?>
                            <span class="category">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17.63 5.84C17.27 5.33 16.67 5 16 5L5 5.01C3.9 5.01 3 5.9 3 7v10c0 1.1.9 1.99 2 1.99L16 19c.67 0 1.27-.33 1.63-.84L22 12l-4.37-6.16z" />
                                </svg>
                                <?php the_category(', '); ?>
                            </span>
                        <?php endif; ?>

                        <?php if (has_tag()) : ?>
                            <span class="tags">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M21.41 11.41l-8.83-8.83c-.37-.37-.88-.58-1.41-.58H4c-1.1 0-2 .9-2 2v7.17c0 .53.21 1.04.59 1.41l8.83 8.83c.78.78 2.05.78 2.83 0l7.17-7.17c.78-.78.78-2.04-.01-2.83zM6.5 8C5.67 8 5 7.33 5 6.5S5.67 5 6.5 5 8 5.67 8 6.5 7.33 8 6.5 8z" />
                                </svg>
                                <?php the_tags('', ', '); ?>
                            </span>
                        <?php endif; ?>
                    </div>
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
                <footer class="entry-footer">
                    <?php if (current_user_can('edit_posts')) : ?>
                        <?php
                        edit_post_link(
                            __('Edit Post', 'attribute-canva'),
                            '<div class="edit-link"><span class="btn btn-secondary btn-small">',
                            '</span></div>'
                        );
                        ?>
                    <?php endif; ?>
                </footer><!-- .entry-footer -->
            </article><!-- #post-<?php the_ID(); ?> -->

            <!-- Post Navigation -->
            <nav class="post-navigation" aria-label="<?php esc_attr_e('Post navigation', 'attribute-canva'); ?>">
                <div class="nav-previous">
                    <?php
                    previous_post_link(
                        '%link',
                        '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                        </svg>' . __('Previous Post', 'attribute-canva')
                    );
                    ?>
                </div>
                <div class="nav-next">
                    <?php
                    next_post_link(
                        '%link',
                        __('Next Post', 'attribute-canva') . '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/>
                        </svg>'
                    );
                    ?>
                </div>
            </nav><!-- .post-navigation -->

            <!-- Author Bio -->
            <?php if (get_the_author_meta('description')) : ?>
                <div class="author-bio card">
                    <div class="author-avatar">
                        <?php echo get_avatar(get_the_author_meta('ID'), 80); ?>
                    </div>
                    <div class="author-info">
                        <h3 class="author-name">
                            <?php printf(__('About %s', 'attribute-canva'), get_the_author()); ?>
                        </h3>
                        <p class="author-description">
                            <?php echo wp_kses_post(get_the_author_meta('description')); ?>
                        </p>
                        <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"
                            class="btn btn-outline btn-small">
                            <?php printf(__('View all posts by %s', 'attribute-canva'), get_the_author()); ?>
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Comments Section -->
            <?php if (comments_open() || get_comments_number()) : ?>
                <div class="comments-wrapper">
                    <?php comments_template(); ?>
                </div>
            <?php endif; ?>

        <?php endwhile; ?>
    </div><!-- .container -->
</main><!-- #primary -->

<?php get_footer(); ?>