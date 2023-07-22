<?php

/**
 * TLC Courses Online
 */

get_header();

global $post;
?>


<div class="max__width">
	<?php include 'online/online-filters.php'; ?>

	<section class="tlc-courses">
		<div id="courses_response" class="tlc-courses--items resources-wrap has-deps" data-deps='{"js":["tlc-courses-online-filters"]}' data-deps-path="tlc_ajax_object"></div>
	</section>
</div>

<?php get_footer(); ?>