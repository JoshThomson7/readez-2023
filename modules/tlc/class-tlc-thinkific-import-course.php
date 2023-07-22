<?php
/**
 * TLC_Thinkific_Import_Course
 *
 * Class in charge of importing Thinkific courses
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

final class TLC_Thinkific_Import_Course {

    private $course_id = 0;
    private $course_data = array();
    private $api;
    private $debug = false;

    public function __construct($course_id, $debug = false) {

        $this->course_id = $course_id;
        $this->debug = $debug;

        // Bail early if no course ID
        if(!$this->course_id) { return;}

        $this->api = new TLC_Thinkific_API();

        if($this->debug) {
            FL1_Helpers::pretty_print($this->format_data());
            die();
        }

        $this->save();

    }

    /**
     * Saves the course data to the database
     */
    public function save() {

        $data = $this->format_data() ?? new stdClass();

        if($data->id && class_exists('WC_Product_Simple')) {

            $product_id = TLC_Helpers::get_product_from_thinkific_id($data->id);

            $product = new WC_Product_Simple($product_id);
            $product->set_name($data->name);
            $product->set_slug($data->slug);
            $product->set_status($this->handle_status($data->slug));
            $product->set_catalog_visibility($this->handle_visibility($data->hidden));
            $product->set_description($data->description);
            $product->set_regular_price($data->price);

            // Meta
            $product->update_meta_data('thinkific_course_id', $data->id);
            $product->update_meta_data('thinkific_product_id', $data->product_id);
            $product->update_meta_data('thinkific_is_private', $data->private);
            $product->update_meta_data('thinkific_course_enrollment_duration', $data->enrolment_duration);
            $product->update_meta_data('thinkific_chapters', json_encode($data->chapters));

            $id = $product->save();

            if($id) {
				wp_remove_object_terms($id, 'uncategorized', 'product_cat');
				wp_set_object_terms($id, 'Thinkific', 'product_cat', true);

				if(!empty($data->categories)) {
					foreach($data->categories as $category) {
						wp_set_object_terms($id, $category, 'product_thinkific_cat', true);
					}
				}

                $attachment_id = FL1_Helpers::wp_insert_attachment_from_url($data->image, $id);
                if($attachment_id) {
                    $product->set_image_id($attachment_id);
                    $product->save();
                }
            }

        }

    }

    /**
     * Format data.
     * @return array
     */
    public function format_data() {

        $this->course_data = $this->api->crud('courses/'.$this->course_id) ?? array();

        if(!is_wp_error($this->course_data) && $this->course_data->product_id) {

            $this->course_data->image = $this->course_data->course_card_image_url;
            
            $product = $this->api->crud('products/'.$this->course_data->product_id);

            if(!is_wp_error($product)) {
                $this->course_data->private = $product->private;
                $this->course_data->hidden = $product->hidden;
                $this->course_data->status = $product->status;
                $this->course_data->price = $product->price;
                $this->course_data->enrolment_duration = $product->days_until_expiry;
				$this->course_data->categories = array();
			
				if(!empty($product->collection_ids)) {

					foreach($product->collection_ids as $category_id) {
						$category = $this->api->crud('collections/'.$category_id);
						if(!isset($category->error) && isset($category->name)) {
							$this->course_data->categories[] = $category->name;
						}
					}

				}
            }

            if($this->course_data->chapter_ids && is_array($this->course_data->chapter_ids)) {

                foreach($this->course_data->chapter_ids as $chapters_id) {
                
                    $chapters = $this->api->crud('chapters/'.$chapters_id.'/');

                    if(!is_wp_error($chapters)) {
                
                        if($chapters->content_ids && is_array($chapters->content_ids)) {
                
                            $chapters_contents = $this->api->crud('chapters/'.$chapters_id.'/contents/');

                            if(!is_wp_error($chapters_contents) && $chapters_contents->items && is_array($chapters_contents->items) && (count($chapters_contents->items) == count($chapters->content_ids) )) {
                                $chapters->lessons = array_combine($chapters->content_ids, $chapters_contents->items);
                                unset($chapters->content_ids);
                            }

                        }

                        $this->course_data->chapters[] = $chapters;

                    }

                }

            }

        }

        return $this->clean_up($this->course_data);

    }

    /**
     * Handle the status of the product
     * @param string $status
     */
    private function handle_status($status = 'published') {

        switch($status) {
            case 'draft':
                return 'draft';
                break;
            case 'published':
                return 'publish';
                break;
            default:
                return 'publish';
            break;
        }

    }

    /**
     * Handle visibility
     * @param bool $hidden
     */
    private function handle_visibility($hidden) {

        if($hidden) {
            return 'hidden';
        }

        return 'visible';

    }

    /**
     * Clean up the data
     * @param object $data
     */
    private function clean_up($data) {

        $clean_up = array(
            'subtitle',
            'intro_video_youtube',
            'contact_information',
            'keywords',
            'duration',
            'user_id',
            'banner_image_url',
            'course_card_image_url',
            'intro_video_wistia_identifier',
            'administrator_user_ids',
            'instructor_id',
            'course_card_text',
            'reviews_enabled',
            'chapter_ids'
        );

        foreach($clean_up as $key) {
            unset($data->$key);
        }

        return $data;

    }

}