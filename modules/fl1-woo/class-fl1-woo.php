<?php
/**
 * FL1 Woo
 *
 * Class in charge of initialising everything FL1 Woo
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class FL1_Woo {

    public function __construct() {

        $this->define_constants();

        add_filter(FL1_SLUG.'_load_dependencies', array($this, 'load_dependencies'));
        add_action(FL1_SLUG.'_init', array($this, 'init'));
        add_action(FL1_SLUG.'_setup_theme',	array($this, 'setup_theme'));

    }

    /**
     * Setup constants.
     *
     * @access private
     * @since 1.0
     * @return void
     */
    private function define_constants() {

        define('FL1_WOO_VERSION', '1.0');
        define('FL1_WOO_PLUGIN_FOLDER', 'fl1-woo');
        define('FL1_WOO_SLUG', 'fl1_woo');
        define('FL1_WOO_PATH', FL1_PATH.'/modules/'.FL1_WOO_PLUGIN_FOLDER.'/');
        define('FL1_WOO_URL', FL1_URL.'/modules/'.FL1_WOO_PLUGIN_FOLDER.'/');

    }
    
    /**
     * Loads all dependencies.
     *
     * @access public
     * @since 1.0
     * @return void
     */
    public function load_dependencies($deps) {

        $deps[] = FL1_WOO_PATH. 'class-fl1-woo-helpers.php';
        $deps[] = FL1_WOO_PATH. 'class-fl1-woo-public.php';
        $deps[] = FL1_WOO_PATH. 'class-fl1-woo-user.php';
        $deps[] = FL1_WOO_PATH. 'class-fl1-woo-cart.php';
        $deps[] = FL1_WOO_PATH. 'class-fl1-woo-checkout.php';
        $deps[] = FL1_WOO_PATH. 'class-fl1-woo-register.php';
        $deps[] = FL1_WOO_PATH. 'class-fl1-woo-account.php';
        // $deps[] = FL1_WOO_PATH. 'class-fl1-woo-single-product.php';

        return $deps;

    }

    public function init() {

        new FL1_Woo_Public();
        new FL1_Woo_Cart();
        new FL1_Woo_Checkout();
        new FL1_Woo_Register();
        new FL1_Woo_Account();
        // new FL1_Woo_Single_Product();
        
    }

    public function setup_theme() {

        add_theme_support('woocommerce');

    }

}

if(function_exists('is_woocommerce')) {
    new FL1_Woo();
}