<h2><a href="<?php the_permalink()?>"><?php the_title() ?></a> </h2>
<?php
    // date formating
    $date = new DateTime(get_post_meta(get_the_ID() , 'ljm_event_dateof', TRUE));
    echo $date->format('jS M Y'); 
?>
<p><?php echo wp_trim_words(get_the_content(), 15)?> </p><a href="<?php the_permalink()?>">learn more</a>
