<?php
/**
 * WooCommerce My Account Template
 *
 * @package modules/woocommerce
 * @version 1.0
*/

if(!defined('ABSPATH')) exit; // Exit if accessed directly

$user = new TLC_User(get_current_user_id());
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <title><?php wp_title( ' - ', true, 'right' ); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="format-detection" content="telephone=no">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

    <main class="venue">
        <header class="bar">
            <div class="header-left">
                <figure class="logo tooltip">
                    <a href="<?php echo esc_url(home_url()); ?>">
                        <img src="<?php echo esc_url(get_stylesheet_directory_uri().'/img/tlc-logo.svg'); ?>" alt="<?php bloginfo('name'); ?>"/>
                    </a>
                </figure>

                <h1><?php echo FL1_Woo_Helpers::get_my_account_endpoint_title(); ?></h1>
            </div>

            <div class="header-right">
                <ul>
                    <li>
                        <a href="<?php echo home_url(); ?>" class="link animate-icon tooltip" title="Back to main site" data-tooltipster='{"position": "bottom"}'>
                            <span>Main site</span>
                            <i class="fa fa-chevron-right"></i>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo wp_logout_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>" class="link animate-icon tooltip">
                            <span>Log out</span>
                            <i class="fa fa-arrow-right-from-bracket"></i>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url(wc_get_account_endpoint_url('edit-account')); ?>" class="user tooltip" title="<?php echo $user->get_full_name(); ?>">
                            <span><?php echo $user->get_initials(); ?></span>
                        </a>
                    </li>
                </ul>
            </div>
        </header>

        <div class="floor">
            <aside class="stage">
                <ul>
                    <?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
                        <li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
                            <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>" class="tooltip" title="<?php echo $label; ?>" data-tooltipster='{"position": "right"}'>
                                <i class="fa-light fa-fw <?php echo FL1_Woo_Helpers::get_my_account_endpoint_icon($label); ?>"></i>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </aside>

            <div class="pit">
                <?php do_action('woocommerce_account_content'); ?>
            </div>
        </div>
    </main>

<?php wp_footer(); ?>
</body>
</html>