<?php
add_filter('wp_insert_post_data', 'filter_data_custom');

function filter_data_custom($data) {
    // sanitize incoming text to make shure no malicius code enters the database
    if ($data['post_type'] == 'ljm_note') {
        $data['post_content'] = sanitize_textarea_field($data['post_content']);
        $data['post_title'] = sanitize_text_field($data['post_title']);
    }
    //changes status to private so that it cant be accesed without login
    if ($data['post_type'] == 'ljm_note' && $data['post_status'] != 'trash') {
        $data['post_status'] = 'private';
    }


    return $data;
}

add_filter( 'private_title_format', 'wpdocs_remove_private_protected_from_titles' );

function wpdocs_remove_private_protected_from_titles( $format ) {
    return '%s';
}
?>