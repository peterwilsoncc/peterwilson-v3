<?php
namespace PWCC\Handlebars;

use LightnCandy\LightnCandy;

class Template_Part extends \Extended_Template_Part {

	/**
	 * Locate the template part file according to the slug and name.
	 *
	 * @return string The template part file name. Empty string if none is found.
	 */
	protected function locate_template() {

		if ( isset( $this->template ) ) {
			return $this->template;
		}

		$templates = [];

		if ( ! empty( $this->name ) ) {
			$templates[] = "{$this->args['dir']}/{$this->slug}-{$this->name}.hb.html";
		}

		$templates[] = "{$this->args['dir']}/{$this->slug}.hb.html";

		$this->template = locate_template( $templates );

		if ( 0 !== validate_file( $this->template ) ) {
			$this->template = '';
		}

		return $this->template;

	}


	/**
	 * Load the template part.
	 *
	 * @param  string $template_file The template part file path.
	 */
	protected function load_template( $template_file ) {
		global $posts, $post, $wp_did_header, $wp_query, $wp_rewrite, $wpdb, $wp_version, $wp, $id, $comment, $user_ID;
		$template = file_get_contents( $template_file );
		if ( empty( $template ) ) {
			return null;
		}

		// Check the cache
		// $compiled = wp_cache_get( $name, 'h2-handlebars' );
		$hash = sha1( $template );
		if ( empty( $compiled ) || $compiled['hash'] !== $hash ) {
			$options = get_handlebars_options();

			try {
				$compiled = LightnCandy::compile( $template, get_handlebars_options() );
			} catch ( Exception $e ) {
				var_dump( $e );
				return null;
			}

			$compiled = array(
				'hash' => $hash,
				'template' => $compiled,
			);

			// wp_cache_set( $name, $compiled, 'h2-handlebars' );
		}

		$renderer = LightnCandy::prepare( $compiled['template'], get_temp_dir() );

		$data = [
			'posts'         => RenderAPIData\get_posts_data( $posts, [
				'author'  => true,
				'terms'   => true,
				'replies' => false,
			] ),
			'post'          => RenderAPIData\get_post_data( $post,  [
				'author'  => true,
				'terms'   => true,
				'replies' => false,
			]  ),
			'wp_did_header' => $wp_did_header,
			'wp_query'      => $wp_query,
			'wp_rewrite'    => $wp_rewrite,
			'wpdb'          => $wpdb,
			'wp_version'    => $wp_version,
			'wp'            => $wp,
			'id'            => $id,
			'comment'       => $comment,
			'user_ID'       => $user_ID,
		];

		$data = json_decode( wp_json_encode( $data ), true );

		echo $renderer( $data ); // WPCS: XSS ok.
	}


	/**
	 * Get the output of the template part.
	 *
	 * @return string The template part output.
	 */
	public function get_output() {

		if ( false === $this->args['cache'] || ! $output = $this->get_cache() ) {

			ob_start();
			if ( $this->has_template() ) {
				$this->load_template( $this->locate_template() );
			}
			$output = ob_get_clean();

			if ( false !== $this->args['cache'] ) {
				$this->set_cache( $output );
			}
		}

		return $output;

	}

}
