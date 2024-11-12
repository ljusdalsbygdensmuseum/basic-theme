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
    //check if loged in
    if (!is_user_logged_in()) {
        die('You need to be logged in to like.');
    }
    
    $prof_id = (int) sanitize_text_field($data['prof_id']);

    //check if professor exists
    if(get_post_type($prof_id) != 'ljm_professors'){
        die('Invalid professor id.');
    }

    // check if user has already liked this professor
    $user_like_posts = new WP_Query(array(
        'author' => get_current_user_id(),
        'post_type' => 'ljm_like',
        'meta_query' => array(
            array(
                'key' => 'ljm_like_prof_id',
                'compare' => '=',
                'value' => $prof_id
            )
        )
    ));
    if ($user_like_posts->found_posts) {
        die('You have already liked this post.');
    }

    return wp_insert_post(array(
        'post_type' => 'ljm_like',
        'post_status' => 'publish',
        'post_title' => 'like',
        'meta_input' => array(
            'ljm_like_prof_id' => $prof_id
        )
    ));
}

function like_delete_post($data) {
    $id = (int) sanitize_text_field($data['like_id']);

    if(!is_user_logged_in()){
        die('You need to be logged in to remove a like.');
    }
    if(get_post_type($id) != 'ljm_like'){
        die('Invalid like id.');
    }
    if(get_current_user_id() != get_post_field( 'post_author', $id )){
        die('You can not delete another users post.');
    }
    wp_delete_post($id);
    return 'removed like';
}