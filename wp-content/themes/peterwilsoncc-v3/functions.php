<?php
namespace PWCC\ThemeV3;

add_action( 'after_setup_theme', __NAMESPACE__ . '\\bootstrap' );

function bootstrap() {
	add_theme_support( 'title-tag' );

	add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_assets' );
	add_action( 'hm_parse_request_^(pattern-library)(\/?|\/(.*))$', __NAMESPACE__ . '\\bootstrap_pl' );
}

function bootstrap_pl() {
	add_filter( 'pwcc_themev3_assets_path', function( $path ) {
		return 'assets-dev';
	} );

	add_filter( 'pwcc_handlebars_template_directory', function ( $path ) {
		return 'handlebars-dev';
	} );
}

function assets_uri( $path = '' ) {
	$assets_path = trim( apply_filters( 'pwcc_themev3_assets_path', 'assets' ), '/\\' );

	$path = trim( $path, '/\\' );

	return esc_url( get_stylesheet_directory_uri() . "/${assets_path}/${path}" );
}

function enqueue_assets() {
	wp_enqueue_style(
		'pwcc_v3',
		assets_uri( 'css/styles.css' ),
		[],
		'2016-02-26',
		'all'
	);

}
