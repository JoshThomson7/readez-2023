<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * WooCommerce Register Template
 *
 * @package modules/woocommerce
 * @version 1.0
*/
if(is_user_logged_in()) {
    wp_redirect(get_permalink(get_option('woocommerce_myaccount_page_id')));
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <title><?php wp_title( '-', true, 'right' ); ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />

    <?php
        wp_head();
        global $woocommerce, $current_user, $post;
    ?>
</head>

<body>
    <section class="wc__login__register">

        <div class="wc__login__register--back">
            <a href="<?php echo esc_url(home_url()); ?>">
                <i class="fal fa-times"></i>
            </a>
        </div><!-- wc__login__register back -->

        <div class="wc__login__register--banner">
            <h2>Inspire. Support. Develop.</h2>

            <ul>
                <li><i class="fal fa-check"></i>Custom Dashboard</li>
                <li><i class="fal fa-check"></i>Access your resources</li>
                <li><i class="fal fa-check"></i>View your orders</li>
                <li><i class="fal fa-check"></i>Update your details</li>
            </ul>

        </div><!-- wc__login__register banner -->

        <div class="wc__login__register--form">

			<div class="wc__login__register--form-content">

				<figure>
					<img src="<?php echo esc_url(get_stylesheet_directory_uri()); ?>/img/logo.png" alt="<?php bloginfo('name'); ?>">
				</figure>

				<div class="wc__is__user">

					<?php if(is_page('register')): ?>
						<p>Already registered? <a href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')); ?>">Log in here.</a></p>
					<?php elseif(is_wc_endpoint_url( 'lost-password' )): ?>
						<?php if(isset($_GET['show-reset-form']) && $_GET['show-reset-form'] === 'true'): ?>
							<h2>Create your new password</h2>
							<p>Enter your new password below. <a href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')); ?>">Cancel and back to login.</a></p>
						<?php else: ?>
							<h2>Forgot your password?</h2>
							<p>Please enter your username or email address. You will receive a link to create a new password via email. <a href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')); ?>">Back to login.</a></p>
						<?php endif; ?>
					<?php else: ?>
						<?php if(is_page('magic-link')): ?>
							<p>Want to use your email and password to </a> <a href="<?php echo FL1_Woo_Helpers::get_my_account_url(); ?>">log in</a>?</p>
						<?php else: ?>
							<p>New to <?php bloginfo('name'); ?>? <a href="<?php echo esc_url(home_url()); ?>/register/">Register here.</a></p>
						<?php endif; ?>
					<?php endif; ?>
					
				</div><!-- wc__is__user -->

				<?php wc_print_notices(); ?>

				<?php if(isset($_GET['password-reset']) && $_GET['password-reset'] === 'true'): ?>
					<?php wc_print_notice('Password reset successfully. You can now log in to your account.', 'success'); ?>
				<?php elseif(isset($_GET['reset-link-sent']) && $_GET['reset-link-sent'] === 'true'): ?>
					<?php wc_print_notice("We've sent you an email. If your account is registered with us you'll be able to reset your password.<br>Please make sure you also check your SPAM folder.", 'success'); ?>
				<?php elseif(isset($_GET['account_status']) && $_GET['account_status'] === 'unverified'): ?>
					<?php wc_print_notice('Please check your email to verify your account', 'success'); ?>
				<?php elseif(isset($_GET['account_status']) && $_GET['account_status'] === 'verified'): ?>
					<?php wc_print_notice('Your account has been verified. You can now log in.', 'success'); ?>
				<?php endif; ?>

				<?php                  
					if(is_page('register')) {
						require_once(FL1_WOO_PATH.'templates/login-register/register.php');

					} elseif(is_page('magic-link')) {
						require_once(FL1_WOO_PATH.'templates/login-register/magic.php');
					
					} elseif(is_wc_endpoint_url( 'lost-password' )) {
						if(isset($_GET['show-reset-form']) && $_GET['show-reset-form'] === 'true') { 
							require_once(FL1_WOO_PATH.'templates/login-register/reset-password.php');
						} else {
							require_once(FL1_WOO_PATH.'templates/login-register/forgot-password.php');
						}
					
					} else { 
						require_once(FL1_WOO_PATH.'templates/login-register/login.php');
					}
				?>
			</div>

            <div class="wc__login__register--footer">

                <ul>
                    <?php if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'): ?>
                        <li class="secure"><i class="far fa-lock"></i> Your connection is secure</li>
                    <?php endif; ?>
                    <li>&copy; <?php bloginfo('name'); ?> and its affiliate companies.</li>
                </ul>

            </div><!-- wc__login__register meta -->

        </div><!-- wc__login__register form -->

    </section><!-- wc__wrapper -->

    <?php wp_footer(); ?>
</body>
</html>
