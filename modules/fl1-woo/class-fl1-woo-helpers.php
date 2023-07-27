<?php
/**
 * FL1 WooCommerce Helpers
 *
 * Class in charge of FL1 WooCommerce Helpers
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class FL1_Woo_Helpers {

    public static function is_account_endpoint($endpoint) {

        if(self::get_my_account_endpoint() === $endpoint) {
            return true;
        }
    
        return false;
    }

    /**
     * Account menu items
     * @return array
     */
    public static function account_menu_items() {

        $items = array(
            'dashboard'         => __( 'Dashboard', 'woocommerce' ),
            'my-courses'        => __( 'Courses', 'woocommerce' ),
            'my-events'         => __( 'Events', 'woocommerce' ),
            'orders'            => __( 'Orders', 'woocommerce' ),
            'payment-methods'   => __( 'Payment methods', 'woocommerce' ),
            'edit-address'      => __( 'Addresses', 'woocommerce' ),
            'edit-account'      => __( 'Account', 'woocommerce' ),
            'customer-logout'   => __( 'Log out', 'woocommerce' ),
        );
    
        return $items;

    }

    /**
     * Account url
     * @return string
     */
    public static function get_my_account_url($endpoint = '') {

        return $endpoint ? wc_get_account_endpoint_url($endpoint) : get_permalink(get_option('woocommerce_myaccount_page_id'));

    }

    /**
     * Account menu items
     * @return string
     */
    public static function get_my_account_endpoint_title() {

        $endpoint = self::get_my_account_endpoint();
        $items = self::account_menu_items();

        if(array_key_exists($endpoint, $items)) {
            return $items[$endpoint];
        }

    }

    /**
     * Account endpoint
     * @return string
     */
    public static function get_my_account_endpoint() {

        global $wp_query;
        $query = $wp_query->query;

        $account_page = get_post(get_option('woocommerce_myaccount_page_id'));

        if(is_array($query) && count($query) == 2 && !isset($query['page']) && isset($query['pagename']) && $query['pagename'] === $account_page->post_name) {
            $endpoint = array_key_last($query);
            return $endpoint;
        } else { 
            return 'dashboard';
        }

    }

    /**
     * Account menu icon
     * @return string
     */
    public static function get_my_account_endpoint_icon($label) {

        switch ($label) {
            case 'Dashboard':
                $icon = 'fa-dashboard';
                break;

            case 'Courses':
                $icon = 'fa-chalkboard-user';
                break;

            case 'Events':
                $icon = 'fa-calendar-day';
                break;

            case 'Orders':
                $icon = 'fa-box';
                break;

            case 'Downloads':
                $icon = 'fa-folder-arrow-down';
                break;

            case 'Payment methods':
                $icon = 'fa-credit-card';
                break;

            case 'Address':
            case 'Addresses':
                $icon = 'fa-address-book';
                break;

            case 'Account':
            case 'Account details':
                $icon = 'fa-user';
                break;

            case 'Logout':
            case 'Log out':
                $icon = 'fa-arrow-right-from-bracket';
                break;

            default:
                $icon = '';
                break;
        }

        return $icon;

    }
    
}

