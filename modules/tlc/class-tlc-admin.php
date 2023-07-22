<?php
/**
 * TLC Public
 *
 * Class in charge of TLC Public facing side
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class TLC_Admin {

    public function __construct() {

        add_action('user_register', array($this, 'user_register'), 10, 2);
        add_action('profile_update', array($this, 'profile_update'), 10, 3);
        add_action('acf/save_post', array($this, 'acf_save_post'), 20);

		add_filter('acf/fields/relationship/query/name=courses_featured', array($this, 'courses_featured_query'), 10, 3);
		add_filter('acf/fields/relationship/result/name=courses_featured', array($this, 'courses_featured_result'), 10, 4);


    }

    private function on_user_save_or_update($user_id) { 

        $customer = new TLC_User($user_id);
		$role = $customer->get_role();

		// Bail early if role isn't customer
		if($role !== 'customer') return;

        $api = new TLC_Thinkific_API();
        $user_exists = $api->user_exists($customer->get_email());
        $thinkific_user_id = $user_exists;

        if(!$user_exists) {
            $user = $api->create_user($user_id);

            if($user && $user->id) {
                $thinkific_user_id = $user->id;
            }
        } else {
            $user = $api->update_user($user_id);
        }

        update_field('thinkific_user_id', $thinkific_user_id, 'user_' . $user_id);

    }

    public function user_register($user_id, $user_data) {

        $this->on_user_save_or_update($user_id);

    }

    public function profile_update($user_id, $user_data, $raw_data) {

        $this->on_user_save_or_update($user_id);

    }

    public function acf_save_post($post_id) {

        $screen = get_current_screen();
        $post_type = get_post_type($post_id);

        if($post_type === 'product' || ($post_id === 'options' && $screen->id === 'the-literacy-co_page_tlc-settings') ) {

            $id = $post_id === 'options' ? 'option' : $post_id;
            $course_ids = get_field('thinkific_course_id', $id);
			$course_ids = explode(',', $course_ids);
			$course_ids = array_map('trim', $course_ids);
			$course_ids = is_array($course_ids) ? $course_ids : array($course_ids);

            if(!empty($course_ids)) {
				foreach($course_ids as $course_id) {
                	new TLC_Thinkific_Import_Course($course_id);
				}
            }

            update_field('thinkific_import_course', 0, 'option');
            update_field('thinkific_course_id', '', 'option');

        }

    }

	public function courses_featured_query($args, $field, $post_id) {

		$args['meta_query'] = array(
			'relation' => 'AND',
			array(
				'relation' => 'OR',
				array(
					'key'     => 'thinkific_is_private',
					'compare' => 'NOT EXISTS',
				),
				array(
					'key'     => 'thinkific_is_private',
					'value'   => '0',
					'compare' => '=',
				),
				array(
					'key'     => 'thinkific_is_private',
					'value'   => '',
					'compare' => '=',
				),
			),
		);

		return $args;

	}

	public function courses_featured_result($text, $post, $field, $post_id) {

		$_product = new TLC_Product($post_id);

		if($_product->thinkific_is_private()) {
			$text .= ' (Private)';
		}

		return $text;

	}

}