<?php
/**
 * Blog Item
 *
 * @package Blog
 * @version 1.0
*/

$blog = new FL1_Blog($blog_id);

// Image
$blog_image = $blog->image(900, 500, true);
$banner_image = '';
if(!empty($blog_image)) {
    $banner_image = ' style="background-image: url('.$blog_image['url'].')"';
} else {
    $banner_image = ' style="background-image: url('.get_stylesheet_directory_uri().'/img/sq-blog-placeholder.jpg)"';
}

// Main category
$blog_cat_id = $blog->main_category('ids');
$blog_cat = $blog->main_category('id=>name');
?>
<article class="blog--post <?php echo $featured; ?>">
	<div class="blog--post-padder">
		<a class="blog--post-img" href="<?php echo $blog->url(); ?>" <?php echo $banner_image; ?>></a>
		
		<div class="blog--post-content">
			<?php if($blog_cat): ?>
				<h5>
					<a href="<?php echo get_term_link($blog_cat_id, 'category'); ?>"><?php echo $blog_cat; ?></a>
				</h5>
			<?php endif; ?>
			<h2><a href="<?php echo $blog->url(); ?>" title="<?php echo $blog->title(); ?>"><?php echo $blog->title(); ?></a></h2>

			<date>
				<?php echo $blog->date('M jS Y') ?>
			</date>
			
			<p><?php echo $blog->excerpt($featured ? 35 : 15); ?></p>

			<div class="blog--post-action">
				<a href="<?php echo $blog->url(); ?>" class="button primary <?php echo $featured ? 'large' : ''; ?>">
					<span>Read more</span>
				</a>
			</div>
		</div>
	</div>
</article>