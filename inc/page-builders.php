<?php

/**
 * Page Builder Integration
 * Supports Elementor, Beaver Builder, and Divi
 *
 * @package Attribute Canva
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Elementor Integration (Enhanced)
 */
class Attributes_Canva_Elementor_Integration
{

    public function __construct()
    {
        add_action('after_setup_theme', [$this, 'add_elementor_support']);
        add_action('elementor/widgets/register', [$this, 'register_custom_widgets']);
        add_action('elementor/elements/categories_registered', [$this, 'add_widget_categories']);
        add_filter('elementor/frontend/print_google_fonts', [$this, 'disable_google_fonts'], 10, 1);
    }

    public function add_elementor_support()
    {
        // Enhanced Elementor support
        add_theme_support('elementor');
        add_theme_support('elementor-canvas');
        add_theme_support('elementor-full-width');
        add_theme_support('elementor-header-footer');

        // Disable Elementor's default colors and fonts
        add_filter('elementor/editor/localize_settings', [$this, 'disable_elementor_defaults']);
    }

    public function disable_elementor_defaults($config)
    {
        $config['default_schemes'] = [
            'color' => [],
            'typography' => []
        ];
        return $config;
    }

    public function add_widget_categories($elements_manager)
    {
        $elements_manager->add_category(
            'attributes-canva',
            [
                'title' => __('Attributes Canva', 'attribute-canva'),
                'icon' => 'fa fa-paint-brush',
            ]
        );
    }

    public function register_custom_widgets($widgets_manager)
    {
        require_once get_template_directory() . '/inc/elementor/widgets/hero-section.php';
        require_once get_template_directory() . '/inc/elementor/widgets/testimonial-card.php';
        require_once get_template_directory() . '/inc/elementor/widgets/contact-form.php';

        $widgets_manager->register(new \Attributes_Canva_Hero_Widget());
        $widgets_manager->register(new \Attributes_Canva_Testimonial_Widget());
        $widgets_manager->register(new \Attributes_Canva_Contact_Form_Widget());
    }

    public function disable_google_fonts($print_google_fonts)
    {
        return false; // Use theme fonts instead
    }
}

/**
 * Beaver Builder Integration
 */
class Attributes_Canva_Beaver_Builder_Integration
{

    public function __construct()
    {
        add_action('init', [$this, 'check_beaver_builder']);
        add_filter('fl_builder_register_settings_form', [$this, 'add_theme_settings'], 10, 2);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_beaver_styles']);
    }

    public function check_beaver_builder()
    {
        if (!class_exists('FLBuilder')) {
            return;
        }

        // Add theme support for Beaver Builder
        add_theme_support('fl-builder-color-presets');
        add_theme_support('fl-builder-spacing-presets');

        // Register custom modules
        $this->register_beaver_modules();

        // Add theme colors to Beaver Builder
        add_filter('fl_builder_color_presets', [$this, 'add_theme_colors']);
    }

    public function register_beaver_modules()
    {
        if (class_exists('FLBuilder')) {
            require_once get_template_directory() . '/inc/beaver-builder/modules/hero-module.php';
            require_once get_template_directory() . '/inc/beaver-builder/modules/testimonial-module.php';
        }
    }

    public function add_theme_colors($colors)
    {
        return array_merge($colors, [
            '0073aa' => __('Theme Primary', 'attribute-canva'),
            'FF5722' => __('Theme Accent', 'attribute-canva'),
            '333333' => __('Theme Dark', 'attribute-canva'),
            'f9f9f9' => __('Theme Light', 'attribute-canva'),
        ]);
    }

    public function enqueue_beaver_styles()
    {
        if (class_exists('FLBuilderModel') && FLBuilderModel::is_builder_active()) {
            wp_enqueue_style(
                'attributes-beaver-builder',
                get_template_directory_uri() . '/assets/css/beaver-builder.css',
                [],
                ATTRIBUTE_CANVA_VERSION
            );
        }
    }

    public function add_theme_settings($form, $id)
    {
        if ($id === 'global') {
            $form['tabs']['theme'] = [
                'title' => __('Theme Settings', 'attribute-canva'),
                'sections' => [
                    'theme_colors' => [
                        'title' => __('Theme Colors', 'attribute-canva'),
                        'fields' => [
                            'primary_color' => [
                                'type' => 'color',
                                'label' => __('Primary Color', 'attribute-canva'),
                                'default' => '#0073aa',
                                'show_reset' => true,
                                'show_alpha' => true,
                            ],
                            'accent_color' => [
                                'type' => 'color',
                                'label' => __('Accent Color', 'attribute-canva'),
                                'default' => '#FF5722',
                                'show_reset' => true,
                                'show_alpha' => true,
                            ]
                        ]
                    ]
                ]
            ];
        }
        return $form;
    }
}

/**
 * Divi Integration
 */
class Attributes_Canva_Divi_Integration
{

