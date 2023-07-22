<?php
/**
 * TLC Init
 *
 * Class in charge of initialising everything TLC
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class TLC {

    public function __construct() {

        $this->define_constants();

        add_filter(FL1_SLUG.'_load_dependencies', array($this, 'load_dependencies'));
        add_action(FL1_SLUG.'_init', array($this, 'init'));
        add_action(FL1_SLUG.'_setup_theme',	array($this, 'setup_theme'));
        add_action(FL1_SLUG.'_acf_init', array($this, 'acf_init'));
		add_filter(FL1_SLUG.'_body_classes', array($this, 'body_classes'));

    }

    /**
     * Setup constants.
     *
     * @access private
     * @since 1.0
     * @return void
     */
    private function define_constants() {

        define('TLC_VERSION', '1.0');
        define('TLC_PLUGIN_FOLDER', 'tlc');
        define('TLC_SLUG', 'tlc');
        define('TLC_PATH', FL1_PATH.'/modules/'.TLC_PLUGIN_FOLDER.'/');
        define('TLC_URL', FL1_URL.'/modules/'.TLC_PLUGIN_FOLDER.'/');
        define('TLC_THINKIFIC_API_URL', 'https://api.thinkific.com/api/public/v1/');
        define('TLC_REST_API_NAMESPACE', TLC_SLUG);
        define('TLC_REST_API_URL', esc_url(home_url()).'/wp-json/'.TLC_REST_API_NAMESPACE.'/');

    }
    
    /**
     * Loads all dependencies.
     *
     * @access public
     * @since 1.0
     * @return void
     */
    public function load_dependencies($deps) {

        $deps[] = TLC_PATH. 'class-tlc-cpt.php';
        $deps[] = TLC_PATH. 'class-tlc-helpers.php';
        $deps[] = TLC_PATH. 'class-tlc-admin.php';
        $deps[] = TLC_PATH. 'class-tlc-public.php';
        $deps[] = TLC_PATH. 'class-tlc-thinkific-api.php';
        $deps[] = TLC_PATH. 'class-tlc-thinkific-import-course.php';
        $deps[] = TLC_PATH. 'class-tlc-user.php';
        $deps[] = TLC_PATH. 'class-tlc-product.php';
        $deps[] = TLC_PATH. 'class-tlc-resources.php';
        $deps[] = TLC_PATH. 'class-tlc-wc-checkout.php';
        $deps[] = TLC_PATH. 'class-tlc-wc-my-account.php';
        $deps[] = TLC_PATH. 'class-tlc-team.php';
        $deps[] = TLC_PATH. 'class-tlc-testimonial.php';
        $deps[] = TLC_PATH. 'class-tlc-courses.php';
        $deps[] = TLC_PATH. 'class-tlc-events.php';
        $deps[] = TLC_PATH. 'class-tlc-event.php';

        return $deps;

    }

    public function init() {

        new TLC_Admin();
        new TLC_Public();

		$resources = new TLC_Resources();
        $resources->init();

		$resources = new TLC_Events();
        $resources->init();
        
    }

    public function setup_theme() {

        new TLC_CPT();
        new TLC_WC_Checkout();
        new TLC_WC_My_Account();
        
		$courses = new TLC_Courses();
		$courses->init();

	}

    /**
	 * Remove duplicate sub page
	 *
	 * @since 1.0
	 */
	public function acf_init() {

        if(function_exists('acf_add_options_sub_page')) {
        
            acf_add_options_sub_page(array(
                'page_title'  => 'Settings',
                'menu_title'  => 'Settings',
                'menu_slug' => 'tlc-settings',
                'parent_slug' => TLC_SLUG,
            ));

        }

    }

	/**
	 * Returns body CSS class names.
	 *
	 * @since 1.0
     * @param array $classes
	 */
    public function body_classes($classes) {
        global $post;

        $theme_colour = get_field('theme_colour', $post->ID);
        
        if($theme_colour) {
            $classes[] = 'tlc-theme-'.$theme_colour;
        }

        
        return $classes;
    }

}

// Release the Kraken!
$tlc = new TLC();