<?php
/**
 * @package   peterwilson-2017
 * @author    Peter Wilson <wilson@peterwilson.cc>
 * @copyright 2017 Peter Wilson
 * @license   GPLv2
 * @version   1.0.0-alpha
 */

namespace PWCC\PeterWilson2017;

/**
 * Bootstrap the theme.
 *
 * Runs on the `after_setup_theme` hook.
 */
function bootstrap() {
	add_filter( 'http_request_args', __NAMESPACE__ . '\\disable_theme_checks', 10, 2 );
}

/**
 * Prevent WP checking for theme updates.
 *
 * Ensures that WordPress does not check for updates to this theme on WordPress.org.
 *
 * Runs on the hook `http_request_args`.
 *
 * @param array  $args The array of HTTP request args.
 * @param string $url  The request URL.
 * @return array The array of HTTP request args modified to remove this theme.
 */
function disable_theme_checks( array $args, string $url ) {
	if ( 0 !== strpos( $url, 'https://api.wordpress.org/themes/update-check' ) ) {
		return $args;
	}

	// Remove this theme from the data sent to WP.org.
	$themes = json_decode( $args['body']['themes'], true );
	unset( $themes['themes']['peter-wilson-2017'] );
	$args['body']['themes'] = wp_json_encode( $themes );

	return $args;
}
