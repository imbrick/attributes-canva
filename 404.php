<?php

/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package Attribute Canva
 */

get_header(); ?>

<main id="primary" class="site-main">
    <section class="error-404 not-found">
        <div class="container">
            <header class="page-header">
                <h1 class="page-title"><?php esc_html_e('404 - Page Not Found', 'attribute-canva'); ?></h1>
                <div class="error-illustration">
                    <svg width="200" height="200" viewBox="0 0 200 200" fill="none">
                        <circle cx="100" cy="100" r="80" stroke="var(--primary-color)" stroke-width="4" />
                        <text x="100" y="110" text-anchor="middle" font-size="48" fill="var(--primary-color)">404</text>
                    </svg>
                </div>
            </header>

            <div class="page-content">
                <p><?php esc_html_e('The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'attribute-canva'); ?></p>

                <!-- Search Form -->
                <div class="search-form-wrapper">
                    <?php get_search_form(); ?>
                </div>

                <!-- Popular Posts -->
                <div class="popular-content">
                    <h3><?php esc_html_e('Popular Posts', 'attribute-canva'); ?></h3>
                    <?php
                    $popular_posts = new WP_Query([
                        'posts_per_page' => 3,
                        'meta_key' => '_post_views_count',
                        'orderby' => 'meta_value_num',
                        'order' => 'DESC'
                    ]);

                    if ($popular_posts->have_posts()) :
                        echo '<ul class="popular-posts-list">';
                        while ($popular_posts->have_posts()) : $popular_posts->the_post();
                            echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
                        endwhile;
                        echo '</ul>';
                        wp_reset_postdata();
                    endif;
                    ?>
                </div>

                <!-- Quick Navigation -->
                <div class="quick-nav">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                        <?php esc_html_e('Return Home', 'attribute-canva'); ?>
                    </a>
                    <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="btn btn-outline">
                        <?php esc_html_e('Browse Blog', 'attribute-canva'); ?>
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>