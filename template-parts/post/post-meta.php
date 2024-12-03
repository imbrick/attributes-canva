<?php
/**
 * Template part for displaying post meta.
 *
 * @package Attribute Canva
 */
?>

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
</div><!-- .entry-meta -->
