<?php
// reroute for custom search function

add_action('rest_api_init', 'register_reroute_search');

function register_reroute_search(){
    register_rest_route('ljm/v1', 'search', array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'json_reroute_search', 
    ));
}

function json_reroute_search($data) {
    $return_array = array(
        'general_info' => array(),
        'events' => array(),
        'programs' => array(),
        'professors' => array(),
        'campuses' => array(),
    );

    //base search
    $base = new WP_Query(array(
        'post_type' => array('post', 'page', 'ljm_event', 'ljm_program', 'ljm_professors', 'ljm_campus'),
        'posts_per_page' => -1,
        's' => sanitize_text_field($data['value'])
    ));

    //related programs
    $programs = array('relation' => 'OR');
    $has_programs = false;

    while($base->have_posts()){
        $base->the_post();
        
        if(get_post_type_object(get_post_type())->name == 'ljm_program'){
            $has_programs = true;
            //event
            array_push($programs, array(
                'key' => 'ljm_event_related_programs',
                'compare'   => 'LIKE',
                'value' => '"'.get_the_ID().'"'
            ));
            //professor
            array_push($programs, array(
                'key' => 'ljm_professors_related_programs',
                'compare'   => 'LIKE',
                'value' => '"'.get_the_ID().'"'
            ));
        }
    }
    

    //meta search
    //check if there are programs in query because when you have 0 programs it posts all of post_type
    if($has_programs){
        $meta = new WP_Query(array(
            'post_type' => array('ljm_event', 'ljm_professors'),
            'posts_per_page' => -1,
            'meta_query' => $programs
        ));
    
        $posts = new WP_Query();
        $posts->posts = array_merge($base->posts, $meta->posts);//--------merging several queries
        $posts->post_count = count($posts->posts); //------------------------------------------------------------------------
        

    }else{
        //fallback so that the while loop dont throw an error
        $posts = $base;
    }
    
    //variable for checking if post has alredy been prosesed
    $allID = array();

    while($posts->have_posts()){
        $posts->the_post();

        //checking if post has alredy been prosesed
        if(in_array(get_the_ID(), $allID)){
            continue;
        }
        //add current id so it wont be re-prosesed
        array_push($allID, get_the_ID());

        $post = array(
            'ID' => get_the_ID(),
            'post_type' => get_post_type_object(get_post_type())->labels->name,
            'title' => get_the_title(),
            'url'   => get_the_permalink(),
            //'content'   => get_the_content(),
            'banner' => array(
                'img' => get_post_meta(get_the_ID(), 'page_banner_img'),
                'text' => get_post_meta(get_the_ID(), 'page_banner_text')
            )
        );

        if (get_post_type() == 'post' || get_post_type() == 'page') {
            array_push($return_array['general_info'], $post);
        }
        if (get_post_type() == 'ljm_event') {
            array_push($return_array['events'], $post);
        }
        if (get_post_type() == 'ljm_program') {
            array_push($return_array['programs'], $post);
        }
        if (get_post_type() == 'ljm_professors') {
            array_push($return_array['professors'], $post);
        }
        if (get_post_type() == 'ljm_campus') {
            array_push($return_array['campuses'], $post);
        }

    }

    return $return_array;
}

?>