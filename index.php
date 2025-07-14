<?php

/**
 * The main template file.
 *
 * This is a fallback template file in WorPress.
 * It is used to display content when no more specific template is found.
 *
 * @package Attribute_Canva
 * @author  Theme Author
 * @license GPL-2.0+
 * @link    https://example.com
 * @since   1.0.0
 *
 * Security check - prevent direct access
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header(); ?>

<main id="primary" class="site-main">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <?php if (is_singular()) : ?>
                        <h1 class="entry-title"><?php the_title(); ?></h1>
                    <?php else : ?>
                        <h2 class="entry-title">
                            <a href="<?php the_permalink(); ?>" rel="bookmark">
                                <?php the_title(); ?>
                            </a>
                        </h2>
                    <?php endif; ?>
                </header><!-- .entry-header -->

                <div class="entry-content">
                    <?php
                    the_content(
                        sprintf(
                            wp_kses(
                                __(
                                    'Continue reading %s ' .
                                        '<span class="meta-nav">&rarr;</span>',
                                    'attribute-canva'
                                ),
                                ['span' => ['class' => []]]
                            ),
                            the_title(
                                '<span class="screen-reader-text">"',
                                '"</span>',
                                false
                            )
                        )
                    );
                    ?>
                </div><!-- .entry-content -->

                <footer class="entry-footer">
                    <?php attribute_canva_entry_footer(); ?>
                </footer><!-- .entry-footer -->
            </article><!-- #post-<?php the_ID(); ?> -->
        <?php endwhile; ?>

        <?php the_posts_pagination(); ?>
    <?php else : ?>
        <section class="no-results not-found">
            <header class="page-header">
                <h1 class="page-title">
                    <?php esc_html_e('Nothing Found', 'attribute-canva'); ?>
                </h1>
            </header><!-- .page-header -->

            <div class="page-content">
                <?php if (is_home() && current_user_can('publish_posts')) : ?>
                    <p>
                        <?php
                        printf(
                            wp_kses(
                                __(
                                    'Ready to publish your first post? ' .
                                        '<a href="%1$s">Get started here</a>.',
                                    'attribute-canva'
                                ),
                                ['a' => ['href' => []]]
                            ),
                            esc_url(admin_url('post-new.php'))
                        );
                        ?>
                    </p>
                <?php else : ?>
                    <p><?php esc_html_e('It seems we can’t find what you’re looking for. Perhaps searching can help.', 'attribute-canva'); ?></p>
                    <?php get_search_form(); ?>
                <?php endif; ?>
            </div><!-- .page-content -->
        </section><!-- .no-results -->
    <?php endif; ?>
</main><!-- #primary -->

<?php
get_sidebar();
get_footer();
