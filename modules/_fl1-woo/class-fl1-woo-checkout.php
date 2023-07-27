<?php
/**
 * FL1 WooCommerce Checkout
 *
 * Class in charge of WooCommerce's Checkout action and hook overrides
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class FL1_Woo_Checkout {

    public function __construct() {

        remove_action('woocommerce_order_details_after_order_table', 'woocommerce_order_again_button');
        add_filter('woocommerce_thankyou_order_received_text', array($this, 'order_received_text'), 10, 2 );
		add_filter('woocommerce_checkout_fields', array($this, 'checkout_fields'));
		add_action('woocommerce_before_checkout_registration_form', array($this, 'before_checkout_registration_form'));
		add_filter('woocommerce_continue_shopping_redirect', array($this, 'continue_shopping_url'));

        
    }

    /**
     * Change the order received text
     * 
     * @param string $text
     * @param WC_Order $order
     */
    public function order_received_text( $text, $order ) {

        if ( isset ( $order ) ) {
            $text = sprintf( "Thank you, %s. Your order has been received.", esc_html( $order->get_billing_first_name() ) );
        }

        return $text;
    }

	/**
	 * Add custom checkout fields
	 * @param array $fields
	 */
    public function checkout_fields($fields) {

		$fields['account']['account_username']['label'] = __( 'Email', 'woocommerce' );
		$fields['account']['account_username']['placeholder'] = __( 'Email', 'woocommerce' );
		$fields['account']['account_password']['label'] = __( 'Password', 'woocommerce' );
		$fields['account']['account_password']['placeholder'] = __( 'Password', 'woocommerce' );

        return $fields;

    }

	/**
	 * Display custom message before checkout registration form
	 * @param WC_Checkout $checkout
	 */
	public function before_checkout_registration_form($checkout) {
		
		echo '<p>Access your order history, online courses and materials by creating an account. If you already have an account, please log in at the top of this page.</p>';

	}

	public function continue_shopping_url($url) {
		
		return get_permalink(get_page_by_path('resources'));

	}

}

