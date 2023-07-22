<div class="tlc-wc--enrollments">
	<?php if(!empty($enrollments->items)): ?>
		<?php
			foreach($enrollments->items as $enrollment):

				$course_id = $enrollment->course_id;
				$product_id = TLC_Helpers::get_product_from_thinkific_id($course_id);

				if(!$product_id) continue;

				$product = new TLC_Product($product_id);
				$product_img = vt_resize($product->get_image_id(), '', 600, 600, false);

				$percentage = round($enrollment->percentage_completed * 100);
				$last_updated = new DateTime($enrollment->updated_at, wp_timezone());
				$last_updated = $last_updated->modify('+1 hour');

				$button_label = 'Go to course';
				if($percentage == 100) $button_label = 'View course';
				
		?>
				<article>
					<?php if(is_array($product_img)): ?>
						<figure>
							<a href="<?php echo $user->thinkific_sso_url($product->thinkific_take_url()); ?>" target="_blank">
								<img src="<?php echo $product_img['url']; ?>" />
							</a>
						</figure>
					<?php endif; ?>

					<div class="enrollment-content">
						<h4>
							<a href="<?php echo $user->thinkific_sso_url($product->thinkific_take_url()); ?>" target="_blank"><?php echo $product->get_name(); ?></a>
						</h4>
						<h5><small>Last updated</small> <?php echo $last_updated->format('j M Y - H:i') ?></h5>
					</div>

					<div class="enrollment-actions">
						<div class="progress tooltip" data-tooltipster='{"side":"top"}' title="Percentage completed">
							<div class="progress--figure"><?php echo $percentage; ?>%</div>
							<div class="progress--bar <?php echo TLC_Helpers::get_percentage_colour($percentage); ?>" style="width: <?php echo $percentage; ?>%;"></div>
						</div>

						<a href="<?php echo $user->thinkific_sso_url($product->thinkific_take_url()); ?>" class="button primary" target="_blank"><?php echo $button_label; ?></a>
					</div>
				</article>
		<?php endforeach; ?>

	<?php else: ?>
		<div class="not__found">
			<figure><i class="fa-duotone fa-ghost"></i></figure>
			<h3>No online courses found</h3>
			<p>You have not yet enrolled in an online courses.</p>
			<p><a href="<?php echo get_permalink(get_page_by_path('courses/online-courses')); ?>" class="button primary small">Explore our online courses</a></p>
		</div>
	<?php endif; ?>
</div>