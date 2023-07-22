<?php
/**
 * TLC Courses
 *
 * Class in charge of Events/Courses action and hook overrides
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class TLC_Courses {

    public function init() {

        add_filter('avb_banners_path', array($this, 'avb_banners_path'), 10, 2);
		add_filter('single_template', array($this, 'singles'));

		add_action('wp_ajax_nopriv_tlc_courses_online_filters', array($this, 'tlc_courses_online_filters'));
        add_action('wp_ajax_tlc_courses_online_filters', array($this, 'tlc_courses_online_filters'));

		add_action('wp_ajax_nopriv_tlc_events_filters', array($this, 'tlc_events_filters'));
        add_action('wp_ajax_tlc_events_filters', array($this, 'tlc_events_filters'));
        
    }

	/**
     * WP_Query
     * 
     * @param array $custom_args
     */
    public static function get_events($custom_args = array()) {

        $posts = array();

		$now = new DateTime('now', wp_timezone());

        $default_args = array(
            'post_type' => 'event',
			'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'meta_value',
			'meta_key' => '_event_start_date',
            'order' => 'ASC',
			'fields' => 'ids',
			'meta_query' => array(
				array(
					'key' => '_event_end_date',
					'value' => $now->format('Y-m-d'),
					'compare' => '>=',
					'type' => 'DATE'
				),
			),
        );

        $args = wp_parse_args($custom_args, $default_args);

        $posts = new WP_Query($args);
        return $posts->posts;

    }

	/**
     * WP_Query
     * 
     * @param array $custom_args
     */
    public static function get_thinkific_courses($custom_args = array()) {

        $posts = array();

        $default_args = array(
            'post_type' => 'product',
			'posts_per_page' => -1,
			'orderby' => 'title',
			'order' => 'ASC',
			'fields' => 'ids',
			'tax_query' => array(
				'relation' => 'AND',
				array(
					'taxonomy'  => 'product_visibility',
					'terms'     => array('exclude-from-catalog'),
					'field'     => 'name',
					'operator'  => 'NOT IN',
				),
				array(
					'taxonomy' => 'product_cat',
					'field' => 'slug',
					'terms' => 'thinkific',
				),
			),
			'meta_query' => array(
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
			)
        );

        $args = wp_parse_args($custom_args, $default_args);

        $get_posts = new WP_Query($args);
		$posts = new stdClass();
        $posts->posts = $get_posts->posts;
		$posts->max_num_pages = $get_posts->max_num_pages;

		return $posts;

    }

	/**
	 * AVB Banner Path
	 */
	public function avb_banners_path($path, $post_id) {

		if(get_post_type($post_id) === 'event') {
			$path = TLC_PATH . 'templates/courses/events/single-event-hero.php';
		}

		return $path;

	}

	/**
     * page_template filter function
     * 
     * @param string $template
     */
    public function singles($template) {
    
        global $post;

        if($post->post_type === 'event') {
            $template = TLC_PATH . 'templates/courses/events/single-event.php';
        }

        return $template;
    
    }

	/**
     * Filter Thinkific Online Courses.
     *
     * @since	1.0
     */
    public function tlc_courses_online_filters() {

        // Security check.
        wp_verify_nonce('$C.cGLu/1zxq%.KH}PjIKK|2_7WDN`x[vdhtF5GS4|+6%$wvG)2xZgJcWv3H2K_M', 'ajax_security');

        $form_data = isset($_POST['formData']) && !empty($_POST['formData']) ? $_POST['formData'] : null;
		$wpQueryArgs = isset($_POST['wpQueryArgs']) && !empty($_POST['wpQueryArgs']) ? $_POST['wpQueryArgs'] : array();
		$paged = isset($wpQueryArgs['paged']) && !empty($wpQueryArgs['paged']) ? $wpQueryArgs['paged'] : 1;

        $args = array();
		$args['posts_per_page'] = 18;
		$args['paged'] = $paged;

		$thinkific_cat_id = isset($form_data['tlc_thinkific_cat']) && !empty($form_data['tlc_thinkific_cat']) ? $form_data['tlc_thinkific_cat'] : null;

		if($thinkific_cat_id) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'product_thinkific_cat',
					'field' => 'id',
					'terms' => $thinkific_cat_id,
				),
			);
		}

		$pagination = true;
        $thinkific_courses = self::get_thinkific_courses($args);
        
        include TLC_PATH .'templates/courses/online/online-loop.php';

        wp_die();

    }

	/**
     * Filter Thinkific Online Courses.
     *
     * @since	1.0
     */
    public function tlc_events_filters() {

        // Security check.
        wp_verify_nonce('$C.cGLu/1zxq%.KH}PjIKK|2_7WDN`x[vdhtF5GS4|+6%$wvG)2xZgJcWv3H2K_M', 'ajax_security');

        $form_data = isset($_POST['formData']) && !empty($_POST['formData']) ? $_POST['formData'] : null;
		$wpQueryArgs = isset($_POST['wpQueryArgs']) && !empty($_POST['wpQueryArgs']) ? $_POST['wpQueryArgs'] : array();
		$paged = isset($wpQueryArgs['paged']) && !empty($wpQueryArgs['paged']) ? $wpQueryArgs['paged'] : 1;

        $args = array();
		$args['posts_per_page'] = 18;
		$args['paged'] = $paged;

		$event_cat_id = isset($form_data['tlc_thinkific_cat']) && !empty($form_data['tlc_thinkific_cat']) ? $form_data['tlc_thinkific_cat'] : null;

		if($event_cat_id) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'event-categories',
					'field' => 'id',
					'terms' => $event_cat_id,
				),
			);
		}

		$date_range = isset($form_data['tlc_event_date_range']) && !empty($form_data['tlc_event_date_range']) ? $form_data['tlc_event_date_range'] : array();

		if($date_range) {
			if(strpos($date_range, ' to ')) {
				$dates = explode(' to ', $date_range);
				$start = $dates[0];
				$end = $dates[1];
			} else {
				$start = $date_range;
				$end = $date_range;
			}

			$args['meta_query'] = array(
				array(
					'key' => '_event_start_date',
					'value' => $end,
					'compare' => '<=',
					'type' => 'DATE'
				),
				array(
					'key' => '_event_end_date',
					'value' => $start,
					'compare' => '>=',
					'type' => 'DATE'
				),
			);
		}

		$events = self::get_events($args);
        
        include TLC_PATH .'templates/courses/events/events-loop.php';

        wp_die();

    }
    
}

