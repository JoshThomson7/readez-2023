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

        //add_filter('woocommerce_account_content', array($this, 'account_content'));

    }

    private function is_wc_dashboard() {
        global $wp;
    
        $is_dashboard = false;
    
        if(is_account_page() && !is_wc_endpoint_url() && $wp->request === 'dashboard') {
            $is_dashboard = true;
        }
    
        return $is_dashboard;
    }

    /**
     * Change the account content
     * 
     * @return void
     */
    public function account_content() {
        global $wp;
    
        if($this->is_wc_dashboard()) {
            remove_action( 'woocommerce_account_content', 'woocommerce_account_content' );
        ?>
    
        Dashboard
    
        <?php
    
        } else {
            if($wp->request === 'my-physio/insurance') {
                remove_action( 'woocommerce_account_content', 'woocommerce_account_content' );
                include APM_PATH . 'templates/patient/insurance.php';
            }
        }
    }

    private function endpoints() {

        add_rewrite_endpoint('my-courses', EP_ROOT | EP_PAGES);
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

