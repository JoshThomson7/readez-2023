<?php
/**
 * TLC Resources
 *
 * @author FL1 Digital
 * @version 1.0
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

global $post;

$years = get_terms( array(
	'taxonomy' => 'product_year',
	'hide_empty' => false,
));

$product_cats = get_terms( array(
	'taxonomy' => 'product_cat',
	'hide_empty' => true,
	'include' => get_field('tlc_filter_terms', 'option') ?? array(),
	'orderby' => 'include',
));
?>

<section class="tlc-resources">
	<div class="max__width">
		<div class="tlc-resources-wrap">
			<aside class="tlc-filters">
				<form id="resources_filters">
					<article class="expand">
						<a href="#" class="filter-trigger button primary">
							<span>Apply filters</span>
						</a>
					</article>

					<?php if(!empty($years)): ?>
						<article class="expand">
							<h3>Year <i class="fa fa-chevron-up"></i></h3>

							<ul>
								<?php
									foreach($years as $year):
									$year_for_id = 'product_year_'.$year->term_id;
									$year_slug = $year->slug;
									$year_name = $year->name;
								?>
									<li>
										<input id="<?php echo $year_for_id; ?>" type="checkbox" name="product_year" value="<?php echo $year->term_id; ?>">
										<label for="<?php echo $year_for_id; ?>">
											<span><?php echo $year_name; ?></span>
										</label>
									</li>
								<?php endforeach; ?>
							</ul>
						</article>
					<?php endif; ?>



					<?php if(!empty($product_cats)): ?>
						<?php
							foreach($product_cats as $product_cat):
								$children = get_terms( array(
									'taxonomy' => 'product_cat',
									'hide_empty' => true,
									'parent' => $product_cat->term_id,
								));
						?>

						<article class="expand">
							<h3><?php echo $product_cat->name; ?> <i class="fa fa-chevron-up"></i></h3>

							<ul>
								<li>
									<input id="product_cat_<?php echo $product_cat->term_id; ?>" type="checkbox" name="product_cat[]" value="<?php echo $product_cat->term_id; ?>">
									<label for="product_cat_<?php echo $product_cat->term_id; ?>">
										<span>All</span>
									</label>
								</li>

								<?php
									if($children):
										foreach($children as $child):
										
										$child_for_id = 'product_cat_'.$child->term_id;
										$child_slug = $child->slug;
										$child_name = $child->name;
								?>
										<li>
											<input id="<?php echo $child_for_id; ?>" type="checkbox" name="product_cat[]" value="<?php echo $child->term_id; ?>">
											<label for="<?php echo $child_for_id; ?>">
												<span><?php echo $child_name; ?></span>
											</label>
										</li>
								<?php 
										endforeach;
									endif;
								?>
							</ul>
						</article>

						<?php endforeach; ?>
					<?php endif; ?>

					<article class="expand">
						<a href="#" class="filter-trigger button primary">
							<span>Apply filters</span>
						</a>
					</article>
				</form>
			</aside>

			<div id="resources_response" class="resources-wrap col has-deps" data-deps='{"js":["tlc-resources-filters"]}' data-deps-path="tlc_ajax_object"></div>
		</div>
	</div>
</section>

<?php get_footer(); ?>