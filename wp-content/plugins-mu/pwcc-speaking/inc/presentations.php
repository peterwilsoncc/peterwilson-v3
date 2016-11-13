<?php
namespace PWCC\Speaking\Presentations;

function init() {
	register_cpt();
}

function register_cpt() {
	$cpt_args = [
		'public'        => true,
		'menu_position' => 20,
		'menu_icon'     => 'dashicons-megaphone',
		'hierarchical'  => false,
		'has_archive'   => true,
		'query_var'     => 'presentations',
		'supports'      => [
			'title',
			'editor',
			'excerpt',
			'revisions',
		],
	];

	$cpt_names = [
		'singular' => 'Presentation',
		'plural'   => 'Presentations',
		'slug'     => 'presentations',
	];

	$post_type = register_extended_post_type( 'pwcc_presentation', $cpt_args, $cpt_names );

	return $post_type;
}
