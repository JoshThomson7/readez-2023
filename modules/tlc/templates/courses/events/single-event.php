<?php

/**
 * Events Single
 */
if (!defined('ABSPATH')) exit;

get_header();

$event = new TLC_Event(get_the_ID());
?>

<div class="flexible__content">
	<section class="fc-layout fc_free_text">
		<div class="fc-layout-container" style="padding: 80px 0 80px 0">
			<div class="max__width">
				<div class="fc-free-text">
					<?php echo $event->content(); ?>
				</div>
			</div><!-- max__width -->
		</div><!-- fc-layout-container -->
	</section>

	<section id="event_book" class="fc-layout fc_free_text">
		<div class="fc-layout-divider ellipse-top offset-50"></div>
		<div class="fc-layout-container" style="padding: 40px 0 80px 0">
			<div class="max__width">
				<div class="fc-layout-heading centred">
					<div class="fc-layout-heading-left">
						<div class="fc-dots-separator"><span class="quaternary"></span><span class="tertiary"></span><span class="secondary"></span><span class="primary"></span></div>
						<h2>Book</h2>
						<p>Fill in the form below to book a place.</p>
					</div>
					<div class="fc-layout-heading-right"></div>
				</div>
				<div class="fc-free-text">
					<?php echo $event->booking_form(); ?>
				</div>
			</div><!-- max__width -->
		</div><!-- fc-layout-container -->
	</section>
</div>
<?php get_footer(); ?>