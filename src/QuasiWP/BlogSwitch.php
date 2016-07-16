<?php
namespace QuasiWP;

class BlogSwitch {
	public function mock_blog_switch() {
		$mock_blogs = array( 0 );

		\Spies\mock_function( 'switch_to_blog' )->and_return( function( $blog_id ) use ( &$mock_blogs ) {
			array_unshift( $mock_blogs, $blog_id );
		} );

		\Spies\mock_function( 'restore_current_blog' )->and_return( function() use ( &$mock_blogs ) {
			array_shift( $mock_blogs );
		} );

		\Spies\mock_function( 'get_current_blog_id' )->and_return( function() use ( &$mock_blogs ) {
			return $mock_blogs[0];
		} );
	}
}

