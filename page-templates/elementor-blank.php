<?php

/**
 * Template Name: Elementor Blank Canvas
 * Template Post Type: page
 */

get_header();
?>
<main id="main" class="elementor-blank-template">
    <?php
    while (have_posts()) : the_post();
        the_content();
    endwhile;
    ?>
</main>
<?php get_footer(); ?>