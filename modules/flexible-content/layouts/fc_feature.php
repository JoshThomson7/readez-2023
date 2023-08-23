<?php
/**
 * Feature
 */
$attachment_id = get_sub_field('feature_image');
$feature_text_width = '';
$feature_image_gif = get_sub_field('feature_image_gif');
$image_size = 'full';

if($attachment_id) {
    $crop = get_sub_field('feature_image_crop') ?? false;
    $feature_img = vt_resize($attachment_id,'' , 800, 800, $crop);

    // Feature image align
    $feature_img_align = '';
    if(get_sub_field('feature_image_align') == 'right') {
        $feature_img_align = ' right';
    }

    // Feature text width
    $feature_text_width = get_sub_field('feature_text_width').'%';

	$expand = get_sub_field('feature_image_expand') ?? false;
}

if($feature_image_gif){
    $feature_img = get_sub_field('feature_image');
}

?>

<div class="fc_feature_wrapper<?php echo $feature_img_align.($expand ? ' fc-feature-img-expand' : ''); ?>">
    <?php if($attachment_id): ?>
        <div class="feature__image">
            <?php if($attachment_id && !$feature_image_gif): ?>
				<?php if($expand): ?>
					<div class="feature__image-expand" style="background-image: url(<?php echo $feature_img['url']; ?>);"></div>
				<?php endif; ?>
				<img src="<?php echo $feature_img['url']; ?>" />
            <?php endif; ?>

            <?php if($feature_image_gif): ?>
				<?php if($expand): ?>
					<div class="feature__image-expand" style="background-image: url(<?php echo $feature_img['url']; ?>);"></div>
				<?php endif; ?>
				<?php echo wp_get_attachment_image($feature_img, $image_size); ?>
            <?php endif; ?>
        </div><!-- feature__image -->
    <?php endif; ?>

    <div class="feature__text" style="width: <?php echo $feature_text_width; ?>">
        <?php if(get_sub_field('feature_top_heading')): ?><h5><?php the_sub_field('feature_top_heading') ?></h5><?php endif; ?>
        <?php if(get_sub_field('feature_heading')): ?><h3><?php the_sub_field('feature_heading') ?></h3><?php endif; ?>
        <?php the_sub_field('feature_text'); ?>

        <?php if(get_sub_field('feature_link_text') && get_sub_field('feature_link_url')): ?>
            <div class="feature__action">
                <a href="<?php the_sub_field('feature_link_url'); ?>" class="button primary">
                    <span><?php the_sub_field('feature_link_text'); ?></span>
                </a>
            </div><!-- feature__action -->
        <?php endif; ?>
    </div><!-- feature__text -->
</div><!-- fc_feature_wrapper -->
