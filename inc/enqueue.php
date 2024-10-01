<?php 
// ENQUE
function the_test_scripts(){
    //wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css');
    //wp_enqueue_style('main_test_styles', get_theme_file_uri('css/main.css'));

	if (!wp_script_is('jquery', 'enqueued')) {
		wp_register_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js', false, '3.6.0', true );
		wp_enqueue_script( 'jquery' );
	}

	wp_enqueue_script('main-university-js', get_theme_file_uri('/build/index.js'), array('jquery'), '1.0', true);
	wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/index.css'));

	wp_localize_script( 'main-university-js', 'universalData', array(
		'root_url' => get_site_url(),
		'nonce' => wp_create_nonce('wp_rest')
	));//-----------------------prints the url so that one can find it in js

}
add_action('wp_enqueue_scripts', 'the_test_scripts');

function the_test_admin_scripts(){

	if (!wp_script_is('jquery', 'enqueued')) {
		wp_register_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js', false, '3.6.0', true );
		wp_enqueue_script( 'jquery' );
	}


	wp_enqueue_script('main-university-js', get_theme_file_uri('/build/admin.js'), array('jquery'), '1.0', true);
	wp_enqueue_style('university_extra_styles', get_theme_file_uri('/build/admin.css'));

	wp_localize_script( 'main-university-js', 'universalData', array(
		'root_url' => get_site_url(),
		'nonce' => wp_create_nonce('wp_rest')
	));

}
add_action('admin_enqueue_scripts', 'the_test_admin_scripts');

add_action('login_enqueue_scripts', 'the_test_admin_scripts');
?>