<?php 
// NOTES
if (!is_user_logged_in()) {
    wp_redirect(esc_url(site_url()));
    exit;
}
// Queries
$the_notes_posts = new WP_Query(array(
    'post_type' => 'ljm_note',
    'posts_per_page' => -1,
    'author' => get_current_user_id()
));
get_header();
?>
<?php page_banner(get_the_ID())?>
<main class="container">
    <ul class="note__container">
        
        <?php
        while ($the_notes_posts->have_posts()) {
            $the_notes_posts->the_post();
            ?>
                <li class="note">
                    <input class="note__title" type="text" value="<?php echo esc_attr(get_the_title())?>">
                    <textarea class="note__content"><?php echo esc_attr(strip_tags(get_the_content()))?></textarea>
                    <button class="note__btn">Edit</button>
                    <button class="note__btn">Delete</button>
                </li>
            <?php
        }
        ?>
    </ul>
</main>
<?php
get_footer();

?>