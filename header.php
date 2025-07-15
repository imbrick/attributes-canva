<?php

/**
 * The header for the theme.
 *
 * Displays the <head> section, site branding, navigation, and search bar.
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


// Prevent direct access to this file.
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Improves browser tab color on mobile -->
    <meta name="theme-color" content="#ffffff">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php
    // Ensure the wp_body_open hook is present for compatibility with WP 5.2+.
    if (function_exists('wp_body_open')) {
        wp_body_open();
    }
    ?>

    <?php do_action('attribute_canva_before_header'); ?>

    <header id="site-header"
        class="site-header sticky-header"
        role="banner"
        aria-label="<?php esc_attr_e('Site Header', 'attribute-canva'); ?>">
        <?php get_template_part('template-parts/header/header-main'); ?>
    </header><!-- #site-header -->

    <?php do_action('attribute_canva_after_header'); ?>

    <noscript>
        <p>
            <?php
            esc_html_e(
                'JavaScript is disabled in your browser. ' .
                    'Some features of this site may not work as expected.',
                'attribute-canva'
            );
            ?>
        </p>
    </noscript>