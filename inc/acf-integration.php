<?php

/**
 * Advanced Custom Fields (ACF) Integration
 * Provides seamless ACF compatibility and custom field groups
 *
 * @package Attribute Canva
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * ACF Integration Class
 */
class Attributes_Canva_ACF_Integration
{

    public function __construct()
    {
        add_action('init', [$this, 'check_acf_plugin']);
        add_action('acf/init', [$this, 'register_acf_blocks']);
        add_action('acf/init', [$this, 'register_acf_options_pages']);
        add_filter('acf/fields/wysiwyg/toolbars', [$this, 'add_custom_wysiwyg_toolbar']);
        add_action('acf/include_field_types', [$this, 'include_custom_field_types']);
        add_filter('acf/load_field', [$this, 'load_dynamic_choices']);
    }

    /**
     * Check if ACF is active and set up integration
     */
    public function check_acf_plugin()
    {
        if (!function_exists('acf_add_local_field_group')) {
            // Show admin notice if ACF is not active
            add_action('admin_notices', [$this, 'acf_missing_notice']);
            return;
        }

        // ACF is active, proceed with integration
        $this->register_field_groups();
        add_filter('acf/settings/save_json', [$this, 'acf_json_save_point']);
        add_filter('acf/settings/load_json', [$this, 'acf_json_load_point']);
    }

    /**
     * Admin notice when ACF is missing
     */
    public function acf_missing_notice()
    {
?>
        <div class="notice notice-warning is-dismissible">
            <p>
                <?php
                printf(
                    __('%s theme works best with Advanced Custom Fields Pro. <a href="%s" target="_blank">Download ACF Pro</a> for enhanced functionality.', 'attribute-canva'),
                    '<strong>Attributes Canva</strong>',
                    'https://www.advancedcustomfields.com/pro/'
                );
                ?>
            </p>
        </div>
    <?php
    }

    /**
     * Set custom save point for ACF JSON
     */
    public function acf_json_save_point($path)
    {
        return get_stylesheet_directory() . '/acf-json';
    }

    /**
     * Set custom load point for ACF JSON
     */
    public function acf_json_load_point($paths)
    {
        unset($paths[0]);
        $paths[] = get_stylesheet_directory() . '/acf-json';
        $paths[] = get_template_directory() . '/acf-json';
        return $paths;
    }

    /**
     * Register ACF Blocks
     */
    public function register_acf_blocks()
    {
        if (!function_exists('acf_register_block_type')) {
            return;
        }

        // Hero Section Block
        acf_register_block_type([
            'name'              => 'hero-section',
            'title'             => __('Hero Section', 'attribute-canva'),
            'description'       => __('A custom hero section block.', 'attribute-canva'),
            'render_template'   => 'template-parts/blocks/hero-section.php',
            'category'          => 'attributes-canva',
            'icon'              => 'format-image',
            'keywords'          => ['hero', 'banner', 'header'],
            'supports'          => [
                'align' => ['wide', 'full'],
                'anchor' => true,
                'customClassName' => true,
            ],
            'example'           => [
                'attributes' => [
                    'mode' => 'preview',
                    'data' => [
                        'hero_title' => __('Sample Hero Title', 'attribute-canva'),
                        'hero_subtitle' => __('This is a preview of the hero section.', 'attribute-canva'),
                    ]
                ]
            ]
        ]);

        // Testimonial Block
        acf_register_block_type([
            'name'              => 'testimonial',
            'title'             => __('Testimonial', 'attribute-canva'),
            'description'       => __('A custom testimonial block.', 'attribute-canva'),
            'render_template'   => 'template-parts/blocks/testimonial.php',
            'category'          => 'attributes-canva',
            'icon'              => 'format-quote',
            'keywords'          => ['testimonial', 'quote', 'review'],
            'supports'          => [
                'align' => ['center'],
                'customClassName' => true,
            ]
        ]);

        // Feature Grid Block
        acf_register_block_type([
            'name'              => 'feature-grid',
            'title'             => __('Feature Grid', 'attribute-canva'),
            'description'       => __('A responsive feature grid block.', 'attribute-canva'),
            'render_template'   => 'template-parts/blocks/feature-grid.php',
            'category'          => 'attributes-canva',
            'icon'              => 'grid-view',
            'keywords'          => ['features', 'grid', 'services'],
            'supports'          => [
                'align' => ['wide', 'full'],
                'customClassName' => true,
            ]
        ]);

        // Call to Action Block
        acf_register_block_type([
            'name'              => 'call-to-action',
            'title'             => __('Call to Action', 'attribute-canva'),
            'description'       => __('A custom call to action block.', 'attribute-canva'),
            'render_template'   => 'template-parts/blocks/call-to-action.php',
            'category'          => 'attributes-canva',
            'icon'              => 'megaphone',
            'keywords'          => ['cta', 'button', 'action'],
            'supports'          => [
                'align' => ['wide', 'full'],
                'customClassName' => true,
            ]
        ]);
    }

