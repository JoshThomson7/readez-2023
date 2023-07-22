<?php
/**
 * Blog Featured
 *
 * @package Blog
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

$current_page = get_query_var('paged');

$blog_args['posts_per_page'] = 1;
$blogs = FL1_Blog_Helpers::get_blogs($blog_args);

if($current_page < 2):
?>
	<section class="blog">
		<div class="max__width">
			<?php 
				$featured = 'featured';
				if(!empty($blogs)) {
					$blog_id = reset($blogs['posts']);
					include FL1_BLOG_PATH .'templates/blog-item.php';
				}
			?>
		</div><!-- max__width -->
	</section><!-- blog -->
<?php endif; ?>