<?php 
// INDEX
get_header();
?>
<?php page_banner(get_the_ID(), 'What are you looking for?')?>
<main class="container">
    <?php get_search_form()?>
</main>
<?php
get_footer();

?>