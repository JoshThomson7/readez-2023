<?php
global $post;

$product = new TLC_Product($post->ID);
$related = $product->related_products() ?? array();
$is_related_carousel = $product->product_related_is_carousel();

$args = array(
	'post_type' => 'product',
	'post_status' => 'publish',
	'post__in' => $related,
	'fields' => 'ids',
);

if(empty($related)) {
	unset($args['post__in']);
	
	$args['posts_per_page'] = 3;
	$args['tax_query'] = array(
		'relation' => 'AND',
		array(
			'taxonomy'  => 'product_visibility',
			'terms'     => array('exclude-from-catalog'),
			'field'     => 'name',
			'operator'  => 'NOT IN',
		),
		array(
			'taxonomy' => 'product_cat',
			'field' => 'term_id',
			'terms' => $product->get_category_ids(),
			'operator' => 'IN',
		),
	);
}

$resources = new WP_Query($args);
$resources = array(
	'posts' => $resources->posts,
	'max_num_pages' => $resources->max_num_pages,
);
?>
<div class="flexible__content">
	<section class="fc-layout fc_resources">
		<div class="fc-layout-divider ellipse-top offset-50"></div>
		<div class="fc-layout-container" style="padding: 40px 0 40px 0">
			<div class="max__width">
				<div class="fc-layout-heading centred">
					<div class="fc-layout-heading-left">
						<div class="fc-dots-separator"><span class="quaternary"></span><span class="tertiary"></span><span class="secondary"></span><span class="primary"></span></div>
						<h2>Why not also check these out?</h2>
						<p>We've hand-picked some related resources you might be intesrested in.</p>
					</div>
					<div class="fc-layout-heading-right"></div>
				</div>
				
				<div class="resources-wrap<?php echo $is_related_carousel ? ' resources-wrap--carousel grid-boxes-carousel' : ''; ?>">
					<?php include TLC_PATH .'templates/resources/resources-loop.php'; ?>
				</div>
			</div><!-- max__width -->
		</div><!-- fc-layout-container -->
	</section>
</div>