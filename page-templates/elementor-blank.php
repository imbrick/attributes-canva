<?php
/**
 * Template Name: Elementor Blank
 * Template Post Type: post, page
 *
 * A blank template for Elementor.
 *
 * @package Attribute Canva
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header(); ?>

<main id="primary" class="site-main">
    <?php
    while (have_posts()) :
        the_post();

        // Elementor compatibility - display the content
        the_content();

    endwhile; // End of the loop.
    ?>
</main><!-- #primary -->

<?php get_footer(); ?>
