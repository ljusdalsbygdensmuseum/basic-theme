<?php
// page banner
function page_banner($id, $alt_title = null){
    $title = get_the_title($id);
    if ($alt_title != null) {
        $title = $alt_title;
    }
    $banner_text = get_post_meta($id, 'page_banner_text', true);
    $banner_image = get_post_meta($id, 'page_banner_img', true);

    ?>
    <div class="page-banner container-fluid">
        <div class="row">
        <figure class="overlay">
            <?php if (isset($banner_image) && !empty($banner_image)) {
                ?>
                    <img src="<?php echo wp_get_attachment_image_url($banner_image)?>">
                <?php
            }?>
            </figure>
            <div class="banner-information">
                <h2><?php echo $title?></h2>
                <?php if(isset($banner_text) && !empty($banner_text)){
                    ?>
                    <p><?php echo $banner_text;?></p>
                    <?php
                }?>
            </div>
        </div>
        
        
    </div>
    <?php
}