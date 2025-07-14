<?php

/**
 * Enhanced Security Functions for Attributes Canva Theme
 * This file should be included in functions.php
 *
 * @package Attribute Canva
 */

// Security check - prevent direct access
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Enhanced Security Class
 */
class Attributes_Canva_Security
{

    private static $instance = null;
    private $rate_limits = [];

    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        add_action('init', [$this, 'init_security_measures']);
        add_action('send_headers', [$this, 'security_headers']);
        add_filter('wp_headers', [$this, 'additional_security_headers']);
        add_action('wp_login_failed', [$this, 'log_failed_login']);
        add_filter('authenticate', [$this, 'check_login_rate_limit'], 30, 3);
    }

    /**
     * Initialize security measures
     */
    public function init_security_measures()
    {
        // Remove WordPress version from head and feeds
        remove_action('wp_head', 'wp_generator');
        add_filter('the_generator', '__return_empty_string');

        // Remove WLW manifest link
        remove_action('wp_head', 'wlwmanifest_link');

        // Remove RSD link
        remove_action('wp_head', 'rsd_link');

        // Remove shortlink
        remove_action('wp_head', 'wp_shortlink_wp_head');

        // Disable XML-RPC if not needed
        if (!get_theme_mod('attributes_canva_enable_xmlrpc', false)) {
            add_filter('xmlrpc_enabled', '__return_false');
        }

        // Hide login errors
        add_filter('login_errors', [$this, 'hide_login_errors']);

        // Disable file editing in admin
        if (!defined('DISALLOW_FILE_EDIT')) {
            define('DISALLOW_FILE_EDIT', true);
        }
    }

    /**
     * Add security headers
     */
    public function security_headers()
    {
        if (!is_admin() && !wp_doing_ajax()) {
            // Prevent MIME type sniffing
            header('X-Content-Type-Options: nosniff');

            // Prevent clickjacking
            header('X-Frame-Options: SAMEORIGIN');

            // Enable XSS protection
            header('X-XSS-Protection: 1; mode=block');

            // Referrer policy
            header('Referrer-Policy: strict-origin-when-cross-origin');

            // Permissions policy
            header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
        }
    }

    /**
     * Additional security headers via filter
     */
    public function additional_security_headers($headers)
    {
        if (!is_admin()) {
            // Content Security Policy (basic)
            $csp = "default-src 'self'; ";
            $csp .= "script-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com; ";
            $csp .= "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com; ";
            $csp .= "font-src 'self' https://fonts.gstatic.com; ";
            $csp .= "img-src 'self' data: https:; ";
            $csp .= "connect-src 'self'; ";
            $csp .= "frame-ancestors 'self';";

            $headers['Content-Security-Policy'] = $csp;
        }

        return $headers;
    }

    /**
     * Hide detailed login errors
     */
    public function hide_login_errors()
    {
        return __('Invalid login credentials.', 'attribute-canva');
    }

    /**
     * Rate limiting for login attempts
     */
    public function check_login_rate_limit($user, $username, $password)
    {
        if (empty($username) || empty($password)) {
            return $user;
        }

        $ip = $this->get_client_ip();
        $key = 'login_attempts_' . md5($ip);
        $attempts = get_transient($key) ?: 0;

        if ($attempts >= 5) {
            return new WP_Error(
                'login_rate_limited',
                __('Too many login attempts. Please try again in 15 minutes.', 'attribute-canva')
            );
        }

        return $user;
    }

    /**
     * Log failed login attempts
     */
    public function log_failed_login($username)
    {
        $ip = $this->get_client_ip();
        $key = 'login_attempts_' . md5($ip);
        $attempts = get_transient($key) ?: 0;

        set_transient($key, $attempts + 1, 15 * MINUTE_IN_SECONDS);

        // Log the attempt
        error_log(sprintf(
            'Failed login attempt for user "%s" from IP: %s',
            sanitize_user($username),
            $ip
        ));
    }

    /**
     * Get client IP address
     */
    private function get_client_ip()
    {
        $ip_keys = ['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'];

        foreach ($ip_keys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }

        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    /**
     * Sanitize and validate input data
     */
    public static function sanitize_input($input, $type = 'text')
    {
        switch ($type) {
            case 'email':
                return sanitize_email($input);
            case 'url':
                return esc_url_raw($input);
            case 'html':
                return wp_kses_post($input);
            case 'textarea':
                return sanitize_textarea_field($input);
            case 'number':
                return absint($input);
            case 'float':
                return floatval($input);
            case 'boolean':
                return (bool) $input;
            case 'array':
                return is_array($input) ? array_map('sanitize_text_field', $input) : [];
            case 'json':
                $decoded = json_decode($input, true);
                return is_array($decoded) ? $decoded : [];
            default:
                return sanitize_text_field($input);
        }
    }

    /**
     * Enhanced nonce verification
     */
    public static function verify_nonce($nonce, $action = 'attributes_canva_nonce')
    {
        if (!wp_verify_nonce($nonce, $action)) {
            wp_die(
                __('Security check failed. Please refresh the page and try again.', 'attribute-canva'),
                __('Security Error', 'attribute-canva'),
                ['response' => 403]
            );
        }
        return true;
    }

    /**
     * Check user capabilities with enhanced error handling
     */
    public static function check_capability($capability = 'read')
    {
        if (!current_user_can($capability)) {
            wp_die(
                __('You do not have sufficient permissions to access this page.', 'attribute-canva'),
                __('Insufficient Permissions', 'attribute-canva'),
                ['response' => 403]
            );
        }
        return true;
    }

    /**
     * Rate limiting for AJAX requests
     */
    public static function check_ajax_rate_limit($action = 'general', $limit = 60, $window = 60)
    {
        $ip = (new self())->get_client_ip();
        $key = "ajax_rate_limit_{$action}_" . md5($ip);
        $requests = get_transient($key) ?: 0;

        if ($requests >= $limit) {
            wp_send_json_error([
                'message' => sprintf(
                    __('Rate limit exceeded. You can make %d requests per %d seconds.', 'attribute-canva'),
                    $limit,
                    $window
                ),
                'code' => 'rate_limit_exceeded'
            ], 429);
        }

        set_transient($key, $requests + 1, $window);
        return true;
    }

    /**
     * Validate file upload security
     */
    public static function validate_file_upload($file)
    {
        // Check file size (5MB limit)
        if ($file['size'] > 5 * 1024 * 1024) {
            return new WP_Error('file_too_large', __('File size exceeds 5MB limit.', 'attribute-canva'));
        }

        // Check file type
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file['type'], $allowed_types)) {
            return new WP_Error('invalid_file_type', __('File type not allowed.', 'attribute-canva'));
        }

        // Check file extension
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($file_ext, $allowed_extensions)) {
            return new WP_Error('invalid_file_extension', __('File extension not allowed.', 'attribute-canva'));
        }

        return true;
    }

    /**
     * SQL injection protection for custom queries
     */
    public static function prepare_query($query, $params = [])
    {
        global $wpdb;

        if (empty($params)) {
            return $query;
        }

        return $wpdb->prepare($query, $params);
    }

    /**
     * Generate secure random token
     */
    public static function generate_token($length = 32)
    {
        return bin2hex(random_bytes($length / 2));
    }

    /**
     * Hash sensitive data
     */
    public static function hash_data($data, $salt = '')
    {
        if (empty($salt)) {
            $salt = wp_salt('auth');
        }
        return hash_hmac('sha256', $data, $salt);
    }

    /**
     * Validate and sanitize form data
     */
    public static function validate_form_data($data, $rules)
    {
        $validated = [];
        $errors = [];

        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? '';
            $type = $rule['type'] ?? 'text';
            $required = $rule['required'] ?? false;
            $min_length = $rule['min_length'] ?? 0;
            $max_length = $rule['max_length'] ?? 0;

            // Check if required field is empty
            if ($required && empty($value)) {
                $errors[$field] = sprintf(
                    __('%s is required.', 'attribute-canva'),
                    $rule['label'] ?? $field
                );
                continue;
            }

            // Skip validation if field is empty and not required
            if (empty($value) && !$required) {
                $validated[$field] = '';
                continue;
            }

            // Sanitize based on type
            $sanitized = self::sanitize_input($value, $type);

            // Validate length
            if ($min_length > 0 && strlen($sanitized) < $min_length) {
                $errors[$field] = sprintf(
                    __('%s must be at least %d characters long.', 'attribute-canva'),
                    $rule['label'] ?? $field,
                    $min_length
                );
                continue;
            }

            if ($max_length > 0 && strlen($sanitized) > $max_length) {
                $errors[$field] = sprintf(
                    __('%s must be no more than %d characters long.', 'attribute-canva'),
                    $rule['label'] ?? $field,
                    $max_length
                );
                continue;
            }

            // Additional validation based on type
            if ($type === 'email' && !is_email($sanitized)) {
                $errors[$field] = __('Please enter a valid email address.', 'attribute-canva');
                continue;
            }

            if ($type === 'url' && !filter_var($sanitized, FILTER_VALIDATE_URL)) {
                $errors[$field] = __('Please enter a valid URL.', 'attribute-canva');
                continue;
            }

            $validated[$field] = $sanitized;
        }

        return [
            'data' => $validated,
            'errors' => $errors,
            'is_valid' => empty($errors)
        ];
    }

    /**
     * Security audit logging
     */
    public static function log_security_event($event, $details = [])
    {
        $log_entry = [
            'timestamp' => current_time('mysql'),
            'event' => sanitize_text_field($event),
            'user_id' => get_current_user_id(),
            'ip_address' => (new self())->get_client_ip(),
            'user_agent' => sanitize_text_field($_SERVER['HTTP_USER_AGENT'] ?? ''),
            'details' => $details
        ];

        // Log to file
        error_log('Security Event: ' . wp_json_encode($log_entry));

        // Optionally store in database
        if (get_theme_mod('attributes_canva_security_logging', false)) {
            $option_key = 'attributes_canva_security_log';
            $log = get_option($option_key, []);
            $log[] = $log_entry;

            // Keep only last 100 entries
            if (count($log) > 100) {
                $log = array_slice($log, -100);
            }

            update_option($option_key, $log);
        }
    }
}

