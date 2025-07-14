<?php

/**
 * Attributes Canva Theme Functions
 * Enhanced with Page Builder, ACF, and Multilingual Support
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Define theme constants
define('ATTRIBUTE_CANVA_VERSION', '1.1.0');
define('ATTRIBUTE_CANVA_THEME_DIR', get_template_directory());
define('ATTRIBUTE_CANVA_THEME_URI', get_template_directory_uri());

// Include core files from the inc/ folder
$inc_dir = get_template_directory() . '/inc/';

require_once($inc_dir . 'theme-setup.php');
require_once($inc_dir . 'enqueue-scripts.php');
require_once($inc_dir . 'ajax-handlers.php');
require_once($inc_dir . 'customizer.php');
require_once($inc_dir . 'widgets.php');
require_once($inc_dir . 'starter-content.php');

// NEW: Enhanced integrations
require_once($inc_dir . 'page-builders.php');      // Page Builder Integration
require_once($inc_dir . 'acf-integration.php');    // ACF Integration
require_once($inc_dir . 'multilingual.php');       // Multilingual Support

// Include Elementor compatibility if Elementor is active
if (did_action('elementor/loaded')) {
    require_once($inc_dir . 'elementor/custom-widget.php');
}

/**************************\
 * ENHANCED THEME FEATURES
\**************************/

/**
 * Enhanced Theme Setup with New Features
 */
function attributes_canva_enhanced_theme_setup()
{
    // Add theme support for new features
    add_theme_support('custom-background');
    add_theme_support('custom-header', [
        'default-image' => '',
        'width' => 1920,
        'height' => 1080,
        'flex-height' => true,
        'flex-width' => true,
        'uploads' => true,
        'random-default' => false,
        'header-text' => true,
        'default-text-color' => '333',
    ]);

    // Add support for Block Editor features
    add_theme_support('responsive-embeds');
    add_theme_support('wp-block-styles');
    add_theme_support('editor-color-palette', [
        [
            'name' => __('Primary Blue', 'attribute-canva'),
            'slug' => 'primary-blue',
            'color' => '#0073aa',
        ],
        [
            'name' => __('Accent Orange', 'attribute-canva'),
            'slug' => 'accent-orange',
            'color' => '#FF5722',
        ],
        [
            'name' => __('Dark Gray', 'attribute-canva'),
            'slug' => 'dark-gray',
            'color' => '#333333',
        ],
        [
            'name' => __('Light Gray', 'attribute-canva'),
            'slug' => 'light-gray',
            'color' => '#f9f9f9',
        ],
        [
            'name' => __('White', 'attribute-canva'),
            'slug' => 'white',
            'color' => '#ffffff',
        ],
    ]);

    // Add support for editor font sizes
    add_theme_support('editor-font-sizes', [
        [
            'name' => __('Small', 'attribute-canva'),
            'size' => 14,
            'slug' => 'small',
        ],
        [
            'name' => __('Normal', 'attribute-canva'),
            'size' => 16,
            'slug' => 'normal',
        ],
        [
            'name' => __('Medium', 'attribute-canva'),
            'size' => 20,
            'slug' => 'medium',
        ],
        [
            'name' => __('Large', 'attribute-canva'),
            'size' => 24,
            'slug' => 'large',
        ],
        [
            'name' => __('Extra Large', 'attribute-canva'),
            'size' => 32,
            'slug' => 'extra-large',
        ],
    ]);
}
add_action('after_setup_theme', 'attributes_canva_enhanced_theme_setup', 20);

/**
 * Enhanced Script Enqueuing with New CSS Files
 */
