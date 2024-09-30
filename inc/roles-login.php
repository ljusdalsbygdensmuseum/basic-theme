<?php
//redirection
function redirectSubsToFront() {
    $currentUser = wp_get_current_user();
    
    if (count($currentUser->roles) == 1 AND $currentUser->roles[0] == 'subscriber') {
        wp_redirect(esc_url(site_url()));
        exit;
    }
    
}
add_action('admin_init', 'redirectSubsToFront');

//redirection
function removeSubsAdminBar() {
    $currentUser = wp_get_current_user();
    
    if (count($currentUser->roles) == 1 AND $currentUser->roles[0] == 'subscriber') {
        show_admin_bar(false);
    }
    
}
add_action('wp_loaded', 'removeSubsAdminBar');

// Customize Login Screen
add_filter('login_headerurl', 'ourHeaderUrl');

function ourHeaderUrl() {
  return esc_url(site_url('/'));
}

add_filter('login_headertitle', 'ourLoginTitle');

function ourLoginTitle() {
  return get_bloginfo('name');
}




?>