    /**
     * Register ACF Options Pages
     */
    public function register_acf_options_pages()
    {
        if (!function_exists('acf_add_options_page')) {
            return;
        }

        // Main Theme Options
        acf_add_options_page([
            'page_title'    => __('Theme Options', 'attribute-canva'),
            'menu_title'    => __('Theme Options', 'attribute-canva'),
            'menu_slug'     => 'theme-options',
            'capability'    => 'edit_posts',
            'icon_url'      => 'dashicons-admin-appearance',
            'position'      => 30,
        ]);

        // Header Options
        acf_add_options_sub_page([
            'page_title'    => __('Header Settings', 'attribute-canva'),
            'menu_title'    => __('Header', 'attribute-canva'),
            'parent_slug'   => 'theme-options',
        ]);

        // Footer Options
        acf_add_options_sub_page([
            'page_title'    => __('Footer Settings', 'attribute-canva'),
            'menu_title'    => __('Footer', 'attribute-canva'),
            'parent_slug'   => 'theme-options',
        ]);

        // Social Media Options
        acf_add_options_sub_page([
            'page_title'    => __('Social Media', 'attribute-canva'),
            'menu_title'    => __('Social Media', 'attribute-canva'),
            'parent_slug'   => 'theme-options',
        ]);
    }

    /**
     * Add custom WYSIWYG toolbar
     */
    public function add_custom_wysiwyg_toolbar($toolbars)
    {
        $toolbars['Simple'] = [
            1 => ['bold', 'italic', 'underline', 'link', 'unlink']
        ];

        $toolbars['Basic'] = [
            1 => ['formatselect', 'bold', 'italic', 'bullist', 'numlist', 'link', 'unlink', 'undo', 'redo']
        ];

        return $toolbars;
    }

    /**
     * Include custom field types
     */
    public function include_custom_field_types()
    {
        // Custom field types can be added here
        // require_once get_template_directory() . '/inc/acf/fields/custom-field-type.php';
    }

    /**
     * Load dynamic choices for select fields
     */
    public function load_dynamic_choices($field)
    {
        // Example: Load pages dynamically
        if ($field['name'] === 'page_selector') {
            $field['choices'] = [];
            $pages = get_pages();
            foreach ($pages as $page) {
                $field['choices'][$page->ID] = $page->post_title;
            }
        }

        // Example: Load menu locations
        if ($field['name'] === 'menu_location') {
            $field['choices'] = get_registered_nav_menus();
        }

        return $field;
    }

