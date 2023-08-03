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

        add_filter('woocommerce_variable_sale_price_html', array($this, 'variations_price'), 10, 2);
        add_filter('woocommerce_variable_price_html', array($this,'variations_price'), 10, 2);
        add_filter('woocommerce_variable_subscription_price_html', array($this,'variations_price'), 10, 2);

    }

    public function single_product_summary() {
        require_once(FL1_WOO_PATH . 'templates/single-product/single-product.php');
    }

    /**
     * variations_price()
     * 
     * Gets rid of 0.00 price variations and adds FROM
     * 
     * @param  string $price
     * @param  object $product
     * @return string
     */
    public function variations_price( $price, $product ) {
        
        if(!is_admin() || wp_doing_ajax()) {
            $prefix = sprintf('%s ', __('From', 'iconic'));

            $product_type = $product->get_type();
        
            $min_price_regular = $product->get_variation_regular_price( 'min', true );
            $min_price_sale    = $product->get_variation_sale_price( 'min', true );
            $max_price = $product->get_variation_price( 'max', true );
            $min_price = $product->get_variation_price( 'min', true );

            // Skip free variations
            if($min_price === '0.00' || $min_price === '0.00') { 
                $variations = $product->get_children();

                foreach($variations as $variation) { 
                    $variation_price = get_post_meta($variation, '_regular_price', true);

                    if($variation_price == 0) { continue; }
                    
                    $variation_prices[] = $variation_price;
                }
                
                $min_price_regular = min($variation_prices);
                $min_price_sale = min($variation_prices);

            }

            $per_month = '';
            if($product_type === 'variable-subscription') {
                $per_month = ' <small class="wc__price__frequency">per month</small>';
            }
        
            $price = ( $min_price_sale == $min_price_regular ) ? 
                wc_price( $min_price_regular ) . $per_month :
                '<del>' . wc_price( $min_price_regular ) . '</del>' . '<ins>' . wc_price( $min_price_sale ) . $per_month.'</ins>';

            $price = ( $min_price == $max_price ) ? $price : sprintf('%s%s', $prefix, $price);

        }
    
        return $price;
    
    }
    
}

