<?php

/**
 * Enhanced Theme Customizer with live preview capabilities
 * Modern customizer implementation with instant preview
 */

if (!function_exists('attributes_canva_customize_register')) {
    /**
     * Add enhanced theme customizer options with live preview
     */
    function attributes_canva_customize_register($wp_customize)
    {
        // Remove default sections we don't need
        $wp_customize->remove_section('colors');
        $wp_customize->remove_section('background_image');

        // ======================
        // THEME COLORS SECTION
        // ======================
        $wp_customize->add_section('attributes_canva_colors', array(
            'title'    => __('Theme Colors', 'attribute-canva'),
            'priority' => 30,
            'description' => __('Customize your theme colors for a professional look. Changes preview instantly.', 'attribute-canva'),
        ));

        // Primary Color
        $wp_customize->add_setting('attributes_canva_primary_color', array(
            'default'   => '#3182ce',
            'transport' => 'postMessage', // Enable live preview
            'sanitize_callback' => 'sanitize_hex_color',
        ));

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'attributes_canva_primary_color', array(
            'label'    => __('Primary Color', 'attribute-canva'),
            'section'  => 'attributes_canva_colors',
            'settings' => 'attributes_canva_primary_color',
            'description' => __('Used for buttons, links, and accents.', 'attribute-canva'),
        )));

        // Secondary Color
        $wp_customize->add_setting('attributes_canva_secondary_color', array(
            'default'   => '#64748b',
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_hex_color',
        ));

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'attributes_canva_secondary_color', array(
            'label'    => __('Secondary Color', 'attribute-canva'),
            'section'  => 'attributes_canva_colors',
            'settings' => 'attributes_canva_secondary_color',
            'description' => __('Used for secondary buttons and borders.', 'attribute-canva'),
        )));

        // Accent Color
        $wp_customize->add_setting('attributes_canva_accent_color', array(
            'default'   => '#f59e0b',
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_hex_color',
        ));

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'attributes_canva_accent_color', array(
            'label'    => __('Accent Color', 'attribute-canva'),
            'section'  => 'attributes_canva_colors',
            'settings' => 'attributes_canva_accent_color',
            'description' => __('Used for highlights and special elements.', 'attribute-canva'),
        )));

        // Text Color
        $wp_customize->add_setting('attributes_canva_text_color', array(
            'default'   => '#1f2937',
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_hex_color',
        ));

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'attributes_canva_text_color', array(
            'label'    => __('Text Color', 'attribute-canva'),
            'section'  => 'attributes_canva_colors',
            'settings' => 'attributes_canva_text_color',
            'description' => __('Main text color for content.', 'attribute-canva'),
        )));

        // Advanced Color Options
        $wp_customize->add_setting('attributes_canva_gradient_bg', [
            'default' => '',
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field',
        ]);

        $wp_customize->add_control('attributes_canva_gradient_bg', [
            'label' => __('Gradient Background', 'attribute-canva'),
            'section' => 'attributes_canva_colors',
            'type' => 'text',
            'description' => __('CSS gradient (e.g., linear-gradient(45deg, #ff6b6b, #4ecdc4))', 'attribute-canva'),
        ]);

        // Border Radius Control
        $wp_customize->add_setting('attributes_canva_border_radius', [
            'default' => 8,
            'transport' => 'postMessage',
            'sanitize_callback' => 'absint',
        ]);

        $wp_customize->add_control('attributes_canva_border_radius', [
            'label' => __('Border Radius (px)', 'attribute-canva'),
            'section' => 'attributes_canva_layout',
            'type' => 'range',
            'input_attrs' => [
                'min' => 0,
                'max' => 50,
                'step' => 1,
            ],
        ]);

        // Animation Speed Control
        $wp_customize->add_setting('attributes_canva_animation_speed', [
            'default' => 'normal',
            'transport' => 'postMessage',
            'sanitize_callback' => 'attributes_canva_sanitize_select',
        ]);

        $wp_customize->add_control('attributes_canva_animation_speed', [
            'label' => __('Animation Speed', 'attribute-canva'),
            'section' => 'attributes_canva_performance',
            'type' => 'select',
            'choices' => [
                'fast' => __('Fast (0.15s)', 'attribute-canva'),
                'normal' => __('Normal (0.3s)', 'attribute-canva'),
                'slow' => __('Slow (0.5s)', 'attribute-canva'),
            ],
        ]);

        // ======================
        // TYPOGRAPHY SECTION
        // ======================
        $wp_customize->add_section('attributes_canva_typography', array(
            'title'    => __('Typography', 'attribute-canva'),
            'priority' => 35,
            'description' => __('Choose fonts that match your brand. Preview changes instantly.', 'attribute-canva'),
        ));

        // Google Fonts Enable/Disable
        $wp_customize->add_setting('attributes_canva_enable_google_fonts', array(
            'default'   => true,
            'transport' => 'refresh',
            'sanitize_callback' => 'wp_validate_boolean',
        ));

        $wp_customize->add_control('attributes_canva_enable_google_fonts', array(
            'label'    => __('Enable Google Fonts', 'attribute-canva'),
            'section'  => 'attributes_canva_typography',
            'type'     => 'checkbox',
            'description' => __('Enable Google Fonts integration for more font options.', 'attribute-canva'),
        ));

        // Headings Font
        $wp_customize->add_setting('attributes_canva_headings_font', array(
            'default'   => 'Inter',
            'transport' => 'postMessage',
            'sanitize_callback' => 'attributes_canva_sanitize_font',
        ));

        $wp_customize->add_control('attributes_canva_headings_font', array(
            'label'    => __('Headings Font', 'attribute-canva'),
            'section'  => 'attributes_canva_typography',
            'type'     => 'select',
            'choices'  => attributes_canva_get_font_choices(),
            'description' => __('Font for headings (H1-H6).', 'attribute-canva'),
        ));

        // Body Font
        $wp_customize->add_setting('attributes_canva_body_font', array(
            'default'   => 'Inter',
            'transport' => 'postMessage',
            'sanitize_callback' => 'attributes_canva_sanitize_font',
        ));

        $wp_customize->add_control('attributes_canva_body_font', array(
            'label'    => __('Body Font', 'attribute-canva'),
            'section'  => 'attributes_canva_typography',
            'type'     => 'select',
            'choices'  => attributes_canva_get_font_choices(),
            'description' => __('Font for body text and paragraphs.', 'attribute-canva'),
        ));

        // Font Size
        $wp_customize->add_setting('attributes_canva_font_size', array(
            'default'   => 16,
            'transport' => 'postMessage',
            'sanitize_callback' => 'absint',
        ));

        $wp_customize->add_control('attributes_canva_font_size', array(
            'label'    => __('Base Font Size (px)', 'attribute-canva'),
            'section'  => 'attributes_canva_typography',
            'type'     => 'range',
            'input_attrs' => array(
                'min'  => 14,
                'max'  => 22,
                'step' => 1,
            ),
            'description' => __('Base font size in pixels. Affects all text scaling.', 'attribute-canva'),
        ));

        // Line Height
        $wp_customize->add_setting('attributes_canva_line_height', array(
            'default'   => 1.6,
            'transport' => 'postMessage',
            'sanitize_callback' => 'attributes_canva_sanitize_number',
        ));

        $wp_customize->add_control('attributes_canva_line_height', array(
            'label'    => __('Line Height', 'attribute-canva'),
            'section'  => 'attributes_canva_typography',
            'type'     => 'range',
            'input_attrs' => array(
                'min'  => 1.2,
                'max'  => 2.0,
                'step' => 0.1,
            ),
            'description' => __('Line height for better readability.', 'attribute-canva'),
        ));

        // ======================
        // HEADER SECTION
        // ======================
        $wp_customize->add_section('attributes_canva_header', array(
            'title'    => __('Header Options', 'attribute-canva'),
            'priority' => 40,
            'description' => __('Customize your site header with live preview.', 'attribute-canva'),
        ));

        // Header Layout
        $wp_customize->add_setting('attributes_canva_header_layout', array(
            'default'   => 'layout-1',
            'transport' => 'postMessage',
            'sanitize_callback' => 'attributes_canva_sanitize_select',
        ));

        $wp_customize->add_control('attributes_canva_header_layout', array(
            'label'    => __('Header Layout', 'attribute-canva'),
            'section'  => 'attributes_canva_header',
            'type'     => 'select',
            'choices'  => array(
                'layout-1' => __('Logo Left, Menu Right', 'attribute-canva'),
                'layout-2' => __('Logo Center, Menu Below', 'attribute-canva'),
                'layout-3' => __('Logo Right, Menu Left', 'attribute-canva'),
                'layout-4' => __('Hamburger Menu', 'attribute-canva'),
            ),
        ));

        // Header Background
        $wp_customize->add_setting('attributes_canva_header_background', array(
            'default'   => '#ffffff',
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_hex_color',
        ));

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'attributes_canva_header_background', array(
            'label'    => __('Header Background', 'attribute-canva'),
            'section'  => 'attributes_canva_header',
            'settings' => 'attributes_canva_header_background',
        )));

        // Sticky Header
        $wp_customize->add_setting('attributes_canva_sticky_header', array(
            'default'   => true,
            'transport' => 'postMessage',
            'sanitize_callback' => 'wp_validate_boolean',
        ));

        $wp_customize->add_control('attributes_canva_sticky_header', array(
            'label'    => __('Sticky Header', 'attribute-canva'),
            'section'  => 'attributes_canva_header',
            'type'     => 'checkbox',
            'description' => __('Make header stick to top when scrolling.', 'attribute-canva'),
        ));

        // Show Search in Header
        $wp_customize->add_setting('attributes_canva_header_search', array(
            'default'   => true,
            'transport' => 'postMessage',
            'sanitize_callback' => 'wp_validate_boolean',
        ));

        $wp_customize->add_control('attributes_canva_header_search', array(
            'label'    => __('Show Search in Header', 'attribute-canva'),
            'section'  => 'attributes_canva_header',
            'type'     => 'checkbox',
            'description' => __('Display search form in the header.', 'attribute-canva'),
        ));

        // Header CTA Button
        $wp_customize->add_setting('attributes_canva_header_cta_enabled', array(
            'default'   => true,
            'transport' => 'postMessage',
            'sanitize_callback' => 'wp_validate_boolean',
        ));

        $wp_customize->add_control('attributes_canva_header_cta_enabled', array(
            'label'    => __('Show CTA Button', 'attribute-canva'),
            'section'  => 'attributes_canva_header',
            'type'     => 'checkbox',
            'description' => __('Display call-to-action button in header.', 'attribute-canva'),
        ));

        // CTA Button Text
        $wp_customize->add_setting('attributes_canva_header_cta_text', array(
            'default'   => __('Get Started', 'attribute-canva'),
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field',
        ));

        $wp_customize->add_control('attributes_canva_header_cta_text', array(
            'label'    => __('CTA Button Text', 'attribute-canva'),
            'section'  => 'attributes_canva_header',
            'type'     => 'text',
            'active_callback' => 'attributes_canva_is_header_cta_enabled',
        ));

        // CTA Button URL
        $wp_customize->add_setting('attributes_canva_header_cta_url', array(
            'default'   => home_url('/contact'),
            'transport' => 'refresh',
            'sanitize_callback' => 'esc_url_raw',
        ));

        $wp_customize->add_control('attributes_canva_header_cta_url', array(
            'label'    => __('CTA Button URL', 'attribute-canva'),
            'section'  => 'attributes_canva_header',
            'type'     => 'url',
            'active_callback' => 'attributes_canva_is_header_cta_enabled',
        ));

        // Custom Logo Variants
        $wp_customize->add_setting('attributes_canva_logo_dark', [
            'default' => '',
            'transport' => 'refresh',
            'sanitize_callback' => 'absint',
        ]);

        $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'attributes_canva_logo_dark', [
            'label' => __('Dark Mode Logo', 'attribute-canva'),
            'section' => 'title_tagline',
            'mime_type' => 'image',
            'description' => __('Logo for dark mode (optional)', 'attribute-canva'),
        ]));

        $wp_customize->add_setting('attributes_canva_logo_mobile', [
            'default' => '',
            'transport' => 'refresh',
            'sanitize_callback' => 'absint',
        ]);

        $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'attributes_canva_logo_mobile', [
            'label' => __('Mobile Logo', 'attribute-canva'),
            'section' => 'title_tagline',
            'mime_type' => 'image',
            'description' => __('Smaller logo for mobile devices (optional)', 'attribute-canva'),
        ]));

        // ======================
        // LAYOUT SECTION
        // ======================
        $wp_customize->add_section('attributes_canva_layout', array(
            'title'    => __('Layout Options', 'attribute-canva'),
            'priority' => 45,
            'description' => __('Control the layout and spacing of your site with instant preview.', 'attribute-canva'),
        ));

        // Container Width
        $wp_customize->add_setting('attributes_canva_container_width', array(
            'default'   => 1200,
            'transport' => 'postMessage',
            'sanitize_callback' => 'absint',
        ));

        $wp_customize->add_control('attributes_canva_container_width', array(
            'label'    => __('Container Width (px)', 'attribute-canva'),
            'section'  => 'attributes_canva_layout',
            'type'     => 'range',
            'input_attrs' => array(
                'min'  => 960,
                'max'  => 1600,
                'step' => 20,
            ),
            'description' => __('Maximum width of content containers.', 'attribute-canva'),
        ));

        // Content Spacing
        $wp_customize->add_setting('attributes_canva_content_spacing', array(
            'default'   => 'standard',
            'transport' => 'postMessage',
            'sanitize_callback' => 'attributes_canva_sanitize_select',
        ));

        $wp_customize->add_control('attributes_canva_content_spacing', array(
            'label'    => __('Content Spacing', 'attribute-canva'),
            'section'  => 'attributes_canva_layout',
            'type'     => 'select',
            'choices'  => array(
                'compact'  => __('Compact', 'attribute-canva'),
                'standard' => __('Standard', 'attribute-canva'),
                'relaxed'  => __('Relaxed', 'attribute-canva'),
                'loose'    => __('Loose', 'attribute-canva'),
            ),
        ));

        // Sidebar Position
        $wp_customize->add_setting('attributes_canva_sidebar_position', array(
            'default'   => 'right',
            'transport' => 'refresh',
            'sanitize_callback' => 'attributes_canva_sanitize_select',
        ));

        $wp_customize->add_control('attributes_canva_sidebar_position', array(
            'label'    => __('Sidebar Position', 'attribute-canva'),
            'section'  => 'attributes_canva_layout',
            'type'     => 'select',
            'choices'  => array(
                'none'  => __('No Sidebar', 'attribute-canva'),
                'left'  => __('Left Sidebar', 'attribute-canva'),
                'right' => __('Right Sidebar', 'attribute-canva'),
            ),
        ));

        // ======================
        // BLOG SECTION
        // ======================
        $wp_customize->add_section('attributes_canva_blog', array(
            'title'    => __('Blog Options', 'attribute-canva'),
            'priority' => 50,
            'description' => __('Customize your blog layout and display with live preview.', 'attribute-canva'),
        ));

        // Blog Layout
        $wp_customize->add_setting('attributes_canva_blog_layout', array(
            'default'   => 'grid',
            'transport' => 'refresh',
            'sanitize_callback' => 'attributes_canva_sanitize_select',
        ));

        $wp_customize->add_control('attributes_canva_blog_layout', array(
            'label'    => __('Blog Layout', 'attribute-canva'),
            'section'  => 'attributes_canva_blog',
            'type'     => 'select',
            'choices'  => array(
                'list'     => __('List View', 'attribute-canva'),
                'grid'     => __('Grid View (2 columns)', 'attribute-canva'),
                'grid-3'   => __('Grid View (3 columns)', 'attribute-canva'),
                'masonry'  => __('Masonry Grid', 'attribute-canva'),
            ),
        ));

        // Show Featured Images
        $wp_customize->add_setting('attributes_canva_show_featured_images', array(
            'default'   => true,
            'transport' => 'postMessage',
            'sanitize_callback' => 'wp_validate_boolean',
        ));

        $wp_customize->add_control('attributes_canva_show_featured_images', array(
            'label'    => __('Show Featured Images', 'attribute-canva'),
            'section'  => 'attributes_canva_blog',
            'type'     => 'checkbox',
        ));

        // Show Excerpts
        $wp_customize->add_setting('attributes_canva_show_excerpts', array(
            'default'   => true,
            'transport' => 'postMessage',
            'sanitize_callback' => 'wp_validate_boolean',
        ));

        $wp_customize->add_control('attributes_canva_show_excerpts', array(
            'label'    => __('Show Post Excerpts', 'attribute-canva'),
            'section'  => 'attributes_canva_blog',
            'type'     => 'checkbox',
        ));

        // Excerpt Length
        $wp_customize->add_setting('attributes_canva_excerpt_length', array(
            'default'   => 25,
            'transport' => 'postMessage',
            'sanitize_callback' => 'absint',
        ));

        $wp_customize->add_control('attributes_canva_excerpt_length', array(
            'label'    => __('Excerpt Length (words)', 'attribute-canva'),
            'section'  => 'attributes_canva_blog',
            'type'     => 'number',
            'input_attrs' => array(
                'min' => 10,
                'max' => 100,
            ),
            'active_callback' => 'attributes_canva_is_excerpts_enabled',
        ));

        // Show Reading Time
        $wp_customize->add_setting('attributes_canva_show_reading_time', array(
            'default'   => true,
            'transport' => 'postMessage',
            'sanitize_callback' => 'wp_validate_boolean',
        ));

        $wp_customize->add_control('attributes_canva_show_reading_time', array(
            'label'    => __('Show Reading Time', 'attribute-canva'),
            'section'  => 'attributes_canva_blog',
            'type'     => 'checkbox',
            'description' => __('Display estimated reading time for posts.', 'attribute-canva'),
        ));

        // ======================
        // FOOTER SECTION
        // ======================
        $wp_customize->add_section('attributes_canva_footer', array(
            'title'    => __('Footer Options', 'attribute-canva'),
            'priority' => 55,
            'description' => __('Customize your site footer with live preview.', 'attribute-canva'),
        ));

        // Footer Background
        $wp_customize->add_setting('attributes_canva_footer_background', array(
            'default'   => '#1f2937',
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_hex_color',
        ));

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'attributes_canva_footer_background', array(
            'label'    => __('Footer Background', 'attribute-canva'),
            'section'  => 'attributes_canva_footer',
            'settings' => 'attributes_canva_footer_background',
        )));

        // Footer Text Color
        $wp_customize->add_setting('attributes_canva_footer_text_color', array(
            'default'   => '#ffffff',
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_hex_color',
        ));

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'attributes_canva_footer_text_color', array(
            'label'    => __('Footer Text Color', 'attribute-canva'),
            'section'  => 'attributes_canva_footer',
            'settings' => 'attributes_canva_footer_text_color',
        )));

        // Footer Widget Columns
        $wp_customize->add_setting('attributes_canva_footer_columns', array(
            'default'   => 3,
            'transport' => 'refresh',
            'sanitize_callback' => 'absint',
        ));

        $wp_customize->add_control('attributes_canva_footer_columns', array(
            'label'    => __('Footer Widget Columns', 'attribute-canva'),
            'section'  => 'attributes_canva_footer',
            'type'     => 'select',
            'choices'  => array(
                1 => __('1 Column', 'attribute-canva'),
                2 => __('2 Columns', 'attribute-canva'),
                3 => __('3 Columns', 'attribute-canva'),
                4 => __('4 Columns', 'attribute-canva'),
            ),
        ));

        // Show Back to Top
        $wp_customize->add_setting('attributes_canva_back_to_top', array(
            'default'   => true,
            'transport' => 'postMessage',
            'sanitize_callback' => 'wp_validate_boolean',
        ));

        $wp_customize->add_control('attributes_canva_back_to_top', array(
            'label'    => __('Show Back to Top Button', 'attribute-canva'),
            'section'  => 'attributes_canva_footer',
            'type'     => 'checkbox',
        ));

        // Custom Footer Text
        $wp_customize->add_setting('attributes_canva_footer_text', array(
            'default'   => '',
            'transport' => 'postMessage',
            'sanitize_callback' => 'wp_kses_post',
        ));

        $wp_customize->add_control('attributes_canva_footer_text', array(
            'label'    => __('Custom Footer Text', 'attribute-canva'),
            'section'  => 'attributes_canva_footer',
            'type'     => 'textarea',
            'description' => __('Optional custom text for footer. Leave blank for default.', 'attribute-canva'),
        ));

        // ======================
        // PERFORMANCE SECTION
        // ======================
        $wp_customize->add_section('attributes_canva_performance', array(
            'title'    => __('Performance & Features', 'attribute-canva'),
            'priority' => 60,
            'description' => __('Optimize your site performance and enable advanced features.', 'attribute-canva'),
        ));

        // Enable Lazy Loading
        $wp_customize->add_setting('attributes_canva_lazy_loading', array(
            'default'   => true,
            'transport' => 'refresh',
            'sanitize_callback' => 'wp_validate_boolean',
        ));

        $wp_customize->add_control('attributes_canva_lazy_loading', array(
            'label'    => __('Enable Lazy Loading', 'attribute-canva'),
            'section'  => 'attributes_canva_performance',
            'type'     => 'checkbox',
            'description' => __('Load images only when they come into view.', 'attribute-canva'),
        ));

        // Disable Google Fonts
        $wp_customize->add_setting('attributes_canva_disable_google_fonts', array(
            'default'   => false,
            'transport' => 'refresh',
            'sanitize_callback' => 'wp_validate_boolean',
        ));

        $wp_customize->add_control('attributes_canva_disable_google_fonts', array(
            'label'    => __('Disable Google Fonts', 'attribute-canva'),
            'section'  => 'attributes_canva_performance',
            'type'     => 'checkbox',
            'description' => __('Use system fonts for better performance (if enabled above).', 'attribute-canva'),
        ));

        // Enable Dark Mode Toggle
        $wp_customize->add_setting('attributes_canva_dark_mode_toggle', array(
            'default'   => true,
            'transport' => 'postMessage',
            'sanitize_callback' => 'wp_validate_boolean',
        ));

        $wp_customize->add_control('attributes_canva_dark_mode_toggle', array(
            'label'    => __('Enable Dark Mode Toggle', 'attribute-canva'),
            'section'  => 'attributes_canva_performance',
            'type'     => 'checkbox',
            'description' => __('Show dark mode toggle button for users.', 'attribute-canva'),
        ));

        // Enable Smooth Scrolling
        $wp_customize->add_setting('attributes_canva_smooth_scrolling', array(
            'default'   => true,
            'transport' => 'postMessage',
            'sanitize_callback' => 'wp_validate_boolean',
        ));

        $wp_customize->add_control('attributes_canva_smooth_scrolling', array(
            'label'    => __('Enable Smooth Scrolling', 'attribute-canva'),
            'section'  => 'attributes_canva_performance',
            'type'     => 'checkbox',
            'description' => __('Enable smooth scrolling for anchor links.', 'attribute-canva'),
        ));
    }
    add_action('customize_register', 'attributes_canva_customize_register');
}

