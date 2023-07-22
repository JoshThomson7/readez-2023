<?php
/**
 * TLC Resources
 *
 * Class in charge of speakers
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class TLC_Resources {

    public function init() {

        add_action('wp_ajax_nopriv_tlc_resources_filters', array($this, 'tlc_resources_filters'));
        add_action('wp_ajax_tlc_resources_filters', array($this, 'tlc_resources_filters'));

    }

    /**
     * WP_Query
     * 
     * @param array $custom_args
     */
    public function get_resources($custom_args = array()) {

        $posts = array();

        $default_args = array(
            'post_type' => 'product',
			'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
			'fields' => 'ids',
			'tax_query'   => array(
				'relation' => 'AND',
				array(
					'taxonomy'  => 'product_visibility',
					'terms'     => array('exclude-from-catalog'),
					'field'     => 'name',
					'operator'  => 'NOT IN',
				),
				array(
					'taxonomy'  => 'product_cat',
					'terms'     => array('thinkific'),
					'field'     => 'name',
					'operator'  => 'NOT IN',
				)
			)
        );

        $args = wp_parse_args($custom_args, $default_args);

        $posts = new WP_Query($args);
        return array(
			'posts' => $posts->posts,
			'max_num_pages' => $posts->max_num_pages
		);

    }

    /**
     * Filter Speakers.
     *
     * @since	1.0
     */
    public function tlc_resources_filters() {

        // Security check.
        wp_verify_nonce('$C.cGLu/1zxq%.KH}PjIKK|2_7WDN`x[vdhtF5GS4|+6%$wvG)2xZgJcWv3H2K_M', 'ajax_security');

        $form_data = isset($_POST['formData']) && !empty($_POST['formData']) ? $_POST['formData'] : null;
		$wpQueryArgs = isset($_POST['wpQueryArgs']) && !empty($_POST['wpQueryArgs']) ? $_POST['wpQueryArgs'] : array();

		$product_year = array();
		$option_cats = get_field('tlc_filter_terms', 'option') ?? array();
		$product_cats = $option_cats;

        $args = array();
		$args['posts_per_page'] = 18;

        if($form_data) {
            $product_year = isset($form_data['product_year']) && !empty($form_data['product_year']) ? $form_data['product_year'] : array();
            $product_cats = isset($form_data['product_cat']) && !empty($form_data['product_cat']) ? $form_data['product_cat'] : $product_cats;
        }

		$paged = isset($wpQueryArgs['paged']) && !empty($wpQueryArgs['paged']) ? $wpQueryArgs['paged'] : 1;

		if($paged > 1) {
			$args['paged'] = $paged;
		}

		$args['tax_query'][] = array(
			'taxonomy' => 'product_cat',
			'terms' => $product_cats,
			'field' => 'term_id',
			'operator' => 'IN'
		);

		if(!empty($product_year)) {
			$args['tax_query'][] = array(
				'taxonomy' => 'product_year',
				'terms' => $product_year,
				'field' => 'term_id',
				'operator' => 'IN'
			);
		}

		$pagination = true;
        $resources = $this->get_resources($args);
        
        include TLC_PATH .'templates/resources/resources-loop.php';

        wp_die();

    }

    /**
     * Add speaker fields to searchables
     */
    public function wvl_speaker_searchables($searchables, $post_type) {

        if($post_type === 'speaker') {
            $searchables['taxonomies'] = array('speaker_theme', 'speaker_travels_from');
            $searchables['meta'] = array('speaker_role');
        }

        return $searchables;

    }

}

