<?php
/**
 * FL1 Blog Public
 *
 * Class in charge of FW Public facing side
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class FL1_Blog_Public {

    public function __construct() {

        define('FL1_BLOG_PAGE_ID', get_field('blog_page_id', 'option'));

        add_action('wp_enqueue_scripts', array($this, 'enqueue'));

        // Templates
        add_filter('page_template', array($this, 'blog_templates'));
        add_filter('single_template', array($this, 'blog_single_template' ));
        add_filter('category_template', array($this, 'blog_category_template'));
        add_filter('archive_template', array($this, 'blog_category_template'));

        add_filter('avb_banners_path', array($this, 'avb_banners_path'), 10, 2);

    }

    public function enqueue() {

        if(is_page(array(FL1_BLOG_PAGE_ID, 'blog', 'news')) || is_single() || is_category() || is_archive() || is_tag()) {
            wp_enqueue_style('fl1-blog', FL1_BLOG_URL . 'assets/css/fl1-blog.min.css', array(), FL1_BLOG_VERSION);
        }
    
    }

    public function blog_templates($page_template) {
        global $post;

        if(is_page(FL1_BLOG_PAGE_ID)) {
            $page_template = FL1_BLOG_PATH . 'templates/blog.php';
        }

        return $page_template;

    }
    
    public function blog_single_template($single_template) {
        global $post;

        if ($post->post_type === 'post') {
            $single_template = FL1_BLOG_PATH . 'templates/blog-single.php';
        }

        return $single_template;
    }

    public function blog_category_template( $archive_template ) {

        $archive_template = FL1_BLOG_PATH . 'templates/blog-archive.php';

        return $archive_template;
    }

	public function avb_banners_path($path, $post_id) {

		if(is_page(FL1_BLOG_PAGE_ID)) {
			$path = FL1_BLOG_PATH . 'templates/blog-featured.php';
		}

		if(get_post_type($post_id) === 'post') {
			$path = FL1_BLOG_PATH . 'templates/blog-single-title.php';
		}

		if(is_category() || is_tag()) {
			$path = FL1_BLOG_PATH . 'templates/blog-archive-title.php';
		}

		return $path;

	}

}

