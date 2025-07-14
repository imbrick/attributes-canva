<?php

/**
 * Performance Optimization and Error Handling for Attributes Canva Theme
 * Include this in functions.php or create as separate file and include
 *
 * @package Attribute Canva
 */

// Security check - prevent direct access
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Performance and Error Handling Class
 */
class Attributes_Canva_Performance
{

    private static $instance = null;
    private $errors = [];
    private $performance_data = [];

    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        add_action('init', [$this, 'init_performance_optimizations']);
        add_action('wp_enqueue_scripts', [$this, 'optimize_script_loading'], 5);
        add_action('wp_head', [$this, 'add_performance_hints'], 1);
        add_filter('script_loader_tag', [$this, 'add_script_attributes'], 10, 3);
        add_filter('style_loader_tag', [$this, 'add_style_attributes'], 10, 4);

        // Error handling
        if (defined('ATTRIBUTES_CANVA_DEBUG') && ATTRIBUTES_CANVA_DEBUG) {
            set_error_handler([$this, 'custom_error_handler']);
            register_shutdown_function([$this, 'check_for_fatal_error']);
        }

        // Performance monitoring
        add_action('wp_footer', [$this, 'performance_monitoring']);
    }

    /**
     * Initialize performance optimizations
     */
    public function init_performance_optimizations()
    {
        // Remove unnecessary WordPress features
        $this->cleanup_wp_head();

        // Optimize database queries
        $this->optimize_database_queries();

        // Enable output buffering for better compression
        if (!ob_get_level()) {
            ob_start();
        }

        // Defer parsing of JavaScript
        add_filter('script_loader_tag', [$this, 'defer_scripts'], 10, 3);

        // Preload critical resources
        add_action('wp_head', [$this, 'preload_critical_resources'], 1);

        // Add performance hints
        add_action('wp_head', [$this, 'add_dns_prefetch']);
    }

    /**
     * Clean up WordPress head section
     */
    private function cleanup_wp_head()
    {
        // Remove unnecessary meta tags and links
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wp_shortlink_wp_head');
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');

        // Remove emoji support if not needed
        if (!get_theme_mod('attributes_canva_enable_emojis', false)) {
            remove_action('wp_head', 'print_emoji_detection_script', 7);
            remove_action('wp_print_styles', 'print_emoji_styles');
            remove_action('admin_print_scripts', 'print_emoji_detection_script');
            remove_action('admin_print_styles', 'print_emoji_styles');
            remove_filter('the_content_feed', 'wp_staticize_emoji');
            remove_filter('comment_text_rss', 'wp_staticize_emoji');
            remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
        }

        // Remove jQuery migrate if not needed
        add_action('wp_default_scripts', function ($scripts) {
            if (!is_admin() && isset($scripts->registered['jquery'])) {
                $script = $scripts->registered['jquery'];
                if ($script->deps) {
                    $script->deps = array_diff($script->deps, ['jquery-migrate']);
                }
            }
        });

        // Disable pingbacks/trackbacks
        add_filter('xmlrpc_enabled', '__return_false');
        add_filter('wp_headers', function ($headers) {
            unset($headers['X-Pingback']);
            return $headers;
        });
    }

    /**
     * Optimize database queries
     */
    private function optimize_database_queries()
    {
        // Optimize widget queries
        add_filter('widget_posts_args', function ($args) {
            $args['no_found_rows'] = true;
            $args['update_post_meta_cache'] = false;
            $args['update_post_term_cache'] = false;
            return $args;
        });

        // Optimize archive queries
        add_action('pre_get_posts', function ($query) {
            if (!is_admin() && $query->is_main_query()) {
                if (is_home() || is_archive()) {
                    $query->set('no_found_rows', true);
                }
            }
        });

        // Remove unnecessary queries
        remove_action('wp_head', 'wp_oembed_add_discovery_links');
        remove_action('wp_head', 'wp_oembed_add_host_js');
    }

    /**
     * Optimize script loading
     */
    public function optimize_script_loading()
    {
        // Conditionally load scripts based on page content
        if (!is_admin()) {
            // Only load comment reply script when needed
            if (is_singular() && comments_open() && get_option('thread_comments')) {
                wp_enqueue_script('comment-reply');
            }

            // Only load block library CSS on posts with blocks
            if (is_singular() && has_blocks()) {
                wp_enqueue_style('wp-block-library');
            } else {
                wp_dequeue_style('wp-block-library');
                wp_dequeue_style('wp-block-library-theme');
                wp_dequeue_style('wc-blocks-style');
            }

            // Conditionally load page builder assets
            $this->conditional_page_builder_assets();
        }
    }

    /**
     * Conditionally load page builder assets
     */
    private function conditional_page_builder_assets()
    {
        global $post;

        if (!$post) {
            return;
        }

        $builder_used = attributes_canva_is_builder_page();

        // Only load builder-specific CSS if builder is used
        if ($builder_used) {
            switch ($builder_used) {
                case 'elementor':
                    // Elementor assets are handled by Elementor itself
                    break;
                case 'beaver-builder':
                    wp_enqueue_style('attributes-beaver-builder');
                    break;
                case 'divi':
                    wp_enqueue_style('attributes-divi-builder');
                    break;
            }
        }
    }

    /**
     * Add performance hints to head
     */
    public function add_performance_hints()
    {
        // Preconnect to external domains
        echo '<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>';
        echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
        echo '<link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>';

        // Resource hints for better loading
        echo '<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">';
        echo '<meta name="theme-color" content="#3182ce">';

        // Prefetch DNS for external resources
        $this->add_dns_prefetch();
    }

    /**
     * Add DNS prefetch hints
     */
    public function add_dns_prefetch()
    {
        $domains = [
            '//fonts.googleapis.com',
            '//fonts.gstatic.com',
            '//cdnjs.cloudflare.com',
            '//www.google-analytics.com',
            '//www.googletagmanager.com'
        ];

        foreach ($domains as $domain) {
            echo '<link rel="dns-prefetch" href="' . esc_url($domain) . '">';
        }
    }

    /**
     * Preload critical resources
     */
    public function preload_critical_resources()
    {
        // Preload critical CSS
        $critical_css = get_template_directory_uri() . '/assets/css/style.css';
        echo '<link rel="preload" href="' . esc_url($critical_css) . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">';
        echo '<noscript><link rel="stylesheet" href="' . esc_url($critical_css) . '"></noscript>';

        // Preload critical fonts
        $font_files = [
            '/assets/fonts/primary-font.woff2',
            '/assets/fonts/heading-font.woff2'
        ];

        foreach ($font_files as $font) {
            $font_url = get_template_directory_uri() . $font;
            if (file_exists(get_template_directory() . $font)) {
                echo '<link rel="preload" href="' . esc_url($font_url) . '" as="font" type="font/woff2" crossorigin>';
            }
        }

        // Preload hero image if on front page
        if (is_front_page() && has_post_thumbnail()) {
            $hero_image = get_the_post_thumbnail_url(null, 'large');
            if ($hero_image) {
                echo '<link rel="preload" href="' . esc_url($hero_image) . '" as="image">';
            }
        }
    }

    /**
     * Add script attributes for performance
     */
    public function add_script_attributes($tag, $handle, $src)
    {
        // Scripts that should be deferred
        $defer_scripts = [
            'attributes-enhanced',
            'attributes-dark-mode-toggle',
            'google-analytics',
            'gtag'
        ];

        // Scripts that should be loaded async
        $async_scripts = [
            'attributes-ajax',
            'comment-reply'
        ];

        if (in_array($handle, $defer_scripts)) {
            $tag = str_replace(' src', ' defer src', $tag);
        } elseif (in_array($handle, $async_scripts)) {
            $tag = str_replace(' src', ' async src', $tag);
        }

        // Add integrity and crossorigin for external scripts
        if (strpos($src, 'cdnjs.cloudflare.com') !== false) {
            $tag = str_replace('></script>', ' crossorigin="anonymous"></script>', $tag);
        }

        return $tag;
    }

    /**
     * Add style attributes for performance
     */
    public function add_style_attributes($html, $handle, $href, $media)
    {
        // Add preload for non-critical CSS
        $non_critical_styles = [
            'attributes-dark-mode',
            'font-awesome',
            'attributes-beaver-builder',
            'attributes-divi-builder'
        ];

        if (in_array($handle, $non_critical_styles)) {
            $html = str_replace("rel='stylesheet'", "rel='preload' as='style' onload=\"this.onload=null;this.rel='stylesheet'\"", $html);
            $html .= '<noscript>' . str_replace("rel='preload' as='style' onload=\"this.onload=null;this.rel='stylesheet'\"", "rel='stylesheet'", $html) . '</noscript>';
        }

        return $html;
    }

    /**
     * Defer non-critical scripts
     */
    public function defer_scripts($tag, $handle, $src)
    {
        // Don't defer scripts in admin or if script has localized data
        if (is_admin() || strpos($tag, 'var ') !== false) {
            return $tag;
        }

        // List of scripts to defer
        $defer_handles = [
            'wp-embed',
            'comment-reply',
            'attributes-enhanced'
        ];

        if (in_array($handle, $defer_handles)) {
            return str_replace(' src', ' defer src', $tag);
        }

        return $tag;
    }

    /**
     * Custom error handler
     */
    public function custom_error_handler($errno, $errstr, $errfile, $errline)
    {
        // Don't handle suppressed errors
        if (!(error_reporting() & $errno)) {
            return false;
        }

        $error_types = [
            E_ERROR => 'Fatal Error',
            E_WARNING => 'Warning',
            E_PARSE => 'Parse Error',
            E_NOTICE => 'Notice',
            E_CORE_ERROR => 'Core Error',
            E_CORE_WARNING => 'Core Warning',
            E_COMPILE_ERROR => 'Compile Error',
            E_COMPILE_WARNING => 'Compile Warning',
            E_USER_ERROR => 'User Error',
            E_USER_WARNING => 'User Warning',
            E_USER_NOTICE => 'User Notice',
            E_STRICT => 'Strict Notice',
            E_RECOVERABLE_ERROR => 'Recoverable Error',
            E_DEPRECATED => 'Deprecated',
            E_USER_DEPRECATED => 'User Deprecated'
        ];

        $error_type = $error_types[$errno] ?? 'Unknown Error';

        $error_info = [
            'type' => $error_type,
            'message' => $errstr,
            'file' => $errfile,
            'line' => $errline,
            'timestamp' => current_time('mysql'),
            'url' => $_SERVER['REQUEST_URI'] ?? '',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'ip' => $_SERVER['REMOTE_ADDR'] ?? ''
        ];

        // Store error for later processing
        $this->errors[] = $error_info;

        // Log error
        $log_message = sprintf(
            'Attributes Canva %s: %s in %s on line %d',
            $error_type,
            $errstr,
            $errfile,
            $errline
        );

        error_log($log_message);

        // Display error to admins if debug mode is on
        if (WP_DEBUG && current_user_can('manage_options')) {
            $this->display_admin_error($error_info);
        }

        // Don't execute PHP internal error handler
        return true;
    }

    /**
     * Check for fatal errors
     */
    public function check_for_fatal_error()
    {
        $error = error_get_last();

        if ($error && in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE])) {
            $error_info = [
                'type' => 'Fatal Error',
                'message' => $error['message'],
                'file' => $error['file'],
                'line' => $error['line'],
                'timestamp' => current_time('mysql')
            ];

            // Log fatal error
            error_log('Attributes Canva Fatal Error: ' . wp_json_encode($error_info));

            // Store in database for admin review
            $this->store_error_in_database($error_info);
        }
    }

    /**
     * Display error to administrators
     */
    private function display_admin_error($error_info)
    {
        if (is_admin() || wp_doing_ajax()) {
            return;
        }

        echo '<div style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 10px; margin: 10px 0; border-radius: 4px;">';
        echo '<strong>Theme Debug:</strong> ' . esc_html($error_info['type']) . ' - ';
        echo esc_html($error_info['message']) . ' in ' . esc_html($error_info['file']) . ' on line ' . esc_html($error_info['line']);
        echo '</div>';
    }

    /**
     * Store error in database
     */
    private function store_error_in_database($error_info)
    {
        $errors = get_option('attributes_canva_errors', []);
        $errors[] = $error_info;

        // Keep only last 50 errors
        if (count($errors) > 50) {
            $errors = array_slice($errors, -50);
        }

        update_option('attributes_canva_errors', $errors);
    }

    /**
     * Performance monitoring
     */
    public function performance_monitoring()
    {
        if (!defined('ATTRIBUTES_CANVA_DEBUG') || !ATTRIBUTES_CANVA_DEBUG) {
            return;
        }

        // Collect performance data
        $this->performance_data = [
            'memory_peak' => memory_get_peak_usage(true),
            'memory_current' => memory_get_usage(true),
            'execution_time' => microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'],
            'queries' => get_num_queries(),
            'timestamp' => current_time('mysql')
        ];

        // Display performance info to admins
        if (current_user_can('manage_options')) {
            $this->display_performance_info();
        }

        // Log performance data
        if ($this->performance_data['execution_time'] > 2 || $this->performance_data['queries'] > 50) {
            error_log('Attributes Canva Performance Warning: ' . wp_json_encode($this->performance_data));
        }
    }

    /**
     * Display performance information
     */
    private function display_performance_info()
    {
        echo '<!-- Performance Debug -->';
        echo '<div id="performance-debug" style="position: fixed; bottom: 10px; right: 10px; background: rgba(0,0,0,0.8); color: white; padding: 10px; border-radius: 4px; font-size: 12px; z-index: 9999;">';
        echo '<strong>Performance:</strong><br>';
        echo 'Memory: ' . size_format($this->performance_data['memory_peak']) . '<br>';
        echo 'Time: ' . round($this->performance_data['execution_time'], 3) . 's<br>';
        echo 'Queries: ' . $this->performance_data['queries'];
        echo '</div>';
    }

    /**
     * Get performance data
     */
    public function get_performance_data()
    {
        return $this->performance_data;
    }

    /**
     * Get stored errors
     */
    public function get_errors()
    {
        return get_option('attributes_canva_errors', []);
    }

    /**
     * Clear stored errors
     */
    public function clear_errors()
    {
        delete_option('attributes_canva_errors');
    }

    /**
     * Optimize images on upload
     */
    public static function optimize_image_upload($image_data)
    {
        // Add image optimization logic here
        // This is a placeholder for image optimization functionality

        return $image_data;
    }

    /**
     * Generate critical CSS
     */
    public static function generate_critical_css()
    {
        // This would generate critical CSS for above-the-fold content
        // Placeholder for future implementation

        return '';
    }

    /**
     * Cache management
     */
    public static function clear_theme_cache()
    {
        // Clear theme-specific transients
        $transients = [
            'attributes_canva_active_builders',
            'attributes_canva_multilingual_plugin',
            'attributes_canva_latest_version'
        ];

        foreach ($transients as $transient) {
            delete_transient($transient);
        }

        // Clear object cache if available
        if (function_exists('wp_cache_flush')) {
            wp_cache_flush();
        }
    }
}

