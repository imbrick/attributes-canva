<?php

/**
 * Template part for displaying a message when no search results are found.
 *
 * @package Attribute Canva
 *
 * Security check - prevent direct access
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<section class="no-results not-found">
    <header class="page-header">
        <h1 class="page-title"><?php esc_html_e('No Results Found', 'attribute-canva'); ?></h1>
    </header><!-- .page-header -->

    <div class="page-content">
        <p>
            <?php
            printf(
                /* translators: %s: search query. */
                esc_html__('Sorry, no results were found for "%s". Please try again with different keywords.', 'attribute-canva'),
                '<strong>' . esc_html(get_search_query()) . '</strong>'
            );
            ?>
        </p>
        <?php get_search_form(); ?>
    </div><!-- .page-content -->
</section><!-- .no-results -->