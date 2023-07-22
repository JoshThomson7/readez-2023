<?php if(count($EM_Bookings->bookings) > 0): ?>
	<div class="events-wrap row">
	<?php
		$count_events = 0;
		foreach ($EM_Bookings->bookings as $EM_Booking):

			if($event_limit > 0 && $count_events >= $event_limit) break;

			$EM_Event = $EM_Booking->get_event();
			$_event = new TLC_Event($EM_Event->post_id);

			$attendees = $EM_Booking->meta['attendees'];
			$attendees_count = count($attendees);
			$booking_status = strtolower($EM_Booking->get_status());
	?>
		<article>
			<div class="padder">
				<div class="dates">
					<div class="date">
						<span><?php echo $_event->date('start', 'M Y'); ?></span>
						<strong><?php echo $_event->date('start', 'j'); ?></strong>
					</div>
					<?php if($_event->date('end')): ?>
						<span class="separator"></span>
						<div class="date">
							<span><?php echo $_event->date('end', 'M Y') ?></span>
							<strong><?php echo $_event->date('end', 'j') ?></strong>
						</div>
					<?php endif; ?>
				</div>
				
				<div class="meta">
					<h4><a href="<?php echo $_event->url(); ?>"><?php echo $_event->title(); ?></a></h4>
					<span class="price"><?php echo $_event->price(true); ?></span>

					<div class="meta--data">
						<span class="event-status <?php echo $booking_status;?>"><?php echo $EM_Booking->get_status(); ?></span>
						<a href="#" class="toggle-attendees"><span>View attendees</span> (<?php echo $attendees_count; ?>)</a>
					</div>	

					<div class="attendees">
						<table class="shop_table">
							<thead>
								<tr>
									<th>Name</th>
									<th>Email</th>
									<th>School</th>
									<th>Position</th>
								</tr>
							</thead>
							<tbody>
								<?php
									foreach($attendees as $attendee): 
									$attendee = reset($attendee);
								?>
									<tr>
										<td><?php echo $attendee['attendee_name']; ?></td>
										<td><?php echo $attendee['attendee_email']; ?></td>
										<td><?php echo $attendee['attendee_school']; ?></td>
										<td><?php echo $attendee['attendee_position']; ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>

				<div class="actions">
					<?php
						$cancel_link = '';
						if( !in_array($EM_Booking->booking_status, array(2,3)) && $EM_Booking->can_cancel() ){
							$cancel_url = em_add_get_params($_SERVER['REQUEST_URI'], array('action'=>'booking_cancel', 'booking_id'=>$EM_Booking->booking_id, '_wpnonce'=>$nonce));
							$cancel_link = '<a class="em-bookings-cancel button primary tooltip" title="This action cannot be undone" href="'.$cancel_url.'" onclick="if( !confirm(EM.booking_warning_cancel) ){ return false; }">'.__('Cancel booking','events-manager').'</a>';
						}
						echo apply_filters('em_my_bookings_booking_actions', $cancel_link, $EM_Booking);
					?>
				</div>
			</div>
		</article>
	<?php $count_events++; endforeach; ?>

<?php else: ?>
	<div class="tlc-wc--enrollments">
		<div class="not__found">
			<figure><i class="fa-duotone fa-ghost"></i></figure>
			<h3>No live courses found</h3>
			<p>You have not yet booked a live course.</p>
			<p><a href="<?php echo get_permalink(get_page_by_path('courses/live-courses')); ?>" class="button primary small">Explore our live courses</a></p>
		</div>
	</div>
<?php endif; ?>