/**
 * File System Security and Optimization
 */
class Attributes_Canva_File_Security
{

    public static function secure_file_permissions()
    {
        $secure_files = [
            'wp-config.php' => 0600,
            '.htaccess' => 0644,
            'index.php' => 0644
        ];

        foreach ($secure_files as $file => $permission) {
            $file_path = ABSPATH . $file;
            if (file_exists($file_path)) {
                chmod($file_path, $permission);
            }
        }
    }

    public static function create_security_files()
    {
        $security_content = "<?php\n// Silence is golden\n";
        $directories = [
            get_template_directory() . '/inc/',
            get_template_directory() . '/acf-json/',
            get_template_directory() . '/languages/'
        ];

        foreach ($directories as $dir) {
            if (!file_exists($dir)) {
                wp_mkdir_p($dir);
            }

            $index_file = $dir . 'index.php';
            if (!file_exists($index_file)) {
                file_put_contents($index_file, $security_content);
            }
        }

        // Create .htaccess for additional security
        $htaccess_content = "# Attributes Canva Security\n";
        $htaccess_content .= "Options -Indexes\n";
        $htaccess_content .= "<Files *.php>\n";
        $htaccess_content .= "Order Deny,Allow\n";
        $htaccess_content .= "Deny from all\n";
        $htaccess_content .= "</Files>\n";

        $secure_dirs = [
            get_template_directory() . '/inc/',
            get_template_directory() . '/acf-json/'
        ];

        foreach ($secure_dirs as $dir) {
            $htaccess_file = $dir . '.htaccess';
            if (!file_exists($htaccess_file)) {
                file_put_contents($htaccess_file, $htaccess_content);
            }
        }
    }
}

