<?php
/**
 * Advanced Video Banners
 * Class in charge of initialising everything AVB
 * 
 * @package AVB
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class AVB {

    public function __construct() {

        $this->define_constants();
        
        add_filter(FL1_SLUG.'_load_dependencies', array($this, 'load_dependencies'));

    }

    /**
     * Setup constants.
     *
     * @access private
     * @since 1.0
     * @return void
     */
    private function define_constants() {

        define('AVB_VERSION', '2.0');
        define('AVB_SLUG', 'avb');
        define('AVB_PATH', FL1_PATH.'/modules/advanced-video-banners/');
        define('AVB_URL', FL1_URL.'/modules/advanced-video-banners/');

    }
    
    /**
     * Loads all dependencies.
     *
     * @access private
     * @since 1.0
     * @return void
     */
    public function load_dependencies($deps) {

        // Core
        $deps[] = AVB_PATH. 'functions/class-avb-banner.php';

        // Banners
        $deps[] = AVB_PATH. 'functions/class-avb-banner-image.php';
        $deps[] = AVB_PATH. 'functions/class-avb-banner-youtube.php';
        $deps[] = AVB_PATH. 'functions/class-avb-banner-vimeo.php';
        $deps[] = AVB_PATH. 'functions/class-avb-banner-html-video.php';

        return $deps;
        
    }

    /**
     * avb_banners()
     *
     * @param bool $type
    */
    public static function avb_banners($excludes = array()) {

        $display_banners = true;

        if(!empty($excludes)) {
            foreach($excludes as $type => $exclude) {
                if(is_array($exclude)) {
                    foreach($exclude as $ex) {
                        if($type($ex)) {
                            $display_banners = false;
                        }
                    }
                } else {
                    if($type($exclude)) {
                        $display_banners = false;
                    }
                }
            }
        }

        if(is_404()) {
            $display_banners = false;
        }

		$path = AVB_PATH.'templates/avb.php';

        if($display_banners) {
			global $post;
            include apply_filters('avb_banners_path', $path, $post->ID);
        }

    }

}

// Release the Kraken!
$avb = new AVB();
