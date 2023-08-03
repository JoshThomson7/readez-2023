<?php
/**
 * FL1_Product
 *
 * Extends the WooCommerce Product class
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if(class_exists('WC_Product')) {

    class FL1_Product extends WC_Product {

		/**
		 * Returns featured image.
		 * 
		 * @param int $width
		 * @param int $height
		 * @param bool $crop
		 * @see vt_resize() in modules/wp-image-resize.php
		 */
		public function image($width = 900, $height = 900, $crop = true) {

			$attachment_id = get_post_thumbnail_id($this->get_id());

			if($attachment_id) {
				return vt_resize($attachment_id, '', $width, $height, $crop);
			}

			return false;

		}
		
    }

}