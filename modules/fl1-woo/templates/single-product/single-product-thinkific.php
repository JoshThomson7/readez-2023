<?php
	$chapters = $product->thinkific_chapters() ?? array();
	if(!empty($chapters)):
		$is_user_logged_in = is_user_logged_in();	
		$user_id = get_current_user_id();
		$user = new TLC_User($user_id);
		$enrollment = $user->thinkific_course_enrollment_status($product->thinkific_course_id());
?>
	<div class="flexible__content">
		<section class="fc-layout fc_accordion">
			<div class="fc-layout-container">
				<?php
					$accordion_count = 0;
					foreach($chapters as $chapter):
						$chapter_name = $chapter['name'];
						$lessons = $chapter['lessons'] ?? array();
				?>
					<div class="accordion__wrap <?php echo $accordion_count < 1 ? 'active': ''; ?>" id="fc-accordion-2-1">
						<h3 class="toggle"><?php echo $chapter_name; ?> <i class="fal fa-chevron-down"></i></h3>

						<div class="accordion__content">
							<ol class="thinkific-course-lessons">
								<?php
									foreach($lessons as $lesson_id => $lesson):
										$lesson_name = $lesson['name'];
										$lesson_free = $lesson['free'] ?? false;
										$target = '';
										$lesson_url = FL1_WOO_Helpers::get_my_account_url().'?redirect_to='.urlencode($product->get_permalink());
								?>
									<?php
										if($lesson_free):
											if($is_user_logged_in) {
												$target = ' target="_blank"';
												if($enrollment->is_enrolled) {
													$lesson_url = $user->thinkific_sso_url($product->thinkific_take_url());
												} else {
													if($enrollment->is_free_trial) {
														$lesson_url = $user->thinkific_sso_url($product->thinkific_take_url());
													} else {
														$lesson_url = $user->thinkific_sso_url($product->thinkific_free_enroll_course_url());
													}
												}
											}
									?>
										<li>
											<a href="<?php echo $lesson_url; ?>" <?php echo $target; ?>>
												<?php echo $lesson_name; ?>
												<?php echo $lesson_free ? '<figure>Free preview</figure>' : ''; ?>
											</a>
										</li>
									<?php else: ?>
										<li><?php echo $lesson_name; ?></li>
									<?php endif; ?>
								<?php endforeach; ?>
							</ol>
						</div><!-- accordion__content -->

						<div class="accordion__bg"></div>
					</div><!-- accordion__wrap -->
				<?php $accordion_count++; endforeach; ?>
			</div><!-- fc-layout-container -->
		</section><!-- fc_accordion -->
	</div>
<?php endif; ?>