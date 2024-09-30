<?php 
// INDEX
get_header();
?>
<?php page_banner(get_the_ID(), 'Search results for <span class="manual_search_term">"' . esc_html(get_search_query(false)) . '"</span>');
?>
<main class="container">
    <hr>
    <?php get_search_form()?>
    <hr>
<?php
if(!have_posts()){
    echo 'No posts found';
}
while(have_posts()){
    the_post();
    
    get_template_part('template-parts/thumbnail', get_post_type());
    ?>
    <p>from <?php echo get_post_type_object(get_post_type())->labels->name ?></p>
    <hr>
    <?php
}

echo paginate_links();
?>
</main>
<?php
get_footer();

?>