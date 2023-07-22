<?php
/**
 * TLC Events
 *
 * @author FL1 Digital
 * @version 1.0
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class TLC_Events {

    public function init() {

		add_action('fl1_woo_after_customer_created', array($this, 'wc_after_customer_created'));

    }

	/**
	 * Updates bookings with the user id of the person who booked the event
	 * 
	 * @param int $user_id
	 */
	public function wc_after_customer_created($user_id) {

		global $wpdb;

		$user = new FL1_Woo_User($user_id);

		$booking_ids = $wpdb->get_col( $wpdb->prepare("
			SELECT booking_id FROM {$wpdb->prefix}em_bookings_meta
			WHERE meta_key = '_registration_user_email'
			AND meta_value = %s
		", $user->get_email() ) );

		if(!empty($booking_ids)) {
			
			foreach($booking_ids as $booking_id) {

				$wpdb->update(
					$wpdb->prefix . 'em_bookings',
					array(
						'person_id' => $user_id
					),
					array(
						'booking_id' => $booking_id,
						'person_id' => 0
					)
				);

			}
		}


		return $user_id;

	}

}

