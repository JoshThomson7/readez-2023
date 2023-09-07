<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * WooCommerce Single Product (Custom)
 *
 * @package modules/woocommerce
 * @version 1.0
*/

global $product, $post;

// main image
$prod_main_image = null;
if(get_post_thumbnail_id(get_the_ID())) {
    $prod_main_image_id = get_post_thumbnail_id();
    $prod_main_image = vt_resize($prod_main_image_id,'' , 800, 800, false);
    $prod_main_image = $prod_main_image['url'];

} else {
    $prod_main_image = get_stylesheet_directory_uri().'/img/product-holding.png';
}

$product_imgs = get_field('product_images', $post->ID) ?? array();
$product_type = $product->get_type();
$price_html = $product->get_price_html();
?>

<section class="wc-single-product">
    <div class="max__width has-deps" data-deps='{"js":["fl1-woo-add-to-cart"]}' data-deps-path="wc_ajax_object">

        <div class="wc-single-product--hero" data-title="<?php the_title(); ?>">

            <div class="wc-single-product--gallery">
                <div id="wc_product_gallery">
                    <?php                        
                        foreach($product_imgs as $product_img):
						$product_img_id = $product_img['image'];
                        $wc_product_image = vt_resize($product_img_id,'' , 800, 800, false);
                    ?>
                        <figure data-thumb="<?php echo $wc_product_image['url']; ?>">
                            <img src="<?php echo $wc_product_image['url']; ?>" alt="">
                        </figure>
                    <?php endforeach; ?>
                </div>
            </div>

            <aside class="wc-single-product--ad">
				
				<div class="wc-single-product--buy">
					<header>
						<h1><?php the_title(); ?></h1>
					</header>

                    <div class="wc-single-product--content">
					    <?php echo apply_filters('the_content', $product->get_short_description()); ?>
					    <?php echo apply_filters('the_content', $product->get_description()); ?>
				    </div>

                    <div class="wc-single-product--add-to-cart">
						<?php require_once 'add-to-cart/add-to-cart.php'; ?>
					</div><!-- wc-single-product-add-to-cart -->
					
					
				</div>
            </aside>

            <div class="wc-single-float">
                <div class="wc-single-float-price"><?php echo '&pound;'.$price; ?></div>
                <div class="wc-single-float-button">
                    <a href="#wc_sidebar" class="scroll">Buying options</a>
                </div>
            </div>

        </div>

		<?php FC_Helpers::flexible_content(); ?>

    </div><!-- max-width -->
</section>