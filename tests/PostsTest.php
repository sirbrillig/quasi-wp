<?php

use PHPUnit\Framework\TestCase;
use function \Spies\mock_function;
use function \Spies\finish_spying;

class PostsTest extends TestCase {

	public function setUp() {
		$this->posts = new \QuasiWP\Posts();
	}

	public function tearDown() {
		finish_spying();
	}

	public function test_mock_posts_lets_wp_insert_posts_return_an_id() {
		$this->posts->mock_posts();
		$post = [ 'post_content' => 'hello' ];
		$res = wp_insert_post( $post );
		$this->assertEquals( 1, $res );
	}

	public function test_mock_posts_lets_wp_insert_posts_return_the_passed_id() {
		$this->posts->mock_posts();
		$post = [ 'ID' => 50, 'post_content' => 'hello' ];
		$res = wp_insert_post( $post );
		$this->assertEquals( 50, $res );
	}

	public function test_mock_posts_lets_wp_insert_posts_return_a_unique_id() {
		$this->posts->mock_posts();
		$post1 = [ 'ID' => 1, 'post_content' => 'hello' ];
		 wp_insert_post( $post1 );
		$post2 = [ 'post_content' => 'hello' ];
		$res = wp_insert_post( $post2 );
		$this->assertEquals( 2, $res );
	}

}
