<?php 
// Hook into Gravity Forms submission using 'gform_after_submission'
// https://docs.gravityforms.com/gform_after_submission/


function send_to_webhook( $entry, $form ) {

    // Map the Gravity Forms fields to variables
    $first_name = rgar( $entry, '1' ); // Assuming Field ID 1 is First Name
    $last_name  = rgar( $entry, '2' ); // Assuming Field ID 2 is Last Name
    $email      = rgar( $entry, '3' ); // Assuming Field ID 3 is Email
    $phone      = rgar( $entry, '4' ); // Assuming Field ID 4 is Phone
    $message    = rgar( $entry, '5' ); // Assuming Field ID 5 is Message

    // Prepare data to send
    $data = array(
        'first_name' => $first_name,
        'last_name'  => $last_name,
        'email'      => $email,
        'phone'      => $phone,
        'message'    => $message
    );

    // Webhook URL (replace with your own)
    $url = 'https://webhook-test.com/962a0ba219478ea3e24106723b2f79f0';

    // Send data using wp_remote_post()
    $response = wp_remote_post( $url, array(
        'method'    => 'POST',
        'body'      => json_encode( $data ),
        'headers'   => array(
            'Content-Type' => 'application/json'
        ),
    ));

    // Check if there is an error with the request
    if ( is_wp_error( $response ) ) {
        $error_message = $response->get_error_message();
        error_log( 'Gravity Forms webhook failed: ' . $error_message );
    } else {
        error_log( 'Gravity Forms webhook succeeded.' );
    }
}
add_action( 'gform_after_submission_YOUR_FORM_ID', 'send_to_webhook', 10, 2 );