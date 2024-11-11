<?php
// re-route/custom end point

add_action('rest_api_init', 're_route_like');

function re_route_like() {
    register_rest_route('ljm/v1', 'manage_like', array(
        'methods' => 'POST',
        'callback' => 'like_add_post'
    ));

    register_rest_route('ljm/v1', 'manage_like', array(
        'methods' => 'DELETE',
        'callback' => 'like_delete_post'
    ));
}


function like_add_post($data) {
    $user_id = sanitize_text_field($data['user_id']);
    $prof_id = sanitize_text_field($data['prof_id']);

    wp_insert_post(array(
        'post_type' => 'ljm_like',
        'post_status' => 'publish',
        'post_title' => $user_id,
        'meta_input' => array(
            'ljm_like_prof_id' => $prof_id
        )
    ));
    return $user_id;
}

function like_delete_post() {
    return 'remove me';
}