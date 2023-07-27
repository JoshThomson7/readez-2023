<?php
/**
 * TLC Public
 *
 * Class in charge of TLC Public facing side
 */

 // Exit if accessed directly`
if ( ! defined( 'ABSPATH' ) ) exit;

class FL1_Woo_Public {

    public function __construct() {

        add_filter('woocommerce_show_page_title' , '__return_false');
        add_filter('woocommerce_enqueue_styles', '__return_false');
        add_filter('woocommerce_price_trim_zeros', '__return_false');
        remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);

        add_action('fl1_enqueue_scripts_styles', array($this, 'enqueue'));
        add_action('body_class', array($this, 'body_classes'), 20);

        add_filter('page_template', array($this, 'pages'));
        add_filter('single_template', array($this, 'singles'));

        add_action('template_redirect', array($this, 'redirects'));

    }

    public function enqueue() {

        if(is_woocommerce() || is_cart() || is_checkout() || is_page(array('register', 'login', 'magic-link', get_option('woocommerce_myaccount_page_id')))) {
            wp_enqueue_style(FL1_WOO_SLUG, FL1_WOO_URL.'assets/fl1-woo.min.css');
        }

        if(is_singular('product')) {
            wp_enqueue_script('fl1-woo-single', FL1_WOO_URL.'assets/fl1-woo-single-product.min.js', array('jquery'), FL1_WOO_VERSION, true);
        }

        // Ajax
        wp_localize_script('custom-js', 'wc_ajax_object', array(
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'ajaxNonce' => wp_create_nonce('$C.cGLu/1zxq%.KH}PjIKK|2_7WDN`x[vdhtF5GS4|+6%$wvG)2xZgJcWv3H2K_M'),
            'jsPath' => FL1_WOO_URL.'/assets/',
        ));

        if ( function_exists( 'is_woocommerce' ) ) {
            if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() ) {
                # Styles
                wp_dequeue_style( 'woocommerce-general' );
                wp_dequeue_style( 'woocommerce-layout' );
                wp_dequeue_style( 'woocommerce-smallscreen' );
                wp_dequeue_style( 'woocommerce_frontend_styles' );
                wp_dequeue_style( 'woocommerce_fancybox_styles' );
                wp_dequeue_style( 'woocommerce_chosen_styles' );
                wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
    
                # Scripts
                wp_dequeue_script( 'wc_price_slider' );
                wp_dequeue_script( 'wc-single-product' );
                wp_dequeue_script( 'wc-add-to-cart' );
                wp_dequeue_script( 'wc-cart-fragments' );
                wp_dequeue_script( 'wc-checkout' );
                wp_dequeue_script( 'wc-add-to-cart-variation' );
                wp_dequeue_script( 'wc-single-product' );
                wp_dequeue_script( 'wc-cart' );
                wp_dequeue_script( 'wc-chosen' );
                wp_dequeue_script( 'woocommerce' );
                wp_dequeue_script( 'prettyPhoto' );
                wp_dequeue_script( 'prettyPhoto-init' );
                wp_dequeue_script( 'jquery-blockui' );
                wp_dequeue_script( 'jquery-placeholder' );
                wp_dequeue_script( 'fancybox' );
                wp_dequeue_script( 'jqueryui' );
            }
        }

    }

    /**
	 * Returns body CSS class names.
	 *
	 * @since 1.0
     * @param array $classes
	 */
    public function body_classes($classes) {
        return $classes;
    }

    /**
     * page_template filter function
     * 
     * @param string $template
     */
    public function pages($template) {
    
        // Login
        if(is_page(array('login', 'register', 'magic-link')) || is_wc_endpoint_url( 'lost-password' )) {

            $template = FL1_WOO_PATH . 'templates/login-register.php';
        
        } elseif(is_page(get_option('woocommerce_myaccount_page_id'))) {

            $template = FL1_WOO_PATH . 'templates/my-account.php';

            if(!is_user_logged_in()) {
                $template = FL1_WOO_PATH . 'templates/login-register.php';
            }

        } elseif(is_page('checkout')) {
            $template = FL1_WOO_PATH . 'templates/checkout.php';
        }

    
        return $template;
    
    }

    /**
     * single_template filter function
     * 
     * @param string $template
     */
    public function singles($template) {
    
        // global $post;

        // if($post->post_type === 'travel-guide') {
        //     $template = TLC_PATH . 'templates/travel-guides/single-travel-guide.php';
        // }

        return $template;
    
    }

    /**
     * WooCommerce redirects
     */
    public function redirects() {
    
        if(is_page('shop')) { 
            wp_redirect(esc_url(home_url()), 301);
            exit;
        }
    
    }

}

