<?php 
// archive events
get_header();
?>
<?php page_banner(get_the_ID(), 'Our programs')?>
<main class="container">
<?php
while(have_posts()){
    the_post();
    get_template_part('template-parts/thumbnail', get_post_type());
    ?>
    
    <hr>
    <?php
}

echo paginate_links();
?>
</main>
<?php
get_footer();

?>