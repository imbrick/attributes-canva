<?php
/**
 * Attributes Canva Theme Functions
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Include files from the inc/ folder
$inc_dir = get_template_directory() . '/inc/';

require_once( $inc_dir . 'enqueue-scripts.php' );
require_once( $inc_dir . 'ajax-handlers.php' );
require_once( $inc_dir . 'theme-setup.php' );
require_once( $inc_dir . 'customizer.php' );
require_once( $inc_dir . 'widgets.php' );
require_once( $inc_dir . 'starter-content.php' );

// Add any theme-specific code here, if necessary.

/**************************\
 * SEO AND PERFORMANCE
\**************************/

if (!function_exists('attributes_canva_customizer_css')) {
    /**
     * Apply customizer styles dynamically
     */
    function attributes_canva_customizer_css()
    {
        $accent_color = esc_attr(get_theme_mod('attributes_canva_accent_color', '#FF5722'));
        echo "<style>
            :root {
                --accent-color: " . ($accent_color ?: '#FF5722') . ";
            }
        </style>";
    }
    add_action('wp_head', 'attributes_canva_customizer_css');
}

/**
 * Lazy load images for performance
 */
add_filter('wp_get_attachment_image_attributess', function ($attr) {
    if (is_admin() || !isset($attr['loading'])) {
        return $attr;
    }
    $attr['loading'] = 'lazy';
    return $attr;
});

/**
 * Add Schema Markup for SEO
 */
add_action('wp_head', function () {
    if (is_single() || is_page()) {
        echo '<script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "WebPage",
            "name": "' . esc_js(get_the_title()) . '",
            "url": "' . esc_url(get_permalink()) . '"
        }
        </script>';
    }
});
