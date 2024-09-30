<?php 
// page
get_header();

while(have_posts()){
    the_post();
    ?>
    <div class="row breadcrumbs">
        <div class="col">
                <?php
                    breadcrumb_list(get_the_ID());
                ?>

        </div>
    </div>

    <?php page_banner(get_the_ID())?>
    <main class="container">
    <div class="row">
        <div class="col">
            <?php the_content() ?>
        </div>
        <div class="col-sm-4">
            
                <?php
                    submenu_custom(get_the_ID(), false);
                    
                ?>
            
        </div>
    </div>
    <?php
}
?>
</main>
<?php
get_footer();

?>