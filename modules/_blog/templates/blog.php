<?php
/**
 * Blog
 */

get_header();

$featured = FL1_Blog_Helpers::get_blogs(array(
	'posts_per_page' => 1,
));
$featured_id = reset($featured['posts']);

$current_page = get_query_var('paged');
$current_page = max(1, $current_page);

$per_page = 15;

$args = array(
	'paged' => $current_page,
	'posts_per_page' => $per_page,
	'post__not_in' => array($featured_id),
);

$blogs = FL1_Blog_Helpers::get_blogs($args);
?>
<section class="blog">
	<div class="max__width">

		<div class="blog--cats">
			<select onchange="var selectedOption = this.options[this.selectedIndex]; var url = selectedOption.value; if (url !== 'all') { window.location.href = url; }">
				<option value="">Category</option>
				<?php
					$categories = get_categories();
					foreach ($categories as $category) {
						echo '<option value="' . get_term_link($category, 'category') . '">' . $category->name . '</option>';
					}
				?>
			</select>
		</div>

		<div class="blog--loop grid">
			<?php
				$featured = '';
				include FL1_BLOG_PATH . 'templates/blog-loop.php';
				FL1_Helpers::pagination($blogs['max_num_pages'], 4, true);
			?>
		</div>
	</div><!-- max__width -->
</section><!-- blog -->

<?php get_footer(); ?>