    public function __construct()
    {
        add_action('after_setup_theme', [$this, 'add_divi_support']);
        add_action('et_builder_ready', [$this, 'register_divi_modules']);
        add_filter('et_builder_load_actions', [$this, 'load_divi_extensions']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_divi_styles']);
    }

    public function add_divi_support()
    {
        // Add Divi compatibility
        add_theme_support('et-builder-module-styles');
        add_theme_support('et_pb_custom_css');

        // Override Divi's theme builder if needed
        add_filter('et_theme_builder_template_settings', [$this, 'override_divi_templates']);
    }

    public function register_divi_modules()
    {
        if (class_exists('ET_Builder_Module')) {
            require_once get_template_directory() . '/inc/divi/modules/hero-module.php';
            require_once get_template_directory() . '/inc/divi/modules/testimonial-module.php';
        }
    }

    public function load_divi_extensions($actions)
    {
        $actions[] = 'attributes_canva_load_divi_extension';
        return $actions;
    }

    public function enqueue_divi_styles()
    {
        if (function_exists('et_core_is_fb_enabled') && et_core_is_fb_enabled()) {
            wp_enqueue_style(
                'attributes-divi-builder',
                get_template_directory_uri() . '/assets/css/divi-builder.css',
                [],
                ATTRIBUTE_CANVA_VERSION
            );
        }
    }

    public function override_divi_templates($settings)
    {
        // Allow theme templates to override Divi theme builder
        $settings['theme_template_priority'] = 'theme_first';
        return $settings;
    }
}

/**
 * Universal Page Builder Support
 */
class Attributes_Canva_Universal_Builder_Support
{

    public function __construct()
    {
        add_action('init', [$this, 'detect_page_builders']);
        add_filter('body_class', [$this, 'add_builder_body_classes']);
        add_action('wp_head', [$this, 'add_builder_specific_styles']);
    }

    public function detect_page_builders()
    {
        // Detect active page builders
        $builders = [];

        if (did_action('elementor/loaded')) {
            $builders[] = 'elementor';
        }

        if (class_exists('FLBuilder')) {
            $builders[] = 'beaver-builder';
        }

        if (function_exists('et_core_is_fb_enabled')) {
            $builders[] = 'divi';
        }

        if (class_exists('Vc_Manager')) {
            $builders[] = 'visual-composer';
        }

        // Store detected builders for use throughout the theme
        set_transient('attributes_canva_active_builders', $builders, DAY_IN_SECONDS);
    }

    public function add_builder_body_classes($classes)
    {
        $builders = get_transient('attributes_canva_active_builders');

        if ($builders && is_array($builders)) {
            foreach ($builders as $builder) {
                $classes[] = 'has-' . $builder;
            }
        }

        return $classes;
    }

    public function add_builder_specific_styles()
    {
        $builders = get_transient('attributes_canva_active_builders');

        if (!$builders || !is_array($builders)) {
            return;
        }

        echo '<style>';

        // Universal builder styles
        if (in_array('elementor', $builders)) {
            echo '.elementor-page .site-header { z-index: 999; }';
        }

        if (in_array('beaver-builder', $builders)) {
            echo '.fl-builder-content { margin-top: 0; }';
        }

        if (in_array('divi', $builders)) {
            echo '.et_pb_section { margin-bottom: 0; }';
        }

        echo '</style>';
    }
}

/**
 * Initialize Page Builder Integrations
 */
function attributes_canva_init_page_builders()
{
    new Attributes_Canva_Elementor_Integration();
    new Attributes_Canva_Beaver_Builder_Integration();
    new Attributes_Canva_Divi_Integration();
    new Attributes_Canva_Universal_Builder_Support();
}
add_action('plugins_loaded', 'attributes_canva_init_page_builders');

/**
 * Page Builder Template Detection
 */
function attributes_canva_is_builder_page()
{
    global $post;

    if (!$post) {
        return false;
    }

    // Check for Elementor
    if (class_exists('\Elementor\Plugin') && \Elementor\Plugin::$instance->documents->get($post->ID)->is_built_with_elementor()) {
        return 'elementor';
    }

    // Check for Beaver Builder
    if (class_exists('FLBuilderModel') && FLBuilderModel::is_builder_enabled($post->ID)) {
        return 'beaver-builder';
    }

    // Check for Divi
    if (function_exists('et_pb_is_pagebuilder_used') && et_pb_is_pagebuilder_used($post->ID)) {
        return 'divi';
    }

    return false;
}

/**
 * Builder-specific content wrapper
 */
function attributes_canva_builder_content_wrapper($content)
{
    $builder = attributes_canva_is_builder_page();

    if (!$builder) {
        return $content;
    }

    $wrapper_classes = ['builder-content', 'builder-' . $builder];

    return sprintf(
        '<div class="%s">%s</div>',
        esc_attr(implode(' ', $wrapper_classes)),
        $content
    );
}
add_filter('the_content', 'attributes_canva_builder_content_wrapper');
