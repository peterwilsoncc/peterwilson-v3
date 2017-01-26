<?php
namespace PWCC\Styleguide;

use WP;
use WP_Query;

function bootstrap() {
	if ( ! function_exists( 'hm_add_rewrite_rule' ) ) {
		// Dependencies fail. Bail.
		return;
	}

	add_rewrite();
	add_filter( 'posts_pre_query', __NAMESPACE__ . '\\posts_pre_query', 10, 2 );

	// Block WordPress.org updates for this plugin.
	add_filter( 'http_request_args', __NAMESPACE__ . '\\block_worg_updates', 5, 2 );
}

/**
 * Add a rewrite rule for the styleguide.
 */
function add_rewrite() {
	hm_add_rewrite_rule([
		'regex'               => '^(styleguide)(\/?|\/(.*))$',
		'query'               => 'styleguide=$matches[3]',
		'template'            => __DIR__ . '/../styleguide.php',
		'body_class_callback' => __NAMESPACE__ . '\\body_class_callback',
		'query_callback'      => __NAMESPACE__ . '\\query_callback',
		'disable_canonical'   => true,
	]);
}

/**
 * Add styleguide related classes to the body class.
 *
 * Note: `hm_add_rewrite_rule` ensures this runs on the correct pages.
 *
 * @param  array  $classes Classes generated by `get_body_class()`.
 * @return array           Classes to be displayed on body class.
 */
function body_class_callback( array $classes = [] ) {
	$classes[] = 'styleguide';
	if ( ! empty( get_query_var( 'styleguide' ) ) ) {
		$html_class = str_replace( '/', '-', get_query_var( 'styleguide' ) );
		$classes[] = 'styleguide-' . sanitize_html_class( $html_class );
	}
	return $classes;
}

/**
 * Bypass main query on styleguide pages.
 *
 * The styleguide requires WP to be running but does not need any posts,
 * this returns an empty array of posts to prevent WP_Query from running an
 * SQL Query.
 *
 * @param  null     $posts Posts already generated by WP_Query, which is none.
 * @param  WP_Query $query The $wp_query object.
 * @return null|array      An array of post data to bypass the SQL Query.
 *                         Null if the query is to proceed.
 */
function posts_pre_query( $posts, WP_Query $query ) {
	if ( $query->is_main_query() && isset( $query->query_vars['styleguide'] ) ) {
		// These need to be set manually as part of bypassing the database query.
		$query->found_posts   = 1;
		$query->max_num_pages = 1;
		// Bypass the database query.
		return [];
	}
	return $posts;
}

/**
 * Fix up the WP_Query properties that WP always gets wrong.
 *
 * Sets is_home and is_404 to false.
 *
 * Note: `hm_add_rewrite_rule` ensures this runs on the correct query.
 *
 * @param  WP_Query $query The main WP_Query object.
 * @return WP_Query        The main WP_Query object.
 */
function query_callback( WP_Query $query ) {
	// WordPress always gets this wrong.
	$query->is_home = false;
	$query->is_404 = false;
	return $query;
}

/**
 * Prevent WordPress.org from attempting to update a private plugin.
 *
 * Interupts the HTTP request to the WordPress.org API.
 *
 * @param  array  $request_args The arguments used for the HTTP request.
 * @param  string $url          URL the request is made to.
 * @return arrray               The arguments used for the HTTP request.
 */
function block_worg_updates( $request_args, $url ) {
	// Figure out the WordPress.org plugin updates URL.
	$api_url = 'http://api.wordpress.org/plugins/update-check/1.1/';
	if ( wp_http_supports( array( 'ssl' ) ) ) {
		$api_url = set_url_scheme( $api_url, 'https' );
	}

	if ( 0 !== strpos( $url, $api_url ) ) {
		// Not a plugin update request. Bail immediately.
		return $request_args;
	}

	// Remove this plugin from the request for updates.
	$plugins = json_decode( $request_args['body']['plugins'] );
	$plugin_name = plugin_basename( dirname( __DIR__ ) . '/plugin.php' );
	unset( $plugins->plugins->$plugin_name );
	unset( $plugins->active->$plugin_name );
	$request_args['body']['plugins'] = wp_json_encode( $plugins );

	return $request_args;
}

/**
 * Get all handlebars files in the styleguide.
 *
 * @param  string $dir [description]
 * @return [type]      [description]
 */
function get_files( $dir = 'handlebars' ) {
	$all = wp_get_theme()->get_files( 'hb.html', -1, true );
	$dir = untrailingslashit( $dir ) . '/';
	$all = array_filter( $all, function ( $value, $key ) use ( $dir ) {
		return ( 0 === strpos( $key, $dir ) );
	}, ARRAY_FILTER_USE_BOTH );

	return $all;
}

/**
 * Get the short names for all the handlebars files in the style guide.
 * @param  string $dir [description]
 * @return [type]      [description]
 */
function get_names( $dir = 'handlebars' ) {
	$all = get_files( $dir );
	$dir = untrailingslashit( $dir ) . '/';

	$named_files = [];
	$ll = array_filter( $all, function ( $value, $key ) use ( &$named_files, $dir ) {
		$name = substr( $key, strlen( $dir ) );

		preg_match( '/^(\d*-?)?([a-zA-Z0-9\-\_]*)\/.*\/(\d*-?)?([a-zA-Z0-9\-\_]*)(\.hb\.html)$/' , $name, $matches );

		$name = $matches[2] . '.' . $matches[4];
		$named_files[ $name ] = $value;
		return true;
	}, ARRAY_FILTER_USE_BOTH );

	return $named_files;
}

