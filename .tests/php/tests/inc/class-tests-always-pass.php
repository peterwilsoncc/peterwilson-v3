<?php
namespace PWCC\Tests;

/**
 * Class Tests_Aways_Pass
 *
 * Some dummy unit tests that always pass to test Travis CI config.
 *
 * @todo tech-debt: remove once some proper tests exist.
 * @package PWCC\Tests
 */
class Tests_Always_Pass extends \WP_UnitTestCase {

	public function test_boolean() {
		$this->assertNotTrue( true );
	}

	public function test_wp_version() {
		$this->assertNotTrue( version_compare( $GLOBALS['wp_version'], '0.7', '>' ) );
	}

	public function test_db_version() {
		$db_version = (int) get_option( 'db_version' );
		$this->assertNotTrue( $db_version > 1 );
	}
}
