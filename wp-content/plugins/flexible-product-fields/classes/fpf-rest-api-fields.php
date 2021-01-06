<?php
/**
 * REST API for fields.
 *
 * @package Flexible Product Fields
 */

use VendorFPF\WPDesk\PluginBuilder\Plugin\Hookable;

/**
 * Can handle REST API methods for fields.
 */
class FPF_REST_Api_Fields implements Hookable {

	const NONCE_NAME = 'fpf-fields';

	/**
	 * Hooks.
	 */
	public function hooks() {
		add_action( 'rest_api_init', array( $this, 'register_fpf_fields_route' ) );
	}

	/**
	 * Registers REST API route.
	 *
	 * @internal
	 */
	public function register_fpf_fields_route() {
		register_rest_route(
			'flexible_product_fields/v1',
			'/fields/(?P<id>\d+)',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'handle_rest_api_fields' ),
				'permission_callback' => function() {
					return current_user_can( 'manage_options' );
				},
			)
		);
	}

	/**
	 * @param array $fields .
	 *
	 * @return array
	 */
	private function trim_options_values_on_fields( array $fields ) {
		foreach ( $fields as $field_id => $field ) {
			if ( isset( $field['options'] ) ) {
				foreach ( $field['options'] as $option_id => $option ) {
					$field['options'][ $option_id ]['value'] = trim( $option['value'] );
				}
				$fields[ $field_id ] = $field;
			}
		}
		return $fields;
	}

	/**
	 * @param array $json .
	 *
	 * @return WP_REST_Response .
	 */
	private function process_post_data( array $json ) {
		$post_id = $json['post_id']['value'];
		$post    = get_post( $post_id );
		if ( ! $post ) {
			return new WP_REST_Response(null, 404);
		}

		$assign_to = $json['assign_to']['value'];
		update_post_meta( $post_id, '_assign_to', $assign_to );
		update_post_meta( $post_id, '_section', $json['section']['value'] );
		update_post_meta( $post_id, '_fields', $this->trim_options_values_on_fields( $json['fields'] ) );
		if ( 'product' === $assign_to ) {
			$products = isset( $json['products'], $json['products']['value'] ) && is_array( $json['products']['value'] ) ? $json['products']['value'] : array();
			update_post_meta( $post_id, '_products', $products );
			delete_post_meta( $post_id, '_product_id' );
			foreach ( $products as $product ) {
				add_post_meta( $post_id, '_product_id', $product['value'] );
			}
		} else {
			delete_post_meta( $post_id, '_product_id' );
		}
		if ( 'category' === $assign_to ) {
			$categories = isset( $json['categories'], $json['categories']['value'] ) && is_array( $json['categories']['value'] ) ? $json['categories']['value'] : array();
			update_post_meta( $post_id, '_categories', $categories );
			delete_post_meta( $post_id, '_category_id' );
			foreach ( $categories as $category ) {
				add_post_meta( $post_id, '_category_id', $category['value'] );
			}
		} else {
			delete_post_meta( $post_id, '_category_id' );
		}
		if ( 'tag' === $assign_to ) {
			$tags = isset( $json['tags'], $json['tags']['value'] ) && is_array( $json['tags']['value'] ) ? $json['tags']['value'] : array();
			update_post_meta( $post_id, '_tags', $tags );
			delete_post_meta( $post_id, '_tag_id' );
			foreach ( $tags as $tag ) {
				add_post_meta( $post_id, '_tag_id', $tag['value'] );
			}
		} else {
			delete_post_meta( $post_id, '_tag_id' );
		}

		return new WP_REST_Response(null, 200);
	}

	/**
	 * @param WP_REST_Request $request .
	 *
	 * @return WP_REST_Response .
	 */
	public function handle_rest_api_fields( WP_REST_Request $request ) {
		wp_cache_flush();
		$json = $request->get_json_params();
		return $this->process_post_data( $json );
	}

}
