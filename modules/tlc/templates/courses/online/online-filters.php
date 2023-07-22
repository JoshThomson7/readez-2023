<?php
/**
 * Course Filters - Online
 */

$thinkific_cats = TLC_Helpers::get_thinkific_cats();
?>
<section class="tlc-form-filters">
	<form id="tlc_form_filters">
		<?php include TLC_PATH .'templates/courses/course-filters-links.php'; ?>

		<?php if(!empty($thinkific_cats)): ?>
			<div class="filter-group pad-left pad-right border-left">
				<article class="is-select">
					<label>Category</label>
					<select name="tlc_thinkific_cat" class="chosen-select">
						<option value="">Any category</option>
						<?php foreach($thinkific_cats as $cat): ?>
							<option value="<?php echo $cat->term_id; ?>"><?php echo $cat->name; ?></option>
						<?php endforeach; ?>
					</select>
				</article>
			</div>
		<?php endif; ?>
	</form>
</section>