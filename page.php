<?php
/**
 * The template for displaying all single pages.
 *
 * @package Attribute Canva
 */

get_header(); ?>

<main id="primary" class="site-main">
    <?php
    while (have_posts()) :
        the_post();
        ?>

        <!-- Page Content -->
        <article id="page-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <?php if (has_post_thumbnail()) : ?>
                    <div class="page-thumbnail">
                        <?php the_post_thumbnail('large'); ?>
                    </div>
                <?php endif; ?>

                <h1 class="entry-title"><?php the_title(); ?></h1>
            </header><!-- .entry-header -->

            <div class="entry-content">
                <?php
                the_content();

                wp_link_pages([
                    'before' => '<div class="page-links">' . __('Pages:', 'attribute-canva'),
                    'after'  => '</div>',
                ]);
                ?>
            </div><!-- .entry-content -->

            <?php if (current_user_can('edit_posts')) : ?>
                <footer class="entry-footer">
                    <?php edit_post_link(__('Edit Page', 'attribute-canva'), '<span class="edit-link">', '</span>'); ?>
                </footer><!-- .entry-footer -->
            <?php endif; ?>
        </article><!-- #page-<?php the_ID(); ?> -->

        <!-- Optional Comments Section -->
        <?php
        if (comments_open() || get_comments_number()) :
            comments_template();
        endif;
    endwhile;
    ?>
</main><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
