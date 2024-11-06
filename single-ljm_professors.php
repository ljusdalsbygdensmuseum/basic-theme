<?php 
// single
get_header();

while(have_posts()){
    the_post();
    ?>
    <?php page_banner(get_the_ID())?>

    <main class="container">
    
    <?php the_post_thumbnail('medium', ['class' => 'professor-bio-img'])?>

    <?php the_content() ?>
    <div class="heart-container">
        <span class="heart heart-dorment">♡</span>
        <span class="heart heart-active">♥</span>
        <span class="heart-count">3 likes</span>
    </div>
    <?php
    //Grab and sanitize values and explodes into an array
    $related_posts = explode(', ', sanitize_text_field(get_post_meta(get_the_ID(), 'ljm_professors_related_programs', TRUE)));

    //Check if there are any related posts
    if (!empty($related_posts[0])) {
        ?>
        <div class="teacher-of col-sm-8">
            <h3>Teacher of</h3>
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
</main>
<?php
get_footer();

?>