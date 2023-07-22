<?php
/**
 * Events Hero
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$event = new TLC_Event(get_the_ID());
?>

<div class="tlc-event">
    <div class="max__width">
        <div class="tlc-event--hero">
			<article>
            	<h1><?php echo $event->title(); ?></h1>
				<a href="#event_book" class="button primary icon-right large scroll">Book tickets - <?php echo $event->price(); ?><i class="fa-regular fa-ticket"></i></a>
			</article>

			<div class="tlc-event--hero-meta">
				<div class="dates">
					<div class="date">
						<span><?php echo $event->date('start', 'M Y'); ?></span>
						<strong><?php echo $event->date('start', 'j'); ?></strong>
					</div>
					<?php if($event->date('end')): ?>
						<span class="separator"></span>
						<div class="date">
							<span><?php echo $event->date('end', 'M Y') ?></span>
							<strong><?php echo $event->date('end', 'j') ?></strong>
						</div>
					<?php endif; ?>
				</div>

				<div class="meta-data">
					<ul>
						<li>
							<strong>Dates</strong>
							<span><?php echo $event->date('j M Y'); ?></span>
						</li>
						<li>
							<strong>Times</strong>
							<span><?php echo $event->start_time(); ?><?php echo $event->end_time() ? ' - '.$event->end_time() : ''; ?></span>
						</li>

						<?php if($event->audience()): ?>
							<li>
								<strong>Audience</strong>
								<span><?php echo $event->audience(); ?></span>
							</li>
						<?php endif; ?>

						<?php if($event->delivery()): ?>
							<li>
								<strong>Delivery</strong>
								<span><?php echo $event->delivery(); ?></span>
							</li>
						<?php endif; ?>

						<li>
							<strong>Price</strong>
							<span><?php echo $event->price(); ?></span>
						</li>
					</ul>
				</div>

				<div class="actions">
					<a href="#event_book" class="button primary icon-right scroll">Book tickets - <?php echo $event->price(); ?><i class="fa-regular fa-ticket"></i></a>
				</div>
			</div>
        </div>
    </div>
</div>