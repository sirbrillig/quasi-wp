<?php
namespace QuasiWP;

class Themes {
	public function mock_themes() {
		\Spies\mock_function( 'get_stylesheet' )->and_return( function() {
			return get_option( 'stylesheet' );
		} );

		\Spies\mock_function( 'switch_theme' )->and_return( function( $slug ) {
			return update_option( 'stylesheet', $slug );
		} );
	}
}

