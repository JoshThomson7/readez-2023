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

}

