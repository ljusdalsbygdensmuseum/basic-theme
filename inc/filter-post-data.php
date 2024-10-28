<?php
add_filter('wp_insert_post_data', 'filter_data_custom');

function filter_data_custom($data) {
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