<?php

use PHPUnit\Framework\TestCase;

class OptionsTest extends TestCase {

	public function test_mock_options_adds_get_option() {
		$this->assertFalse( function_exists( 'get_option' ) );
		$options = new \QuasiWP\Options();
		$options->mock_options();
		$this->assertTrue( function_exists( 'get_option' ) );
	}
}

