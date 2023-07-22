<?php
/**
 * Online
 */

if(!defined('ABSPATH')) { exit; }
?>
<div class="tlc-courses--items resources-wrap">
	<?php
		if(!empty($thinkific_courses->posts)):
			foreach($thinkific_courses->posts as $thinkific_course_id):
				$_product = new TLC_Product($thinkific_course_id);
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
	<?php else: ?>
		<div class="not__found">
			<figure><i class="fa-duotone fa-laptop-slah"></i></figure>
			<h3>No courses found</h3>
			<p>We could not find any online courses matching your cirteria.</p>
		</div>
	<?php endif; ?>
</div>

<?php if($pagination) { FL1_Helpers::ajax_pagination($thinkific_courses->max_num_pages, 4, $paged); } ?>