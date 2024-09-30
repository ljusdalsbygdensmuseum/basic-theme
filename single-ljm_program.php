<?php 
// program
get_header();
while(have_posts()){
    the_post();
    ?>
    <?php page_banner(get_the_ID())?>

    <main class="container">
    <?php the_content() ?>

    <?php
    //professors query
    $program_professors_relation = NEW WP_Query(array(
        'posts_per_page' => -1,
        'post_type' => 'ljm_professors',
        'orderby'   =>'title',
        'order'     => 'ASC',
        'meta_query'=> array(
            array(
                'key'   => 'ljm_professors_related_programs', 
                'compare' => 'LIKE',
                'value' => '"'.get_the_ID().'"'
            ), 
        )
    ));

    if($program_professors_relation->have_posts()){
        ?>
        <hr>
            <h2>Teachers</h2>
            <ul class="teacher-list">
            <?php
                while ($program_professors_relation->have_posts()) {
                    $program_professors_relation->the_post();
                    ?>
                    <li>
                        <?php get_template_part('template-parts/thumbnail', get_post_type()); ?>
                    </li>
                    <?php
                } wp_reset_postdata();
                ?>
            </ul>
    <?php
    }

    //Event query
    $program_event_relation = NEW WP_Query(array(
        'posts_per_page' => 3,
        'post_type' => 'ljm_event',
        'meta_key'  => 'ljm_event_dateof',
        'orderby'   => 'meta_value_num',
        'order'     => 'ASC',
        'meta_query'=> array(
            array(
                'key'   => 'ljm_event_dateof',
                'compare'=> '>=',
                'value' => date('Ymd'),
            ),
            array(
                'key'   => 'ljm_event_related_programs', 
                'compare' => 'LIKE',
                'value' => '"'.get_the_ID().'"'
            ), 
        )
    ));
    
    if($program_event_relation->have_posts()){
        ?>
        <hr>
        <div class="news event col-sm-6">
            <h2>Upcoming events</h2>
            <ul>
            <?php
                while ($program_event_relation->have_posts()) {
                    $program_event_relation->the_post();
                    ?>
                    <li>
                        <?php get_template_part('template-parts/thumbnail', get_post_type()); ?>
                    </li>
                    <?php
                } wp_reset_postdata();
                ?>
            </ul>
        </div>
    <?php
    }
}
?>
<hr>
<p><a href="<?php echo get_post_type_archive_link( 'ljm_program' ) ?>">All programs</a></p>
</main>
<?php
get_footer();

?>