    /**
     * Register Field Groups
     */
    public function register_field_groups()
    {
        // Page Settings Field Group
        acf_add_local_field_group([
            'key' => 'group_page_settings',
            'title' => __('Page Settings', 'attribute-canva'),
            'fields' => [
                [
                    'key' => 'field_hide_page_title',
                    'label' => __('Hide Page Title', 'attribute-canva'),
                    'name' => 'hide_page_title',
                    'type' => 'true_false',
                    'instructions' => __('Check to hide the page title on this page.', 'attribute-canva'),
                    'default_value' => 0,
                ],
                [
                    'key' => 'field_custom_page_class',
                    'label' => __('Custom Page Class', 'attribute-canva'),
                    'name' => 'custom_page_class',
                    'type' => 'text',
                    'instructions' => __('Add custom CSS class to this page.', 'attribute-canva'),
                ],
                [
                    'key' => 'field_page_layout',
                    'label' => __('Page Layout', 'attribute-canva'),
                    'name' => 'page_layout',
                    'type' => 'select',
                    'choices' => [
                        'default' => __('Default', 'attribute-canva'),
                        'full-width' => __('Full Width', 'attribute-canva'),
                        'no-sidebar' => __('No Sidebar', 'attribute-canva'),
                    ],
                    'default_value' => 'default',
                ],
            ],
            'location' => [
                [
                    [
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'page',
                    ],
                ],
            ],
            'menu_order' => 0,
            'position' => 'side',
            'style' => 'default',
        ]);

        // Post Settings Field Group
        acf_add_local_field_group([
            'key' => 'group_post_settings',
            'title' => __('Post Settings', 'attribute-canva'),
            'fields' => [
                [
                    'key' => 'field_featured_video',
                    'label' => __('Featured Video', 'attribute-canva'),
                    'name' => 'featured_video',
                    'type' => 'url',
                    'instructions' => __('Add a YouTube or Vimeo URL to display instead of featured image.', 'attribute-canva'),
                ],
                [
                    'key' => 'field_post_subtitle',
                    'label' => __('Post Subtitle', 'attribute-canva'),
                    'name' => 'post_subtitle',
                    'type' => 'text',
                    'instructions' => __('Optional subtitle for the post.', 'attribute-canva'),
                ],
                [
                    'key' => 'field_estimated_reading_time',
                    'label' => __('Estimated Reading Time', 'attribute-canva'),
                    'name' => 'estimated_reading_time',
                    'type' => 'number',
                    'instructions' => __('Reading time in minutes (leave empty for auto-calculation).', 'attribute-canva'),
                    'min' => 1,
                    'max' => 60,
                ],
            ],
            'location' => [
                [
                    [
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'post',
                    ],
                ],
            ],
            'menu_order' => 0,
            'position' => 'side',
            'style' => 'default',
        ]);

        // Theme Options Field Group
        acf_add_local_field_group([
            'key' => 'group_theme_options',
            'title' => __('General Settings', 'attribute-canva'),
            'fields' => [
                [
                    'key' => 'field_site_logo_dark',
                    'label' => __('Dark Mode Logo', 'attribute-canva'),
                    'name' => 'site_logo_dark',
                    'type' => 'image',
                    'instructions' => __('Upload a logo for dark mode (optional).', 'attribute-canva'),
                    'return_format' => 'array',
                    'preview_size' => 'medium',
                ],
                [
                    'key' => 'field_enable_dark_mode',
                    'label' => __('Enable Dark Mode Toggle', 'attribute-canva'),
                    'name' => 'enable_dark_mode',
                    'type' => 'true_false',
                    'instructions' => __('Show dark mode toggle button.', 'attribute-canva'),
                    'default_value' => 1,
                ],
                [
                    'key' => 'field_google_analytics',
                    'label' => __('Google Analytics ID', 'attribute-canva'),
                    'name' => 'google_analytics',
                    'type' => 'text',
                    'instructions' => __('Enter your Google Analytics tracking ID (e.g., UA-XXXXXXXX-X).', 'attribute-canva'),
                ],
                [
                    'key' => 'field_custom_css',
                    'label' => __('Custom CSS', 'attribute-canva'),
                    'name' => 'custom_css',
                    'type' => 'textarea',
                    'instructions' => __('Add custom CSS code here.', 'attribute-canva'),
                    'rows' => 10,
                ],
            ],
            'location' => [
                [
                    [
                        'param' => 'options_page',
                        'operator' => '==',
                        'value' => 'theme-options',
                    ],
                ],
            ],
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
        ]);

        // Social Media Field Group
        acf_add_local_field_group([
            'key' => 'group_social_media',
            'title' => __('Social Media Links', 'attribute-canva'),
            'fields' => [
                [
                    'key' => 'field_facebook_url',
                    'label' => __('Facebook URL', 'attribute-canva'),
                    'name' => 'facebook_url',
                    'type' => 'url',
                ],
                [
                    'key' => 'field_twitter_url',
                    'label' => __('Twitter URL', 'attribute-canva'),
                    'name' => 'twitter_url',
                    'type' => 'url',
                ],
                [
                    'key' => 'field_instagram_url',
                    'label' => __('Instagram URL', 'attribute-canva'),
                    'name' => 'instagram_url',
                    'type' => 'url',
                ],
                [
                    'key' => 'field_linkedin_url',
                    'label' => __('LinkedIn URL', 'attribute-canva'),
                    'name' => 'linkedin_url',
                    'type' => 'url',
                ],
                [
                    'key' => 'field_youtube_url',
                    'label' => __('YouTube URL', 'attribute-canva'),
                    'name' => 'youtube_url',
                    'type' => 'url',
                ],
            ],
            'location' => [
                [
                    [
                        'param' => 'options_page',
                        'operator' => '==',
                        'value' => 'acf-options-social-media',
                    ],
                ],
            ],
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
        ]);
    }
}

