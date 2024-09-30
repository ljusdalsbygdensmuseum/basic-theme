<?php
//adjusting queries

add_action('pre_get_posts', 'ljm_query_adjust');

function ljm_query_adjust($query) {
    if (!is_admin() && is_post_type_archive('ljm_event') && $query->is_main_query()) {
        $query->set('meta_key', 'ljm_event_dateof');
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'ASC');
        $query->set('meta_query', array(
            array(
                'key'   => 'ljm_event_dateof',
                'compare'=> '>=',
                'value' => date('Ymd'),
            ),
        ));
    }
    if(!is_admin() && is_post_type_archive('ljm_program') && $query->is_main_query()){
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
        $query->set('posts_per_page', -1);
    }
    if(!is_admin() && is_post_type_archive('ljm_campus') && $query->is_main_query()){
        $query->set('posts_per_page', -1);
    }

}


?>