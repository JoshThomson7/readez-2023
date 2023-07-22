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

    <?php include get_stylesheet_directory().'/modules/mega-menu-mobile.php'; ?>

	<div id="page">
        <section class="header-banner">
            <header class="header">
                <?php if(get_field('sitewide_notice_enable', 'option')): ?>
                    <div class="header--site__wide__notice">
                        <div class="max__width">
                            <?php the_field('sitewide_notice', 'option'); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="header__main">
                    <div class="max__width">
                        <div class="header__main--left">
                            <a href="#nav_mobile" class="burger__menu">
                                <i class="fal fa-bars"></i>
                            </a>

                            <div class="logo">
                                <a href="<?php echo esc_url(home_url()); ?>" title="<?php bloginfo('name'); ?>">
                                    <img src="<?php echo esc_url(get_stylesheet_directory_uri().'/img/tlc-logo.svg'); ?>" alt="<?php bloginfo('name'); ?>"/>
                                </a>
                            </div>

                            <?php include get_stylesheet_directory().'/modules/mega-menu.php'; ?>
                        </div>

                        <div class="header__main--right">
                            <ul>
                                <li>
                                    <a href="<?php echo wc_get_cart_url(); ?>">
                                        <i class="fal fa-basket-shopping-simple"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="spotlight-open">
                                        <i class="fal fa-search"></i>
                                    </a>
                                </li>
                                <li>
                                    <?php
                                        // get woocommerce account url
                                        $acc_btn_text = 'Sign in';
                                        if(is_user_logged_in()) {
                                            $acc_btn_text = 'Portal';
                                        }
                                    ?>
                                    <a href="<?php echo FL1_Woo_Helpers::get_my_account_url(); ?>" class="button primary small icon-right">
                                        <span><?php echo $acc_btn_text; ?></span>
                                        <i class="fa fa-chevron-right"></i>
                                    </a>
                                </li>        
                            </ul>
                        </div>
                    </div><!-- max__width -->
                </div><!-- header__main -->
            </header><!-- header -->
            
            <?php
				AVB::avb_banners(array(
					'is_page' => 'dashboard',
					'is_page' => 'basket',
					'is_search' => '',
				));
            ?>
        </section><!-- header-banner -->