/**
 * Enhanced AJAX Security Handler
 */
function attributes_canva_secure_ajax_handler()
{
    // Initialize security instance
    $security = Attributes_Canva_Security::get_instance();

    // Check nonce
    if (!check_ajax_referer('attr_ajax_nonce', 'security', false)) {
        wp_send_json_error([
            'message' => __('Security verification failed.', 'attribute-canva'),
            'code' => 'security_failed'
        ], 403);
    }

    // Rate limiting
    Attributes_Canva_Security::check_ajax_rate_limit('general', 60, 60);

    // Get and validate action type
    $action_type = Attributes_Canva_Security::sanitize_input($_POST['action_type'] ?? '', 'text');

    if (empty($action_type)) {
        wp_send_json_error([
            'message' => __('Invalid action type.', 'attribute-canva'),
            'code' => 'invalid_action'
        ], 400);
    }

    // Log security event
    Attributes_Canva_Security::log_security_event('ajax_request', [
        'action_type' => $action_type,
        'referrer' => wp_get_referer()
    ]);

    // Handle different action types
    switch ($action_type) {
        case 'contact_form':
            attributes_canva_handle_contact_form();
            break;

        case 'newsletter_signup':
            attributes_canva_handle_newsletter_signup();
            break;

        case 'search_posts':
            attributes_canva_handle_search_posts();
            break;

        default:
            wp_send_json_error([
                'message' => __('Unknown action type.', 'attribute-canva'),
                'code' => 'unknown_action'
            ], 400);
    }

    wp_die();
}

