<?php
namespace QuasiWP;

class Posts {
	public function get_new_post_id( $posts ) {
		$id = count( $posts ) + 1;
		$all_ids = array_filter( $posts, function( $post ) {
			return $post['ID'];
		} );
		while ( in_array( $id, $all_ids ) ) {
			$id += 1;
		}
		return $id;
	}

	public function mock_posts() {
		$mock_posts = [];

		\Spies\mock_function( 'wp_insert_post' )->and_return( function( $post ) use ( &$mock_posts ) {
			$post_array = (array) $post;
			if ( ! isset( $post_array['ID'] ) ) {
				$post_array['ID'] = $this->get_new_post_id( $mock_posts );
			}
			$mock_posts[ $post_array['ID'] ] = $post_array;
			return $post_array['ID'];
		} );

		\Spies\mock_function( 'get_post' )->and_return( function( $id, $output ) use ( &$mock_posts ) {
			if ( ! isset( $mock_posts[ $id ] ) ) {
				return null;
			}
			if ( ! $output ) {
				return (object) $mock_posts[ $id ];
			}
			return $mock_posts[ $id ];
		} );
	}
}
