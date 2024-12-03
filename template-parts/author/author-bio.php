<?php
/**
 * Template part for displaying an author's bio.
 *
 * @package Attribute Canva
 */
?>

<div class="author-bio-section">
    <div class="author-avatar">
        <?php echo get_avatar(get_the_author_meta('ID'), 96); ?>
    </div>
    <div class="author-info">
        <h2 class="author-title"><?php echo esc_html(get_the_author()); ?></h2>
        <p class="author-bio"><?php echo esc_html(get_the_author_meta('description')); ?></p>
    </div>
</div><!-- .author-bio-section -->
