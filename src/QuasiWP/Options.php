<?php
namespace QuasiWP;

class Options {
	public function mock_options() {
		$mock_options = [];

		\Spies\mock_function( 'get_blog_option' )->and_return( function( $_blog_id = null, $key, $default = null ) use ( &$mock_options ) {
			if ( isset( $mock_options[ $_blog_id ][ $key ] ) ) {
				return $mock_options[ $_blog_id ][ $key ];
			}
			return $default;
		} );

		\Spies\mock_function( 'get_option' )->and_return( function( $key, $default = null ) use ( &$mock_options ) {
			return get_blog_option( get_current_blog_id(), $key, $default );
		} );

		\Spies\mock_function( 'update_blog_option' )->and_return( function( $_blog_id, $key, $value ) use ( &$mock_options ) {
			if ( ! isset( $mock_options[ $_blog_id ] ) ) {
				$mock_options[ $_blog_id ] = [];
			}
			$mock_options[ $_blog_id ][ $key ] = $value;
		} );

		\Spies\mock_function( 'update_option' )->and_return( function( $key, $value ) use ( &$mock_options ) {
			return update_blog_option( get_current_blog_id(), $key, $value );
		} );

		\Spies\mock_function( 'delete_blog_option' )->and_return( function( $_blog_id, $key ) use ( &$mock_options ) {
			unset( $mock_options[ $_blog_id ][ $key ] );
		} );

		\Spies\mock_function( 'delete_option' )->and_return( function( $key ) use ( &$mock_options ) {
			return delete_blog_option( get_current_blog_id(), $key );
		} );
	}
}
