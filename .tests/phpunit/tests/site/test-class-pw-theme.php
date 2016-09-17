<?php

/**
 * These are some relatively simple tests to ensure that the correct theme is running.
 */
class Test_PW_Theme extends WP_UnitTestCase {
	/**
	 * Ensure the name of the theme correct.
	 */
	function test_theme_name() {
		$expected = 'Peter Wilson v3';
		$actual = wp_get_theme()->get( 'Name' );

		$this->assertEquals( $expected, $actual );
	}
}
