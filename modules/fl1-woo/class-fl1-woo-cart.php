<?php
/**
 * FL1 WooCommerce Cart
 *
 * Class in charge of WooCommerce's Cart
 * action and hook overrides
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class FL1_Woo_Cart {

    public function __construct() {

        add_filter('woocommerce_persistent_cart_enabled', '__return_false');
        add_filter('woocommerce_order_item_permalink', '__return_false');
        add_filter('get_user_metadata', array($this, 'remove_persistent_cart'), 10, 3); 
        add_filter('update_user_metadata', array($this, 'remove_persistent_cart'), 10, 3); 
        add_filter('add_user_metadata', array($this, 'remove_persistent_cart'), 10, 3);
        add_filter('woocommerce_cart_item_name', array($this, 'remove_variation_from_product_title'), 10, 3);
        add_filter('woocommerce_return_to_shop_redirect', array($this, 'empty_cart_redirect_url'));

        add_action('woocommerce_before_cart', array($this, 'before_cart'));
        add_action('woocommerce_after_cart', array($this, 'after_cart'));
        add_action('woocommerce_cart_is_empty', array($this, 'cart_is_empty'), 10);
        add_action('woocommerce_before_calculate_totals', array($this, 'before_calculate_totals'), 10 ); // Custom calculations
        add_action('woocommerce_after_cart_totals', array($this, 'cards_image'));
        add_action('woocommerce_review_order_after_submit', array($this, 'cards_image'));
        add_action('woocommerce_proceed_to_checkout', array($this, 'continue_shopping'));

        add_action('wp_ajax_wc_ajax_add_to_cart', array($this, 'ajax_add_to_cart'));
        add_action('wp_ajax_nopriv_wc_ajax_add_to_cart', array($this, 'ajax_add_to_cart'));

        //add_action('woocommerce_after_cart', array($this, 'but_whats_in_the_cart')); // Dump cart data
        
    }    

    /**
     * Little helper hook to clear WC cart
     */
    public function wc_clear_cart_url() {
        
        if(isset($_GET['clear-cart'])) { 
            WC()->cart->empty_cart();
        }

    }

    /**
     * Before calculate totals hook
     */
    public function before_calculate_totals() {

        if(is_admin() && ! defined( 'DOING_AJAX' ) ) { return; }
        if(!is_user_logged_in()) { return false; }
        
    }

    /**
     * Custom empty cart message
     */
    public function cart_is_empty() {
        $html = '<div class="wc__empty__basket">';
            $html .= '<div class="message"><figure><i class="fas fa-shopping-basket"></i></figure>';
                $html .= '<p>'.wp_kses_post( apply_filters( 'wc_empty_cart_message', __( 'Your basket is currently empty. Why not check out our products?', 'woocommerce' ) ) ).'</p>';
            $html .= '</div>';
            $html .= '<div class="wc__empty__basket--buttons">';
                $html .= '<a href="'.esc_url(home_url()).'/products'.'" class="button primary small icon-left"><i class="fa fa-chevron-left"></i><span>View Products</span></a>';
            $html .= '</div>';
        $html .= '</div>';

        echo $html;

        /*$cart_empty_args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => 4,
            'cache_results' => false,
            'orderby' => 'rand',
            'product_mode' => 'shop',
            'no_found_rows' => true
        );
        
        $cart_empty_query = new WP_Query($cart_empty_args);

        ?>

        <div class="wc__content">
            <div class="wc__products wc__products__grid">
                <?php
                    while($cart_empty_query->have_posts()) : $cart_empty_query->the_post();

                    if(get_post_thumbnail_id(get_the_ID())) {
                        $attachment_id = get_post_thumbnail_id(get_the_ID());
                        $prod_image = vt_resize($attachment_id,'' , 700, 700, true);

                    } else {
                        $prod_image = ' style="background-image:url('.get_stylesheet_directory_uri().'/img/product-holding.png;);"';
                    }

                    // loop
                    include(FL1_WOO_PATH.'templates/woo-loop-grid.php');

                    endwhile; woocommerce_reset_loop(); wp_reset_postdata();
                ?>

                <?php pagination($cart_empty_query->max_num_pages); ?>
            </div><!-- wc__products -->
        </div>

        <?php*/

    }

    public function before_cart() {
        echo '<div class="wc-cart-wrap">';
    }
    
    public function after_cart() {
        echo '</div>';
    }

    /**
     * Disable the persistent cart
     */
    public function remove_persistent_cart($value, $id, $key) { 
        if ($key == '_woocommerce_persistent_cart') { 
            return false; 
        } 
        return $value; 
    }

    public function cards_image() {
        echo '<div class="wc__checkout__cards"><img src="'.FL1_WOO_URL.'/img/cards.png"></div>';
    }

    public function continue_shopping() {
        echo '<div class="wc__continue__shopping"><a href="'.esc_url(home_url()).'/products'.'">Continue shopping</a></div>';
    }

    /**
     * Removes the attribute from the product title, in the cart.
     * 
     * @return string
     */
    function remove_variation_from_product_title( $title, $cart_item, $cart_item_key ) {
        $_product = $cart_item['data'];
        $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
        
        if ( $_product->is_type( 'variation' ) ) {
            if ( ! $product_permalink ) {
                return sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_title());
            } else {
                return sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_title());
            }
        }

        return $title;
    }

    /**
     * Changes the redirect URL for the Return To Shop button in the cart.
     *
     * @return string
     */
    public function empty_cart_redirect_url() {
        return esc_url(home_url());
    }

    /**
     * Little helper hook to see what's in the cart
     */
    public function but_whats_in_the_cart() {

        if(function_exists('pretty_print') && get_current_user_id() == 3457) {
            //pretty_print(WC()->cart->get_cart());
            pretty_print(WC()->session);
        }

    }

    /**
     * wc_ajax_add_to_cart()
     * 
     * Custom AJAX add to cart
     */
    public function ajax_add_to_cart() {

        // security check
        wp_verify_nonce('$C.cGLu/1zxq%.KH}PjIKK|2_7WDN`x[vdhtF5GS4|+6%$wvG)2xZgJcWv3H2K_M', 'wc_security');

        $product_type = isset($_POST['wc_product_type']) && !empty($_POST['wc_product_type']) ? $_POST['wc_product_type'] : null;
        $cart_data = isset($_POST['wc_cart_data']) && !empty($_POST['wc_cart_data']) ? $_POST['wc_cart_data'] : null;

        $json_response = array(
            'status' => 'error',
            'message' => 'Item could not be added to the cart.',
            'redirect_url' => null
        );

        if($cart_data) {

            foreach($cart_data as $key => $value) {

                // get product data
                $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($value['product_id']));
                $quantity = empty($value['quantity']) ? 1 : wc_stock_amount($value['quantity']);
                $variation_id = null;
                $product_status = get_post_status($product_id);

                // filter validation rules
                $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);

                // is it a gift?
                $cart_item_data = array();

                // all good?
                if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $cart_item_data) && 'publish' === $product_status) {

                    // add to cart
                    do_action('woocommerce_ajax_added_to_cart', $product_id);

                    WC()->cart->calculate_totals();
                    WC()->cart->set_session();
                    WC()->cart->maybe_set_cart_cookies();

                    $json_response = array(
                        'status' => 'success',
                        'message' => 'Item added to the cart.',
                        'redirect_url' => WC()->cart->get_cart_url()
                    );

                }

            }

        }

        wp_send_json($json_response);
        wp_die();

    }

}