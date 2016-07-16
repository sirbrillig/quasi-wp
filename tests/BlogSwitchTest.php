<?php

use PHPUnit\Framework\TestCase;
use function \Spies\mock_function;
use function \Spies\finish_spying;

class BlogSwitch extends TestCase {

	public function setUp() {
		$this->blog = new \QuasiWP\BlogSwitch();
	}

	public function tearDown() {
		finish_spying();
	}

	public function test_switch_to_blog_switches_current_blog_id() {
		$this->blog->mock_blog_switch();
		switch_to_blog( 4 );
		$this->assertEquals( 4, get_current_blog_id() );
	}

	public function test_switch_to_blog_twice_switches_current_blog_id() {
		$this->blog->mock_blog_switch();
		switch_to_blog( 4 );
		switch_to_blog( 6 );
		$this->assertEquals( 6, get_current_blog_id() );
	}

	public function test_restore_current_blog_switches_back_one_blog() {
		$this->blog->mock_blog_switch();
		switch_to_blog( 4 );
		switch_to_blog( 6 );
		restore_current_blog();
		$this->assertEquals( 4, get_current_blog_id() );
	}

	public function test_restore_current_blog_twice_switches_back_two_blogs() {
		$this->blog->mock_blog_switch();
		switch_to_blog( 4 );
		switch_to_blog( 5 );
		switch_to_blog( 6 );
		restore_current_blog();
		restore_current_blog();
		$this->assertEquals( 4, get_current_blog_id() );
	}

}

