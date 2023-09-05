<?php
/**
 * WooCommerce Simple Product
 *
 * @package modules/woocommerce
 * @version 1.0
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $woocommerce;
?>

<div class="wc-simple">
    <div class="wc-simple-qty">
        <input type="number" name="wc_simple" value="1" data-product-id="<?php echo $post->ID; ?>" min="1">
    </div><!-- wc-simple-qty -->

    <div class="simple-price">
        <?php echo $price_html; ?>
    </div>

</div><!-- wc-variations -->
