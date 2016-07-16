<?php

use PHPUnit\Framework\TestCase;
use function \Spies\mock_function;
use function \Spies\get_spy_for;
use function \Spies\finish_spying;

class ThemesTest extends TestCase {

	public function setUp() {
		mock_function( 'get_option' );
		$this->themes = new \QuasiWP\Themes();
	}

	public function tearDown() {
		finish_spying();
	}

	public function test_get_stylesheet_returns_current_theme_slug() {
		mock_function( 'get_option' )->when_called->with( 'stylesheet' )->will_return( 'foobar' );
		$this->themes->mock_themes();
		$this->assertEquals( 'foobar', get_stylesheet() );
	}

	public function test_switch_theme_changes_the_current_theme_option() {
		$this->themes->mock_themes();
		$spy = get_spy_for( 'update_option' );
		switch_theme( 'barfoo' );
		$this->assertTrue( $spy->was_called_with( 'stylesheet', 'barfoo' ) );
	}

}
