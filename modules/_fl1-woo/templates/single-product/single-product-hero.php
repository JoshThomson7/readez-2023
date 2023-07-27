<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * WooCommerce Single Product (Custom)
 *
 * @package modules/woocommerce
 * @version 1.0
*/

global $product, $post;

$user_id = get_current_user_id();
$user = new TLC_User($user_id);

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
            </div>

            <aside class="wc-single-product--ad">
				
				<div class="wc-single-product--buy">
					<header>
						<h1><?php the_title(); ?></h1>
					</header>
					
					<div class="wc-single-product--add-to-cart">
						<?php
							if($product->is_thinkific_product()) {
								$enrollment = $user->thinkific_course_enrollment_status($product->thinkific_course_id());
								
								if($enrollment->is_enrolled) {
									FL1_Woo_Helpers::product_message('Your enrollment', 'You are already enrolled in this course.');
									echo '<a href="'.$user->thinkific_sso_url($product->thinkific_take_url()).'" class="button primary" style="display: inline-flex; margin-top: var(--fl1-spacing-sm);" target="_blank">Go to course</a>';
								} else {
									if($enrollment->is_expired) {
										FL1_Woo_Helpers::product_message('Your enrollment', 'Your enrollment expired. You need to purchase the course to get full access.');
									}
									if($enrollment->is_free_trial) {
										FL1_Woo_Helpers::product_message('Your enrollment', 'You are enrolled in the Free Preview. You need to purchase the course to get full access.');
									}
									require_once 'add-to-cart/add-to-cart.php';
								}
							} else {
								require_once 'add-to-cart/add-to-cart.php';
							}
						?>
					</div><!-- wc-single-product-add-to-cart -->

					<div class="wc-single-product--categories">
						<article>
							<?php
								$categories = $product->get_category_ids();
								$years = wp_get_object_terms($post->ID, 'product_year', array('fields' => 'ids'));
								$thinkific_cats = wp_get_object_terms($post->ID, 'product_thinkific_cat', array('fields' => 'ids'));
								$categories = array_merge($categories, $years, $thinkific_cats);
								foreach($categories as $term_id):
									$cat = get_term($term_id);
									if($cat->name === 'Thinkific') continue;
									$tax = $cat->taxonomy === 'product_year' ? 'product_year' : 'product_cat';
									$link = '<a href="'.TLC_Helpers::resources_url().'?'.$tax.'='.$cat->term_id.'">'.$cat->name.'</a>'; 
									if($cat->taxonomy === 'product_thinkific_cat') {
										$link = '<a href="'.TLC_Helpers::courses_online_url().'?tlc_thinkific_cat='.$cat->term_id.'">'.$cat->name.'</a>'; 
									}

									echo $link;
								endforeach;
							?>
						</article>
					</div>

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