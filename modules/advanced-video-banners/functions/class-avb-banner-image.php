<?php
/**
 * AVB Banner Image
 * Class in charge of the Image type banner
 * 
 * @package AVB
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class AVB_Banner_Image extends AVB_Banner {

	public function crop() {

		return $this->get_prop('image_crop');

	}

    public function image($return = 'url', $width = 800, $height = 800, $crop = true) {

        $image_id = $this->get_prop('image');
        $image_url = '';
        if($image_id) { 
            $image_url = vt_resize($image_id, '', $width, $height, $this->crop());
            return isset($image_url[$return]) ? $image_url[$return] : null;
        }

        return null;

    }

}
