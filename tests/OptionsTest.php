<?php

use PHPUnit\Framework\TestCase;
use function \Spies\mock_function;
use function \Spies\finish_spying;

class OptionsTest extends TestCase {

	public function setUp() {
		mock_function( 'get_current_blog_id' )->and_return( 5 );
		$this->options = new \QuasiWP\Options();
	}

	public function tearDown() {
		finish_spying();
	}

	public function test_mock_options_adds_get_option() {
		$this->assertFalse( function_exists( 'get_option' ) );
		$this->options->mock_options();
		$this->assertTrue( function_exists( 'get_option' ) );
	}

	public function test_get_option_returns_option_that_was_set() {
		$this->options->mock_options();
		update_option( 'foo', 'bar' );
		$this->assertEquals( 'bar', get_option( 'foo' ) );
	}

	public function test_get_option_returns_default_if_not_set() {
		$this->options->mock_options();
		$this->assertEquals( 'bam', get_option( 'foo', 'bam' ) );
	}

	public function test_update_option_changes_the_option() {
		$this->options->mock_options();
		update_option( 'foo', 'bar' );
		update_option( 'foo', 'baz' );
		$this->assertEquals( 'baz', get_option( 'foo' ) );
	}

	public function test_delete_option_unsets_the_option() {
		$this->options->mock_options();
		update_option( 'foo', 'bar' );
		delete_option( 'foo' );
		$this->assertEquals( NULL, get_option( 'foo' ) );
	}

	public function test_get_blog_option_returns_option_for_blog() {
		$this->options->mock_options();
		update_option( 'foo', 'bar' );
		$this->assertEquals( 'bar', get_blog_option( 5, 'foo' ) );
	}

	public function test_get_blog_option_returns_default_if_not_set() {
		$this->options->mock_options();
		$this->assertEquals( 'bah', get_blog_option( 5, 'foo', 'bah' ) );
	}

	public function test_get_blog_option_returns_different_options_for_different_blogs() {
		$this->options->mock_options();
		mock_function( 'get_current_blog_id' )->and_return( 5 );
		update_option( 'foo', 'bar' );
		mock_function( 'get_current_blog_id' )->and_return( 1 );
		update_option( 'foo', 'baz' );
		$this->assertEquals( 'bar', get_blog_option( 5, 'foo' ) );
		$this->assertEquals( 'baz', get_blog_option( 1, 'foo' ) );
	}

	public function test_update_blog_option_sets_different_options_for_different_blogs() {
		$this->options->mock_options();
		update_blog_option( 5, 'foo', 'baz' );
		update_blog_option( 1, 'foo', 'bar' );
		$this->assertEquals( 'baz', get_blog_option( 5, 'foo' ) );
		$this->assertEquals( 'bar', get_blog_option( 1, 'foo' ) );
	}

	public function test_delete_blog_option_unsets_the_option() {
		$this->options->mock_options();
		update_blog_option( 4, 'foo', 'bar' );
		delete_blog_option( 4, 'foo' );
		$this->assertEquals( NULL, get_blog_option( 4, 'foo' ) );
	}

	public function test_delete_blog_option_does_not_unset_the_option_on_other_blogs() {
		$this->options->mock_options();
		update_blog_option( 5, 'foo', 'bar' );
		update_blog_option( 4, 'foo', 'bar' );
		delete_blog_option( 4, 'foo' );
		$this->assertEquals( 'bar', get_blog_option( 5, 'foo' ) );
	}

}