/**
 * Handle contact form submission
 */
function attributes_canva_handle_contact_form()
{
    // Define validation rules
    $rules = [
        'name' => [
            'type' => 'text',
            'required' => true,
            'min_length' => 2,
            'max_length' => 100,
            'label' => __('Name', 'attribute-canva')
        ],
        'email' => [
            'type' => 'email',
            'required' => true,
            'label' => __('Email', 'attribute-canva')
        ],
        'subject' => [
            'type' => 'text',
            'required' => true,
            'min_length' => 5,
            'max_length' => 200,
            'label' => __('Subject', 'attribute-canva')
        ],
        'message' => [
            'type' => 'textarea',
            'required' => true,
            'min_length' => 10,
            'max_length' => 2000,
            'label' => __('Message', 'attribute-canva')
        ]
    ];

    // Validate form data
    $validation = Attributes_Canva_Security::validate_form_data($_POST, $rules);

    if (!$validation['is_valid']) {
        wp_send_json_error([
            'message' => __('Please correct the errors below.', 'attribute-canva'),
            'errors' => $validation['errors']
        ], 400);
    }

    $data = $validation['data'];

    // Send email (implement your email logic here)
    $to = get_option('admin_email');
    $subject = sprintf(__('[%s] New Contact Form Submission', 'attribute-canva'), get_bloginfo('name'));
    $message = sprintf(
        __("New contact form submission:\n\nName: %s\nEmail: %s\nSubject: %s\nMessage:\n%s", 'attribute-canva'),
        $data['name'],
        $data['email'],
        $data['subject'],
        $data['message']
    );

    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        'From: ' . get_bloginfo('name') . ' <' . get_option('admin_email') . '>',
        'Reply-To: ' . $data['name'] . ' <' . $data['email'] . '>'
    ];

    $sent = wp_mail($to, $subject, $message, $headers);

    if ($sent) {
        // Log successful submission
        Attributes_Canva_Security::log_security_event('contact_form_success', [
            'email' => $data['email']
        ]);

        wp_send_json_success([
            'message' => __('Thank you for your message. We will get back to you soon!', 'attribute-canva')
        ]);
    } else {
        wp_send_json_error([
            'message' => __('Sorry, there was an error sending your message. Please try again.', 'attribute-canva'),
            'code' => 'email_failed'
        ], 500);
    }
}

