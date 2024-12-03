<?php

/**
 * Widgets and Sidebars
 */

if (!function_exists('attribute_canva_widgets_init')) {
    /**
     * Register widget areas (sidebar and footer)
     */
    function attribute_canva_widgets_init()
    {
        // Sidebar widget
        register_sidebar(array(
            'name'          => __('Sidebar', 'attr-canva'),
            'id'            => 'sidebar',
            'description'   => __('Add widgets here.', 'attr-canva'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ));

        // Footer widget
        register_sidebar(array(
            'name'          => __('Footer', 'attr-canva'),
            'id'            => 'footer',
            'description'   => __('Add footer widgets here.', 'attr-canva'),
            'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h2 class="footer-widget-title">',
            'after_title'   => '</h2>',
        ));
    }
    add_action('widgets_init', 'attribute_canva_widgets_init');
}
