<?php

/**
 * Ensure the vendor plugins are enabled.
 */
class Test_PW_Vendor_Plugins_Enabled extends WP_UnitTestCase {

	/**
	 * Human Made's Custom Meta Boxes.
	 */
	function test_hm_cmb() {
		$this->assertTrue( function_exists( 'cmb_init' ), 'HM Custom Meta Boxes available.' );
	}

	/**
	 * JB's extended-cpts available.
	 */
	function test_extended_cpts() {
		$this->assertTrue( function_exists( 'register_extended_post_type' ), 'Extended CPTs available.' );
	}

	/**
	 * JB's extended-taxos available.
	 */
	function test_extended_taxos() {
		$this->assertTrue( function_exists( 'register_extended_taxonomy' ), 'Extended Taxos available.' );
	}

	/**
	 * JB's extended template parts available.
	 */
	function test_extended_template_parts() {
		$this->assertTrue( function_exists( 'get_extended_template_part' ), 'Extended Template Parts available.' );
	}
}
