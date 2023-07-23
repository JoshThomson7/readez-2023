<?php
/**
 * Blog
 */

global $paged;
get_header();

$term = get_queried_object();

$title = 'Blog';

if($term->taxonomy === 'category') {
	$top_heading = 'Category';
}

if($term->taxonomy === 'post_tag') {
	$top_heading = 'Tag';
}

$blog_args['paged'] = $paged;
$blog_args['posts_per_page'] = 15;
$blog_args['tax_query'] = array(
	array(
		'taxonomy' => $term->taxonomy,
		'field' => 'id',
		'terms' => $term->term_id
	)
);
$blogs = FL1_Blog_Helpers::get_blogs($blog_args);
?>
<section class="blog">
    <div class="max__width">
		
		<div class="blog--archive">
			<div class="blog--loop">
				<?php include FL1_BLOG_PATH .'templates/blog-loop.php'; ?>
				<?php FL1_Helpers::pagination($blogs['max_num_pages']); ?>
			</div>

			<?php include 'blog-filters.php'; ?>
		</div>
    </div>
</section>

<?php get_footer(); ?>
