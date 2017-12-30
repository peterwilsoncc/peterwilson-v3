<?php
/**
 * @package   peterwilson-2017
 * @author    Peter Wilson <wilson@peterwilson.cc>
 * @copyright 2017 Peter Wilson
 * @license   GPLv2
 */

namespace PWCC\PeterWilson2017;

use PWCC\WhiteListHTML;

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
	add_action( 'wp_resource_hints', __NAMESPACE__ . '\\webfonts_set_two', 10, 2 );
	add_action( 'widgets_init', __NAMESPACE__ . '\\setup_widgetized_areas' );
	setup_theme_support();
	setup_theme_menus();
	set_content_width();

	// Manipulate body class.
	add_filter( 'body_class', __NAMESPACE__ . '\\body_classes', 10, 2 );

	// Manipulate post display.
	add_filter( 'excerpt_more', __NAMESPACE__ . '\\excerpt_more' );
}

/**
 * Enqueue scripts and styles for WP.
 */
function enqueue_assets() {
	$suffix = SCRIPT_DEBUG ? '' : '.min';

	wp_enqueue_style(
		'peter-wilson-2017-gfonts-1',
		'https://fonts.googleapis.com/css?family=Roboto|Roboto+Mono|Roboto+Slab:700',
		[],
		null
	);

	// Let's get some style.
	wp_enqueue_style(
		'peter-wilson-2017-theme',
		get_stylesheet_directory_uri() . "/assets/dist/css/theme{$suffix}.css",
		[],
		wp_get_theme()->get( 'Version' )
	);

	// Load the header script.
	wp_enqueue_script(
		'peter-wilson-2017-theme-header',
		get_stylesheet_directory_uri() . "/assets/dist/js/theme-header{$suffix}.js",
		[],
		wp_get_theme()->get( 'Version' )
	);

	// Load the footer script.
	wp_enqueue_script(
		'peter-wilson-2017-theme-footer',
		get_stylesheet_directory_uri() . "/assets/dist/js/theme-footer{$suffix}.js",
		[],
		wp_get_theme()->get( 'Version' ),
		true
	);
}

/**
 * Add second stage web fonts to prefetch header.
 *
 * Runs on `wp_resource_hints, 10`.
 *
 * @param array  $urls   URLs to print for resource hints.
 * @param string $method The relation type the URLs are printed for, e.g. 'preconnect' or 'prerender'.
 * @return array URLS to print for resource hints.
 */
function webfonts_set_two( $hints, $method ) {
	if ( 'prefetch' !== $method ) {
		return $hints;
	}

	$font_set_two = [
		'family' => 'Roboto+Mono:300,400i,700,700i|Roboto:400i,500,500i,700,700i',
	];

	$font_set_two_url = add_query_arg(
		$font_set_two,
		'https://fonts.googleapis.com/css'
	);

	$hints[] = [
		'crossorigin',
		'as'   => 'style',
		'pr'   => 1.0,
		'href' => $font_set_two_url,
	];

	return $hints;
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
 * Add support for native WordPress menus.
 *
 * Registers two menu locations, the primary and stiemap menu. The IDs come
 * from the faux
 */
function setup_theme_menus() {
	register_nav_menus( [
		'header' => __( 'Primary menu (header).', 'pwcc' ),
		'footer' => __( 'Footer site map.', 'pwcc' ),
	] );
}

/**
 * Add support for native WordPress widgets.
 *
 * Runs on the `widgets_init` hook.
 *
 * Registers two widgetized areas:
 * - sidebar-1: the sole sidebar
 * - footer-1: the footer area.
 */
function setup_widgetized_areas() {
	/* Sidebar */
	register_sidebar( [
		'name'          => __( 'Sidebar', 'pwcc' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Sits along the right of the blog.', 'pwcc' ),
		'before_widget' => '<div id="%1$s" class="Widget Widget-Sidebar1 Widget-%2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="Widget_Title">',
		'after_title'   => '</h2>',
	] );

	/* Footer */
	register_sidebar( [
		'name'          => __( 'Footer', 'pwcc' ),
		'id'            => 'footer-1',
		'description'   => __( 'Displayed in the footer of all pages.', 'pwcc' ),
		'before_widget' => '<div id="%1$s" class="Widget Widget-Footer1 Widget-%2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="Widget_Title">',
		'after_title'   => '</h2>',
	] );
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
 * Modify classes used in the body tag.
 *
 * Runs on the `body_class` filter.
 *
 * @param array $classes        HTML classes for body tag.
 * @param array $custom_classes Custom classes passed with calling.
 * @return array Modified HTML classes.
 */
function body_classes( array $classes, array $custom_classes ) {
	/*
	 * WordPress adds a number of classes that are more complicated
	 * than they need to be. We only need a couple.
	 */
	if ( is_singular() ) {
		$classes[] = 't-Singular';
	} else {
		$classes[] = 't-List';
	}

	return (array) $classes;
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

/**
 * Modify the continue reading link displayed on excerpts.
 *
 * Runs on the filter `excerpt_more`.
 *
 * Props: twentyseventeen core theme.
 *
 * @param string $more_link Continue reading link displayed for excerpts.
 * @return string Modified continue reading link.
 */
function excerpt_more( string $more_link ) {
	if ( is_admin() ) {
		return $more_link;
	}

	$more_link = sprintf(
		'<span class="link-more"><a href="%1$s" class="more-link">%2$s</a></span>',
		esc_url( get_permalink() ),
		/* translators: %s: Name of current post */
		sprintf(
			__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'pwcc' ),
			get_the_title()
		)
	);

	/*
	 * Trust no one, not me, not translators.
	 *
	 * Limits allowed tags to those used in the translation.
	 */
	return '&hellip; ' . WhiteListHTML\get_whitelist_html( $more_link, 'a, span', 'post' );
}