function attributes_canva_enhanced_enqueue_scripts()
{
    // Enqueue new CSS files based on active plugins/features
    $builders = get_transient('attributes_canva_active_builders');

    if ($builders && is_array($builders)) {
        if (in_array('beaver-builder', $builders)) {
            wp_enqueue_style(
                'attributes-beaver-builder',
                ATTRIBUTE_CANVA_THEME_URI . '/assets/css/beaver-builder.css',
                [],
                ATTRIBUTE_CANVA_VERSION
            );
        }

        if (in_array('divi', $builders)) {
            wp_enqueue_style(
                'attributes-divi-builder',
                ATTRIBUTE_CANVA_THEME_URI . '/assets/css/divi-builder.css',
                [],
                ATTRIBUTE_CANVA_VERSION
            );
        }
    }

    // Enqueue multilingual styles if multilingual plugin is active
    if (attributes_canva_is_multilingual()) {
        wp_enqueue_style(
            'attributes-multilingual',
            ATTRIBUTE_CANVA_THEME_URI . '/assets/css/multilingual.css',
            [],
            ATTRIBUTE_CANVA_VERSION
        );
    }

    // Enqueue Font Awesome for social icons and ACF blocks
    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css',
        [],
        '6.0.0'
    );

    // Enhanced JavaScript for new features
    wp_enqueue_script(
        'attributes-enhanced',
        ATTRIBUTE_CANVA_THEME_URI . '/assets/js/enhanced.js',
        ['jquery'],
        ATTRIBUTE_CANVA_VERSION,
        true
    );

    // Localize enhanced script with additional data
    wp_localize_script('attributes-enhanced', 'attributesEnhanced', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('attributes_enhanced_nonce'),
        'is_multilingual' => attributes_canva_is_multilingual(),
        'current_language' => attributes_canva_get_current_language(),
        'is_rtl' => is_rtl(),
        'strings' => [
            'loading' => __('Loading...', 'attribute-canva'),
            'error' => __('An error occurred. Please try again.', 'attribute-canva'),
            'success' => __('Success!', 'attribute-canva'),
        ]
    ]);
}
add_action('wp_enqueue_scripts', 'attributes_canva_enhanced_enqueue_scripts', 15);

/**************************\
 * ENHANCED UTILITY FUNCTIONS
\**************************/

