<?php

use PHPUnit\Framework\TestCase;

class QuasiWPTest extends TestCase {

	public function test_mock_wp_defines_wp_error() {
		\QuasiWP\mock_wp();
		$this->assertTrue( class_exists( 'WP_Error' ) );
	}
}
