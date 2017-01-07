<?php
namespace PWCC\Handlebars\RenderAPIData;

use WP_REST_Request;
use WP_REST_Posts_Controller;

function convert_data( $data, $args = [] ) {
	$defaults = [
		'author'   => false,
		'replies'  => false,
		'wp:term' => false,
	];

	if ( is_bool( $embed ) ) {
		foreach ( $defaults as $key => $value ) {
			$defaults[ $key ] = $embed;
		}
	}

	$server = rest_get_server();
	$data = $server->response_to_data( $data, false );

	if ( true === $embed ) {
		// Hackity hack hack: Ensures we're only dealing with arrays
		$data = json_decode( wp_json_encode( $data ), true );

		return $data;
	}

	if ( isset( $args['terms'] ) && ! isset( $args['wp:term'] ) ) {
		$args['wp:term'] = $args['terms'];
		unset( $args['terms'] );
	}

	$args = wp_parse_args( $args, $defaults );

	$links = $data['_links'];
	// Unset self because it's dumb to embed.
	// Unset terms because we handle those below
	unset( $links['self'] );

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

function get_posts_data( $posts = null, $embed = true ) {
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

function get_post_data( $post = null, $embed = true ) {
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
