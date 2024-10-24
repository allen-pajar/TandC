<?php 
// Hook into Gravity Forms 'gform_pre_render' to trigger when the form initializes
// https://docs.gravityforms.com/gform_pre_render/

add_filter('gform_pre_render_YOUR_FORM_ID', 'populate_dropdown_with_filemaker_data');

function populate_dropdown_with_filemaker_data($form) {
    // Query FileMaker data here
    $filemaker_data = query_filemaker_for_dropdown(); // You’ll write this function to handle the API/database query

    // Find the dropdown field you want to populate in Gravity Forms
    foreach ($form['fields'] as &$field) {
        if ($field->type === 'select' && $field->id == YOUR_DROPDOWN_FIELD_ID) {
            // Clear out any existing choices
            $field->choices = array();

            // Populate dropdown with FileMaker data
            foreach ($filemaker_data as $item) {
                $field->choices[] = array(
                    'text'  => $item['name'],  // The label of the dropdown item
                    'value' => $item['id']     // The value associated with the dropdown item
                );
            }
        }
    }

    return $form; // Return the modified form with the new dropdown choices
}

// Function to query FileMaker data
function query_filemaker_for_dropdown() {
    // Example of querying FileMaker, adjust as necessary for your setup (API, direct DB, etc.)
    // Assuming this returns an array of data from FileMaker for the dropdown
    $data = array(
        array('id' => 1, 'name' => 'Option 1'),
        array('id' => 2, 'name' => 'Option 2'),
        // Add more options based on FileMaker query
    );

    return $data;
}

?>