<?php
/**
 * ATM Helpers
 *
 * Helper static methods for the Advanced Travel Money module.
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class TLC_Helpers {

    /**
     * Returns Event categories
     */
    public static function get_event_cats() {

		return get_terms( array(
			'taxonomy' => 'event-categories',
			'hide_empty' => true,
		));

    }

    /**
     * Returns Thinkific categories
     */
    public static function get_thinkific_cats() {

		return get_terms( array(
			'taxonomy' => 'product_thinkific_cat',
			'hide_empty' => true,
		));

    }

    /**
     * Check if a product exists
     * @param  int $id
     * @return int Product ID
     */
    public static function get_product_from_thinkific_id($id) {

        $args = array(
            'post_type' => 'product',
            'meta_key' => 'thinkific_course_id',
            'meta_value' => $id,
            'fields' => 'ids',
            'posts_per_page' => 1
        );

        $query = new WP_Query($args);

        if(!empty($query->posts)) {
            return reset($query->posts);
        }

        return 0;

    }

	/**
     * Calculates percentage colour and returns CSS class
     */
    public static function get_percentage_colour($percentage) {

        switch ($percentage) {
            case $percentage >= '30' && $percentage < 80 :
                $percentage_class = 'warning';
                break;

            case $percentage >= '80' :
                $percentage_class = 'success';
                break;
            
            default:
                $percentage_class = 'error';
                break;
        }

        return $percentage_class;

    }

	/**
     * Returns the order form URL
     */
    public static function order_form_url() {

        return get_field('tlc_order_form', 'option');

    }

	/**
     * Returns featured courses
	 * @return array
     */
    public static function courses_featured() {

        return get_field('courses_featured', 'option');

    }

	/**
     * Outputs the Thinkific base URL
     */
    public static function thinkific_base_url() {

        return get_field('thinkific_base_url', 'option');

    }

	/**
     * Outputs the courses page URL
     */
    public static function courses_url() {

        return get_permalink(get_field('courses_page_main', 'option'));

    }

	/**
     * Outputs the courses (online) page URL
     */
    public static function courses_online_url() {

        return get_permalink(get_field('courses_online_page', 'option'));

    }

	/**
     * Outputs the courses (events) page URL
     */
    public static function courses_events_url() {

        return get_permalink(get_field('courses_events_page', 'option'));

    }

	/**
     * Outputs the resources page URL
     */
    public static function resources_url() {

        return get_permalink(get_field('tlc_resources_page', 'option'));

    }

	public static function thinkific_sso_url($goto, $user_id) {

		if(!$user_id || !is_numeric($user_id)) return false;
		
		$user = new TLC_User($user_id);

		// Create token header as a JSON string
		$header = json_encode(
			array(
				'typ' => 'JWT',
				'alg' => 'HS256'
			)
		);

		// Create token payload as a JSON string
		$payload = json_encode(
			array(
				'email' => $user->get_email(),
				'first_name' => $user->get_first_name(),
				'last_name' => $user->get_last_name(),
				'iat' => time()
			)
		);

		// Encode Header to Base64Url String
		$base64UrlHeader = FL1_helpers::base64url_encode($header);

		// Encode Payload to Base64Url String
		$base64UrlPayload = FL1_helpers::base64url_encode($payload);

		// Create Signature Hash
		$key = '79039661a5a90855af1fe83ec9f0f9c2';
		$signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $key, true);

		// Encode Signature to Base64Url String
		$base64UrlSignature = FL1_helpers::base64url_encode($signature);

		// Create JWT
		$jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

		$baseUrl = TLC_Helpers::thinkific_base_url().'/api/sso/v2/sso/jwt?jwt=';
		$returnTo = urlencode(($goto ? $goto : TLC_Helpers::thinkific_base_url()));
		$errorUrl = urlencode(TLC_Helpers::thinkific_base_url());
		$url = $baseUrl . $jwt . '&amp;return_to='.$returnTo.'&amp;error_url='.$errorUrl;

		return $url;

	}

}

