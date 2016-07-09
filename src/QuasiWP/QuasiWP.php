<?php
namespace QuasiWP;

class QuasiWP {
	public function mock_wp() {
		require_once( dirname( __FILE__ ) . 'Globals.php' );
		$functions = new Functions();

		$functions->mock_wpdb();
		$functions->mock_wp_options();
		$functions->mock_wp_themes();
		$functions->mock_wp_actions();
		$functions->mock_wp_filters();
		$functions->mock_wp_utilities();
		$functions->mock_wp_shortcodes();
		$functions->mock_wp_translation();
		$functions->mock_wp_theme_mods();
		$functions->mock_wp_blog_stickers();
		$functions->mock_wp_blog_switch();
		$functions->mock_wp_misc();
		$functions->mock_current_time();
	}
}
