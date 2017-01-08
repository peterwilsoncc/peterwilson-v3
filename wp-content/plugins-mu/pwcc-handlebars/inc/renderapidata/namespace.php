<?php
namespace PWCC\Handlebars\RenderAPIData;

use WP_REST_Request;
use WP_REST_Posts_Controller;

/**
 * Convert data from a REST API endpoint.
 *
 * Converts a request for an object to the WP REST API data.
 *
 * @param  WP_REST_Response $data WP_REST_Response object.
 * @param  array|bool   $args Whether to include embeddable data.
 * @return array        Array of representing the REST API Object.
 */
function convert_data( $data, $args = false ) {
	$server = rest_get_server();
	$data = $server->response_to_data( $data, false );

	$links = $data['_links'];
	// Unset self because it's dumb to embed.
	unset( $links['self'] );
	$defaults = [];

	if ( is_bool( $args ) ) {
		foreach ( $links as $key => $value ) {
			$defaults[ $key ] = $args;
		}
		$args = [];
	} else {
		foreach ( $links as $key => $value ) {
			$defaults[ $key ] = false;
		}
		$defaults['wp:term'] = true;
	}

	if ( isset( $args['terms'] ) && ! isset( $args['wp:term'] ) ) {
		$args['wp:term'] = $args['terms'];
		unset( $args['terms'] );
	}

	$args = wp_parse_args( $args, $defaults );

	$embedded = [];

	foreach ( $args as $rel => $include ) {
		if ( ! $include ) {
			continue;
		}

		$items = $links[ $rel ];

		if ( ! is_array( $items ) ) {
			continue;
		}

		$embeds = array();

		foreach ( $items as $item ) {

			/*
			 * This code is a dreadful, dreadful hack to account for edge cases.
			 *
			 * Warming the term cache on WP_Query misses warming the cache for
			 * querying a single post against a single taxonomy. In pseudo code:
			 * `get post 4, categories` or `get post 4, tags`.
			 *
			 * These are the queries the Rest API makes use of when requesting
			 * terms using a standard embed causing the number of database queries to
			 * balloon fairly quickly.
			 *
			 * As we don't really use the extra data these queries return (basically
			 * counts), we can skip straight to the embedding each term directly.
			 *
			 * I thought it was cache invalidation that was supposed to be hard.
			 */
			if ( 'wp:term' === $rel ) {
				$terms = get_the_terms( $data['id'], $item['taxonomy'] );
				if ( is_wp_error( $terms ) || false === $terms ) {
					// $embeds[] = [];
					continue;
				}

				$term_ids = wp_list_pluck( $terms, 'term_id' );

				// Get taxonomy root
				$href = $item['href'];
				$href = add_query_arg( [
					'post' => false,
				], $href );

				$term_embeds = [];
				foreach ( $term_ids as $term_id ) {
					$term_href = untrailingslashit( $href ) . '/' . $term_id;
					$item['href'] = $term_href;
					$term_embeds[] = embed_link( $item );
				}

				$embeds[] = $term_embeds;

				continue;
			}

			$embeds[] = embed_link( $item );
		}

		// Determine if any real links were found.
		$has_links = count( array_filter( $embeds ) );

		if ( $has_links ) {
			$embedded[ $rel ] = $embeds;
		}
	}

	$data['_embedded'] = $embedded;

	// Hackity hack hack: Ensures we're only dealing with arrays
	$data = json_decode( wp_json_encode( $data ), true );

	return $data;
}

/**
 * Return embedded data based on a link.
 * @param  array $item Item to be linked.
 * @return array       Embedded data.
 */
function embed_link( $item ) {
	$server = rest_get_server();

	// Run through our internal routing and serve.
	$request = WP_REST_Request::from_url( $item['href'] );
	if ( ! $request ) {
		return [];
	}

	// Embedded resources get passed context=embed.
	if ( empty( $request['context'] ) ) {
		$request['context'] = 'embed';
	}

	$response = $server->dispatch( $request );

	/** This filter is documented in wp-includes/rest-api/class-wp-rest-server.php */
	$response = apply_filters( 'rest_post_dispatch', rest_ensure_response( $response ), $server, $request );

	return $server->response_to_data( $response, false );
}

/**
 * Convert an array of posts to REST API objects.
 *
 * Convers a bunch of WP_Post type objects to their WP-REST API equivalents
 *
 * @param  array  $posts An array of WP_Posts. Defaults to current query.
 * @param  boolean|array $embed Whether or not links should be embedded.
 *                              An array allows fine grained control over which links to include.
 * @return array         Equivalent to a WP_REST_Posts_Controller endpoint.
 */
function get_posts_data( $posts = null, $embed = false ) {
	// Default to current query
	if ( null === $posts ) {
		$posts = $GLOBALS['wp_query']->posts;
	}

	$data = array();

	$request = new WP_REST_Request();
	$request['context'] = 'view';
	$edit_request = clone $request;
	$edit_request['context'] = 'edit';

	foreach ( $posts as $post ) {
		$data[] = get_post_data( $post, $embed );
	}

	return $data;
}

/**
 * Convert a post to REST API object.
 *
 * Converts a WP_Post objects to the WP-REST API equivalents
 *
 * @param  WP_Post       $posts A WP_Post object. Defaults to current global post.
 * @param  boolean|array $embed Whether or not links should be embedded.
 *                              An array allows fine grained control over which links to include.
 * @return array         Equivalent to single WP_REST_Posts_Controller item.
 */
function get_post_data( $post = null, $embed = false ) {
	// Default to current post
	if ( null === $post ) {
		$post = $GLOBALS['post'];
	}

	$request = new WP_REST_Request();
	$request['context'] = 'view';
	$edit_request = clone $request;
	$edit_request['context'] = 'edit';

	$controller = new WP_REST_Posts_Controller( $post->post_type );
	if ( current_user_can( 'edit_post', $post->ID ) ) {
		$response = $controller->prepare_item_for_response( $post, $edit_request );
	} else {
		$response = $controller->prepare_item_for_response( $post, $request );
	}
	$data = convert_data( $response, $embed );

	return $data;
}
