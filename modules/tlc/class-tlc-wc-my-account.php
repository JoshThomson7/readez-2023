<?php
/**
 * TLC WooCommerce My Account
 *
 * Class in charge of WooCommerce's My Account overrides
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class TLC_WC_My_Account {

    public function __construct() {

        add_filter('woocommerce_account_content', array($this, 'account_content'), 1);
		add_action('woocommerce_order_item_meta_end', array($this, 'order_item_meta_end'), 10, 4);
        
    }
    /**
	 * Change the account content
	 * 
	 * @return void
	 */
	public function account_content() {

		if(FL1_Woo_Helpers::is_account_endpoint('dashboard')) {
			remove_action('woocommerce_account_content', 'woocommerce_account_content');
			include TLC_PATH.'templates/portal/dashboard.php';

		} elseif (FL1_Woo_Helpers::is_account_endpoint('my-courses')) {
			remove_action('woocommerce_account_content', 'woocommerce_account_content');
			include TLC_PATH.'templates/portal/courses.php';

		} elseif (FL1_Woo_Helpers::is_account_endpoint('wishlist')) {
			remove_action('woocommerce_account_content', 'woocommerce_account_content');
			echo '<div class="wishlist-wrapper">';
			echo do_shortcode('[yith_wcwl_wishlist]');
			echo '</div>';

		} elseif (FL1_Woo_Helpers::is_account_endpoint('my-events')) {
			remove_action('woocommerce_account_content', 'woocommerce_account_content');
			include TLC_PATH.'templates/portal/events.php';
		}

	}

	public function order_item_meta_end($item_id, $item, $order, $plain_text) {
		
		$user_id = get_current_user_id();
		$user = new TLC_User($user_id);

		$product_id = $item->get_product_id();
		$_product = new TLC_Product($product_id);

		if($_product->is_thinkific_product()) {
			echo '<br /><br /><a href="'.$user->thinkific_sso_url($_product->thinkific_take_url()).'" target="_blank" class="button primary small" style="display: inline-flex; color: #fff !important;">Go to course</a></small>';
		}

	}
    
}