/**
 * Handle newsletter signup
 */
function attributes_canva_handle_newsletter_signup()
{
    $rules = [
        'email' => [
            'type' => 'email',
            'required' => true,
            'label' => __('Email', 'attribute-canva')
        ]
    ];

    $validation = Attributes_Canva_Security::validate_form_data($_POST, $rules);

    if (!$validation['is_valid']) {
        wp_send_json_error([
            'message' => __('Please enter a valid email address.', 'attribute-canva'),
            'errors' => $validation['errors']
        ], 400);
    }

    $email = $validation['data']['email'];

    // Check if email already exists
    $subscribers = get_option('attributes_canva_newsletter_subscribers', []);

    if (in_array($email, $subscribers)) {
        wp_send_json_error([
            'message' => __('This email is already subscribed to our newsletter.', 'attribute-canva'),
            'code' => 'already_subscribed'
        ], 400);
    }

    // Add to subscribers list
    $subscribers[] = $email;
    update_option('attributes_canva_newsletter_subscribers', $subscribers);

    // Log successful signup
    Attributes_Canva_Security::log_security_event('newsletter_signup', [
        'email' => $email
    ]);

    wp_send_json_success([
        'message' => __('Thank you for subscribing to our newsletter!', 'attribute-canva')
    ]);
}

/**
 * Initialize security system
 */
function attributes_canva_init_security()
{
    Attributes_Canva_Security::get_instance();
}
add_action('init', 'attributes_canva_init_security');

// Register secure AJAX handlers
add_action('wp_ajax_attributes_canva_action', 'attributes_canva_secure_ajax_handler');
add_action('wp_ajax_nopriv_attributes_canva_action', 'attributes_canva_secure_ajax_handler');

// In inc/security.php - Add after existing code
class Attributes_Canva_CSRF_Protection
{

    public static function generate_token()
    {
        if (!session_id()) {
            session_start();
        }

        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;
        return $token;
    }

    public static function verify_token($token)
    {
        if (!session_id()) {
            session_start();
        }

        return isset($_SESSION['csrf_token']) &&
            hash_equals($_SESSION['csrf_token'], $token);
    }
}
