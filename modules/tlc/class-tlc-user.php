<?php
/**
 * TLC User
 *
 * Class in charge of TLC  users
 * 
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Is WooCommerce installed?
if(class_exists('WC_Customer')) {

    /**
     * For WC methods:
     * 
     * @see https://docs.woocommerce.com/wc-apidocs/class-WC_Customer.html
     */
    class TLC_User extends WC_Customer {

        /**
         * Returns the user full name
         */
        public function get_full_name() {

            return $this->get_first_name().' '.$this->get_last_name();

        }

        /**
         * Returns the user initials
         */
        public function get_initials() {

            return substr($this->get_first_name(), 0, 1).substr($this->get_last_name(), 0, 1);


        }

        /**
         * Returns the Thinkific User ID
         */
        public function get_thinkific_user_id() {

            return (int)get_user_meta($this->get_id(), 'thinkific_user_id', true) ?? 0;

        }

        /**
         * Returns the user school
         */
        public function get_school() {

            return get_field('user_school', 'user_'.$this->get_id());

        }

        /**
         * Returns the user position
         */
        public function get_position() {

            return get_field('user_position', 'user_'.$this->get_id());

        }

        /**
         * Returns customer orders
         * 
         * @param array $custom_args
         * @return array
         */
        public function get_orders($custom_args = array()) {

            $default_args = array(
                'post_type'   => wc_get_order_types(),
                'post_status' => array_keys( wc_get_order_statuses() ),
                'posts_per_page' => -1,
                'meta_key'    => '_customer_user',
                'meta_value'  => $this->get_id(),
                'orderby'     => 'date',
                'order'       => 'desc',
                'fields'      => 'ids'
            );

            $args = wp_parse_args($custom_args, $default_args);
            $customer_orders = new WP_Query($args);

            return $customer_orders->posts;

        }


        /** -----------------------------------------------------------
         * Thinkific
         *-----------------------------------------------------------*/

        /**
         * Returns user Thinkific enrollments
         * @return array
         */
        public function thinkific_enrollments($custom_args = array()) {

            $api = new TLC_Thinkific_API();
            $api->set_method('GET');

			$default_args = array(
                'limit' => 1000,
                'query' => array(
                    'user_id' => $this->get_thinkific_user_id(),
                )
			);

			$args = wp_parse_args($custom_args, $default_args);
            $api->set_params($args);

            return $api->crud('enrollments');

        }

		/**
         * Check if user is enrolled in a course
         * @return obj
         */
        public function thinkific_course_enrollment_status($course_id) {

			$enrolled = new stdClass();
			$enrolled->is_enrolled = false;
			$enrolled->is_expired = false;
			$enrolled->is_free_trial = false;

            $get_enrollement = $this->thinkific_enrollments(array(
				'limit' => 1,
				'query' => array(
					'user_id' => $this->get_thinkific_user_id(),
					'course_id' => $course_id,
				)
			));

			if(isset($get_enrollement->items) && !empty($get_enrollement->items)) {

				$enrollement = reset($get_enrollement->items);

				if(isset($enrollement->id) && is_numeric($enrollement->id)) {

					$enrolled->is_enrolled = true;

					if($enrollement->expired) {
						$enrolled->is_enrolled = false;
						$enrolled->is_expired = true;
					}

					if($enrollement->activated_at == '') {
						$enrolled->is_enrolled = false;

						if($enrollement->is_free_trial) {
							$enrolled->is_free_trial = true;
						}
					}

				}
			}

			return $enrolled;

        }

        public function thinkific_enroll_user($product_id) {

            $date = new DateTime('now', wp_timezone());

            $product = new TLC_Product($product_id);
            $thinkific_course_id = $product->thinkific_course_id();

            if(!$thinkific_course_id) return false;

            $enrollment_duration = $product->thinkific_enrollment_duration();

            $body = array(
                'course_id' => (int)$thinkific_course_id,
                'user_id' => $this->get_thinkific_user_id(),
                'activated_at' => $date->format('Y-m-d\TH:i:s\Z'),
            );

            if($enrollment_duration && $enrollment_duration > 0) {
                $expiry_date = new DateTime('now', wp_timezone());
                $expiry_date->modify('+'.$enrollment_duration.' days');
                $body['expiry_date'] = $expiry_date->format('Y-m-d\TH:i:s\Z');
            }

            $api = new TLC_Thinkific_API();
            $api->set_method('POST'); 
            $api->set_params(array());
            $api->set_body($body);
            
            $enroll = $api->crud('enrollments');

            return $enroll;
            
        }

        public function thinkific_sso_url($goto) {

			$wp_nonce = wp_create_nonce('tlc_thinkific_sso');

			return add_query_arg(array(
				'goto' => $goto,
				'user_id' => $this->get_id(),
				'_wpnonce' => $wp_nonce
			), home_url('/'));

			return $goto;

        }

        /**
         * Rest API Data output
         * 
         * @return object $data
         */
        public function rest_api_data() {

            $data = new stdClass();

            $data->ID = $this->get_id();
            $data->username = $this->get_username();
            $data->email = $this->get_email();
            $data->fullName = $this->get_full_name();
            $data->firstName = $this->get_first_name();
            $data->lastName = $this->get_last_name();
            $data->dateRegistered = $this->get_date_created();

            return $data;

        }

    }

}