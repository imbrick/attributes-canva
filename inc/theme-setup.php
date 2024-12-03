<?php

/**
 * Theme Setup
 */

if (!function_exists('attributes_canva_theme_setup')) {
    /**
     * Add theme supports and setup features
     */
    function attributes_canva_theme_setup()
    {
        add_theme_support('post-thumbnails');
        add_theme_support('automatic-feed-links');
        add_theme_support('title-tag');
        add_theme_support(
            'html5',
            [
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
                'script',
                'style',
            ]
        );

        // Add support for custom logo
        add_theme_support('custom-logo', array(
            'height'      => 100,
            'width'       => 350,
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

        // Gutenberg wide images.
        add_theme_support('align-wide');
    }
    add_action('after_setup_theme', 'attributes_canva_theme_setup');
}
