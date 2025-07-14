<?php

/**
 * Template part for displaying the main site header.
 * Enhanced for modern UX/UI standards
 *
 * @package Attribute Canva
 *
 * Security check - prevent direct access
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<div class="header-container">
    <!-- Site Branding -->
    <div class="site-branding">
        <?php if (has_custom_logo()) : ?>
            <div class="site-logo">
                <?php the_custom_logo(); ?>
            </div>
        <?php endif; ?>

        <div class="site-identity">
            <h1 class="site-title">
                <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                    <?php bloginfo('name'); ?>
                </a>
            </h1>

            <?php if (display_header_text()) : ?>
                <p class="site-description"><?php bloginfo('description'); ?></p>
            <?php endif; ?>
        </div>
    </div><!-- .site-branding -->

    <!-- Main Navigation -->
    <nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e('Primary Navigation', 'attribute-canva'); ?>">
        <?php
        wp_nav_menu([
            'theme_location' => 'primary',
            'menu_id'        => 'primary-menu',
            'container'      => false,
            'fallback_cb'    => 'wp_page_menu',
            'depth'          => 2,
        ]);
        ?>
    </nav><!-- #site-navigation -->

    <!-- Header Actions -->
    <div class="header-actions">
        <!-- Search Form -->
        <div class="header-search">
            <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                <label for="header-search" class="sr-only">
                    <?php esc_html_e('Search for:', 'attribute-canva'); ?>
                </label>
                <input
                    type="search"
                    id="header-search"
                    class="search-field"
                    placeholder="<?php esc_attr_e('Search...', 'attribute-canva'); ?>"
                    value="<?php echo get_search_query(); ?>"
                    name="s"
                    aria-label="<?php esc_attr_e('Search', 'attribute-canva'); ?>" />
            </form>
        </div><!-- .header-search -->

        <!-- CTA Button -->
        <?php if (get_theme_mod('header_cta_enabled', true)) : ?>
            <div class="header-cta">
                <a href="<?php echo esc_url(get_theme_mod('header_cta_url', home_url('/contact'))); ?>"
                    class="btn btn-primary">
                    <?php echo esc_html(get_theme_mod('header_cta_text', __('Get Started', 'attribute-canva'))); ?>
                </a>
            </div><!-- .header-cta -->
        <?php endif; ?>

        <!-- Mobile Menu Toggle -->
        <button class="menu-toggle"
            aria-controls="primary-menu"
            aria-expanded="false"
            aria-label="<?php esc_attr_e('Toggle navigation menu', 'attribute-canva'); ?>">
            <span class="menu-icon">
                <span></span>
                <span></span>
                <span></span>
            </span>
            <span class="sr-only"><?php esc_html_e('Menu', 'attribute-canva'); ?></span>
        </button>
    </div><!-- .header-actions -->
</div><!-- .header-container -->