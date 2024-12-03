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
            'attributes-style',
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
            'attributes-main-style',
            get_template_directory_uri() . '/style.css'
        );

        // Enqueue custom JavaScript (child-safe)
        wp_enqueue_script(
            'attributes-script',
            get_stylesheet_directory_uri() . '/assets/js/script.js',
            array('jquery'),
            '1.0',
            true
        );

        // Enqueue dark mode toggle script (child-safe)
        wp_enqueue_script(
            'attributes-dark-mode-toggle',
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
        wp_localize_script('attr-ajax', 'attrAjax', array(
            'ajax_url' => admin_url('admin-ajax.php'), // WordPress AJAX handler
            'nonce'    => wp_create_nonce('attr_ajax_nonce') // Nonce for security
        ));

        // Check if Elementor is active on the current page
        if (function_exists('elementor_load_plugin_textdomain') && \Elementor\Plugin::$instance->preview->is_preview_mode()) {
            // Remove unnecessary theme styles/scripts
            wp_dequeue_style('theme-default-styles'); // Replace with your theme's style handle
            wp_dequeue_script('theme-default-scripts'); // Replace with your theme's script handle
        }
    }
    add_action('wp_enqueue_scripts', 'attributes_canva_enqueue_scripts');
}
