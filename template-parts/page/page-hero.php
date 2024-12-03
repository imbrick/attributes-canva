<?php
/**
 * Template part for displaying the page hero.
 *
 * @package Attribute Canva
 */
?>

<div class="page-hero">
    <?php if (has_post_thumbnail()) : ?>
        <div class="page-thumbnail">
            <?php the_post_thumbnail('large'); ?>
        </div>
    <?php endif; ?>

    <h1 class="page-title"><?php the_title(); ?></h1>
</div><!-- .page-hero -->
