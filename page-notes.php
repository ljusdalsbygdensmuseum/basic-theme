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
        <li id="new-note" class="note">
            <h3>New note</h3>
            <input class="note__title" type="text" placeholder="Title">
            <textarea class="note__content" placeholder="Content"></textarea>
            <div class="note__spinner-container"></div>
            <button class="note__btn note__create">Save</button>
        </li>
    </ul>
        <h3>my notes</h3>
    <ul id="note__container" class="note__container">
        <?php
        while ($the_notes_posts->have_posts()) {
            $the_notes_posts->the_post();
            ?>
                <li data-id="<?php echo get_the_ID()?>" data-state="inactive" class="note">
                    <input readonly class="note__title" type="text" value="<?php echo esc_attr(get_the_title())?>">
                    <textarea readonly class="note__content"><?php echo esc_attr(strip_tags(get_the_content()))?></textarea>
                    <div class="note__spinner-container"></div>
                    <button class="note__btn note__edit">Edit</button>
                    <button class="note__btn note__save hidden">Save</button>
                    <button class="note__btn note__delete">Delete</button>
                </li>
            <?php
        }
        ?>
    </ul>
</main>
<?php
get_footer();

?>