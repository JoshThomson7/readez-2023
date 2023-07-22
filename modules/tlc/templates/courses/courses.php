<?php

/**
 * TLC Courses Template
 */

get_header();

global $post;
?>


<div class="max__width">
	<?php include 'course-filters.php'; ?>

	<section class="tlc-courses">

		<div class="tlc-courses--group">
			<header>
				<h2>Upcoming Live Courses</h2>
				<a href="<?php echo TLC_Helpers::courses_events_url(); ?>" class="link animate-icon">View all Live Courses<i class="fa fa-chevron-right"></i></a>
			</header>

			<?php
				$events = TLC_Courses::get_events(array(
					'posts_per_page' => 3,
				));
			?>
			<div class="tlc-courses--items events-wrap">
				<?php include 'events/events-loop.php'; ?>
			</div>
		</div>

		<div class="tlc-courses--group">
			<header>
				<h2>Featured Online Modules</h2>
				<a href="<?php echo TLC_Helpers::courses_online_url(); ?>" class="link animate-icon">View all Online Modules<i class="fa fa-chevron-right"></i></a>
			</header>

			<?php
				$thinkific_courses = TLC_Courses::get_thinkific_courses(array(
					'post__in' => TLC_Helpers::courses_featured()
				));
				
				$pagination = false;
			?>
			<div class="tlc-courses--items resources-wrap">
				<?php include 'online/online-loop.php'; ?>
			</div>
		</div>

	</section>
</div>

<?php get_footer(); ?>