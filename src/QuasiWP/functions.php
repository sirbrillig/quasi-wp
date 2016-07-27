<?php
namespace QuasiWP;

function mock_wp() {
	require_once( dirname( __FILE__ ) . '/Globals.php' );
	$functions = new Mocks();

	$options = new Options();
	$options->mock_options();

	$blog = new BlogSwitch();
	$blog->mock_blog_switch();

	$themes = new Themes();
	$themes->mock_themes();

	$posts = new Posts();
	$posts->mock_posts();

	$functions->mock_wpdb();
	$functions->mock_wp_actions();
	$functions->mock_wp_filters();
	$functions->mock_wp_utilities();
	$functions->mock_wp_shortcodes();
	$functions->mock_wp_translation();
	$functions->mock_wp_theme_mods();
	$functions->mock_wp_blog_stickers();
	$functions->mock_wp_misc();
	$functions->mock_current_time();
}

function mock_functions( $functions_to_mock ) {
	foreach( $functions_to_mock as $function_name ) {
		\Spies\mock_function( $function_name );
	}
}
