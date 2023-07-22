<?php
/**
 * AVB Image
 *
 * @package advanced-video-banners/
 * @version 2.0
 */

$avb_banner_image = new AVB_Banner_Image($banner_data);
$avb_banner_image_classes = array();

if($banner->get_prop('image_mobile')) {
    $avb_banner_image_classes[] = 'hide-on-mobile';
}

if($banner->get_prop('has_coloured_circles')) {
    $avb_banner_image_classes[] = 'has-circles';
}
?>
<div class="avb-banner__medium image <?php echo join(' ', $avb_banner_image_classes); ?>">
    <img src="<?php echo $avb_banner_image->image(); ?>">
</div>