/**
 * ACF Helper Functions
 */

/**
 * Get ACF field with fallback
 */
function attributes_canva_get_field($field_name, $post_id = null, $default = '')
{
    if (!function_exists('get_field')) {
        return $default;
    }

    $value = get_field($field_name, $post_id);
    return $value !== false && $value !== null ? $value : $default;
}

/**
 * Get ACF option with fallback
 */
function attributes_canva_get_option($field_name, $default = '')
{
    if (!function_exists('get_field')) {
        return $default;
    }

    $value = get_field($field_name, 'option');
    return $value !== false && $value !== null ? $value : $default;
}

/**
 * Display social media links
 */
function attributes_canva_social_links()
{
    $social_links = [
        'facebook' => [
            'url' => attributes_canva_get_option('facebook_url'),
            'icon' => 'facebook-f',
            'title' => __('Facebook', 'attribute-canva')
        ],
        'twitter' => [
            'url' => attributes_canva_get_option('twitter_url'),
            'icon' => 'twitter',
            'title' => __('Twitter', 'attribute-canva')
        ],
        'instagram' => [
            'url' => attributes_canva_get_option('instagram_url'),
            'icon' => 'instagram',
            'title' => __('Instagram', 'attribute-canva')
        ],
        'linkedin' => [
            'url' => attributes_canva_get_option('linkedin_url'),
            'icon' => 'linkedin-in',
            'title' => __('LinkedIn', 'attribute-canva')
        ],
        'youtube' => [
            'url' => attributes_canva_get_option('youtube_url'),
            'icon' => 'youtube',
            'title' => __('YouTube', 'attribute-canva')
        ],
    ];

    $output = '<div class="social-media-links">';

    foreach ($social_links as $platform => $data) {
        if (!empty($data['url'])) {
            $output .= sprintf(
                '<a href="%s" target="_blank" rel="noopener noreferrer" class="social-link social-link-%s" title="%s">
                    <i class="fab fa-%s" aria-hidden="true"></i>
                    <span class="screen-reader-text">%s</span>
                </a>',
                esc_url($data['url']),
                esc_attr($platform),
                esc_attr($data['title']),
                esc_attr($data['icon']),
                esc_html($data['title'])
            );
        }
    }

    $output .= '</div>';

    return $output;
}

