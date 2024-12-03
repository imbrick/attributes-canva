<?php

/**
 * Theme Setup
 */

if (!function_exists('attribute_canva_theme_setup')) {
    /**
     * Add theme supports and setup features
     */
    function attribute_canva_theme_setup()
    {
        // Add support for custom logo
        add_theme_support('custom-logo', array(
            'height'      => 100,
            'width'       => 400,
            'flex-height' => true,
            'flex-width'  => true,
        ));

        // Add support for Elementor
        add_theme_support('elementor');

        // Add support for WooCommerce
        add_theme_support('woocommerce');

        // Add support for editor styles (Gutenberg)
        add_theme_support('editor-styles');
        add_editor_style('style.css');

        // Register navigation menus
        register_nav_menus(array(
            'primary' => __('Primary Menu', 'attr-canva'),
            'header'  => __('Header Menu', 'attr-canva'),
            'footer'  => __('Footer Menu', 'attr-canva'),
            'sidebar' => __('Sidebar Menu', 'attr-canva'),
        ));
    }
    add_action('after_setup_theme', 'attribute_canva_theme_setup');
}
