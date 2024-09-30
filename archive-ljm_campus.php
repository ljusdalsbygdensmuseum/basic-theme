<?php 
// archive events
// teaching how to edit query
get_header();
?>
<?php page_banner(get_the_ID(), 'Our campuses')?>
<main class="container">
<div id="map_container" style="height: 300px; width:100%;"></div>
    
<?php
while(have_posts()){
    the_post();
    $markerposition = get_post_meta(get_the_ID(), 'ljm_campus_location', TRUE).':;:'.get_the_title().':;:'.get_the_permalink();
    echo '<div class="leaflet-marker" style="display: none;" data-info="'.$markerposition.'"></div>';
}

?>
</main>
<?php
get_footer();

?>