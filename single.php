<?php 
// single
get_header();
while(have_posts()){
    the_post();
    ?>
    <?php page_banner(get_the_ID())?>

    <main class="container">
    <h2><?php the_title() ?></h2>
    <p><?php the_date() ?></p>
    <?php the_content() ?>
    <?php
}
?>
</main>
<?php
get_footer();

?>