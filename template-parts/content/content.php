<?php

/**
 * Template part for displaying posts.
 *
 * @package Attribute Canva
 *
 * Security check - prevent direct access
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <?php if (has_post_thumbnail()) : ?>
            <a href="<?php the_permalink(); ?>" class="post-thumbnail">
                <?php the_post_thumbnail('medium'); ?>
            </a>
        <?php endif; ?>

        <?php the_title(sprintf('<h2 class="entry-title"><a href="%s">', esc_url(get_permalink())), '</a></h2>'); ?>
    </header><!-- .entry-header -->

    <div class="entry-meta">
        <span class="posted-on">
            <?php printf(__('Posted on %s', 'attribute-canva'), get_the_date()); ?>
        </span>
        <span class="byline">
            <?php printf(__('by %s', 'attribute-canva'), get_the_author_posts_link()); ?>
        </span>
    </div><!-- .entry-meta -->

    <div class="entry-summary">
        <?php the_excerpt(); ?>
    </div><!-- .entry-summary -->

    <footer class="entry-footer">
        <?php edit_post_link(__('Edit', 'attribute-canva'), '<span class="edit-link">', '</span>'); ?>
    </footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->