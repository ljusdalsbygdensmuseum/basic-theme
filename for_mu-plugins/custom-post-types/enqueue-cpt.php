<?php
function mu_plugin_scripts(){
    wp_enqueue_style('main_mu_plugin_styles', get_theme_file_uri('for_mu-plugins/custom-post-types/css/main.css'));

	wp_register_script('main_js', get_template_directory_uri() . '/for_mu-plugins/custom-post-types/js/main.js', array('jquery'), '1.13.0', true);
	wp_enqueue_script('main_js');


    if (!wp_script_is('jquery', 'enqueued')) {
		wp_register_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js', false, '3.6.0', true );
		wp_enqueue_script( 'jquery' );
	}

	wp_register_script('jqueryui', get_template_directory_uri() . '/for_mu-plugins/custom-post-types/js/jquery-ui-1.13.2.custom/jquery-ui.js', array('jquery'), '1.13.2', true);
	wp_enqueue_script('jqueryui');
	wp_register_style('jqueryui_style', get_template_directory_uri() . '/for_mu-plugins/custom-post-types/js/jquery-ui-1.13.2.custom/jquery-ui.min.css', false, '1.13.2', 'all');
	wp_enqueue_style('jqueryui_style');
}
add_action('admin_enqueue_scripts', 'mu_plugin_scripts');


// ---------------------- need to change get_template_directory_uri() to work with mu-plugins
?>