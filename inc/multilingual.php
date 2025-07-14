<?php

/**
 * Multilingual Support (WPML & Polylang)
 * Provides comprehensive multilingual compatibility
 *
 * @package Attribute Canva
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Multilingual Integration Class
 */
class Attributes_Canva_Multilingual
{

    private $active_plugin = null;

    public function __construct()
    {
        add_action('plugins_loaded', [$this, 'detect_multilingual_plugins']);
        add_action('init', [$this, 'setup_multilingual_support']);
        add_filter('body_class', [$this, 'add_language_body_class']);
        add_action('wp_head', [$this, 'add_language_meta']);
        add_filter('wp_nav_menu_args', [$this, 'filter_menu_by_language']);
    }

    /**
     * Detect active multilingual plugins
     */
    public function detect_multilingual_plugins()
    {
        if (defined('ICL_SITEPRESS_VERSION') && class_exists('SitePress')) {
            $this->active_plugin = 'wpml';
        } elseif (function_exists('pll_current_language')) {
            $this->active_plugin = 'polylang';
        } elseif (class_exists('Qtranslate')) {
            $this->active_plugin = 'qtranslate';
        } elseif (function_exists('weglot_get_current_language')) {
            $this->active_plugin = 'weglot';
        }

        // Store for later use
        set_transient('attributes_canva_multilingual_plugin', $this->active_plugin, DAY_IN_SECONDS);
    }

    /**
     * Setup multilingual support based on detected plugin
     */
    public function setup_multilingual_support()
    {
        switch ($this->active_plugin) {
            case 'wpml':
                $this->setup_wpml_support();
                break;
            case 'polylang':
                $this->setup_polylang_support();
                break;
            case 'qtranslate':
                $this->setup_qtranslate_support();
                break;
            case 'weglot':
                $this->setup_weglot_support();
                break;
        }

        // Universal multilingual features
        $this->setup_universal_features();
    }

    /**
     * WPML Support
     */
    private function setup_wpml_support()
    {
        // Register theme strings for translation
        add_action('init', [$this, 'register_wpml_strings']);

        // Configure WPML for theme components
        add_filter('wpml_custom_field_values_for_post_signature', [$this, 'wpml_custom_fields']);
        add_filter('wpml_elementor_widgets_to_translate', [$this, 'wpml_elementor_widgets']);

        // Menu language filtering
        add_filter('wp_nav_menu_args', [$this, 'wpml_menu_filter']);

        // ACF compatibility
        if (function_exists('acf_add_local_field_group')) {
            add_filter('acf/settings/current_language', [$this, 'wpml_acf_current_language']);
            add_filter('acf/settings/default_language', [$this, 'wpml_acf_default_language']);
        }
    }

    /**
     * Polylang Support
     */
    private function setup_polylang_support()
    {
        // Register strings for Polylang
        add_action('init', [$this, 'register_polylang_strings']);

        // Menu language filtering for Polylang
        add_filter('wp_nav_menu_args', [$this, 'polylang_menu_filter']);

        // Widget text translation
        add_filter('widget_text', [$this, 'translate_widget_text']);

        // ACF compatibility for Polylang
        if (function_exists('acf_add_local_field_group')) {
            add_filter('pll_get_post_types', [$this, 'polylang_acf_post_types']);
        }
    }

    /**
     * qTranslate Support
     */
    private function setup_qtranslate_support()
    {
        // qTranslate text processing
        add_filter('the_title', 'qtranxf_useCurrentLanguageIfNotFoundUseDefaultLanguage');
        add_filter('the_content', 'qtranxf_useCurrentLanguageIfNotFoundUseDefaultLanguage');
        add_filter('widget_text', 'qtranxf_useCurrentLanguageIfNotFoundUseDefaultLanguage');

        // Theme strings
        add_filter('attributes_canva_translate_string', 'qtranxf_useCurrentLanguageIfNotFoundUseDefaultLanguage');
    }

