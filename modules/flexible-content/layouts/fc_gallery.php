<?php
/*
------------------------------------------------
   ______      ____
  / ____/___ _/ / /__  _______  __
 / / __/ __ `/ / / _ \/ ___/ / / /
/ /_/ / /_/ / / /  __/ /  / /_/ /
\____/\__,_/_/_/\___/_/   \__, /
                         /____/
------------------------------------------------
Gallery
*/

$max_width = '';
if(get_sub_field('max_width')) {
    $max_width = 'style="max-width:'.get_sub_field('max_width').'%";';
}

// items per row
$items = get_sub_field('items_per_row');

$carousel = '';
if(get_sub_field('gallery_carousel')) { 
    $carousel = ' gallery__carousel';
}

// captions
$gallery_captions = get_sub_field('gallery_captions');

// Images
$images = get_sub_field('gallery');
if($images):
?>
    <ul class="gallery__images<?php echo $carousel; ?>">
        <?php
            foreach($images as $image):
            $attachment_id = $image['ID'];
            $gallery_img = vt_resize($attachment_id,'' , 800, 600, true);
            $gallery_img_org = vt_resize($attachment_id,'' , 1200, 1200, false);

            $image_caption = wp_get_attachment_caption($attachment_id);
        ?>
            <li data-src="<?php echo $gallery_img_org['url']; ?>" class="<?php echo $items; ?>">
                <a href="#" title=""><img src="<?php echo $gallery_img['url']; ?>" /></a>
                <?php if($image_caption): ?>
                    <p style="text-align: center; margin-top: 10px;"><?php echo $image_caption; ?></p>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul><!-- gallery__images -->
<?php endif; ?>
