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


function like_add_post() {
    return 'add me';
}

function like_delete_post() {
    return 'remove me';
}