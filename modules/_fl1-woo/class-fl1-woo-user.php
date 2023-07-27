<?php
/**
 * FL1_Woo_User
 *
 * Class in charge of users
 * @extends WooCommerce WC_Customer
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Is WooCommerce installed?
if(class_exists('WC_Customer')) {

    /**
     * For WC methods:
     * 
     * @see https://woocommerce.github.io/code-reference/classes/WC-Customer.html
     */
    class FL1_Woo_User extends WC_Customer {

        /**
         * Returns the user full name
         */
        public function get_full_name() {

            return $this->get_first_name().' '.$this->get_last_name();

        }

		/**
         * Returns the author slug
         */
        public function get_author_slug() {

            return strtolower(str_replace(' ', '-', $this->get_full_name()));


        }

		/**
         * Checks if the user account has been verified
		 * 
		 * @return bool
         */
        public function is_account_verified() {

            return get_user_meta($this->ID, 'is_verified', true);

        }

        /**
         * Returns the customer's purchase history
         */
        public function get_purchase_history() {
             
            // Check if the user is valid 
            if ( 0 == $this->ID ) return;
             
            //Create $args array 
            $args = array(
                'posts_per_page' => -1,
                'meta_key' => '_customer_user',
                'meta_value' => $current_user->ID,
                'post_type' => wc_get_order_types(),
                'post_status' => array_keys( wc_get_is_paid_statuses() ),
            );
             
            // Pass the $args to get_posts() function 
            $customer_orders = get_posts( $args);
             
            // loop through the orders and return the IDs 
            if ( ! $customer_orders ) return;
            $product_ids = array();
            foreach ( $customer_orders as $customer_order ) {
                $order = wc_get_order( $customer_order->ID );
                $items = $order->get_items();
                foreach ( $items as $item ) {
                    $product_id = $item->get_product_id();
                    $product_ids[] = $product_id;
                }
            }
               
            return array_unique($product_ids);
              
        }

    }

}