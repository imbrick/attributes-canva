<?php

/**
 * Handle AJAX requests
 */

function attributes_canva_ajax_handler() {
    // Verify the nonce for security
    check_ajax_referer('attr_ajax_nonce', 'security');

    // Get data from the AJAX request
    $data = isset($_POST['data']) ? sanitize_text_field($_POST['data']) : '';

    // Process the data (example logic; replace with your own)
    $response = array(
        'status'  => 'success',
        'message' => 'Data received successfully!',
        'data'    => $data
    );

    // Return the response as JSON
    wp_send_json($response);

    // Terminate the script after sending the response
    wp_die();
}
