<?php
/**
 * TLC Public
 *
 * Class in charge of TLC Public facing side
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class TLC_Public {

    public function __construct() {

        add_action('wp_enqueue_scripts', array($this, 'enqueue'));
        add_action('body_class', array($this, 'body_classes'), 20);

        add_filter('page_template', array($this, 'pages'));
        //add_filter('single_template', array($this, 'singles'));

		add_action('before_footer', array($this, 'after_single_product'));

		add_action('template_redirect', array($this, 'redirects'));

		//add_filter('query_vars', 'tlc_query_vars');

    }

    public function enqueue() {

        wp_enqueue_script(TLC_SLUG.'-js', TLC_URL.'assets/js/tlc.min.js');

        // JS vars
        wp_localize_script(TLC_SLUG.'-js', TLC_SLUG.'_ajax_object', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'ajax_nonce' => wp_create_nonce('$C.cGLu/1zxq%.KH}PjIKK|2_7WDN`x[vdhtF5GS4|+6%$wvG)2xZgJcWv3H2K_M'),
            'siteUrl' => site_url('travel-money'),
            'jsPath' => TLC_URL.'assets/js/',
            'cssPath' => TLC_URL.'assets/css/',
            'imgPath' => TLC_URL.'assets/img/'
        ));

        // Styles
        wp_enqueue_style(TLC_SLUG, TLC_URL.'assets/css/tlc.min.css');

    }

    /**
	 * Returns body CSS class names.
	 *
	 * @since 1.0
     * @param array $classes
	 */
    public function body_classes($classes) {
        global $post;

        if(is_page()) {

            if($post->post_parent) {
                   
                $ancestors = get_post_ancestors( $post->ID );
                $ancestors = array_reverse($ancestors);
                   
                if ( !isset( $parents ) ) $parents = null;

                foreach($ancestors as $ancestor_id) {

                    $post_name = get_post_field('post_name', $ancestor_id);
                    $classes[] = TLC_SLUG.'-'.$post_name;

                }

            }

            $current_post_name = get_post_field('post_name', $post->ID);
            $classes[] = TLC_SLUG.'-'.$current_post_name;

        } elseif(is_singular(FL1_Helpers::registered_post_types(TLC_SLUG))) {

            $post_type = $post->post_type;
            $classes[] = TLC_SLUG.'-single-'.$post_type;

        }

        return $classes;
    }

    /**
     * page_template filter function
     * 
     * @param string $template
     */
    public function pages($template) {
    
        if(is_page(array('courses'))) {
            $template = TLC_PATH . 'templates/courses/courses.php';
        }

        if(is_page(array('online-courses'))) {
            $template = TLC_PATH . 'templates/courses/courses-online.php';
        }

        if(is_page(array('live-courses'))) {
            $template = TLC_PATH . 'templates/courses/courses-events.php';
        }

        if(is_page(array('resources'))) {
            $template = TLC_PATH . 'templates/resources/resources.php';
        }
    
        return $template;
    
    }

    /**
     * After single product content
     * 
     * @param string $template
     */
    public function after_single_product() {

		if(is_product()) {
			include TLC_PATH . 'templates/resources/resources-related.php';
		}
    
    }

	/**
	 * Redirects
	 */
	public function redirects() {

		if(is_product_category()) {
			$term_id = get_queried_object_id();
			wp_redirect(TLC_Helpers::resources_url().'?product_cat='.$term_id);
			exit;
			
		}

		if(is_tax('product_year')) {
			$term_id = get_queried_object_id();
			wp_redirect(TLC_Helpers::resources_url().'?product_year='.$term_id);
			exit;
		}

		if(is_tax('product_thinkific_cat')) {
			$term_id = get_queried_object_id();
			wp_redirect(TLC_Helpers::courses_online_url().'?tlc_thinkific_cat='.$term_id);
			exit;
		}

		if(is_tax('event-categories')) {
			$term_id = get_queried_object_id();
			wp_redirect(TLC_Helpers::courses_events_url().'?tlc_thinkific_cat='.$term_id);
			exit;
		}

		// Thinkific redirect
		$goto = FL1_Helpers::param('goto');
		$user_id = FL1_Helpers::param('user_id');  
		$nonce = FL1_Helpers::param('_wpnonce');

		if($goto && $user_id && is_numeric($user_id) && wp_verify_nonce($nonce, 'tlc_thinkific_sso')) {
			$redirect_url = TLC_Helpers::thinkific_sso_url($goto, $user_id);
			wp_redirect($redirect_url);
			exit;
		}

	}

	/**
	 * Register custom query vars
	 *
	 * @param array $vars The array of available query variables
	 * 
	 * @link https://codex.wordpress.org/Plugin_API/Filter_Reference/query_vars
	 */
	public function tlc_query_vars( $vars ) {
		$vars[] = 'goto';
		return $vars;
	}

}

