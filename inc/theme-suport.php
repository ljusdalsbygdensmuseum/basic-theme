<?php
// theme suport

function test_theme_suport(){
    register_nav_menu('mainMenu', 'Huvud meny');
    register_nav_menu('footerMenu', 'Fot meny');

    add_theme_support('post-thumbnails');
}

add_action('after_setup_theme', 'test_theme_suport');