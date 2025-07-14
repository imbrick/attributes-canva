<?php

/**
 * Handle AJAX requests
 */

if (!function_exists('attributes_canva_ajax_handler')) {
    /**
     * Handle AJAX requests with proper security and error handling
     */
    function attributes_canva_ajax_handler()
    {
        // Verify the nonce for security
        if (!check_ajax_referer('attr_ajax_nonce', 'security', false)) {
            wp_send_json_error(array(
                'message' => __('Security check failed.', 'attribute-canva')
            ));
            wp_die();
        }

        // Check user capabilities if needed
        if (!current_user_can('read')) {
            wp_send_json_error(array(
                'message' => __('You do not have sufficient permissions.', 'attribute-canva')
            ));
            wp_die();
        }

        // Get data from the AJAX request
        $data = isset($_POST['data']) ? sanitize_text_field($_POST['data']) : '';

        // Validate input
        if (empty($data)) {
            wp_send_json_error(array(
                'message' => __('No data provided.', 'attribute-canva')
            ));
            wp_die();
        }

        // Process the data (example logic; replace with your own)
        $response = array(
            'status'  => 'success',
            'message' => __('Data received successfully!', 'attribute-canva'),
            'data'    => $data
        );

        // Return the response as JSON
        wp_send_json_success($response);

        // Terminate the script after sending the response
        wp_die();
    }

    // Register AJAX actions for both logged in and non-logged in users
    add_action('wp_ajax_attributes_canva_action', 'attributes_canva_ajax_handler');
    add_action('wp_ajax_nopriv_attributes_canva_action', 'attributes_canva_ajax_handler');
}
}