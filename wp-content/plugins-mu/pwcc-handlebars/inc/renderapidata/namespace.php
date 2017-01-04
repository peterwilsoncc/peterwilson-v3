<?php
namespace PWCC\Handlebars\RenderAPIData;

use WP_REST_Request;
use WP_REST_Posts_Controller;

function convert_data( $data, $embed = true ) {
	$server = rest_get_server();
	$data = $server->response_to_data( $data, $embed );

	// Hackity hack hack: Ensures we're only dealing with arrays
	$data = json_decode( wp_json_encode( $data ), true );

	return $data;
}

function get_posts_data( $posts = null, $embed = true ) {
	// Default to current query
	if ( $posts === null ) {
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
	if ( $post === null ) {
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
