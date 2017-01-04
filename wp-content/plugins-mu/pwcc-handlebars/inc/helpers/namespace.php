<?php

namespace PWCC\Handlebars\Helpers;

const RELATION_TERM = 'http://v2.wp-api.org/term';

use Exception;

function safe_string( $value ) {
	return array( $value, 'raw' );
}

function esc_attr( $args ) {
	if ( empty( $args[0] ) ) {
		return '';
	}

	if ( ! is_scalar( $args[0] ) ) {
		throw new Exception( 'Only strings can be escaped, attempted to escape: ' . var_export( $args, true ) );
	}
	return safe_string( \esc_attr( $args[0] ) );
}

function esc_url( $args ) {
	if ( empty( $args[0] ) ) {
		return '';
	}

	if ( ! is_scalar( $args[0] ) ) {
		throw new Exception( 'Only strings can be escaped, attempted to escape: ' . var_export( $args, true ) );
	}
	return safe_string( \esc_url( $args[0] ) );
}

function esc_html( $args ) {
	if ( empty( $args[0] ) ) {
		return '';
	}

	if ( ! is_scalar( $args[0] ) ) {
		throw new Exception( 'Only strings can be escaped, attempted to escape: ' . var_export( $args, true ) );
	}
	return safe_string( \esc_html( $args[0] ) );
}

function esc_textarea( $args ) {
	if ( empty( $args[0] ) ) {
		return '';
	}

	if ( ! is_scalar( $args[0] ) ) {
		throw new Exception( 'Only strings can be escaped, attempted to escape: ' . var_export( $args, true ) );
	}
	return safe_string( \esc_textarea( $args[0] ) );
}

function dump( $args ) {
	ob_start();
	var_dump( $args[0] );
	return safe_string( '<pre>' . \esc_html( ob_get_clean() ) . '</pre>' );
}

function format_date( $args ) {
	$time = $args[0];
	return \date_i18n( get_option( 'date_format' ), strtotime( $time ) );
}

function format_time( $args ) {
	$time = $args[0];
	return \date_i18n( get_option( 'time_format' ), strtotime( $time ) );
}

function relative_time( $args ) {
	$time = $args[0];
	return human_time_diff( strtotime( $time ) );
}

/**
 * Get terms for a post
 *
 * @param array $args Post data, then taxonomy
 * @return array
 */
function terms_for_post( $args ) {
	if ( count( $args ) !== 2 || ! is_array( $args[0] ) || ! is_string( $args[1] ) ) {
		throw new Exception( 'terms_for_post expects two parameters: post, taxonomy' );
	}

	list( $post, $tax ) = $args;

	if ( empty( $post['_links'] ) || empty( $post['_links'][ RELATION_TERM ] ) ) {
		return array( array(), 'internal' );
	}

	// Find the index for the embed
	$found = null;
	foreach ( $post['_links'][ RELATION_TERM ] as $index => $link ) {
		if ( $link['taxonomy'] === $tax ) {
			$found = $index;
			break;
		}
	}

	if ( empty( $found ) ) {
		return array( array(), 'internal' );
	}

	// Grab the data for that taxonomy
	$data = $post['_embedded'][ RELATION_TERM ][ $found ];
	return array( $data, 'internal' );
}

function get_comments( $post, $parent = 0 ) {
	if ( empty( $post['_embedded'] ) || empty( $post['_embedded']['replies'] ) ) {
		return array( array(), 'internal' );
	}

	$comments = $post['_embedded']['replies'][0];

	// Reduce down to top-level
	$top_level = array_filter( $comments, function ( $comment ) use ( $parent ) {
		return $comment['parent'] === $parent;
	} );

	// Sort by date
	usort( $top_level, function ( $a, $b ) {
		return strtotime( $a['date'] ) - strtotime( $b['date'] );
	} );

	return array( $top_level, 'internal' );
}

/**
 * Get top-level comments on a post
 *
 * @param array $args Post data as first element
 * @return array
 */
function comments( $args ) {
	$post = $args[0];

	// If the model hasn't been saved yet, there's no replies
	if ( empty( $post['id'] ) ) {
		return array( array(), 'internal' );
	}

	return get_comments( $post );
}

/**
 * Get replies to a given comment
 *
 * @param array $args Post data as first element
 * @return array
 */
function replies( $args ) {
	$comment = $args[0];

	// If the model hasn't been saved yet, there's no replies
	if ( empty( $comment['id'] ) ) {
		return array( array(), 'internal' );
	}

	$post = $comment['post'];
	return get_comments( $post, $comment['id'] );
}

function user_by_id() {
	$args = func_get_args();
	$ctx = array_pop( $args );
	$id = $args[0];

	$users = $ctx['data']['root']['users'];

	// Find the user we want
	$user = array_filter( $users, function ( $user ) use ( $id ) {
		return $user['id'] === $id;
	});

	if ( empty( $user ) ) {
		return array( array(), 'internal' );
	}

	return array( $user[0], 'internal' );
}
