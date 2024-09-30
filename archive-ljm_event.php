<?php 
// archive events
// teaching how to edit query
get_header();
?>
<?php page_banner(get_the_ID(), 'Comming events')?>
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
<p>Check out our <a href="<?php echo site_url('/past-events')?>">past events</a></p>
</main>
<?php
get_footer();

?>