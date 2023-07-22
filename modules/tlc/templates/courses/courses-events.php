<?php

/**
 * TLC Courses Live
 */

get_header();

global $post;
?>


<div class="max__width">
	<?php include 'events/events-filters.php'; ?>

	<section class="tlc-courses">
		<div id="events_response" class="tlc-courses--items events-wrap has-deps" data-deps='{"js":["tlc-events-filters"]}' data-deps-path="tlc_ajax_object"></div>
	</section>
</div>

<?php get_footer(); ?>