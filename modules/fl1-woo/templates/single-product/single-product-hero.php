<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * WooCommerce Single Product (Custom)
 *
 * @package modules/woocommerce
 * @version 1.0
*/

global $product, $post;

$product = new TLC_Product($post->ID);

// main image
$prod_main_image = null;
if(get_post_thumbnail_id(get_the_ID())) {
    $prod_main_image_id = get_post_thumbnail_id();
    $prod_main_image = vt_resize($prod_main_image_id,'' , 800, 800, false);
    $prod_main_image = $prod_main_image['url'];

} else {
    $prod_main_image = get_stylesheet_directory_uri().'/img/product-holding.png';
}

$prod_attachment_ids = $product->get_gallery_image_ids();
$product_type = $product->get_type();
$price_html = $product->get_price_html();
$product_features = get_field('product_features', $post->ID) ?? array();

$chapters = $product->thinkific_chapters() ?? array();
?>

<section class="wc-single-product">
    <div class="max__width has-deps" data-deps='{"js":["fl1-woo-add-to-cart"]}' data-deps-path="wc_ajax_object">

        <div class="wc-single-product--nav">
            <a href="<?php echo esc_url(get_permalink(get_option( 'woocommerce_shop_page_id' ))); ?>">
                <i class="fa fa-chevron-left"></i>
                <span>Return to shop</span>
            </a>
        </div>

        <div class="wc-single-product--hero" data-title="<?php the_title(); ?>">

            <div class="wc-single-product--gallery">
                <div id="wc_product_gallery">
                    <?php
                        if(get_post_thumbnail_id(get_the_ID())) {
                            echo '<figure data-thumb="'.$prod_main_image.'"><img src="'.$prod_main_image.'" /></figure>';
                        }
                        
                        foreach($prod_attachment_ids as $prod_attachment_id):

                        $wc_product_image = vt_resize($prod_attachment_id,'' , 800, 800, false);
                    ?>
                        <figure data-thumb="<?php echo $wc_product_image['url']; ?>">
                            <img src="<?php echo $wc_product_image['url']; ?>" alt="">
                        </figure>
                    <?php endforeach; ?>
                </div>

				Similar products here
            </div>

            <aside class="wc-single-product--ad">
				
				<div class="wc-single-product--buy">
					<header>
						<h1><?php the_title(); ?></h1>
					</header>
					
					<div class="wc-single-product--add-to-cart">
						<?php require_once 'add-to-cart/add-to-cart.php'; ?>
					</div><!-- wc-single-product-add-to-cart -->
					
					<?php if(!empty($product_features)): ?>
						<div class="wc-single-product--features">
							<?php foreach($product_features as $product_feature): ?>
								<article>
									<strong><?php echo $product_feature['label']; ?></strong>
									<span><?php echo $product_feature['value']; ?></span>
								</article>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
				
				<div class="wc-single-product--content">
					<?php echo apply_filters('the_content', $product->get_short_description()); ?>
					<?php echo apply_filters('the_content', $product->get_description()); ?>
				</div>

                <!-- <div class="wc-single-product--message">
                    <h5>Prefer an invoice?</h5>
                    <p>If you prefer to be invoiced, please add the item to the basket, proceed to Checkout and select Pay on Account. <a href="#">Learn more</a>.</p>
                </div> -->

                <div class="wc-single-product--categories">
                    <h6>Categories</h6>
                    <article>
                        <?php
                            $categories = $product->get_category_ids();
							$years = wp_get_object_terms($post->ID, 'product_year', array('fields' => 'ids'));
							$categories = array_merge($categories, $years);
                            foreach($categories as $category):
                                $cat = get_term($category);
                                echo '<a href="'.get_term_link($cat->term_id).'">'.$cat->name.'</a>';
                            endforeach;
                        ?>
                    </article>
                </div>

            </aside>

            <div class="wc-single-float">
                <div class="wc-single-float-price"><?php echo '&pound;'.$price; ?></div>
                <div class="wc-single-float-button">
                    <a href="#wc_sidebar" class="scroll">Buying options</a>
                </div>
            </div>

        </div>

    </div><!-- max-width -->
</section>