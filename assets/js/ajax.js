jQuery(document).ready(function ($) {
    // FIXED: Using correct variable name 'attributesAjax' that matches wp_localize_script
    
    // Example AJAX request on button click
    $('#ajax-button').on('click', function (e) {
        e.preventDefault();

        // Show loading state
        $(this).prop('disabled', true).text('Loading...');

        // Data to send to the server
        let requestData = {
            action: 'attributes_canva_action', // Action name matching the PHP handler
            security: attributesAjax.nonce, // Pass the nonce for security
            data: 'Hello, server!' // Example data
        };

        // Make the AJAX request
        $.post(attributesAjax.ajax_url, requestData, function (response) {
            // Re-enable button
            $('#ajax-button').prop('disabled', false).text('Send AJAX Request');
            
            if (response.success) {
                // Handle success response
                alert(response.data.message + ' Data: ' + response.data.data);
            } else {
                // Handle error response
                alert('Error: ' + (response.data.message || 'An error occurred.'));
            }
        }).fail(function(xhr, status, error) {
            // Handle AJAX failure
            $('#ajax-button').prop('disabled', false).text('Send AJAX Request');
            alert('AJAX Error: ' + error);
        });
    });

    // Example: Dynamic content loading
    $('.load-content').on('click', function(e) {
        e.preventDefault();
        
        let contentId = $(this).data('content-id');
        let targetContainer = $(this).data('target');
        
        $.post(attributesAjax.ajax_url, {
            action: 'attributes_canva_load_content',
            security: attributesAjax.nonce,
            content_id: contentId
        }, function(response) {
            if (response.success) {
                $(targetContainer).html(response.data.content);
            }
        });
    });
});