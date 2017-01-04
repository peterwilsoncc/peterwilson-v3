<?php
namespace PWCC\Handlebars;

use LightnCandy\LightnCandy;

function bootstrap() {

}

function get_hb_template_part( $slug, $name = '', array $vars = [], array $args = [] ) {
	$args = wp_parse_args( $args, array(
		'cache' => false,
		'dir'   => '',
	) );

	$template = new Template_Part( $slug, $name, $vars, $args );
	echo $template->get_output(); // WPCS: XSS ok.
}

function get_handlebars_options() {
	$flags = LightnCandy::FLAG_HANDLEBARSJS;
	$flags |= LightnCandy::FLAG_ERROR_EXCEPTION;
	$flags |= LightnCandy::FLAG_RUNTIMEPARTIAL;
	$flags |= LightnCandy::FLAG_EXTHELPER;

	/**
	 * Filter whether debug mode is enabled for Handlebars compilation.
	 *
	 * @param bool $enable_debug True to enable template debugging, false for production compilation.
	 */
	$enable_debug = apply_filters( 'pwcc_handlebars_debug', WP_DEBUG );
	if ( $enable_debug ) {
		$flags |= LightnCandy::FLAG_RENDER_DEBUG;
	}

	$options = array(
		'flags'   => $flags,
		'helpers' => array(),
	);

	// Register helpers
	$options['helpers']['esc_attr']       = __NAMESPACE__ . '\\Helpers\\esc_attr';
	$options['helpers']['esc_url']        = __NAMESPACE__ . '\\Helpers\\esc_url';
	$options['helpers']['esc_html']       = __NAMESPACE__ . '\\Helpers\\esc_html';
	$options['helpers']['esc_textarea']   = __NAMESPACE__ . '\\Helpers\\esc_textarea';
	$options['helpers']['dump']           = __NAMESPACE__ . '\\Helpers\\dump';
	$options['helpers']['format_date']    = __NAMESPACE__ . '\\Helpers\\format_date';
	$options['helpers']['format_time']    = __NAMESPACE__ . '\\Helpers\\format_time';
	$options['helpers']['relative_time']    = __NAMESPACE__ . '\\Helpers\\relative_time';
	$options['helpers']['terms_for_post'] = __NAMESPACE__ . '\\Helpers\\terms_for_post';
	$options['helpers']['comments']       = __NAMESPACE__ . '\\Helpers\\comments';
	$options['helpers']['replies']        = __NAMESPACE__ . '\\Helpers\\replies';
	$options['helpers']['user_by_id']     = __NAMESPACE__ . '\\Helpers\\user_by_id';

	/**
	 * Filter the available helpers for Handlebars.
	 *
	 * @param array $helpers Map of helper name to callback.
	 */
	$options['helpers'] = apply_filters( 'pwcc_handlebars_helpers', $options['helpers'] );

	return $options;
}
