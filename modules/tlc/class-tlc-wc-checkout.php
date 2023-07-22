<?php
/**
 * TLC WooCommerce Checkout
 *
 * Class in charge of WooCommerce's Checkout action and hook overrides
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class TLC_WC_Checkout {

    public function __construct() {

        add_action('woocommerce_thankyou', array($this, 'auto_complete_orders'), 10, 1 );
        add_action('woocommerce_order_status_changed', array($this, 'order_status_changed'), 20, 4);

		// Custom checkout fields
        add_filter('woocommerce_checkout_fields', array($this, 'checkout_fields'));
        add_action('woocommerce_checkout_update_order_meta', array($this, 'save_checkout_fields'));

		// Display custom fields
		add_filter('woocommerce_email_order_meta_fields', array($this, 'email_display_checkout_fields'));
        add_action('woocommerce_admin_order_data_after_billing_address', array($this, 'billing_display_checkout_fields'));
        
    }

    /**
     * Fires on the thank you page, shown directly after the
     * user has completed the WooCommerce checkout process.
     * 
     * @param int $order_id
     */
    public function auto_complete_orders($order_id) {
        
        if(!$order_id) {
            return false;
        }

        $order = wc_get_order($order_id);

        if(
            'bacs' == get_post_meta($order_id, '_payment_method', true) ||
            'cod' == get_post_meta($order_id, '_payment_method', true) ||
            'cheque' == get_post_meta($order_id, '_payment_method', true) || 
            $order->get_status() === 'processing'
        ) {
            $order->update_status('completed');
        } 

    }

    public function order_status_changed($order_id, $old_status, $new_status, $order) {

        $customer_id = $order->get_customer_id();

        if(!$customer_id) {
            return false;
        }

        if($new_status === 'completed') {

            foreach($order->get_items() as $item_id => $item) {

                $product_id = $item->get_product_id();
                $_product = new TLC_Product($product_id);

                if($_product->is_thinkific_product()) {

                    $customer = $this->maybe_create_thinkific_user($customer_id);
                    $enroll = $customer->thinkific_enroll_user($product_id);

                }

            }

			update_user_meta($customer_id, 'is_verified', true);

        }

    }

    /**
     * Creates a Thinkific user if one doesn't exist
     * @param int $customer_id
     */
    private function maybe_create_thinkific_user($customer_id) {

        $customer = new TLC_User($customer_id);

        if($customer->get_thinkific_user_id()) {
            return $customer;
        }

        $api = new TLC_Thinkific_API();
        $user_exists = $api->user_exists($customer->get_email());
        $thinkific_user_id = $user_exists;

        if(!$user_exists) {
            $user = $api->create_user($customer_id);

            if($user && $user->id) {
                $thinkific_user_id = $user->id;
            }
        }

        update_field('thinkific_user_id', $thinkific_user_id, 'user_' . $customer_id);

        return $customer;

    }

	/**
	 * Add custom checkout fields
	 * @param array $fields
	 */
    public function checkout_fields($fields) {

		unset($fields['billing']['billing_company']);
		unset($fields['billing']['billing_country']);

        $fields['billing']['billing_school'] = array(
            'label'       => __('School', 'text-domain'),
            'placeholder' => __('Enter your school', 'text-domain'),
            'required'    => false,
            'clear'       => false,
            'type'        => 'text',
            'class'       => array('form-row-wide'),
            'priority'    => 35,
        );

        return $fields;

    }

	/**
	 * Save custom checkout fields
	 * @param int $order_id
	 */
    public function save_checkout_fields($order_id) {
        if (!empty($_POST['billing_school'])) {
            $school = sanitize_text_field($_POST['billing_school']);
            update_post_meta($order_id, 'billing_school', $school);
        }
    }

	/**
	 * Display custom fields in emails
	 * @param array $fields
	 */
	public function email_display_checkout_fields($fields) {
        $fields['billing']['fields']['billing_school'] = 'School';
        return $fields;
    }

	/**
	 * Display custom fields in admin
	 * @param WC_Order $order
	 */
    public function billing_display_checkout_fields($order) {
        echo '<p><strong>School:</strong> ' . get_post_meta($order->get_id(), 'billing_school', true) . '</p>';
    }
    
}

