<?php

/**
 * Tests the public speaking plugin.
 *
 * @group speaking
 */
class Test_PW_Speaking extends WP_UnitTestCase {

	/**
	 * Test the custom post types are registered.
	 */
	function test_post_types_registered() {
		$post_types = get_post_types();

		$this->assertContains( 'pwcc_presentation', $post_types );
	}

}
