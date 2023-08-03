<?php if(!empty($chapters)): ?>
	<div class="flexible__content">
		<section class="fc-layout fc_accordion">
			<div class="fc-layout-container">
				<?php
					foreach($chapters as $chapter):
						$chapter_name = $chapter['name'];
						$lessons = $chapter['lessons'] ?? array();
				?>
					<div class="accordion__wrap" id="fc-accordion-2-1">
						<h3 class="toggle"><?php echo $chapter_name; ?> <i class="fal fa-chevron-down"></i></h3>

						<div class="accordion__content">
							<ol class="thinkific-course-lessons">
								<?php
									foreach($lessons as $lesson_id => $lesson):
										$lesson_name = $lesson['name'];
										$lesson_free = $lesson['free'] ?? false;
										$lesson_url = $lesson['take_url'];
								?>
									<?php if($lesson_free): ?>
										<li>
											<a href="<?php echo $lesson_url; ?>" target="_blank">
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
				<?php endforeach; ?>
			</div><!-- fc-layout-container -->
		</section><!-- fc_accordion -->
	</div>
<?php endif; ?>