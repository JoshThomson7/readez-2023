<?php
/**
 * FL1 WooCommerce Account
 *
 * Class in charge of WooCommerce's Account action and hook overrides
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class FL1_Woo_Account {

    public function __construct() {

        $this->endpoints();
        add_filter('woocommerce_account_menu_items', 'FL1_Woo_Helpers::account_menu_items');
        add_filter('woocommerce_account_menu_item_classes', array($this, 'account_menu_item_classes'), 10, 2);

    }

    private function endpoints() {

        add_rewrite_endpoint('my-courses', EP_ROOT | EP_PAGES);
        add_rewrite_endpoint('wishlist', EP_ROOT | EP_PAGES);
        add_rewrite_endpoint('my-events', EP_ROOT | EP_PAGES);

    }

    public function account_menu_item_classes($classes, $endpoint) {

        $separators = array('orders', 'customer-logout');

        if(in_array($endpoint, $separators)) {
            $classes[] = 'sep';
        }

        return $classes;

    }

    
}

