<?php

/**
 * The template for displaying archive pages.
 * Enhanced with modern grid layout
 *
 * @package Attribute Canva
 */

get_header(); ?>

<main id="primary" class="site-main">
    <div class="container">

        <!-- Archive Header -->
        <header class="archive-header">
            <?php if (have_posts()) : ?>
                <h1 class="archive-title">
                    <?php
                    if (is_category()) {
                        single_cat_title();
                    } elseif (is_tag()) {
                        single_tag_title();
                    } elseif (is_author()) {
                        printf(
                            __('Posts by %s', 'attribute-canva'),
                            '<span class="vcard">' . get_the_author() . '</span>'
                        );
                    } elseif (is_date()) {
                        if (is_year()) {
                            printf(__('Year: %s', 'attribute-canva'), get_the_date('Y'));
                        } elseif (is_month()) {
                            printf(__('Month: %s', 'attribute-canva'), get_the_date('F Y'));
                        } else {
                            printf(__('Day: %s', 'attribute-canva'), get_the_date());
                        }
                    } else {
                        _e('Archives', 'attribute-canva');
                    }
                    ?>
                </h1>

                <?php if (is_category() || is_tag()) : ?>
                    <div class="archive-description">
                        <?php echo wp_kses_post(term_description()); ?>
                    </div>
                <?php endif; ?>

                <!-- Post Count -->
                <div class="archive-meta">
                    <span class="post-count">
                        <?php
                        global $wp_query;
                        $total = $wp_query->found_posts;
                        printf(
                            _n('%s post found', '%s posts found', $total, 'attribute-canva'),
                            number_format_i18n($total)
                        );
                        ?>
                    </span>
                </div>

            <?php else : ?>
                <h1 class="archive-title"><?php _e('Nothing Found', 'attribute-canva'); ?></h1>
                <p class="archive-description">
                    <?php _e('It seems we can\'t find any posts matching your query.', 'attribute-canva'); ?>
                </p>
            <?php endif; ?>
        </header><!-- .archive-header -->

        <?php if (have_posts()) : ?>
            <!-- Posts Grid -->
            <div class="post-grid">
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                        <!-- Featured Image -->
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-thumbnail">
                                <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                                    <?php the_post_thumbnail('medium_large', array(
                                        'alt' => the_title_attribute(array('echo' => false))
                                    )); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <!-- Entry Header -->
                        <header class="entry-header">
                            <?php the_title(sprintf(
                                '<h2 class="entry-title"><a href="%s" rel="bookmark">',
                                esc_url(get_permalink())
                            ), '</a></h2>'); ?>

                            <div class="entry-meta">
                                <span class="posted-on">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z" />
                                    </svg>
                                    <?php echo get_the_date(); ?>
                                </span>

                                <span class="byline">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                    </svg>
                                    <?php the_author_posts_link(); ?>
                                </span>

                                <?php if (has_category()) : ?>
                                    <span class="category">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M17.63 5.84C17.27 5.33 16.67 5 16 5L5 5.01C3.9 5.01 3 5.9 3 7v10c0 1.1.9 1.99 2 1.99L16 19c.67 0 1.27-.33 1.63-.84L22 12l-4.37-6.16z" />
                                        </svg>
                                        <?php the_category(', '); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </header><!-- .entry-header -->

                        <!-- Entry Summary -->
                        <div class="entry-content">
                            <div class="entry-summary">
                                <?php the_excerpt(); ?>
                            </div>

                            <div class="read-more">
                                <a href="<?php the_permalink(); ?>" class="btn btn-outline btn-small">
                                    <?php _e('Read More', 'attribute-canva'); ?>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z" />
                                    </svg>
                                </a>
                            </div>
                        </div><!-- .entry-content -->

                        <!-- Entry Footer -->
                        <footer class="entry-footer">
                            <?php if (has_tag()) : ?>
                                <div class="post-tags">
                                    <?php the_tags('<span class="tags-label">Tags:</span> ', ', '); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (current_user_can('edit_posts')) : ?>
                                <?php
                                edit_post_link(
                                    __('Edit', 'attribute-canva'),
                                    '<span class="edit-link btn btn-secondary btn-small">',
                                    '</span>'
                                );
                                ?>
                            <?php endif; ?>
                        </footer><!-- .entry-footer -->
                    </article><!-- #post-<?php the_ID(); ?> -->
                <?php endwhile; ?>
            </div><!-- .post-grid -->

            <!-- Pagination -->
            <nav class="pagination-wrapper" aria-label="<?php esc_attr_e('Posts pagination', 'attribute-canva'); ?>">
                <?php
                the_posts_pagination([
                    'mid_size'  => 2,
                    'prev_text' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                    </svg>' . __('Previous', 'attribute-canva'),
                    'next_text' => __('Next', 'attribute-canva') . '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/>
                    </svg>',
                    'before_page_number' => '<span class="meta-nav screen-reader-text">' . __('Page', 'attribute-canva') . ' </span>',
                ]);
                ?>
            </nav>

        <?php else : ?>
            <!-- No Results -->
            <section class="no-results not-found card">
                <header class="page-header">
                    <h2 class="page-title"><?php _e('Nothing Found', 'attribute-canva'); ?></h2>
                </header>

                <div class="page-content">
                    <p><?php _e('It seems we can\'t find what you\'re looking for. Perhaps searching can help.', 'attribute-canva'); ?></p>

                    <!-- Search Form -->
                    <div class="search-form-wrapper">
                        <?php get_search_form(); ?>
                    </div>

                    <!-- Helpful Links -->
                    <div class="helpful-links">
                        <h3><?php _e('You might also like:', 'attribute-canva'); ?></h3>
                        <ul>
                            <li><a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-outline">
                                    <?php _e('Return to Homepage', 'attribute-canva'); ?>
                                </a></li>
                            <li><a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="btn btn-outline">
                                    <?php _e('Browse All Posts', 'attribute-canva'); ?>
                                </a></li>
                        </ul>
                    </div>
                </div>
            </section><!-- .no-results -->
        <?php endif; ?>
    </div><!-- .container -->
</main><!-- #primary -->

<?php get_footer(); ?>