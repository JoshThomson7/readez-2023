<?php
/**
 * FL1 WooCommerce Single Product
 *
 * Class in charge of WooCommerce's Single Product action and hook overrides
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class FL1_Woo_Single_Product {

    public function __construct() {

        remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
        remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
        remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
        remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
        remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );

        add_filter('woocommerce_single_product_summary', array($this, 'single_product_summary'));

        // add_filter('woocommerce_variable_sale_price_html', array($this, 'variations_price'), 10, 2);
        // add_filter('woocommerce_variable_price_html', array($this,'variations_price'), 10, 2);
        // add_filter('woocommerce_variable_subscription_price_html', array($this,'variations_price'), 10, 2);

    }

    public function single_product_summary() {
        require_once(FL1_WOO_PATH . 'templates/single-product/single-product.php');
    }
    
}

