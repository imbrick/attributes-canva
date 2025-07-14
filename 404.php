<?php

/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package Attribute Canva
 */

get_header(); ?>

<main id="primary" class="site-main">
    <section class="error-404 not-found">
        <header class="page-header">
            <h1 class="page-title"><?php esc_html_e('Oops! That page can&rsquo;t be found.', 'attribute-canva'); ?></h1>
        </header><!-- .page-header -->

        <div class="page-content">
            <p><?php esc_html_e('It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'attribute-canva'); ?></p>

            <?php get_search_form(); ?>

            <div class="widget-area">
                <?php if (is_active_sidebar('sidebar-1')) : ?>
                    <div class="widget">
                        <h2 class="widget-title"><?php esc_html_e('Most Used Categories', 'attribute-canva'); ?></h2>
                        <ul>
                            <?php
                            wp_list_categories(array(
                                'orderby'    => 'count',
                                'order'      => 'DESC',
                                'show_count' => 1,
                                'title_li'   => '',
                                'number'     => 10,
                            ));
                            ?>
                        </ul>
                    </div><!-- .widget -->

                    <div class="widget">
                        <h2 class="widget-title"><?php esc_html_e('Recent Posts', 'attribute-canva'); ?></h2>
                        <ul>
                            <?php
                            $recent_posts = wp_get_recent_posts(array(
                                'numberposts' => 5,
                                'post_status' => 'publish',
                            ));
                            foreach ($recent_posts as $post) : ?>
                                <li><a href="<?php echo get_permalink($post['ID']); ?>"><?php echo esc_html($post['post_title']); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div><!-- .widget -->
                <?php endif; ?>

                <div class="widget">
                    <h2 class="widget-title"><?php esc_html_e('Archives', 'attribute-canva'); ?></h2>
                    <ul>
                        <?php wp_get_archives(array('type' => 'monthly')); ?>
                    </ul>
                </div><!-- .widget -->

                <div class="widget">
                    <h2 class="widget-title"><?php esc_html_e('Tags', 'attribute-canva'); ?></h2>
                    <?php
                    $tags = get_tags();
                    if ($tags) {
                        foreach ($tags as $tag) {
                            echo '<a href="' . get_tag_link($tag->term_id) . '">' . $tag->name . '</a> ';
                        }
                    }
                    ?>
                </div><!-- .widget -->
            </div><!-- .widget-area -->
        </div><!-- .page-content -->
    </section><!-- .error-404 -->
</main><!-- #primary -->

<?php get_footer(); ?>