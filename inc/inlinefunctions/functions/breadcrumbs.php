<?php
function breadcrumb_list($baseID){
    $pageID = $baseID;
    $breadcrumblist = '';
    while(wp_get_post_parent_id($pageID)){
        if ($pageID == $baseID) {
            $breadcrumblist = '<li><a href="'.get_the_permalink($pageID).'">'. get_the_title($pageID).'</a></li>'.$breadcrumblist;
        }
        $pageID = wp_get_post_parent_id($pageID);
        $breadcrumblist = '<li><a href="'.get_the_permalink($pageID).'">'. get_the_title($pageID).'</a></li><li>></li>'.$breadcrumblist;
    }
    $breadcrumblist = '<ul>'.$breadcrumblist.'</ul>';
    echo $breadcrumblist; 
}

?>