    /**
     * Weglot Support
     */
    private function setup_weglot_support()
    {
        // Weglot compatibility
        add_filter('weglot_exclude_paths', [$this, 'weglot_exclude_paths']);
        add_filter('weglot_exclude_blocks', [$this, 'weglot_exclude_blocks']);
    }

    /**
     * Universal multilingual features
     */
    private function setup_universal_features()
    {
        // Load theme textdomain
        add_action('after_setup_theme', [$this, 'load_theme_textdomain']);

        // RTL support
        add_action('wp_head', [$this, 'rtl_support']);

        // Language switcher
        add_action('wp_footer', [$this, 'language_switcher']);

        // Hreflang tags
        add_action('wp_head', [$this, 'add_hreflang_tags']);
    }

    /**
     * Load theme textdomain
     */
    public function load_theme_textdomain()
    {
        load_theme_textdomain('attribute-canva', get_template_directory() . '/languages');
    }

    /**
     * Add language class to body
     */
    public function add_language_body_class($classes)
    {
        $current_lang = $this->get_current_language();

        if ($current_lang) {
            $classes[] = 'lang-' . sanitize_html_class($current_lang);

            // Add RTL class if needed
            if ($this->is_rtl_language($current_lang)) {
                $classes[] = 'rtl-language';
            }
        }

        if ($this->active_plugin) {
            $classes[] = 'multilingual-' . $this->active_plugin;
        }

        return $classes;
    }

    /**
     * Add language meta tags
     */
    public function add_language_meta()
    {
        $current_lang = $this->get_current_language();

        if ($current_lang) {
            echo '<meta name="language" content="' . esc_attr($current_lang) . '">' . "\n";
        }
    }

    /**
     * Get current language
     */
    public function get_current_language()
    {
        switch ($this->active_plugin) {
            case 'wpml':
                return defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : null;
            case 'polylang':
                return function_exists('pll_current_language') ? pll_current_language() : null;
            case 'qtranslate':
                return function_exists('qtranxf_getLanguage') ? qtranxf_getLanguage() : null;
            case 'weglot':
                return function_exists('weglot_get_current_language') ? weglot_get_current_language() : null;
            default:
                return get_locale();
        }
    }

    /**
     * Get all available languages
     */
    public function get_available_languages()
    {
        $languages = [];

        switch ($this->active_plugin) {
            case 'wpml':
                if (function_exists('icl_get_languages')) {
                    $wpml_languages = icl_get_languages('skip_missing=0');
                    foreach ($wpml_languages as $lang) {
                        $languages[$lang['language_code']] = [
                            'name' => $lang['native_name'],
                            'url' => $lang['url'],
                            'flag' => $lang['country_flag_url'] ?? '',
                            'active' => $lang['active']
                        ];
                    }
                }
                break;

            case 'polylang':
                if (function_exists('pll_the_languages')) {
                    $pll_languages = pll_the_languages(['raw' => 1]);
                    foreach ($pll_languages as $lang) {
                        $languages[$lang['slug']] = [
                            'name' => $lang['name'],
                            'url' => $lang['url'],
                            'flag' => $lang['flag'] ?? '',
                            'active' => $lang['current_lang']
                        ];
                    }
                }
                break;

            case 'weglot':
                if (function_exists('weglot_get_languages')) {
                    $weglot_languages = weglot_get_languages();
                    foreach ($weglot_languages as $lang) {
                        $languages[$lang['language_to']] = [
                            'name' => $lang['local_name'],
                            'url' => $lang['url'],
                            'flag' => '',
                            'active' => $lang['current']
                        ];
                    }
                }
                break;
        }

        return $languages;
    }

    /**
     * Check if language is RTL
     */
    public function is_rtl_language($lang_code)
    {
        $rtl_languages = ['ar', 'he', 'fa', 'ur', 'yi', 'ji', 'iw', 'ku', 'ps'];
        return in_array($lang_code, $rtl_languages);
    }

    /**
     * RTL support
     */
    public function rtl_support()
    {
        $current_lang = $this->get_current_language();

        if ($this->is_rtl_language($current_lang)) {
            echo '<style>body { direction: rtl; } .rtl-language .header-left { order: 2; } .rtl-language .header-right { order: 1; }</style>';
        }
    }

