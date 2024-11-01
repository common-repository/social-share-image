<?php

/**
 * Main Class.
 *
 * @package Social_Share_Image
 */

/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Include is_plugin_active() if not exists.
 */
if ( ! function_exists( 'is_plugin_active' ) ) {
	include_once ABSPATH . 'wp-admin/includes/plugin.php';
}

// If class is exist, then don't execute this.
if ( ! class_exists( 'Social_Share_Image_Main' ) ) {

	/**
	 * Calls for admin methods.
	 */
	class Social_Share_Image_Main {

		/**
		 * Constructor for class.
		 */
		public function __construct() {

			if ( is_plugin_active( 'wordpress-seo/wp-seo.php' ) ) {
				// Assign Meta tags in for Yoast SEO.
				add_filter( 'wpseo_frontend_presenter_classes', array( $this, 'ssi_yoast_images' ) );
			}
			// Assign Meta tags in header.
			add_action( 'wp_head', array( $this, 'ssi_meta_image' ), 999 );

		}
		/**
		 * Assign Meta tags in for Yoast SEO.
		 */
		public function ssi_yoast_images( $classes ) {

			$ssi_featimg_override = ! empty( get_option( 'ssi_featImg_override' ) ) ? 'on' : '';
			if ( ! empty( $ssi_featimg_override ) ) {
				$classes = array_filter(
					$classes,
					function( $class ) {
						return strpos( $class, 'Image_Presenter' ) === false;
					}
				);
			}
			return $classes;
		}

		/**
		 * Assign Meta tags in header.
		 */
		public function ssi_meta_image() {

			$ssi_featimg_override = ! empty( get_option( 'ssi_featImg_override' ) ) ? 'on' : '';
			if ( is_singular() && ! empty( $ssi_featimg_override ) ) {
				$p_id      = get_the_ID();
				$ssi_image = get_post_meta( $p_id, 'ssi_image', true );
				if ( ! empty( $ssi_image ) ) {
					$post_image = get_site_url() . '/ssi-image/' . $p_id;
					$meta_tags  = array(
						'og:image'      => $post_image,
						'twitter:image' => $post_image,
					);

					foreach ( $meta_tags as $key => $value ) {
						if ( ! empty( $value ) ) {
							echo '<meta property="' . esc_attr( $key ) . '" content="' . esc_attr( $value ) . '">';
						}
					}
				}
			}
		}
	}
	new Social_Share_Image_Main();
}
