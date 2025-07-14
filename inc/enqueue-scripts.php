<?php

/**
 * Enqueue styles and scripts
 *
 * Security check - prevent direct access
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!function_exists('attributes_canva_enqueue_scripts')) {
    /**
     * Enqueue styles, scripts, and localize AJAX variables
     * Ensures proper support for child themes.
     */
    function attributes_canva_enqueue_scripts()
    {
        // Critical CSS first
        wp_enqueue_style(
            'attributes-critical',
            ATTRIBUTE_CANVA_THEME_URI . '/style.css',
            [],
            ATTRIBUTE_CANVA_VERSION
        );

        // Main styles
        wp_enqueue_style(
            'attributes-main',
            ATTRIBUTE_CANVA_THEME_URI . '/assets/css/style.css',
            ['attributes-critical'],
            ATTRIBUTE_CANVA_VERSION
        );

        // Conditional loading
        if (is_page_template('page-templates/elementor-blank.php')) {
            // Don't load theme styles for Elementor canvas
            wp_dequeue_style('attributes-main');
        }

        // Dark mode CSS (conditional)
        if (get_theme_mod('attributes_canva_enable_dark_mode', true)) {
            wp_enqueue_style(
                'attributes-dark-mode',
                ATTRIBUTE_CANVA_THEME_URI . '/assets/css/dark-mode.css',
                ['attributes-main'],
                ATTRIBUTE_CANVA_VERSION
            );
        }

        // JavaScript with proper dependencies
        wp_enqueue_script(
            'attributes-enhanced',
            ATTRIBUTE_CANVA_THEME_URI . '/assets/js/enhanced.js',
            ['jquery'],
            ATTRIBUTE_CANVA_VERSION,
            true
        );

        // Localize script
        wp_localize_script('attributes-enhanced', 'attributesEnhanced', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('attributes_enhanced_nonce'),
            'theme_url' => ATTRIBUTE_CANVA_THEME_URI,
            'is_mobile' => wp_is_mobile(),
            'strings' => [
                'loading' => __('Loading...', 'attribute-canva'),
                'error' => __('An error occurred', 'attribute-canva'),
            ]
        ]);
    }
    add_action('wp_enqueue_scripts', 'attributes_canva_enqueue_scripts');
}
