<?php 
// single event
get_header();
while(have_posts()){
    the_post();
    ?>
    <?php page_banner(get_the_ID())?>

    <main class="container">
    <?php the_content() ?>
    <?php
    //Grab and sanitize values and explodes into an array
    $related_posts = explode(', ', sanitize_text_field(get_post_meta(get_the_ID(), 'ljm_event_related_programs', TRUE)));

    //Check if there are any related posts
    if (!empty($related_posts[0])) {
        ?>
        <div class="news event col-sm-8">
            <h3>Related programs</h3>
            <ul>
            <?php
            //Prints each related post
            foreach ($related_posts as $post) {
                //Removes "" from $post for easy handling
                $post = str_replace('"', '', $post);
                ?>
                <li><a href="<?php the_permalink($post)?>"><?php echo get_the_title($post)?></a></li>
                <?php
            }
            ?>
            </ul>
        </div>
        <?php
    }
}
?>
<hr>
<p>Check out our <a href="<?php echo get_post_type_archive_link( 'ljm_event' ) ?>">other events</a></p>
</main>
<?php
get_footer();

?>