<?php
/**
 * The template for displaying single posts.
 *
 * @package Attribute Canva
 */

get_header(); ?>

<main id="primary" class="site-main">
    <?php
    while (have_posts()) :
        the_post();
        ?>

        <!-- Post Content -->
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <?php if (has_post_thumbnail()) : ?>
                    <div class="post-thumbnail">
                        <?php the_post_thumbnail('large'); ?>
                    </div>
                <?php endif; ?>

                <h1 class="entry-title"><?php the_title(); ?></h1>

                <div class="entry-meta">
                    <span class="posted-on">
                        <?php printf(__('Posted on %s', 'attribute-canva'), get_the_date()); ?>
                    </span>
                    <span class="byline">
                        <?php printf(__('by %s', 'attribute-canva'), get_the_author_posts_link()); ?>
                    </span>
                    <span class="category">
                        <?php _e('Categories: ', 'attribute-canva'); the_category(', '); ?>
                    </span>
                    <span class="tags">
                        <?php the_tags(__('Tags: ', 'attribute-canva'), ', '); ?>
                    </span>
                </div>
            </header><!-- .entry-header -->

            <div class="entry-content">
                <?php the_content(); ?>
                <?php
                wp_link_pages([
                    'before' => '<div class="page-links">' . __('Pages:', 'attribute-canva'),
                    'after'  => '</div>',
                ]);
                ?>
            </div><!-- .entry-content -->

            <footer class="entry-footer">
                <?php edit_post_link(__('Edit', 'attribute-canva'), '<span class="edit-link">', '</span>'); ?>
            </footer><!-- .entry-footer -->
        </article><!-- #post-<?php the_ID(); ?> -->

        <!-- Post Navigation -->
        <nav class="post-navigation">
            <div class="nav-previous"><?php previous_post_link('%link', __('&larr; Previous Post', 'attribute-canva')); ?></div>
            <div class="nav-next"><?php next_post_link('%link', __('Next Post &rarr;', 'attribute-canva')); ?></div>
        </nav><!-- .post-navigation -->

        <!-- Comments Section -->
        <?php
        if (comments_open() || get_comments_number()) :
            comments_template();
        endif;
    endwhile;
    ?>
</main><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
