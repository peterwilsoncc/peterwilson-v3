<?php

class PW_WordPress_Version extends WP_UnitTestCase {
	function test_version() {
		$expected = '4.7';
		$actual   = get_bloginfo( 'version' );

		$this->assertEquals( $expected, $actual );
	}
}
