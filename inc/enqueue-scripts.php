<?php

/**
 * Enqueue styles and scripts
 */

if (!function_exists('attributes_canva_enqueue_scripts')) {
    /**
     * Enqueue styles, scripts, and localize AJAX variables
     * Ensures proper support for child themes.
     */
    function attributes_canva_enqueue_scripts()
    {
        // Enqueue custom CSS from child or parent theme
        wp_enqueue_style(
            'custom-style',
            get_stylesheet_directory_uri() . '/assets/css/style.css',
            array(),
            '1.0'
        );

        // Enqueue dark mode CSS from child or parent theme
        wp_enqueue_style(
            'dark-mode',
            get_stylesheet_directory_uri() . '/assets/css/dark-mode.css',
            array(),
            '1.0'
        );

        // Enqueue parent theme's main stylesheet
        wp_enqueue_style(
            'attributes-style',
            get_template_directory_uri() . '/style.css'
        );

        // Enqueue custom JavaScript (child-safe)
        wp_enqueue_script(
            'custom-script',
            get_stylesheet_directory_uri() . '/assets/js/script.js',
            array('jquery'),
            '1.0',
            true
        );

        // Enqueue dark mode toggle script (child-safe)
        wp_enqueue_script(
            'dark-mode-toggle',
            get_stylesheet_directory_uri() . '/assets/js/dark-mode.js',
            array('jquery'),
            '1.0',
            true
        );

        // Enqueue AJAX script and localize variables
        wp_enqueue_script(
            'attributes-ajax',
            get_stylesheet_directory_uri() . '/assets/js/ajax.js',
            array('jquery'),
            '1.0',
            true
        );

        // Pass AJAX URL and nonce to the script
        wp_localize_script('attributes-ajax', 'attributesAjax', array(
            'ajax_url' => admin_url('admin-ajax.php'), // WordPress AJAX handler
            'nonce'    => wp_create_nonce('attributes_ajax_nonce') // Nonce for security
        ));
    }
    add_action('wp_enqueue_scripts', 'attributes_canva_enqueue_scripts');
}
