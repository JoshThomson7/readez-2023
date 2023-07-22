<?php
/**
 * TLC WooCommerce My Account - Dashboard
 */

$user_id = get_current_user_id();
$user = new TLC_User($user_id);
$enrollments = $user->thinkific_enrollments(array('limit' => 1));

$EM_Person = new EM_Person($user_id);
$EM_Bookings = $EM_Person->get_bookings();
$event_limit = 1;
$nonce = wp_create_nonce('booking_cancel');
?>

<section class="tlc tlc-wc">

	<h3>Recent online course enrollment <a href="<?php echo get_permalink(get_page_by_path('courses/online-courses')); ?>">View all</a></h3>
	<?php include 'courses-loop.php'; ?>

	<h3>Upcoming live course <a href="<?php echo get_permalink(get_page_by_path('courses/live-courses')); ?>">View all</a></h3>
	<?php include 'events-loop.php'; ?>
</section>