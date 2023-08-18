<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * WooCommerce Checkout
 *
 * @package modules/woocommerce
 * @version 1.0
*/
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <title><?php wp_title( '-', true, 'right' ); ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />

    <?php
        wp_head();
        global $woocommerce, $current_user, $post;
    ?>
</head>

<body <?php body_class(); ?>>
    <header class="header">

        <div class="header__main">

            <div class="max__width">

                <div class="header__main--left">

                    <a href="#nav_mobile" class="burger__menu"><span></span><span></span><span></span></a>

                    <!-- <div class="logo">
                        <a href="<?php echo esc_url(home_url()); ?>" title="<?php bloginfo('name'); ?>">
                            <img src="<?php echo esc_url(get_stylesheet_directory_uri().'/img/logo.png'); ?>" alt="<?php bloginfo('name'); ?>"/>
                        </a>
                    </div> -->
                    <!-- logo -->

                </div><!-- left -->

                <div class="header__main--right">
                    <div class="wc__cart">
                        <ul>
                            <li>
                                <a href="<?php echo wc_get_cart_url(); ?>" class="button primary border small icon-left">
                                    <i class="fa-regular fa-chevron-left"></i>
                                    <span>Back to basket</span>
                                </a>
                            </li>

                            <?php if(!is_wc_endpoint_url('order-received')): ?>
                                <li class="wc__header__cart">
                                    <a href="<?php echo wc_get_cart_url(); ?>" class="button primary border small icon-left">
                                        <i class="fa-regular fa-basket-shopping-simple"></i>
                                        <span>x<?php echo $woocommerce->cart->cart_contents_count; ?> - <?php echo $woocommerce->cart->get_cart_total(); ?></span>
                                    </a>
                                </li><!-- wc__header__cart -->
                            <?php endif; ?>

                            <?php if(is_wc_endpoint_url('order-received') && is_user_logged_in()): ?>
                                <li>
                                    <a href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')); ?>" class="button primary small icon-right">
                                        <span>Portal</span>
                                        <i class="fa fa-chevron-right"></i>
                                    </a>
                                </li><!-- wc__header__cart -->
                            <?php endif; ?>
                        </ul>
                    </div><!-- wc__cart -->
                </div><!-- right -->

            </div><!-- max__width -->
        </div><!-- header__main -->
    </header><!-- header -->

    <div class="wc__wrapper">
        <?php echo do_shortcode('[woocommerce_checkout]') ?>

        <div class="footer__checkout">
            <ul>
                <li>&copy;<?php echo date("Y"); ?> <?php bloginfo('name') ?> and its affiliate companies.</li>
                <?php if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'): ?>
                    <li class="secure"><i class="far fa-lock"></i> Your connection is secure</li>
                <?php endif; ?>
            </ul>
        </div><!-- footer__checkout -->
    </div>

    <?php wp_footer(); ?>
</body>
</html>