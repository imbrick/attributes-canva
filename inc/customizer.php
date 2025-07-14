<?php

/**
 * Enhanced Theme Customizer with clean canvas options
 */

if (!function_exists('attributes_canva_customize_register')) {
    /**
     * Add enhanced theme customizer options
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
            'description' => __('Customize your theme colors for a professional look.', 'attribute-canva'),
        ));

        // Primary Color
        $wp_customize->add_setting('attributes_canva_primary_color', array(
            'default'   => '#3182ce',
            'transport' => 'refresh',
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
            'transport' => 'refresh',
            'sanitize_callback' => 'sanitize_hex_color',
        ));

        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'attributes_canva_secondary_color', array(
            'label'    => __('Secondary Color', 'attribute-canva'),
            'section'  => 'attributes_canva_colors',
            'settings' => 'attributes_canva_secondary_color',
            'description' => __('Used for secondary buttons and borders.', 'attribute-canva'),
        )));

        // ======================
        // TYPOGRAPHY SECTION
        // ======================
        $wp_customize->add_section('attributes_canva_typography', array(
            'title'    => __('Typography', 'attribute-canva'),
            'priority' => 35,
            'description' => __('Choose fonts that match your brand.', 'attribute-canva'),
        ));

        // Headings Font
        $wp_customize->add_setting('attributes_canva_headings_font', array(
            'default'   => 'system-ui',
            'transport' => 'refresh',
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
            'default'   => 'system-ui',
            'transport' => 'refresh',
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
            'default'   => '16',
            'transport' => 'refresh',
            'sanitize_callback' => 'absint',
        ));

        $wp_customize->add_control('attributes_canva_font_size', array(
            'label'    => __('Base Font Size (px)', 'attribute-canva'),
            'section'  => 'attributes_canva_typography',
            'type'     => 'range',
            'input_attrs' => array(
                'min'  => 14,
                'max'  => 20,
                'step' => 1,
            ),
            'description' => __('Base font size in pixels.', 'attribute-canva'),
        ));

        // ======================
        // HEADER SECTION
        // ======================
        $wp_customize->add_section('attributes_canva_header', array(
            'title'    => __('Header Options', 'attribute-canva'),
            'priority' => 40,
            'description' => __('Customize your site header.', 'attribute-canva'),
        ));

        // Header Style
        $wp_customize->add_setting('attributes_canva_header_style', array(
            'default'   => 'standard',
            'transport' => 'refresh',
            'sanitize_callback' => 'attributes_canva_sanitize_select',
        ));

        $wp_customize->add_control('attributes_canva_header_style', array(
            'label'    => __('Header Style', 'attribute-canva'),
            'section'  => 'attributes_canva_header',
            'type'     => 'select',
            'choices'  => array(
                'standard' => __('Standard', 'attribute-canva'),
                'centered' => __('Centered', 'attribute-canva'),
                'minimal'  => __('Minimal', 'attribute-canva'),
            ),
        ));

        // Show Search in Header
        $wp_customize->add_setting('attributes_canva_header_search', array(
            'default'   => true,
            'transport' => 'refresh',
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
            'transport' => 'refresh',
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
            'transport' => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        ));

        $wp_customize->add_control('attributes_canva_header_cta_text', array(
            'label'    => __('CTA Button Text', 'attribute-canva'),
            'section'  => 'attributes_canva_header',
            'type'     => 'text',
            'active_callback' => function () use ($wp_customize) {
                return $wp_customize->get_setting('attributes_canva_header_cta_enabled')->value();
            },
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
            'active_callback' => function () use ($wp_customize) {
                return $wp_customize->get_setting('attributes_canva_header_cta_enabled')->value();
            },
        ));

        // ======================
        // LAYOUT SECTION
        // ======================
        $wp_customize->add_section('attributes_canva_layout', array(
            'title'    => __('Layout Options', 'attribute-canva'),
            'priority' => 45,
            'description' => __('Control the layout and spacing of your site.', 'attribute-canva'),
        ));

        // Container Width
        $wp_customize->add_setting('attributes_canva_container_width', array(
            'default'   => '1200',
            'transport' => 'refresh',
            'sanitize_callback' => 'absint',
        ));

        $wp_customize->add_control('attributes_canva_container_width', array(
            'label'    => __('Container Width (px)', 'attribute-canva'),
            'section'  => 'attributes_canva_layout',
            'type'     => 'range',
            'input_attrs' => array(
                'min'  => 1000,
                'max'  => 1400,
                'step' => 50,
            ),
            'description' => __('Maximum width of content containers.', 'attribute-canva'),
        ));

        // Content Spacing
        $wp_customize->add_setting('attributes_canva_content_spacing', array(
            'default'   => 'standard',
            'transport' => 'refresh',
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
            ),
        ));

        // Show Sidebar
        $wp_customize->add_setting('attributes_canva_show_sidebar', array(
            'default'   => false,
            'transport' => 'refresh',
            'sanitize_callback' => 'wp_validate_boolean',
        ));

        $wp_customize->add_control('attributes_canva_show_sidebar', array(
            'label'    => __('Show Sidebar', 'attribute-canva'),
            'section'  => 'attributes_canva_layout',
            'type'     => 'checkbox',
            'description' => __('Display sidebar on posts and pages.', 'attribute-canva'),
        ));

        // ======================
        // BLOG SECTION
        // ======================
        $wp_customize->add_section('attributes_canva_blog', array(
            'title'    => __('Blog Options', 'attribute-canva'),
            'priority' => 50,
            'description' => __('Customize your blog layout and display.', 'attribute-canva'),
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
                'list' => __('List View', 'attribute-canva'),
                'grid' => __('Grid View', 'attribute-canva'),
                'masonry' => __('Masonry Grid', 'attribute-canva'),
            ),
        ));

        // Show Featured Images
        $wp_customize->add_setting('attributes_canva_show_featured_images', array(
            'default'   => true,
            'transport' => 'refresh',
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
            'transport' => 'refresh',
            'sanitize_callback' => 'wp_validate_boolean',
        ));

        $wp_customize->add_control('attributes_canva_show_excerpts', array(
            'label'    => __('Show Post Excerpts', 'attribute-canva'),
            'section'  => 'attributes_canva_blog',
            'type'     => 'checkbox',
        ));

        // Excerpt Length
        $wp_customize->add_setting('attributes_canva_excerpt_length', array(
            'default'   => '25',
            'transport' => 'refresh',
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
            'active_callback' => function () use ($wp_customize) {
                return $wp_customize->get_setting('attributes_canva_show_excerpts')->value();
            },
        ));

        // ======================
        // FOOTER SECTION
        // ======================
        $wp_customize->add_section('attributes_canva_footer', array(
            'title'    => __('Footer Options', 'attribute-canva'),
            'priority' => 55,
            'description' => __('Customize your site footer.', 'attribute-canva'),
        ));

        // Footer Style
        $wp_customize->add_setting('attributes_canva_footer_style', array(
            'default'   => 'standard',
            'transport' => 'refresh',
            'sanitize_callback' => 'attributes_canva_sanitize_select',
        ));

        $wp_customize->add_control('attributes_canva_footer_style', array(
            'label'    => __('Footer Style', 'attribute-canva'),
            'section'  => 'attributes_canva_footer',
            'type'     => 'select',
            'choices'  => array(
                'minimal'  => __('Minimal', 'attribute-canva'),
                'standard' => __('Standard', 'attribute-canva'),
                'extended' => __('Extended', 'attribute-canva'),
            ),
        ));

        // Show Back to Top
        $wp_customize->add_setting('attributes_canva_back_to_top', array(
            'default'   => true,
            'transport' => 'refresh',
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
            'transport' => 'refresh',
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
            'title'    => __('Performance', 'attribute-canva'),
            'priority' => 60,
            'description' => __('Optimize your site performance.', 'attribute-canva'),
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

        // Minify CSS
        $wp_customize->add_setting('attributes_canva_minify_css', array(
            'default'   => false,
            'transport' => 'refresh',
            'sanitize_callback' => 'wp_validate_boolean',
        ));

        $wp_customize->add_control('attributes_canva_minify_css', array(
            'label'    => __('Minify CSS', 'attribute-canva'),
            'section'  => 'attributes_canva_performance',
            'type'     => 'checkbox',
            'description' => __('Compress CSS for faster loading.', 'attribute-canva'),
        ));
    }
    add_action('customize_register', 'attributes_canva_customize_register');
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
        return (array_key_exists($input, $valid_fonts) ? $input : 'system-ui');
    }
}

/**
 * Get font choices
 */
if (!function_exists('attributes_canva_get_font_choices')) {
    function attributes_canva_get_font_choices()
    {
        return array(
            'system-ui' => __('System UI (Default)', 'attribute-canva'),
            'georgia' => 'Georgia',
            'times' => 'Times New Roman',
            'arial' => 'Arial',
            'helvetica' => 'Helvetica',
            'verdana' => 'Verdana',
            'tahoma' => 'Tahoma',
            'trebuchet' => 'Trebuchet MS',
            'impact' => 'Impact',
            'comic-sans' => 'Comic Sans MS',
            'courier' => 'Courier New',
            'lucida' => 'Lucida Console',
        );
    }
}

/**
 * Output customizer CSS
 */
if (!function_exists('attributes_canva_customizer_css_output')) {
    function attributes_canva_customizer_css_output()
    {
        $primary_color = get_theme_mod('attributes_canva_primary_color', '#3182ce');
        $secondary_color = get_theme_mod('attributes_canva_secondary_color', '#64748b');
        $headings_font = get_theme_mod('attributes_canva_headings_font', 'system-ui');
        $body_font = get_theme_mod('attributes_canva_body_font', 'system-ui');
        $font_size = get_theme_mod('attributes_canva_font_size', '16');
        $container_width = get_theme_mod('attributes_canva_container_width', '1200');
        $content_spacing = get_theme_mod('attributes_canva_content_spacing', 'standard');

        // Convert font choices to CSS
        $font_stacks = array(
            'system-ui' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif',
            'georgia' => 'Georgia, "Times New Roman", Times, serif',
            'times' => '"Times New Roman", Times, serif',
            'arial' => 'Arial, Helvetica, sans-serif',
            'helvetica' => '"Helvetica Neue", Helvetica, Arial, sans-serif',
            'verdana' => 'Verdana, Geneva, sans-serif',
            'tahoma' => 'Tahoma, Geneva, sans-serif',
            'trebuchet' => '"Trebuchet MS", Helvetica, sans-serif',
            'impact' => 'Impact, Charcoal, sans-serif',
            'comic-sans' => '"Comic Sans MS", cursive, sans-serif',
            'courier' => '"Courier New", Courier, monospace',
            'lucida' => '"Lucida Console", Monaco, monospace',
        );

        $headings_font_stack = isset($font_stacks[$headings_font]) ? $font_stacks[$headings_font] : $font_stacks['system-ui'];
        $body_font_stack = isset($font_stacks[$body_font]) ? $font_stacks[$body_font] : $font_stacks['system-ui'];

        // Spacing variations
        $spacing_values = array(
            'compact' => '0.75',
            'standard' => '1',
            'relaxed' => '1.5',
        );
        $spacing_multiplier = isset($spacing_values[$content_spacing]) ? $spacing_values[$content_spacing] : '1';

?>
        <style type="text/css">
            :root {
                --primary-color: <?php echo esc_attr($primary_color); ?>;
                --secondary-color: <?php echo esc_attr($secondary_color); ?>;
                --headings-font: <?php echo esc_attr($headings_font_stack); ?>;
                --body-font: <?php echo esc_attr($body_font_stack); ?>;
                --base-font-size: <?php echo esc_attr($font_size); ?>px;
                --container-width: <?php echo esc_attr($container_width); ?>px;
                --spacing-multiplier: <?php echo esc_attr($spacing_multiplier); ?>;
            }

            body {
                font-family: var(--body-font);
                font-size: var(--base-font-size);
            }

            h1,
            h2,
            h3,
            h4,
            h5,
            h6 {
                font-family: var(--headings-font);
            }

            .container {
                max-width: var(--container-width);
            }

            .btn-primary,
            button:not(.btn-secondary):not(.btn-outline):not(.menu-toggle) {
                background-color: var(--primary-color);
                border-color: var(--primary-color);
            }

            .btn-secondary {
                border-color: var(--secondary-color);
                color: var(--secondary-color);
            }

            a {
                color: var(--primary-color);
            }

            .main-navigation a::after {
                background-color: var(--primary-color);
            }

            /* Spacing adjustments */
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
        </style>
<?php
    }
    add_action('wp_head', 'attributes_canva_customizer_css_output');
}
