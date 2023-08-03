<?php
/**
 * Front page
 */

get_header();

AVB::avb_banners();

global $post;

FC_Helpers::flexible_content();

get_footer(); ?>
