<?php

function submenu_custom($baseID) {
    if (wp_get_post_parent_id($baseID) || get_children($baseID) ) {
        $editedID = $baseID;
        while($editedID){
            if (wp_get_post_parent_id($editedID) != 0) {
                $editedID = wp_get_post_parent_id($editedID);
            }else{
                break;
            }
        }
        $title = '<p class="submenutitle"><a href="'.get_the_permalink($editedID).'">'.get_the_title($editedID).'</a></p>';
        $args = array(
            'title_li' => null,
            'child_of' => $editedID,
        );
        echo $title;
        wp_list_pages($args);
    }else{
        return;
    }
}