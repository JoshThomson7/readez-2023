<?php
/**
 * TLC_Product
 *
 * Extends the WooCommerce Product class
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if(class_exists('WC_Product')) {

    class TLC_Product extends WC_Product {

        /**
         * Returns related products
         * @return bool
         */
        public function related_products() {
            
            return get_field('product_related', $this->get_id()) ?? array();
            
        }

        /**
         * Returns whether the related products are a carousel
         * @return bool
         */
        public function product_related_is_carousel() {
            
            return get_field('product_related_is_carousel', $this->get_id()) ?? array();
            
        }

        /**
         * Returns whether the product is a Thinkific product
         * @return bool
         */
        public function is_thinkific_product() {
            
            return $this->thinkific_course_id() ? true : false;
            
        }

        /**
         * Returns the Thinkific Course ID
         * @return int
         */
        public function thinkific_course_id() {
            
            return $this->get_meta('thinkific_course_id');
            
        }

        /**
         * Returns the Thinkific Product ID
         * @return int
         */
        public function thinkific_product_id() {
            
            return $this->get_meta('thinkific_product_id');
            
        }

        /**
         * Returns whether the product is private
         * @return bool
         */
        public function thinkific_is_private() {
            
            return $this->get_meta('thinkific_is_private');
            
        }

        /**
         * Returns the Thinkific enrollment duration in days
         * @return int
         */
        public function thinkific_enrollment_duration() {
            
            return $this->get_meta('thinkific_course_enrollment_duration');
            
        }

        /**
         * Returns the Thinkific chapters as an array
         * @return array
         */
        public function thinkific_chapters() {
            
            $chapters = $this->get_meta('thinkific_chapters') ?? array();
            return $chapters ? json_decode($chapters, true) : array();
            
        }

        /**
         * Returns the Thinkific course-taking URL
         * @return string
         */
        public function thinkific_take_url() {
            
            return TLC_Helpers::thinkific_base_url().'/courses/take/'.$this->get_slug().'/';
            
        }

		/**
         * Returns the Thinkific course-enrollment URL
         * @return string
         */
		public function thinkific_free_enroll_course_url() {

			return TLC_Helpers::thinkific_base_url().'/enroll/'.$this->thinkific_product_id().'?et=free_trial';

		}

		/**
         * Disables the Add to Cart button
         * @return string
         */
		public function product_no_atc() {

			return get_field('product_no_atc', $this->get_id());

		}
		
		/**
		 * Returns the Thinkific categories
		 * @return array
		 */
		public function thinkific_categories() {

			return get_the_terms($this->get_id(), 'product_thinkific_cat');

		}
    }

}