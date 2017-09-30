<?php
/**
 * @package   peterwilson-2017
 * @author    Peter Wilson <wilson@peterwilson.cc>
 * @copyright 2017 Peter Wilson
 * @license   GPLv2
 */

namespace PWCC\PeterWilson2017;

/**
 * Bootstrap the theme.
 *
 * Runs on the `after_setup_theme` hook.
 */
function bootstrap() {
	// At priority 0 to ensure it runs before enqueued scripts and styles are echoed.
	add_action( 'wp_head', __NAMESPACE__ . '\\javascript_detection', 0 );
	add_filter( 'http_request_args', __NAMESPACE__ . '\\disable_theme_checks', 10, 2 );
	add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_assets' );
	setup_theme_support();
	set_content_width();
}

/**
 * Enqueue scripts and styles for WP.
 */
function enqueue_assets() {
	$suffix = SCRIPT_DEBUG ? '' : '.min';

	// Let's get some style.
	wp_enqueue_style(
		'peter-wilson-2017-theme',
		get_stylesheet_directory_uri() . "/assets/css/theme{$suffix}.css",
		[],
		wp_get_theme()->get( 'Version' )
	);
}

/**
 * Add support for various theme features.
 *
 * Theme features supported are:
 * - post formats
 * - feed links
 * - html5 (complete)
 * - title tag
 */
function setup_theme_support() {
	// Support post formats.
	add_theme_support( 'post-formats', [ 'status', 'image' ] );

	// RSS Feeds.
	add_theme_support( 'automatic-feed-links' );

	// HTML5.
	add_theme_support(
		'html5',
		[
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		]
	);

	// Generate title tag automatically.
	add_theme_support( 'title-tag' );
}

/**
 * Set the content width of the theme.
 *
 * WordPress™ requires this and sure, why not.
 *
 * @global int $content_width The content width.
 */
function set_content_width() {
	$content_width = 1160;

	// Check if is post or page and there is a sidebar.
	if ( is_singular() && is_active_sidebar( 'sidebar-1' ) ) {
		$content_width = 760;
	}

	$GLOBALS['content_width'] = $content_width;
}

/**
 * Handles JavaScript detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * Runs on `wp_head, 0`.
 */
function javascript_detection() {
	echo "<script>(function(h){h.className=h.className.replace(/\bno-js\b/,'js')})(document.documentElement)</script>\n";
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
