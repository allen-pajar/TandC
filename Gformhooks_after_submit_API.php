<?php 
// Hook into Gravity Forms submission using 'gform_after_submission'
// https://docs.gravityforms.com/gform_after_submission/


function submit_data_to_filemaker($entry, $form) {
    // Retrieve the data from the submitted form fields
    $field_data = array(
        'name'   => rgar($entry, '1'),  // Assuming field ID 1 is the name field, adjust as necessary
        'email'  => rgar($entry, '2'),  // Assuming field ID 2 is the email field
        'phone'  => rgar($entry, '3'),  // Assuming field ID 3 is the phone field
        // Add more fields as needed
    );

    // Send data to FileMaker
    $response = submit_to_filemaker($field_data);

    if (is_wp_error($response)) {
        // Handle the error appropriately (logging, notification, etc.)
        error_log('FileMaker submission failed: ' . $response->get_error_message());
    }
}
add_action('gform_after_submission_YOUR_FORM_ID', 'submit_data_to_filemaker', 10, 2);



// Function to submit data to FileMaker
function submit_to_filemaker($data) {
    // Example API request to FileMaker, adjust as per your setup
    $api_url = 'https://your-filemaker-server.com/fmi/data/vLatest/databases/YOUR_DB/layouts/YOUR_LAYOUT/records';
    $api_token = 'YOUR_API_TOKEN';  // Assuming you're using an API token

    // Prepare the payload for FileMaker (adjust the structure based on the API)
    $payload = array(
        'fieldData' => array(
            'Name'  => $data['name'],   // Map form field data to FileMaker fields
            'Email' => $data['email'],
            'Phone' => $data['phone'],
        ),
    );

    // Make the API request
    $response = wp_remote_post($api_url, array(
        'headers' => array(
            'Authorization' => 'Bearer ' . $api_token,
            'Content-Type'  => 'application/json',
        ),
        'body'    => json_encode($payload),
    ));

    // Check for errors in the request
    if (is_wp_error($response)) {
        return $response;  // Return the error
    }

    // Handle successful response (optional)
    $response_body = wp_remote_retrieve_body($response);
    return json_decode($response_body, true);
}

?>