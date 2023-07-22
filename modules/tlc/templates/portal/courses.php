<?php
/**
 * TLC WooCommerce My Account - Courses
 */

$user_id = get_current_user_id();
$user = new TLC_User($user_id);
$enrollments = $user->thinkific_enrollments();
?>

<section class="tlc tlc-wc">
	<?php include 'courses-loop.php'; ?>
</section>