<?php

/**
 * Widgets and Sidebars
 */

if (!function_exists('attributes_canva_widgets_init')) {
    /**
     * Register widget areas (sidebar and footer)
     */
    function attributes_canva_widgets_init()
    {
        // Primary Sidebar widget
        register_sidebar([
            'name'          => __('Primary Sidebar', 'attribute-canva'),
            'id'            => 'sidebar-1',
            'description'   => __('Widgets added here will appear in the sidebar.', 'attribute-canva'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ]);

        // Footer widget areas (3 columns)
        for ($i = 1; $i <= 3; $i++) {
            register_sidebar(array(
                'name'          => sprintf(__('Footer %d', 'attribute-canva'), $i),
                'id'            => 'footer-' . $i,
                'description'   => sprintf(__('Add footer widgets here for column %d.', 'attribute-canva'), $i),
                'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h2 class="footer-widget-title">',
                'after_title'   => '</h2>',
            ));
        }

        // Page sidebar widget
        register_sidebar([
            'name'          => __('Page Sidebar', 'attribute-canva'),
            'id'            => 'page-sidebar',
            'description'   => __('Widgets in this area will be shown on all pages.', 'attribute-canva'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ]);
    }
    add_action('widgets_init', 'attributes_canva_widgets_init');
}
