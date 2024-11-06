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
    <?php
        $like_posts = new WP_Query(array(
            'post_type' => 'ljm_like',
            'meta_query' => array(
                array(
                    'key' => 'ljm_like_prof_id',
                    'compare' => '=',
                    'value' => get_the_ID()
                )
            )
        ));
        $user_like_posts = new WP_Query(array(
            'author' => get_current_user_id(),
            'post_type' => 'ljm_like',
            'meta_query' => array(
                array(
                    'key' => 'ljm_like_prof_id',
                    'compare' => '=',
                    'value' => get_the_ID()
                )
            )
        ));
        
        if ($user_like_posts->found_posts) {
            $user_liked = "true";
        }else{
            $user_liked = "false";
        }
    ?>
    <div data-user_liked="<?php echo $user_liked ?>" class="heart-container">
        <span class="heart heart-dorment">♡</span>
        <span class="heart heart-active">♥</span>
        <span class="heart-count"><?php echo $like_posts->found_posts;// found posts returns the num of posts starting with 1 ?> likes</span> 
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