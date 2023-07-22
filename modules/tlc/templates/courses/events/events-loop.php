<?php
/**
 * Events
 */

if(!defined('ABSPATH')) { exit; }

if(!empty($events)):
	foreach($events as $events_id):
		$_event = new TLC_Event($events_id);
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
					<span><?php echo $_event->price(true); ?></span>
				</div>
			</div>
		</article>
	<?php endforeach; ?>
<?php else: ?>
	<div class="not__found">
		<figure><i class="fa-duotone fa-calendar-xmark"></i></figure>
		<h3>No courses found</h3>
		<p>We could not find any upcoming courses matching your cirteria.</p>
	</div>
<?php endif; ?>