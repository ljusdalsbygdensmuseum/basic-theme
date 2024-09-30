<?php 
// FRONT PAGE
get_header();
while(have_posts()){
    the_post();
    ?>
    <section>
    <?php page_banner(get_the_ID())?>

    <main class="container">
            <?php the_content() ?>
            <?php
        }
        ?>
    </section>
    <section>
        <div class="row">
            <div class="news event col-sm-6">
                <h2><a href="<?php echo get_post_type_archive_link( 'ljm_event' )?>">Event</a></h2>
                <ul>
                    <?php
                    $eventblogquery = NEW WP_Query(array(
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
                        )
                    ));
                    while ($eventblogquery->have_posts()) {
                        $eventblogquery->the_post();
                        ?>
                        <li>
                            <?php get_template_part('template-parts/thumbnail', get_post_type());?>
                        </li>
                        <?php
                    } wp_reset_postdata();
                    ?>
                </ul>
            </div>
            <div class="news col-sm-6">
                <h2><a href="<?php echo get_post_type_archive_link( 'post' )?>">Blog</a></h2>
                <ul>
                    <?php
                    $blogPostQuery = NEW WP_Query(array(
                        'posts_per_page' => 3,
                    ));
                    while ($blogPostQuery->have_posts()) {
                        $blogPostQuery->the_post();
                        ?>
                        <li>
                            <?php get_template_part('template-parts/thumbnail', get_post_type()); ?>
                        </li>
                        <?php
                    } wp_reset_postdata();
                    ?>
                </ul>
            </div>
        </div>
        
    </section>
</main>
<?php
get_footer();

?>