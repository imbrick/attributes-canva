<?php

/**
 * Attributes Canva Theme Functions
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Define theme constants
define('ATTRIBUTE_CANVA_VERSION', '1.0.0');
define('ATTRIBUTE_CANVA_THEME_DIR', get_template_directory());
define('ATTRIBUTE_CANVA_THEME_URI', get_template_directory_uri());

// Include files from the inc/ folder
$inc_dir = get_template_directory() . '/inc/';

require_once($inc_dir . 'theme-setup.php');
require_once($inc_dir . 'enqueue-scripts.php');
require_once($inc_dir . 'ajax-handlers.php');
require_once($inc_dir . 'customizer.php');
require_once($inc_dir . 'widgets.php');
require_once($inc_dir . 'starter-content.php');

// Include Elementor compatibility if Elementor is active
if (did_action('elementor/loaded')) {
    require_once($inc_dir . 'elementor/custom-widget.php');
}

/**************************\
 * THEME UTILITY FUNCTIONS
\**************************/

if (!function_exists('attribute_canva_entry_footer')) {
    /**
     * Prints HTML with meta information for the categories, tags and comments.
     */
    function attribute_canva_entry_footer()
    {
        // Hide category and tag text for pages.
        if ('post' === get_post_type()) {
            /* translators: used between list items, there is a space after the comma */
            $categories_list = get_the_category_list(esc_html__(', ', 'attribute-canva'));
            if ($categories_list) {
                /* translators: 1: list of categories. */
                printf('<span class="cat-links">' . esc_html__('Posted in %1$s', 'attribute-canva') . '</span>', $categories_list); // WPCS: XSS OK.
            }

            /* translators: used between list items, there is a space after the comma */
            $tags_list = get_the_tag_list('', esc_html_x(', ', 'list item separator', 'attribute-canva'));
            if ($tags_list) {
                /* translators: 1: list of tags. */
                printf('<span class="tags-links">' . esc_html__('Tagged %1$s', 'attribute-canva') . '</span>', $tags_list); // WPCS: XSS OK.
            }
        }

        if (!is_single() && !post_password_required() && (comments_open() || get_comments_number())) {
            echo '<span class="comments-link">';
            comments_popup_link(
                sprintf(
                    wp_kses(
                        /* translators: %s: post title */
                        __('Leave a Comment<span class="screen-reader-text"> on %s</span>', 'attribute-canva'),
                        array(
                            'span' => array(
                                'class' => array(),
                            ),
                        )
                    ),
                    get_the_title()
                )
            );
            echo '</span>';
        }

        edit_post_link(
            sprintf(
                wp_kses(
                    /* translators: %s: Name of current post. Only visible to screen readers */
                    __('Edit <span class="screen-reader-text">%s</span>', 'attribute-canva'),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                get_the_title()
            ),
            '<span class="edit-link">',
            '</span>'
        );
    }
}

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

        if ($accent_color) {
            echo "<style type='text/css'>
                :root {
                    --accent-color: " . sanitize_hex_color($accent_color) . ";
                }
                a:hover, a:focus {
                    color: " . sanitize_hex_color($accent_color) . ";
                }
                .cta-button {
                    background-color: " . sanitize_hex_color($accent_color) . ";
                }
            </style>";
        }
    }
    add_action('wp_head', 'attributes_canva_customizer_css');
}

/**
 * Lazy load images for performance
 */
if (!function_exists('attributes_canva_add_lazy_loading')) {
    function attributes_canva_add_lazy_loading($attr, $attachment, $size)
    {
        if (is_admin()) {
            return $attr;
        }

        if (!isset($attr['loading'])) {
            $attr['loading'] = 'lazy';
        }

        return $attr;
    }
    add_filter('wp_get_attachment_image_attributes', 'attributes_canva_add_lazy_loading', 10, 3);
}

/**
 * Add Schema Markup for SEO
 */
if (!function_exists('attributes_canva_add_schema_markup')) {
    function attributes_canva_add_schema_markup()
    {
        if (is_single() || is_page()) {
            $schema = array(
                '@context' => 'https://schema.org',
                '@type' => 'WebPage',
                'name' => get_the_title(),
                'url' => get_permalink(),
                'description' => get_the_excerpt() ?: get_bloginfo('description'),
                'author' => array(
                    '@type' => 'Person',
                    'name' => get_the_author()
                ),
                'datePublished' => get_the_date('c'),
                'dateModified' => get_the_modified_date('c')
            );

            echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>';
        }
    }
    add_action('wp_head', 'attributes_canva_add_schema_markup');
}

/**
 * Remove unnecessary WordPress head elements
 */
if (!function_exists('attributes_canva_clean_head')) {
    function attributes_canva_clean_head()
    {
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wp_shortlink_wp_head');
    }
    add_action('init', 'attributes_canva_clean_head');
}

/**
 * Add custom body classes
 */
if (!function_exists('attributes_canva_body_classes')) {
    function attributes_canva_body_classes($classes)
    {
        // Add page slug to body class if not front page
        if (is_page() && !is_front_page()) {
            $classes[] = 'page-' . sanitize_html_class(get_post_field('post_name'));
        }

        // Add elementor class if Elementor is active
        if (did_action('elementor/loaded')) {
            $classes[] = 'elementor-active';
        }

        return $classes;
    }
    add_filter('body_class', 'attributes_canva_body_classes');
}