if (!function_exists('attribute_canva_entry_footer')) {
    /**
     * Enhanced entry footer with reading time and social sharing
     */
    function attribute_canva_entry_footer()
    {
        // Hide category and tag text for pages.
        if ('post' === get_post_type()) {
            /* translators: used between list items, there is a space after the comma */
            $categories_list = get_the_category_list(esc_html__(', ', 'attribute-canva'));
            if ($categories_list) {
                /* translators: 1: list of categories. */
                printf('<span class="cat-links">' . esc_html__('Posted in %1$s', 'attribute-canva') . '</span>', $categories_list);
            }

            /* translators: used between list items, there is a space after the comma */
            $tags_list = get_the_tag_list('', esc_html_x(', ', 'list item separator', 'attribute-canva'));
            if ($tags_list) {
                /* translators: 1: list of tags. */
                printf('<span class="tags-links">' . esc_html__('Tagged %1$s', 'attribute-canva') . '</span>', $tags_list);
            }

            // Add reading time
            echo '<span class="reading-time-wrapper">';
            attributes_canva_display_reading_time();
            echo '</span>';
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

/**
 * Enhanced Social Sharing Function
 */
function attributes_canva_social_sharing($post_id = null)
{
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $post_url = get_permalink($post_id);
    $post_title = get_the_title($post_id);
    $post_excerpt = get_the_excerpt($post_id);

    $social_links = [
        'facebook' => [
            'url' => 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($post_url),
            'title' => __('Share on Facebook', 'attribute-canva'),
            'icon' => 'fab fa-facebook-f'
        ],
        'twitter' => [
            'url' => 'https://twitter.com/intent/tweet?url=' . urlencode($post_url) . '&text=' . urlencode($post_title),
            'title' => __('Share on Twitter', 'attribute-canva'),
            'icon' => 'fab fa-twitter'
        ],
        'linkedin' => [
            'url' => 'https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode($post_url),
            'title' => __('Share on LinkedIn', 'attribute-canva'),
            'icon' => 'fab fa-linkedin-in'
        ],
        'pinterest' => [
            'url' => 'https://pinterest.com/pin/create/button/?url=' . urlencode($post_url) . '&description=' . urlencode($post_title),
            'title' => __('Share on Pinterest', 'attribute-canva'),
            'icon' => 'fab fa-pinterest-p'
        ],
        'email' => [
            'url' => 'mailto:?subject=' . urlencode($post_title) . '&body=' . urlencode($post_excerpt . ' ' . $post_url),
            'title' => __('Share via Email', 'attribute-canva'),
            'icon' => 'fas fa-envelope'
        ]
    ];

    $output = '<div class="social-sharing">';
    $output .= '<h4 class="social-sharing-title">' . __('Share this post:', 'attribute-canva') . '</h4>';
    $output .= '<div class="social-sharing-links">';

    foreach ($social_links as $platform => $data) {
        $output .= sprintf(
            '<a href="%s" target="_blank" rel="noopener noreferrer" class="social-share-link social-share-%s" title="%s">
                <i class="%s" aria-hidden="true"></i>
                <span class="screen-reader-text">%s</span>
            </a>',
            esc_url($data['url']),
            esc_attr($platform),
            esc_attr($data['title']),
            esc_attr($data['icon']),
            esc_html($data['title'])
        );
    }

    $output .= '</div></div>';

    return $output;
}

/**
 * Enhanced Breadcrumb Function
 */
function attributes_canva_breadcrumbs()
{
    if (is_front_page()) {
        return;
    }

    $separator = ' <span class="breadcrumb-separator">/</span> ';
    $home_title = __('Home', 'attribute-canva');

    echo '<nav class="breadcrumbs" aria-label="' . esc_attr__('Breadcrumb Navigation', 'attribute-canva') . '">';
    echo '<ol class="breadcrumb-list">';
    echo '<li class="breadcrumb-item"><a href="' . get_home_url() . '">' . $home_title . '</a></li>';

    if (is_category() || is_single()) {
        echo '<li class="breadcrumb-item">';
        the_category(' &middot; ');
        echo '</li>';

        if (is_single()) {
            echo '<li class="breadcrumb-item current" aria-current="page">';
            the_title();
            echo '</li>';
        }
    } elseif (is_page()) {
        if ($post->post_parent) {
            $anc = get_post_ancestors($post->ID);
            $title = get_the_title();

            foreach ($anc as $ancestor) {
                $output = '<li class="breadcrumb-item"><a href="' . get_permalink($ancestor) . '" title="' . get_the_title($ancestor) . '">' . get_the_title($ancestor) . '</a></li>';
            }

            echo $output;
            echo '<li class="breadcrumb-item current" aria-current="page">' . $title . '</li>';
        } else {
            echo '<li class="breadcrumb-item current" aria-current="page">' . get_the_title() . '</li>';
        }
    }

    echo '</ol>';
    echo '</nav>';
}

/**
 * Enhanced Custom Body Classes
 */
function attributes_canva_enhanced_body_classes($classes)
{
    // Add page slug to body class if not front page
    if (is_page() && !is_front_page()) {
        $classes[] = 'page-' . sanitize_html_class(get_post_field('post_name'));
    }

    // Add builder classes
    $builders = get_transient('attributes_canva_active_builders');
    if ($builders && is_array($builders)) {
        foreach ($builders as $builder) {
            $classes[] = 'has-' . sanitize_html_class($builder);
        }
    }

    // Add ACF class if active
    if (function_exists('acf_add_local_field_group')) {
        $classes[] = 'has-acf';
    }

    // Add multilingual classes
    if (attributes_canva_is_multilingual()) {
        $classes[] = 'is-multilingual';

        $current_lang = attributes_canva_get_current_language();
        if ($current_lang) {
            $classes[] = 'lang-' . sanitize_html_class($current_lang);
        }
    }

    // Add layout classes from ACF
    if (is_page()) {
        $page_layout = attributes_canva_get_page_layout();
        if ($page_layout !== 'default') {
            $classes[] = 'layout-' . sanitize_html_class($page_layout);
        }

        $custom_class = attributes_canva_get_field('custom_page_class');
        if (!empty($custom_class)) {
            $classes[] = sanitize_html_class($custom_class);
        }
    }

    // Add post format classes
    if (is_single()) {
        $post_format = get_post_format();
        if ($post_format) {
            $classes[] = 'post-format-' . sanitize_html_class($post_format);
        }
    }

    return $classes;
}
add_filter('body_class', 'attributes_canva_enhanced_body_classes');

/**************************\
 * ENHANCED SEO AND PERFORMANCE
\**************************/

/**
 * Enhanced Customizer CSS with more options
 */
function attributes_canva_enhanced_customizer_css()
{
    $accent_color = esc_attr(get_theme_mod('attributes_canva_accent_color', '#FF5722'));
    $primary_color = esc_attr(get_theme_mod('attributes_canva_primary_color', '#0073aa'));
    $body_font = esc_attr(get_theme_mod('attributes_canva_body_font', 'Arial'));
    $heading_font = esc_attr(get_theme_mod('attributes_canva_heading_font', 'Arial'));

    if ($accent_color || $primary_color || $body_font || $heading_font) {
        echo "<style type='text/css'>
            :root {
                --accent-color: " . sanitize_hex_color($accent_color) . ";
                --primary-color: " . sanitize_hex_color($primary_color) . ";
                --body-font: " . esc_attr($body_font) . ", sans-serif;
                --heading-font: " . esc_attr($heading_font) . ", sans-serif;
            }
            body {
                font-family: var(--body-font);
            }
            h1, h2, h3, h4, h5, h6 {
                font-family: var(--heading-font);
            }
            a:hover, a:focus {
                color: var(--accent-color);
            }
            .cta-button, .hero-button, .btn-primary {
                background-color: var(--primary-color);
            }
            .cta-button:hover, .hero-button:hover, .btn-primary:hover {
                background-color: var(--accent-color);
            }
            .social-share-link:hover {
                color: var(--accent-color);
            }
        </style>";
    }
}
add_action('wp_head', 'attributes_canva_enhanced_customizer_css');

/**
 * Enhanced Schema Markup
 */
function attributes_canva_enhanced_schema_markup()
{
    if (is_single() || is_page()) {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => get_the_title(),
            'url' => get_permalink(),
            'description' => get_the_excerpt() ?: get_bloginfo('description'),
            'author' => [
                '@type' => 'Person',
                'name' => get_the_author(),
                'url' => get_author_posts_url(get_the_author_meta('ID'))
            ],
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c'),
            'publisher' => [
                '@type' => 'Organization',
                'name' => get_bloginfo('name'),
                'url' => home_url()
            ]
        ];

        // Add featured image if available
        if (has_post_thumbnail()) {
            $schema['image'] = get_the_post_thumbnail_url(null, 'large');
        }

        // Add reading time
        $reading_time = attributes_canva_reading_time();
        if ($reading_time) {
            $schema['timeRequired'] = 'PT' . $reading_time . 'M';
        }

        // Add language information
        if (attributes_canva_is_multilingual()) {
            $schema['inLanguage'] = attributes_canva_get_current_language();
        }

        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>';
    }
}
add_action('wp_head', 'attributes_canva_enhanced_schema_markup');

/**
 * Performance: Preload critical resources
 */
function attributes_canva_preload_resources()
{
    // Preload critical CSS
    echo '<link rel="preload" href="' . get_stylesheet_directory_uri() . '/assets/css/style.css" as="style">';

    // Preload critical fonts
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';

    // Preload Font Awesome
    echo '<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" as="style">';
}
add_action('wp_head', 'attributes_canva_preload_resources', 1);

/**
 * Enhanced Image Optimization
 */
function attributes_canva_enhanced_image_optimization($attr, $attachment, $size)
{
    if (is_admin()) {
        return $attr;
    }

    // Add lazy loading
    if (!isset($attr['loading'])) {
        $attr['loading'] = 'lazy';
    }

    // Add decoding attribute
    if (!isset($attr['decoding'])) {
        $attr['decoding'] = 'async';
    }

    // Add fetchpriority for hero images
    if ($size === 'large' && (is_front_page() || is_page())) {
        global $wp_query;
        if ($wp_query->current_post === 0) {
            $attr['fetchpriority'] = 'high';
            unset($attr['loading']); // Remove lazy loading for above-fold images
        }
    }

    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'attributes_canva_enhanced_image_optimization', 10, 3);

/**************************\
 * ADMIN ENHANCEMENTS
\**************************/

/**
 * Enhanced Admin Dashboard Widget
 */
function attributes_canva_dashboard_widget()
{
    wp_add_dashboard_widget(
        'attributes_canva_dashboard',
        __('Attributes Canva Theme Info', 'attribute-canva'),
        'attributes_canva_dashboard_widget_content'
    );
}
add_action('wp_dashboard_setup', 'attributes_canva_dashboard_widget');

function attributes_canva_dashboard_widget_content()
{
    $active_builders = get_transient('attributes_canva_active_builders') ?: [];
    $is_multilingual = attributes_canva_is_multilingual();
    $has_acf = function_exists('acf_add_local_field_group');

    echo '<div class="attributes-canva-dashboard">';
    echo '<h4>' . __('Theme Features Status', 'attribute-canva') . '</h4>';
    echo '<ul>';
    echo '<li><strong>' . __('Version:', 'attribute-canva') . '</strong> ' . ATTRIBUTE_CANVA_VERSION . '</li>';
    echo '<li><strong>' . __('Page Builders:', 'attribute-canva') . '</strong> ' . (empty($active_builders) ? __('None detected', 'attribute-canva') : implode(', ', $active_builders)) . '</li>';
    echo '<li><strong>' . __('ACF Integration:', 'attribute-canva') . '</strong> ' . ($has_acf ? '<span style="color: green;">✓ Active</span>' : '<span style="color: orange;">Not detected</span>') . '</li>';
    echo '<li><strong>' . __('Multilingual:', 'attribute-canva') . '</strong> ' . ($is_multilingual ? '<span style="color: green;">✓ Active</span>' : '<span style="color: gray;">Not detected</span>') . '</li>';
    echo '</ul>';

    echo '<h4>' . __('Quick Links', 'attribute-canva') . '</h4>';
    echo '<p>';
    echo '<a href="' . admin_url('customize.php') . '" class="button">' . __('Customize Theme', 'attribute-canva') . '</a> ';
    if ($has_acf) {
        echo '<a href="' . admin_url('edit.php?post_type=acf-field-group') . '" class="button">' . __('Manage ACF Fields', 'attribute-canva') . '</a> ';
    }
    echo '<a href="' . admin_url('admin.php?page=theme-docs') . '" class="button">' . __('View Documentation', 'attribute-canva') . '</a>';
    echo '</p>';
    echo '</div>';
}

/**
 * Add theme update notification
 */
function attributes_canva_update_notification()
{
    $current_version = ATTRIBUTE_CANVA_VERSION;
    $latest_version = get_transient('attributes_canva_latest_version');

    if ($latest_version && version_compare($current_version, $latest_version, '<')) {
        echo '<div class="notice notice-info is-dismissible">';
        echo '<p>' . sprintf(
            __('A new version (%s) of Attributes Canva theme is available. <a href="%s">Check for updates</a>.', 'attribute-canva'),
            $latest_version,
            admin_url('themes.php')
        ) . '</p>';
        echo '</div>';
    }
}
add_action('admin_notices', 'attributes_canva_update_notification');

/**
 * Theme activation hook
 */
function attributes_canva_theme_activation()
{
    // Flush rewrite rules
    flush_rewrite_rules();

    // Set default theme options
    $default_options = [
        'attributes_canva_accent_color' => '#FF5722',
        'attributes_canva_primary_color' => '#0073aa',
        'attributes_canva_body_font' => 'Arial',
        'attributes_canva_heading_font' => 'Arial',
    ];

    foreach ($default_options as $option => $value) {
        if (!get_theme_mod($option)) {
            set_theme_mod($option, $value);
        }
    }

    // Create ACF JSON directory if it doesn't exist
    $acf_json_dir = get_stylesheet_directory() . '/acf-json';
    if (!file_exists($acf_json_dir)) {
        wp_mkdir_p($acf_json_dir);
    }
}
add_action('after_switch_theme', 'attributes_canva_theme_activation');