/**
 * Initialize performance and security systems
 */
function attributes_canva_init_performance()
{
    Attributes_Canva_Performance::get_instance();
    Attributes_Canva_File_Security::create_security_files();
}
add_action('after_setup_theme', 'attributes_canva_init_performance');

/**
 * Admin dashboard for performance monitoring
 */
function attributes_canva_performance_dashboard()
{
    if (!current_user_can('manage_options')) {
        return;
    }

    $performance = Attributes_Canva_Performance::get_instance();
    $errors = $performance->get_errors();
    $performance_data = $performance->get_performance_data();

?>
    <div class="wrap">
        <h1><?php _e('Theme Performance & Errors', 'attribute-canva'); ?></h1>

        <?php if (!empty($errors)): ?>
            <div class="notice notice-warning">
                <h2><?php _e('Recent Errors', 'attribute-canva'); ?></h2>
                <div style="max-height: 300px; overflow-y: auto;">
                    <?php foreach (array_reverse(array_slice($errors, -10)) as $error): ?>
                        <div style="padding: 10px; border-bottom: 1px solid #ddd;">
                            <strong><?php echo esc_html($error['type']); ?>:</strong>
                            <?php echo esc_html($error['message']); ?><br>
                            <small>
                                <?php echo esc_html($error['file']); ?>:<?php echo esc_html($error['line']); ?>
                                (<?php echo esc_html($error['timestamp']); ?>)
                            </small>
                        </div>
                    <?php endforeach; ?>
                </div>
                <p>
                    <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=theme-performance&action=clear_errors'), 'clear_errors'); ?>"
                        class="button"><?php _e('Clear Errors', 'attribute-canva'); ?></a>
                </p>
            </div>
        <?php endif; ?>

        <?php if (!empty($performance_data)): ?>
            <div class="notice notice-info">
                <h2><?php _e('Performance Data', 'attribute-canva'); ?></h2>
                <p><strong><?php _e('Memory Usage:', 'attribute-canva'); ?></strong> <?php echo size_format($performance_data['memory_peak']); ?></p>
                <p><strong><?php _e('Execution Time:', 'attribute-canva'); ?></strong> <?php echo round($performance_data['execution_time'], 3); ?>s</p>
                <p><strong><?php _e('Database Queries:', 'attribute-canva'); ?></strong> <?php echo $performance_data['queries']; ?></p>
            </div>
        <?php endif; ?>

        <div class="card">
            <h2><?php _e('Performance Tools', 'attribute-canva'); ?></h2>
            <p>
                <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=theme-performance&action=clear_cache'), 'clear_cache'); ?>"
                    class="button button-primary"><?php _e('Clear Theme Cache', 'attribute-canva'); ?></a>
            </p>
        </div>
    </div>
<?php
}

// Handle admin actions
if (isset($_GET['action']) && isset($_GET['_wpnonce'])) {
    $action = sanitize_text_field($_GET['action']);

    if ($action === 'clear_errors' && wp_verify_nonce($_GET['_wpnonce'], 'clear_errors')) {
        Attributes_Canva_Performance::get_instance()->clear_errors();
        wp_redirect(admin_url('admin.php?page=theme-performance'));
        exit;
    }

    if ($action === 'clear_cache' && wp_verify_nonce($_GET['_wpnonce'], 'clear_cache')) {
        Attributes_Canva_Performance::clear_theme_cache();
        wp_redirect(admin_url('admin.php?page=theme-performance'));
        exit;
    }
}

// Add admin menu
function attributes_canva_add_performance_menu()
{
    add_submenu_page(
        'themes.php',
        __('Performance & Errors', 'attribute-canva'),
        __('Performance', 'attribute-canva'),
        'manage_options',
        'theme-performance',
        'attributes_canva_performance_dashboard'
    );
}
add_action('admin_menu', 'attributes_canva_add_performance_menu');