    /**
     * Language switcher
     */
    public function language_switcher()
    {
        $languages = $this->get_available_languages();

        if (count($languages) > 1) {
            echo '<div class="language-switcher">';
            echo '<label for="language-select" class="screen-reader-text">' . __('Select Language', 'attribute-canva') . '</label>';
            echo '<select id="language-select" onchange="window.location.href=this.value">';

            foreach ($languages as $code => $lang) {
                printf(
                    '<option value="%s" %s>%s</option>',
                    esc_url($lang['url']),
                    selected($lang['active'], true, false),
                    esc_html($lang['name'])
                );
            }

            echo '</select>';
            echo '</div>';
        }
    }

    /**
     * Add hreflang tags
     */
    public function add_hreflang_tags()
    {
        if (!is_singular() && !is_front_page()) {
            return;
        }

        $languages = $this->get_available_languages();

        foreach ($languages as $code => $lang) {
            printf(
                '<link rel="alternate" hreflang="%s" href="%s" />' . "\n",
                esc_attr($code),
                esc_url($lang['url'])
            );
        }
    }

    /**
     * Filter menu by language
     */
    public function filter_menu_by_language($args)
    {
        // Let individual plugin methods handle this
        return $args;
    }

    /**
     * Register WPML strings
     */
    public function register_wpml_strings()
    {
        if (function_exists('icl_register_string')) {
            // Register theme strings
            icl_register_string('Attributes Canva', 'Site Tagline', get_bloginfo('description'));
            icl_register_string('Attributes Canva', 'Copyright Text', '© ' . date('Y') . ' ' . get_bloginfo('name'));
            icl_register_string('Attributes Canva', 'Read More', __('Read More', 'attribute-canva'));
            icl_register_string('Attributes Canva', 'Search Placeholder', __('Search...', 'attribute-canva'));
        }
    }

    /**
     * Register Polylang strings
     */
    public function register_polylang_strings()
    {
        if (function_exists('pll_register_string')) {
            // Register theme strings for Polylang
            pll_register_string('Site Tagline', get_bloginfo('description'), 'Attributes Canva');
            pll_register_string('Copyright Text', '© ' . date('Y') . ' ' . get_bloginfo('name'), 'Attributes Canva');
            pll_register_string('Read More', __('Read More', 'attribute-canva'), 'Attributes Canva');
            pll_register_string('Search Placeholder', __('Search...', 'attribute-canva'), 'Attributes Canva');
        }
    }

    /**
     * WPML menu filter
     */
    public function wpml_menu_filter($args)
    {
        if (function_exists('icl_get_languages')) {
            // WPML automatically handles menu translation
            return $args;
        }
        return $args;
    }

    /**
     * Polylang menu filter
     */
    public function polylang_menu_filter($args)
    {
        if (function_exists('pll_current_language')) {
            // Polylang automatically handles menu translation
            return $args;
        }
        return $args;
    }

    /**
     * Translate widget text
     */
    public function translate_widget_text($text)
    {
        switch ($this->active_plugin) {
            case 'wpml':
                return function_exists('icl_t') ? icl_t('Widgets', md5($text), $text) : $text;
            case 'polylang':
                return function_exists('pll__') ? pll__($text) : $text;
            default:
                return $text;
        }
    }

    /**
     * WPML Elementor widgets configuration
     */
    public function wpml_elementor_widgets($widgets)
    {
        $widgets['attributes-canva-hero'] = [
            'conditions' => ['widgetType' => 'attributes-canva-hero'],
            'fields' => [
                [
                    'field' => 'hero_title',
                    'type' => __('Hero Title', 'attribute-canva'),
                    'editor_type' => 'LINE'
                ],
                [
                    'field' => 'hero_subtitle',
                    'type' => __('Hero Subtitle', 'attribute-canva'),
                    'editor_type' => 'AREA'
                ]
            ]
        ];

        return $widgets;
    }

    /**
     * WPML ACF current language
     */
    public function wpml_acf_current_language($language)
    {
        return defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : $language;
    }

