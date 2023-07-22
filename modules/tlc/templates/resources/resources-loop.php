<?php
    if ( ! defined( 'ABSPATH' ) ) exit;
    
    foreach($resources['posts'] as $resource_id):
        $_product = new TLC_Product($resource_id);
		$_product_img_id = $_product->get_image_id();
?>
    <article>
        <div class="resource-pad">
			<?php
				if($_product_img_id):
					$_product_img = vt_resize($_product_img_id, '', 500, 500, false);
					$_product_img_url = !empty($_product_img) && is_array($_product_img) ? $_product_img['url'] : null;
					if($_product_img_url):
			?>
					<figure style="background-image: url(<?php echo $_product_img_url; ?>)">
						<a href="<?php echo $_product->get_permalink(); ?>"></a>
					</figure>
			<?php 
					endif;
				endif;
			?>

            <div class="resource-info">
                <h3><a href="<?php echo $_product->get_permalink(); ?>"><?php echo $_product->get_title(); ?></a></h3>
				<?php echo $_product->get_price_html(); ?>
            </div>
        </div>
    </article>
<?php endforeach; ?>

<?php if($pagination) { FL1_Helpers::ajax_pagination($resources['max_num_pages'], 4, $paged); } ?>