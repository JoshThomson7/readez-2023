<?php
/*
    Masonry
*/
?>

    <div class="masonry__wrapper">
        <?php if(have_rows('masonry', $reuse_id)): ?>
            <div class="masonry" data-isotope='{ "itemSelector": ".masonry__item" }'>
                <?php
                    while(have_rows('masonry', $reuse_id)) : the_row();

                    $row_layout = get_row_layout();

                    if(get_row_layout() === 'blog'):

                        $args = array(
                            'post_type'         => 'post',
 		        	        'post_status'       => 'publish',
 		        	        'posts_per_page'    => 1,
							'orderby'           => 'date',
                            'order'             => 'desc',
							'fields'			=> 'ids',
                        );

						if(get_sub_field('ms_blog_type') == 'latest') {
                            $args['orderby'] = 'date';

                        } elseif(get_sub_field('ms_blog_type') == 'featured') {
                            $args['post__in'] = array(get_sub_field('ms_blog_featured'));

                        } elseif(get_sub_field('ms_blog_type') == 'random') {
                            $args['orderby'] = 'rand';
                        }

                        $ms_blog = new WP_Query($args);
                ?>
                        <div class="masonry__item <?php echo $row_layout; ?>">

                            <div class="masonry__item__wrapper">
                                <?php
                                    foreach($ms_blog->posts as $blog_id):

                                    $blog = new FL1_Blog($blog_id);
									$blog_image = $blog->image(900, 600, true);
									$blog_cat = $blog->main_category('id=>name');

									$banner_image = '';
									if(!empty($blog_image)) {
										$banner_image = ' style="background-image: url('.$blog_image['url'].')"';
									}
                                ?>
                                    <a href="<?php echo $blog->url(); ?>" class="blog__img" <?php echo $banner_image; ?>>
                                        <div class="blog__img__gradient"></div>
                                    </a>

                                    <div class="blog__content">
                                        <h3>
											<?php if($blog_cat): ?><span><?php echo $blog_cat; ?></span><?php endif; ?>
                                            <a href="<?php echo $blog->url(); ?>" title="<?php echo $blog->title(); ?>"><?php echo $blog->title(); ?></a>
                                        </h3>
                                        <p><?php echo $blog->excerpt(30); ?></p>

                                        <div class="blog__meta">
                                            <date><?php echo $blog->date('j M Y') ?></date>
                                            <a href="<?php echo $blog->url(); ?>" title="Full article" class="arrow__link">Full article <i class="fa-light fa-chevron-right"></i></a>
                                        </div><!-- blog__meta -->
                                    </div><!-- blog__content -->
                                <?php endforeach; ?>

                            </div><!-- masonry__item__wrapper -->

                        </div><!-- masonry__item -->

                <?php
                    elseif(get_row_layout() === 'team'):

						$team_id = get_sub_field('ms_team_member');
						$_team = new TLC_Team($team_id);
						$member_id = strtolower(preg_replace("#[^A-Za-z0-9]#", "", get_the_title($team_id)));
						$short_bio = get_sub_field('ms_team_short_bio');
                ?>
                        <div class="masonry__item <?php echo $row_layout; ?>">

                            <div class="masonry__item__wrapper">
                                
								<a href="#<?php echo $member_id; ?>" class="team__img team__modal" style="background-image:url(<?php echo $_team->image(400, 400)['url']; ?>)"></a>

								<div class="team__content">
									<h3><a href="#" title="<?php echo $_team->name(); ?>"><?php echo $_team->name(); ?></a></h3>
									<?php if($short_bio): ?><p><?php echo $short_bio; ?></p><?php endif; ?>
									<div class="team__meta">
										<a href="#<?php echo $member_id; ?>" title="Meet <?php echo $_team->name('first'); ?>" class="arrow__link team__modal">
											Meet <?php echo $_team->name('first'); ?> <i class="fa-light fa-chevron-right"></i>
										</a>
									</div><!-- blog__meta -->
								</div><!-- blog__content -->
								
								<div class="team__popup__holder">
									<div id="<?php echo $member_id; ?>" class="team__popup">

										<div class="team__popup__img">
											<img src="<?php echo $_team->image(600, 700)['url']; ?>" alt="<?php echo $_team->name(); ?>" />
										</div><!-- team__popup__img -->

										<div class="team__popup__content">
											<div class="team__popup__nav">
												<ul>
													<li><a href="#" class="team__close"><i class="fa-regular fa-times"></i></a></li>
												</ul>
											</div><!-- team__popup__nav -->

											<h3><?php echo $_team->name(); ?> <span><?php echo $_team->job_title(); ?></span></h3>

											<?php if($_team->email()): ?>
												<div class="team__popup__icon">
													<i class="fa-regular fa-envelope"></i>
													<?php echo FL1_Helpers::hide_email($_team->email()); ?>
												</div>
											<?php endif; ?>

											<?php if($_team->phone()): ?>
												<div class="team__popup__icon">
													<i class="fa-regular fa-phone"></i>
													<a href="tel:<?php echo $_team->phone(); ?>" target="_blank"><?php echo $_team->phone(); ?></a>
												</div>
											<?php endif; ?>

											<?php echo $_team->bio(); ?>
										</div><!-- team__popup__content -->
									</div><!-- team__popup -->
								</div><!-- team__popup__holder -->
                            </div><!-- masonry__item__wrapper -->
                                
                        </div><!-- masonry__item -->

                <?php
                    elseif(get_row_layout() === 'course'):

                        $args = array(
                            'posts_per_page'    => 1,
                        );

                        if(get_sub_field('ms_course_type') == 'featured') {
                            $args['post__in'] = array(get_sub_field('ms_course_featured'));
                        }

                        $events = TLC_Courses::get_events($args);
                ?>
                        <div class="masonry__item <?php echo $row_layout; ?>">

							<div class="masonry__item__wrapper">
								<header>
									Upcoming Course
									<a href="<?php echo get_permalink(get_page_by_path('courses')); ?>">View all</a>
								</header>
								
								<?php
									foreach($events as $event_id):

									$event = em_get_event($event_id, 'post_id');
									$event_title = $event->output("#_EVENTNAME");
									$event_date = $event->output("#_EVENTDATES");
									$event_permalink = $event->output("#_EVENTURL");
									$event_excerpt = FL1_Helpers::trunc($event->output("#_EVENTEXCERPT"), 35);
								?>
									<div class="course__content">
										<h3>
											<a href="<?php echo $event_permalink; ?>" title="<?php echo $event_title; ?>"><?php echo $event_title; ?></a>
											<?php if($event_date): ?><span><?php echo $event_date; ?></span><?php endif; ?>
										</h3>
										
										<?php echo $event_excerpt; ?>

										<div class="course__meta">
											<a href="<?php echo $event_permalink; ?>" title="View course" class="arrow__link">View course <i class="fa-light fa-chevron-right"></i></a>
										</div>
									</div>
								<?php endforeach; ?>

							</div><!-- masonry__item__wrapper -->

						</div><!-- masonry__item -->

                <?php
                    elseif(get_row_layout() === 'free_text'):

                        $free_text = apply_filters('the_content', get_sub_field('free_text', false, false));
                ?>
                        <div class="masonry__item <?php echo $row_layout; ?>">

							<div class="masonry__item__wrapper">
								<header>
									<?php the_sub_field('heading'); ?>
								</header>
								
								<div class="free_text_content">
									<?php echo $free_text; ?>
								</div>

							</div><!-- masonry__item__wrapper -->

						</div><!-- masonry__item -->

                <?php
                    elseif(get_row_layout() === 'resources'):

                        $resources = get_sub_field('resources');
                ?>
                        <div class="masonry__item <?php echo $row_layout; ?>">

							<div class="masonry__item__wrapper">
								<header>
									Top Resources
									<a href="<?php echo get_permalink(get_page_by_path('resources')); ?>">View all</a>
								</header>
								
								<div class="resources__content">
									<?php if(!empty($resources)): ?>
										<?php
											foreach($resources as $resource_id):
												$resource = get_term($resource_id, 'product_cat');
										?>
											<a href="<?php echo get_permalink(get_page_by_path('resources')); ?>?product_cat=<?php echo $resource_id; ?>">
												<?php echo $resource->name; ?>
												<i class="fa-light fa-chevron-right"></i>
											</a>
										<?php endforeach; ?>
									<?php endif; ?>
								</div>

							</div><!-- masonry__item__wrapper -->

						</div><!-- masonry__item -->

                <?php endif; ?>
                <?php endwhile; ?>
            </div><!-- masonry -->
        <?php endif; ?>
    </div><!-- masonry__wrapper -->
