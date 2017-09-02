<?php
namespace PWCC\Tests;

/**
 * Class Tests_Public_Git_Repos
 *
 * @package PWCC\Tests
 * @group   git
 */
class Tests_Public_Git_Repos extends \WP_UnitTestCase {
	/**
	 * Ensure public git repos are included as submodules.
	 *
	 * Checks that all public repos are included via HTTP scheme, one
	 * private repo is permitted for the premium plugins.
	 */
	function test_public_git_repos() {
		$git_submodules = '.gitmodules';

		if ( ! file_exists( $git_submodules ) ) {
			$this->markTestSkipped( '.gitmodules does NOT exist.' );
		}
		$submodules = file_get_contents( $git_submodules, 'r' );

		/*
		 * Count submodules in use. It is expected all except one will
		 * use the HTTP protocol.
		 */
		$submodule_count = substr_count( $submodules, '[submodule' );
		$expected        = $submodule_count - 1;

		// The actual number of submodules using the HTTP protocol.
		$actual = substr_count( $submodules, 'url = http' );

		$this->assertSame( $expected, $actual );
	}
}