    /**
     * WPML ACF default language
     */
    public function wpml_acf_default_language($language)
    {
        global $sitepress;
        return $sitepress ? $sitepress->get_default_language() : $language;
    }

    /**
     * Polylang ACF post types
     */
    public function polylang_acf_post_types($post_types)
    {
        // Add ACF field groups to Polylang translation
        $post_types['acf-field-group'] = 'acf-field-group';
        return $post_types;
    }

    /**
     * Weglot exclude paths
     */
    public function weglot_exclude_paths($paths)
    {
        // Exclude admin and API paths
        $paths[] = '/wp-admin';
        $paths[] = '/wp-json';
        return $paths;
    }

    /**
     * Weglot exclude blocks
     */
    public function weglot_exclude_blocks($blocks)
    {
        // Exclude specific CSS selectors from translation
        $blocks[] = '.language-switcher';
        $blocks[] = '.social-media-links';
        return $blocks;
    }
}

/**
 * Multilingual Helper Functions
 */

/**
 * Get translated string
 */
function attributes_canva_translate($string, $context = 'Attributes Canva')
{
    $plugin = get_transient('attributes_canva_multilingual_plugin');

    switch ($plugin) {
        case 'wpml':
            return function_exists('icl_t') ? icl_t($context, md5($string), $string) : $string;
        case 'polylang':
            return function_exists('pll__') ? pll__($string) : $string;
        case 'qtranslate':
            return function_exists('qtranxf_useCurrentLanguageIfNotFoundUseDefaultLanguage')
                ? qtranxf_useCurrentLanguageIfNotFoundUseDefaultLanguage($string) : $string;
        default:
            return $string;
    }
}

/**
 * Get language switcher HTML
 */
function attributes_canva_language_switcher($type = 'dropdown')
{
    $multilingual = new Attributes_Canva_Multilingual();
    $languages = $multilingual->get_available_languages();

    if (count($languages) <= 1) {
        return '';
    }

    $output = '';

    switch ($type) {
        case 'list':
            $output = '<ul class="language-switcher-list">';
            foreach ($languages as $code => $lang) {
                $class = $lang['active'] ? 'current-language' : '';
                $output .= sprintf(
                    '<li class="%s"><a href="%s">%s</a></li>',
                    esc_attr($class),
                    esc_url($lang['url']),
                    esc_html($lang['name'])
                );
            }
            $output .= '</ul>';
            break;

        case 'flags':
            $output = '<div class="language-switcher-flags">';
            foreach ($languages as $code => $lang) {
                $class = $lang['active'] ? 'current-language' : '';
                $flag = !empty($lang['flag']) ? '<img src="' . esc_url($lang['flag']) . '" alt="' . esc_attr($lang['name']) . '">' : $code;
                $output .= sprintf(
                    '<a href="%s" class="%s" title="%s">%s</a>',
                    esc_url($lang['url']),
                    esc_attr($class),
                    esc_attr($lang['name']),
                    $flag
                );
            }
            $output .= '</div>';
            break;

        default: // dropdown
            $output = '<div class="language-switcher-dropdown">';
            $output .= '<select onchange="window.location.href=this.value">';
            foreach ($languages as $code => $lang) {
                $output .= sprintf(
                    '<option value="%s" %s>%s</option>',
                    esc_url($lang['url']),
                    selected($lang['active'], true, false),
                    esc_html($lang['name'])
                );
            }
            $output .= '</select>';
            $output .= '</div>';
            break;
    }

    return $output;
}

/**
 * Check if site is multilingual
 */
function attributes_canva_is_multilingual()
{
    $plugin = get_transient('attributes_canva_multilingual_plugin');
    return !empty($plugin);
}

/**
 * Get current language code
 */
function attributes_canva_get_current_language()
{
    $multilingual = new Attributes_Canva_Multilingual();
    return $multilingual->get_current_language();
}

/**
 * Initialize Multilingual Support
 */
function attributes_canva_init_multilingual()
{
    new Attributes_Canva_Multilingual();
}
add_action('plugins_loaded', 'attributes_canva_init_multilingual', 5);