/**
 * Active callback functions
 */
function attributes_canva_is_header_cta_enabled()
{
    return get_theme_mod('attributes_canva_header_cta_enabled', true);
}

function attributes_canva_is_excerpts_enabled()
{
    return get_theme_mod('attributes_canva_show_excerpts', true);
}

/**
 * Sanitization functions
 */
if (!function_exists('attributes_canva_sanitize_select')) {
    function attributes_canva_sanitize_select($input, $setting)
    {
        $input = sanitize_key($input);
        $choices = $setting->manager->get_control($setting->id)->choices;
        return (array_key_exists($input, $choices) ? $input : $setting->default);
    }
}

if (!function_exists('attributes_canva_sanitize_font')) {
    function attributes_canva_sanitize_font($input)
    {
        $valid_fonts = attributes_canva_get_font_choices();
        return (array_key_exists($input, $valid_fonts) ? $input : 'Inter');
    }
}

if (!function_exists('attributes_canva_sanitize_number')) {
    function attributes_canva_sanitize_number($input)
    {
        return is_numeric($input) ? floatval($input) : 1.6;
    }
}

/**
 * Get font choices including Google Fonts
 */
if (!function_exists('attributes_canva_get_font_choices')) {
    function attributes_canva_get_font_choices()
    {
        $fonts = array(
            // System Fonts
            'system-ui' => __('System UI (Default)', 'attribute-canva'),
            'arial' => 'Arial',
            'helvetica' => 'Helvetica',
            'georgia' => 'Georgia',
            'times' => 'Times New Roman',
            'verdana' => 'Verdana',
            'tahoma' => 'Tahoma',
        );

        // Add Google Fonts if enabled
        if (get_theme_mod('attributes_canva_enable_google_fonts', true)) {
            $google_fonts = array(
                'Inter' => 'Inter (Google)',
                'Roboto' => 'Roboto (Google)',
                'Open Sans' => 'Open Sans (Google)',
                'Lato' => 'Lato (Google)',
                'Montserrat' => 'Montserrat (Google)',
                'Source Sans Pro' => 'Source Sans Pro (Google)',
                'Oswald' => 'Oswald (Google)',
                'Raleway' => 'Raleway (Google)',
                'Poppins' => 'Poppins (Google)',
                'Nunito' => 'Nunito (Google)',
                'Playfair Display' => 'Playfair Display (Google)',
                'Merriweather' => 'Merriweather (Google)',
            );
            $fonts = array_merge($fonts, $google_fonts);
        }

        return $fonts;
    }
}

