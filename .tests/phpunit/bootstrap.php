<?php
/**
  * Bootstrap the plugin unit testing environment.
  *
  * @package WordPress
  * @subpackage JSON API
  */

$test_root = dirname( __FILE__ ) . '/lib';

require $test_root . '/includes/functions.php';

/**
 * Set the theme for unit tests.
 */
function _pwcc_tests_register_theme() {
	global $wp_test_current_theme;
	$wp_test_current_theme = 'peterwilsoncc-v3';
	add_filter( 'pre_option_template', function() {
		global $wp_test_current_theme;
		return $wp_test_current_theme;
	} );
	add_filter( 'pre_option_stylesheet', function() {
		global $wp_test_current_theme;
		return $wp_test_current_theme;
	} );
}
tests_add_filter( 'muplugins_loaded', '_pwcc_tests_register_theme' );

require $test_root . '/includes/bootstrap.php';
