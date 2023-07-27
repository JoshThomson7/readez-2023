<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * WooCommerce Add to Cart (Standard)
 *
 * @package modules/woocommerce
 * @version 1.0
*/

global $woocommerce, $product;

$product_type = $product->get_type();
$price = $product->get_regular_price();
?>
<form class="wc-add-to-cart--form" data-wc-product-type="<?php echo $product_type; ?>">
    <?php 
        if($product_type !== 'simple') { 
            require_once $product_type.'.php';
        }
    ?>

    <div class="wc-add-to-cart--button">
        <a href="#" class="wc-add-to-cart-button button" target="_blank">GET A QUOTE</a>
    </div><!-- wc-qty-add-to-cart -->
</form>
