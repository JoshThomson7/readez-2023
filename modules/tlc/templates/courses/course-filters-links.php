<?php
global $post;

$is_active_courses = is_page('courses') ? ' class="active"' : null;
$is_active_online = is_page('online-courses') ? ' active' : null;
$is_active_live = is_page('live-courses') ? ' active' : null;
$is_active_pathways = is_page('pathways-courses') ? ' class="active" ' : null;
?>
<div class="filter-group radios pad-left pad-right">
	<article class="is-link">
		<a href="<?php echo get_permalink(get_page_by_path('courses')); ?>"<?php echo $is_active_courses; ?>>All</a>
	</article>

	<article class="is-link">
		<a href="<?php echo get_permalink(get_page_by_path('courses/online-courses')); ?>" class="tooltip<?php echo $is_active_online; ?>" title="Online training modules and course recordings">Online</a>
	</article>

	<article class="is-link">
		<a href="<?php echo get_permalink(get_page_by_path('courses/live-courses')); ?>" class="tooltip<?php echo $is_active_live; ?>" title="Venue-based courses and webinars">Live</a>
	</article>

	<?php /* <article class="is-link">
		<a href="#"<?php echo $is_active_pathways; ?>>Pathways</a>
	</article> */ ?>
</div>