<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly
/**
 * WooCommerce Single Product (Custom)
 *
 * @package modules/woocommerce
 * @version 1.0
 */

global $product, $post;

$product = new TLC_Product($post->ID);
?>

<div class="wc-single-product--content fc-free-text">
	<?php echo apply_filters('the_content', $product->get_short_description()); ?>
	<?php echo apply_filters('the_content', $product->get_description()); ?>
	<?php
		if($product->is_thinkific_product()) {
			include 'single-product-thinkific.php';
		}
	?>
</div>