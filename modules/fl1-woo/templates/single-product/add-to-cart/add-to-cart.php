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
		<?php if($product->product_no_atc()): ?>
			<a href="<?php echo TLC_Helpers::order_form_url(); ?>" class="button primary">Download Order Form</a>
		<?php else: ?>
			<button type="submit" class="wc-add-to-cart-button button icon-right">Add to basket</button>
			<div class="wc-single-product--price"><?php echo $price_html; ?></div>
			<?php
				if($product_type === 'simple') { 
					require_once $product_type.'.php';
				}
			?>
		<?php endif; ?>
    </div><!-- wc-qty-add-to-cart -->

	<?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?>

    <div class="wc-continue">
        <a href="<?php echo wc_get_cart_url(); ?>" class="view-cart"><i class="fa fa-shopping-cart"></i> View cart</a>
        <a href="<?php echo wc_get_checkout_url(); ?>" class="go-to-checkout"><i class="fa fa-credit-card"></i> Checkout</a>
    </div><!-- wc-deal-continue -->

    <div class="wc-add-to-cart-notice"></div>
</form>
