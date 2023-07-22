<?php
/**
 * FC Public
 *
 * Class in charge of FC Public facing side
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class FC_Public {

    public function __construct() {

        add_action('wp_enqueue_scripts', array($this, 'enqueue'));
        add_action('body_class', array($this, 'body_classes'), 20);
        add_action('fc_tab_scroller', array($this, 'fc_tab_scroller'));

    }

    public function enqueue() {

        //wp_enqueue_script(FC_SLUG.'-js', FC_URL.'assets/js/FC.min.js');

        // JS vars
        // wp_localize_script(FC_SLUG.'-js', FC_SLUG.'_ajax_object', array(
        //     'ajax_url' => admin_url( 'admin-ajax.php' ),
        //     'ajax_nonce' => wp_create_nonce('$C.cGLu/1zxq%.KH}PjIKK|2_7WDN`x[vdhtF5GS4|+6%$wvG)2xZgJcWv3H2K_M'),
        //     'siteUrl' => site_url('travel-money'),
        //     'jsPath' => FC_URL.'assets/js/',
        //     'cssPath' => FC_URL.'assets/css/',
        //     'imgPath' => FC_URL.'assets/img/'
        // ));

        // // Styles
        // wp_enqueue_style(FC_SLUG, FC_URL.'assets/css/flexible-content.min.css');

    }

    /**
	 * Returns body CSS class names.
	 *
	 * @since 1.0
     * @param array $classes
	 */
    public function body_classes($classes) {
        global $post;
    
        if(have_rows('fc_content_types', $post->ID)) { 
            $classes[] = 'page-has-flexible-content';

            if(get_field('fc_wave_separator')) {
                $classes[] = 'page-has-wave-separator';
            }
        }
        
        return $classes;
    }

    /**
	 * Outputs the tab scroller.
	 */
    public function fc_tab_scroller($post_id) {
    
        if(get_field('fc_tab_scroller', $post_id)) {
            $type = get_field('fc_menu_type', $post_id);
            if($type === 'fc_sections' && have_rows('fc_content_types', $post_id)) { 
                include FC_PATH.'layouts/fc-tab-scrollbar.php';
            }

            if($type === 'child_pages' || $type === 'parent_child_pages') { 
                include FC_PATH.'layouts/fc-tab-child-pages.php';
            }
        }
        
    }

}

