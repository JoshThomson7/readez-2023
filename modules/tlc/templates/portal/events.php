<?php
/**
 * TLC WooCommerce My Account - Events
 */

$user_id = get_current_user_id();
$user = new TLC_User($user_id);
$EM_Person = new EM_Person($user_id);
$EM_Bookings = $EM_Person->get_bookings();
$event_limit = 0;
$nonce = wp_create_nonce('booking_cancel');
?>

<section class="tlc tlc-wc">
	<?php include 'events-loop.php'; ?>
</section>