<?php 
// archive events
// teaching how to edit query and pagination
get_header();
?>
<?php page_banner(get_the_ID())?>
<main class="container">
<?php

$pastevents = new WP_Query(array(
    'paged' => get_query_var('paged', 1),                                                   //---------- custom pagination
    'posts_per_page' => 2,
    'post_type' => 'ljm_event',
    'meta_key'  => 'ljm_event_dateof',
    'orderby'   => 'meta_value_num',
    'order'     => 'ASC',
    'meta_query'=> array(
        array(
            'key'   => 'ljm_event_dateof',
            'compare'=> '<',
            'value' => date('Ymd'),
        ),
    )
));


while($pastevents->have_posts()){
    $pastevents->the_post();

    get_template_part('template-parts/thumbnail', get_post_type());
    ?>
    
    <hr>
    <?php
}

echo paginate_links(array(
    'total' => $pastevents->max_num_pages,                                                  //---------- custom pagination
));
?>
</main>
<?php
get_footer();

?>