/**
 * Live preview JavaScript
 */
function attributes_canva_customize_preview_js()
{
    wp_enqueue_script(
        'attributes-canva-customizer-preview',
        get_template_directory_uri() . '/assets/js/customizer-preview.js',
        array('customize-preview'),
        ATTRIBUTE_CANVA_VERSION,
        true
    );
}
add_action('customize_preview_init', 'attributes_canva_customize_preview_js');

/**
 * Customizer controls JavaScript
 */
function attributes_canva_customize_controls_js()
{
    wp_enqueue_script(
        'attributes-canva-customizer-controls',
        get_template_directory_uri() . '/assets/js/customizer-controls.js',
        array('customize-controls'),
        ATTRIBUTE_CANVA_VERSION,
        true
    );

    /**
     * Customizer controls JavaScript
     */
    function attributes_canva_customize_controls_js()
    {
        wp_enqueue_script(
            'attributes-canva-customizer-controls',
            get_template_directory_uri() . '/assets/js/customizer-controls.js',
            array('customize-controls'),
            ATTRIBUTE_CANVA_VERSION,
            true
        );

        wp_enqueue_style(
            'attributes-canva-customizer-controls',
            get_template_directory_uri() . '/assets/css/customizer-controls.css',
            array(),
            ATTRIBUTE_CANVA_VERSION
        );
    }
    add_action('customize_controls_enqueue_scripts', 'attributes_canva_customize_controls_js');

    /**
     * Output customizer CSS with live preview support
     */
    if (!function_exists('attributes_canva_customizer_css_output')) {
        function attributes_canva_customizer_css_output()
        {
            $primary_color = get_theme_mod('attributes_canva_primary_color', '#3182ce');
            $secondary_color = get_theme_mod('attributes_canva_secondary_color', '#64748b');
            $accent_color = get_theme_mod('attributes_canva_accent_color', '#f59e0b');
            $text_color = get_theme_mod('attributes_canva_text_color', '#1f2937');
            $headings_font = get_theme_mod('attributes_canva_headings_font', 'Inter');
            $body_font = get_theme_mod('attributes_canva_body_font', 'Inter');
            $font_size = get_theme_mod('attributes_canva_font_size', 16);
            $line_height = get_theme_mod('attributes_canva_line_height', 1.6);
            $container_width = get_theme_mod('attributes_canva_container_width', 1200);
            $content_spacing = get_theme_mod('attributes_canva_content_spacing', 'standard');
            $header_background = get_theme_mod('attributes_canva_header_background', '#ffffff');
            $footer_background = get_theme_mod('attributes_canva_footer_background', '#1f2937');
            $footer_text_color = get_theme_mod('attributes_canva_footer_text_color', '#ffffff');

            // Convert font choices to CSS
            $font_stacks = array(
                'system-ui' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif',
                'arial' => 'Arial, Helvetica, sans-serif',
                'helvetica' => '"Helvetica Neue", Helvetica, Arial, sans-serif',
                'georgia' => 'Georgia, "Times New Roman", Times, serif',
                'times' => '"Times New Roman", Times, serif',
                'verdana' => 'Verdana, Geneva, sans-serif',
                'tahoma' => 'Tahoma, Geneva, sans-serif',
                // Google Fonts
                'Inter' => '"Inter", -apple-system, BlinkMacSystemFont, sans-serif',
                'Roboto' => '"Roboto", Arial, sans-serif',
                'Open Sans' => '"Open Sans", Arial, sans-serif',
                'Lato' => '"Lato", Arial, sans-serif',
                'Montserrat' => '"Montserrat", Arial, sans-serif',
                'Source Sans Pro' => '"Source Sans Pro", Arial, sans-serif',
                'Oswald' => '"Oswald", Arial, sans-serif',
                'Raleway' => '"Raleway", Arial, sans-serif',
                'Poppins' => '"Poppins", Arial, sans-serif',
                'Nunito' => '"Nunito", Arial, sans-serif',
                'Playfair Display' => '"Playfair Display", Georgia, serif',
                'Merriweather' => '"Merriweather", Georgia, serif',
            );

            $headings_font_stack = isset($font_stacks[$headings_font]) ? $font_stacks[$headings_font] : $font_stacks['Inter'];
            $body_font_stack = isset($font_stacks[$body_font]) ? $font_stacks[$body_font] : $font_stacks['Inter'];

            // Spacing variations
            $spacing_values = array(
                'compact' => '0.75',
                'standard' => '1',
                'relaxed' => '1.25',
                'loose' => '1.5',
            );
            $spacing_multiplier = isset($spacing_values[$content_spacing]) ? $spacing_values[$content_spacing] : '1';

            // Google Fonts import
            $google_fonts_to_load = array();
            if (get_theme_mod('attributes_canva_enable_google_fonts', true)) {
                if (strpos($headings_font, '(Google)') !== false || in_array($headings_font, ['Inter', 'Roboto', 'Open Sans', 'Lato', 'Montserrat', 'Source Sans Pro', 'Oswald', 'Raleway', 'Poppins', 'Nunito', 'Playfair Display', 'Merriweather'])) {
                    $google_fonts_to_load[] = str_replace(' ', '+', $headings_font) . ':wght@300;400;500;600;700';
                }
                if ($body_font !== $headings_font && (strpos($body_font, '(Google)') !== false || in_array($body_font, ['Inter', 'Roboto', 'Open Sans', 'Lato', 'Montserrat', 'Source Sans Pro', 'Oswald', 'Raleway', 'Poppins', 'Nunito', 'Playfair Display', 'Merriweather']))) {
                    $google_fonts_to_load[] = str_replace(' ', '+', $body_font) . ':wght@300;400;500;600;700';
                }
            }

?>
            <style type="text/css" id="attributes-canva-customizer-css">
                <?php if (!empty($google_fonts_to_load)): ?>@import url('https://fonts.googleapis.com/css2?family=<?php echo implode('&family=', $google_fonts_to_load); ?>&display=swap');

                <?php endif; ?> :root {
                    --primary-color: <?php echo esc_attr($primary_color); ?>;
                    --secondary-color: <?php echo esc_attr($secondary_color); ?>;
                    --accent-color: <?php echo esc_attr($accent_color); ?>;
                    --text-color: <?php echo esc_attr($text_color); ?>;
                    --headings-font: <?php echo esc_attr($headings_font_stack); ?>;
                    --body-font: <?php echo esc_attr($body_font_stack); ?>;
                    --base-font-size: <?php echo esc_attr($font_size); ?>px;
                    --line-height: <?php echo esc_attr($line_height); ?>;
                    --container-width: <?php echo esc_attr($container_width); ?>px;
                    --spacing-multiplier: <?php echo esc_attr($spacing_multiplier); ?>;
                    --header-background: <?php echo esc_attr($header_background); ?>;
                    --footer-background: <?php echo esc_attr($footer_background); ?>;
                    --footer-text-color: <?php echo esc_attr($footer_text_color); ?>;
                }

                body {
                    font-family: var(--body-font);
                    font-size: var(--base-font-size);
                    line-height: var(--line-height);
                    color: var(--text-color);
                }

                h1,
                h2,
                h3,
                h4,
                h5,
                h6 {
                    font-family: var(--headings-font);
                    color: var(--text-color);
                }

                .container {
                    max-width: var(--container-width);
                }

                /* Header Styles */
                .site-header {
                    background-color: var(--header-background);
                    <?php if (get_theme_mod('attributes_canva_sticky_header', true)): ?>position: sticky;
                    top: 0;
                    z-index: 999;
                    <?php endif; ?>
                }

                /* Footer Styles */
                .site-footer {
                    background-color: var(--footer-background);
                    color: var(--footer-text-color);
                }

                .site-footer a {
                    color: var(--footer-text-color);
                }

                /* Button Styles */
                .btn-primary,
                button:not(.btn-secondary):not(.btn-outline):not(.menu-toggle) {
                    background-color: var(--primary-color);
                    border-color: var(--primary-color);
                    color: #ffffff;
                }

                .btn-primary:hover,
                button:not(.btn-secondary):not(.btn-outline):not(.menu-toggle):hover {
                    background-color: var(--accent-color);
                    border-color: var(--accent-color);
                }

                .btn-secondary {
                    border-color: var(--secondary-color);
                    color: var(--secondary-color);
                }

                .btn-secondary:hover {
                    background-color: var(--secondary-color);
                    color: #ffffff;
                }

                /* Link Styles */
                a {
                    color: var(--primary-color);
                }

                a:hover,
                a:focus {
                    color: var(--accent-color);
                }

                /* Navigation Styles */
                .main-navigation a::after {
                    background-color: var(--primary-color);
                }

                .main-navigation a:hover,
                .main-navigation a:focus {
                    color: var(--primary-color);
                }

                /* Content Spacing */
                .entry-header {
                    margin-bottom: calc(3rem * var(--spacing-multiplier));
                    padding-bottom: calc(2rem * var(--spacing-multiplier));
                }

                .entry-content>*+* {
                    margin-top: calc(1.5rem * var(--spacing-multiplier));
                }

                .post-navigation {
                    margin: calc(3rem * var(--spacing-multiplier)) 0;
                    padding: calc(2rem * var(--spacing-multiplier)) 0;
                }

                .widget {
                    padding: calc(2rem * var(--spacing-multiplier));
                    margin-bottom: calc(2rem * var(--spacing-multiplier));
                }

                /* Header Layout Styles */
                <?php if (get_theme_mod('attributes_canva_header_layout', 'layout-1') === 'layout-2'): ?>.header-container {
                    flex-direction: column;
                    text-align: center;
                }

                .site-branding {
                    margin-bottom: 1rem;
                }

                <?php elseif (get_theme_mod('attributes_canva_header_layout', 'layout-1') === 'layout-3'): ?>.header-container {
                    flex-direction: row-reverse;
                }

                <?php endif; ?>

                /* Hide/Show Elements Based on Customizer Settings */
                <?php if (!get_theme_mod('attributes_canva_header_search', true)): ?>.header-search {
                    display: none !important;
                }

                <?php endif; ?><?php if (!get_theme_mod('attributes_canva_header_cta_enabled', true)): ?>.header-cta {
                    display: none !important;
                }

                <?php endif; ?><?php if (!get_theme_mod('attributes_canva_show_featured_images', true)): ?>.post-thumbnail,
                .page-thumbnail {
                    display: none !important;
                }

                <?php endif; ?><?php if (!get_theme_mod('attributes_canva_show_excerpts', true)): ?>.entry-summary {
                    display: none !important;
                }

                <?php endif; ?><?php if (!get_theme_mod('attributes_canva_show_reading_time', true)): ?>.reading-time,
                .reading-time-wrapper {
                    display: none !important;
                }

                <?php endif; ?><?php if (!get_theme_mod('attributes_canva_back_to_top', true)): ?>.back-to-top {
                    display: none !important;
                }

                <?php endif; ?><?php if (!get_theme_mod('attributes_canva_dark_mode_toggle', true)): ?>.dark-mode-toggle {
                    display: none !important;
                }

                <?php endif; ?>

                /* Smooth Scrolling */
                <?php if (get_theme_mod('attributes_canva_smooth_scrolling', true)): ?>html {
                    scroll-behavior: smooth;
                }

                <?php endif; ?>

                /* Blog Layout Styles */
                <?php
                $blog_layout = get_theme_mod('attributes_canva_blog_layout', 'grid');
                if ($blog_layout === 'grid-3'): ?>.post-grid {
                    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                }

                @media (min-width: 1024px) {
                    .post-grid {
                        grid-template-columns: repeat(3, 1fr);
                    }
                }

                <?php elseif ($blog_layout === 'list'): ?>.post-grid {
                    display: block;
                }

                .post-grid article {
                    margin-bottom: 3rem;
                    padding-bottom: 3rem;
                    border-bottom: 1px solid #e5e7eb;
                }

                <?php endif; ?>

                /* Footer Widget Columns */
                .footer-widgets {
                    grid-template-columns: repeat(<?php echo get_theme_mod('attributes_canva_footer_columns', 3); ?>, 1fr);
                }

                @media (max-width: 768px) {
                    .footer-widgets {
                        grid-template-columns: 1fr !important;
                    }
                }

                /* Responsive Typography */
                @media (max-width: 768px) {
                    :root {
                        --base-font-size: <?php echo max(14, $font_size - 2); ?>px;
                    }
                }
            </style>
<?php
        }
        add_action('wp_head', 'attributes_canva_customizer_css_output');
    }

    /**
     * Enqueue Google Fonts
     */
    function attributes_canva_google_fonts()
    {
        if (!get_theme_mod('attributes_canva_enable_google_fonts', true)) {
            return;
        }

        $headings_font = get_theme_mod('attributes_canva_headings_font', 'Inter');
        $body_font = get_theme_mod('attributes_canva_body_font', 'Inter');

        $google_fonts = ['Inter', 'Roboto', 'Open Sans', 'Lato', 'Montserrat', 'Source Sans Pro', 'Oswald', 'Raleway', 'Poppins', 'Nunito', 'Playfair Display', 'Merriweather'];

        $fonts_to_load = array();

        if (in_array($headings_font, $google_fonts)) {
            $fonts_to_load[$headings_font] = $headings_font;
        }

        if (in_array($body_font, $google_fonts) && $body_font !== $headings_font) {
            $fonts_to_load[$body_font] = $body_font;
        }

        if (!empty($fonts_to_load)) {
            $font_families = array();
            foreach ($fonts_to_load as $font) {
                $font_families[] = str_replace(' ', '+', $font) . ':wght@300;400;500;600;700';
            }

            $fonts_url = 'https://fonts.googleapis.com/css2?family=' . implode('&family=', $font_families) . '&display=swap';

            wp_enqueue_style('attributes-canva-google-fonts', $fonts_url, array(), null);
        }
    }
    add_action('wp_enqueue_scripts', 'attributes_canva_google_fonts');

    /**
     * Add customizer CSS to editor
     */
    function attributes_canva_editor_styles()
    {
        $primary_color = get_theme_mod('attributes_canva_primary_color', '#3182ce');
        $text_color = get_theme_mod('attributes_canva_text_color', '#1f2937');
        $headings_font = get_theme_mod('attributes_canva_headings_font', 'Inter');
        $body_font = get_theme_mod('attributes_canva_body_font', 'Inter');
        $font_size = get_theme_mod('attributes_canva_font_size', 16);

        $editor_css = "
        body {
            font-family: {$body_font}, sans-serif !important;
            font-size: {$font_size}px !important;
            color: {$text_color} !important;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: {$headings_font}, sans-serif !important;
            color: {$text_color} !important;
        }
        a {
            color: {$primary_color} !important;
        }
        ";

        wp_add_inline_style('wp-edit-blocks', $editor_css);
    }
    add_action('enqueue_block_editor_assets', 'attributes_canva_editor_styles');

    /**
     * Customizer selective refresh
     */
    function attributes_canva_customize_selective_refresh($wp_customize)
    {
        // Header CTA Text
        $wp_customize->selective_refresh->add_partial('attributes_canva_header_cta_text', array(
            'selector' => '.header-cta .btn',
            'render_callback' => function () {
                return get_theme_mod('attributes_canva_header_cta_text', __('Get Started', 'attribute-canva'));
            }
        ));

        // Footer Text
        $wp_customize->selective_refresh->add_partial('attributes_canva_footer_text', array(
            'selector' => '.custom-footer-text',
            'render_callback' => function () {
                return get_theme_mod('attributes_canva_footer_text', '');
            }
        ));

        // Site Title
        $wp_customize->selective_refresh->add_partial('blogname', array(
            'selector' => '.site-title a',
            'render_callback' => 'attributes_canva_customize_partial_blogname',
        ));

        // Site Description
        $wp_customize->selective_refresh->add_partial('blogdescription', array(
            'selector' => '.site-description',
            'render_callback' => 'attributes_canva_customize_partial_blogdescription',
        ));
    }
    add_action('customize_register', 'attributes_canva_customize_selective_refresh');

    /**
     * Render callbacks for selective refresh
     */
    function attributes_canva_customize_partial_blogname()
    {
        return get_bloginfo('name', 'display');
    }

    function attributes_canva_customize_partial_blogdescription()
    {
        return get_bloginfo('description', 'display');
    }
}
