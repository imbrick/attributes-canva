<?php
/**
 * Attribute Canva Theme Functions
 */

// Include files from the inc/ folder
require_once get_template_directory() . '/inc/enqueue-scripts.php';
require_once get_template_directory() . '/inc/ajax-handlers.php';
require_once get_template_directory() . '/inc/theme-setup.php';
require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/widgets.php';
require_once get_template_directory() . '/inc/starter-content.php';

// Add any theme-specific code here, if necessary.

/**************************\
 * SEO AND PERFORMANCE
\**************************/

if (!function_exists('attribute_canva_customizer_css')) {
    /**
     * Apply customizer styles dynamically
     */
    function attribute_canva_customizer_css() {
        $accent_color = get_theme_mod('attribute_canva_accent_color', '#FF5722');
        echo "<style>
            :root {
                --accent-color: $accent_color;
            }
        </style>";
    }
    add_action('wp_head', 'attribute_canva_customizer_css');
}

/**
 * Lazy load images for performance
 */
add_filter('wp_get_attachment_image_attributes', function ($attr) {
    $attr['loading'] = 'lazy';
    return $attr;
});

