<?php
/**
 * Course Filters - Live
 */

$event_cats = TLC_Helpers::get_event_cats();
?>
<section class="tlc-form-filters">
	<form id="tlc_form_filters" action="">
		<?php include TLC_PATH .'templates/courses/course-filters-links.php'; ?>

		<div class="filter-group pad-left pad-right border-left">
			<?php if(!empty($event_cats)): ?>
				<article class="is-select">
					<label>Category</label>
					<select name="tlc_thinkific_cat" class="chosen-select">
						<option value="">Any category</option>
						<?php foreach($event_cats as $cat): ?>
							<option value="<?php echo $cat->term_id; ?>"><?php echo $cat->name; ?></option>
						<?php endforeach; ?>
					</select>
				</article>
			<?php endif; ?>

			<article class="pickr-date-range is-input-text">
				<label>Date range <figure class="tooltip" title="Clear date" data-tooltipster='{"side":"top"}'><i class="fal fa-times-circle" data-clear></i></figure></label>
				<input type="text" name="tlc_event_date_range" value="" placeholder="Pick a date" data-input />
			</article>
		</div>
	</form>
</section>