/**
 * Calculate reading time
 */
function attributes_canva_reading_time($post_id = null)
{
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    // Check if custom reading time is set
    $custom_time = attributes_canva_get_field('estimated_reading_time', $post_id);
    if ($custom_time) {
        return $custom_time;
    }

    // Calculate based on content
    $content = get_post_field('post_content', $post_id);
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200); // 200 words per minute

    return max(1, $reading_time); // Minimum 1 minute
}

/**
 * Display reading time
 */
function attributes_canva_display_reading_time($post_id = null)
{
    $reading_time = attributes_canva_reading_time($post_id);

    printf(
        '<span class="reading-time">%s</span>',
        sprintf(
            _n('%d min read', '%d mins read', $reading_time, 'attribute-canva'),
            $reading_time
        )
    );
}

/**
 * Check if page should hide title
 */
function attributes_canva_should_hide_title($post_id = null)
{
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    return attributes_canva_get_field('hide_page_title', $post_id, false);
}

/**
 * Get page layout
 */
function attributes_canva_get_page_layout($post_id = null)
{
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    return attributes_canva_get_field('page_layout', $post_id, 'default');
}

/**
 * Get featured video URL
 */
function attributes_canva_get_featured_video($post_id = null)
{
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    return attributes_canva_get_field('featured_video', $post_id);
}

/**
 * Display featured video or image
 */
function attributes_canva_display_featured_media($post_id = null)
{
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $video_url = attributes_canva_get_featured_video($post_id);

    if ($video_url) {
        // Display video embed
        $embed_code = wp_oembed_get($video_url);
        if ($embed_code) {
            echo '<div class="featured-video">' . $embed_code . '</div>';
            return;
        }
    }

    // Fallback to featured image
    if (has_post_thumbnail($post_id)) {
        echo '<div class="featured-image">';
        the_post_thumbnail('large');
        echo '</div>';
    }
}

/**
 * Add custom CSS from ACF options
 */
function attributes_canva_custom_css()
{
    $custom_css = attributes_canva_get_option('custom_css');

    if (!empty($custom_css)) {
        echo '<style type="text/css">' . wp_strip_all_tags($custom_css) . '</style>';
    }
}
add_action('wp_head', 'attributes_canva_custom_css');

/**
 * Add Google Analytics
 */
function attributes_canva_google_analytics()
{
    $ga_id = attributes_canva_get_option('google_analytics');

    if (!empty($ga_id) && !is_admin()) {
    ?>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr($ga_id); ?>"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());
            gtag('config', '<?php echo esc_attr($ga_id); ?>');
        </script>
<?php
    }
}
add_action('wp_head', 'attributes_canva_google_analytics');

/**
 * Add custom page class from ACF
 */
function attributes_canva_custom_page_class($classes)
{
    if (is_page()) {
        $custom_class = attributes_canva_get_field('custom_page_class');
        if (!empty($custom_class)) {
            $classes[] = sanitize_html_class($custom_class);
        }

        $page_layout = attributes_canva_get_page_layout();
        if ($page_layout !== 'default') {
            $classes[] = 'layout-' . sanitize_html_class($page_layout);
        }
    }

    return $classes;
}
add_filter('body_class', 'attributes_canva_custom_page_class');

/**
 * ACF Block Category
 */
function attributes_canva_acf_block_category($categories)
{
    return array_merge(
        $categories,
        [
            [
                'slug'  => 'attributes-canva',
                'title' => __('Attributes Canva', 'attribute-canva'),
                'icon'  => 'layout',
            ],
        ]
    );
}
add_filter('block_categories_all', 'attributes_canva_acf_block_category');

/**
 * Initialize ACF Integration
 */
function attributes_canva_init_acf()
{
    new Attributes_Canva_ACF_Integration();
}
add_action('after_setup_theme', 'attributes_canva_init_acf');
