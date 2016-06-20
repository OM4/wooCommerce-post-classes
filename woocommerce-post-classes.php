<?php
/*
Plugin Name: WooCommerce Post Classes
Plugin URI: https://github.com/OM4/woocommerce-post-classes
Description: Adds additional information (such as shipping class) to the WooCommerce product display.
Version: 0.2
Author: OM4
Author URI: https://om4.com.au/plugins/
Text Domain: woocommerce-post-classes
Git URI: https://github.com/OM4/woocommerce-post-classes
Git Branch: release
License: GPLv2
*/

/*
Copyright 2014-2016 OM4 (email: plugins@om4.com.au    web: https://om4.com.au/plugins/)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! class_exists( 'WC_Post_Classes' ) ) {

	/**
	 * This class is a singleton.
	 *
	 * Class WC_Post_Classes
	 */
	class WC_Post_Classes {

		/**
		 * Refers to a single instance of this class
		 */
		private static $instance = null;

		/**
		 * Creates or returns an instance of this class
		 * @return WC_Post_Classes A single instance of this class
		 */
		public static function instance() {
			if ( null == self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;

		}

		/**
		 * Constructor
		 */
		private function __construct() {
			add_filter( 'post_class', array( $this, 'post_class' ), 30, 3 );
		}

		/**
		 * Include additional classes to the product.
		 *
		 * Executed by the post_class filter.
		 */
		public function post_class( $classes, $class = '', $post_id = '' ) {

			if ( ! $post_id || get_post_type( $post_id ) !== 'product' )
				return $classes;

			$product = wc_get_product( $post_id );

			if ( $product ) {

				// Add shipping class if one is set
				$shipping_class = $product->get_shipping_class();
				if ( ! empty( $shipping_class ) ) {
					$classes[] = 'shipping-class-' . sanitize_html_class( $shipping_class );
					$classes[] = 'shipping-class-id-' . sanitize_html_class( $product->get_shipping_class_id() );
				}


			}

			return $classes;

		}

	}

	WC_Post_Classes::instance();

}