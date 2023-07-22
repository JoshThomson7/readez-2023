<?php
/**
 * TLC CPT
 *
 * Class in charge of registering TLC custom post types
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class TLC_CPT {

	private $post_types = array(
		'case_study',
		'course',
		'product',
		'team',
		'testimonial',
	);

    public function __construct() {

        foreach($this->post_types as $post_type) {
            $method = 'register_'.$post_type.'_cpt';

            if(method_exists($this, $method)) {
                $this->$method();
            }
        }

        add_action('admin_menu', array($this, 'menu_page'));
        add_action('admin_menu', array($this, 'remove_duplicate_subpage'));
        add_filter('parent_file', array($this, 'highlight_current_menu'));

    }

    function menu_page() {
        add_menu_page(
            __('TLC', TLC_SLUG),
            'The Literacy Co',
            'manage_options',
            TLC_SLUG,
            '',
            'dashicons-book',
            30
        );

        $submenu_pages = array(
            array(
                'page_title'  => 'Case Studies',
                'menu_title'  => 'Case Studies',
                'capability'  => 'manage_options',
                'menu_slug'   => 'edit.php?post_type=case-study',
                'function'    => null,
            ),
				array(
					'page_title'  => '',
					'menu_title'  => '&nbsp;- Categories',
					'capability'  => 'manage_options',
					'menu_slug'   => 'edit-tags.php?taxonomy=case-study_category&post_type=case-study',
					'function'    => null,
				),
            array(
                'page_title'  => 'Team',
                'menu_title'  => 'Team',
                'capability'  => 'manage_options',
                'menu_slug'   => 'edit.php?post_type=team',
                'function'    => null,
            ),
            array(
                'page_title'  => 'Testimonials',
                'menu_title'  => 'Testimonials',
                'capability'  => 'manage_options',
                'menu_slug'   => 'edit.php?post_type=testimonial',
                'function'    => null,
            ),
                array(
                    'page_title'  => '',
                    'menu_title'  => '&nbsp;- Categories',
                    'capability'  => 'manage_options',
                    'menu_slug'   => 'edit-tags.php?taxonomy=testimonial_category&post_type=testimonial',
                    'function'    => null,
                ),
        );

        foreach ( $submenu_pages as $submenu ) {

            add_submenu_page(
                TLC_SLUG,
                $submenu['page_title'],
                $submenu['menu_title'],
                $submenu['capability'],
                $submenu['menu_slug'],
                $submenu['function']
            );

        }
    }

    public function highlight_current_menu( $parent_file ) {

        global $submenu_file, $current_screen, $pagenow;

        $cpts = FL1_Helpers::registered_post_types(TLC_SLUG);

        # Set the submenu as active/current while anywhere APM
        if (in_array($current_screen->post_type, $cpts)) {

            if ( $pagenow == 'post.php' ) {
                $submenu_file = 'edit.php?post_type=' . $current_screen->post_type;
            }

            if ( $pagenow == 'edit-tags.php' ) {
                $submenu_file = 'edit-tags.php?taxonomy='.$current_screen->taxonomy.'&post_type=' . $current_screen->post_type;
            }

            $parent_file = TLC_SLUG;

        }

        return $parent_file;

    }

    /**
     * Team CPT
     */
    private function register_case_study_cpt() {

        // CPT
        $cpt = new FL1_CPT(
            array(
                'post_type_name' => 'case-study',
                'plural' => 'Case Studies',
                'menu_name' => 'Case Studies'
            ),
            array(
				'menu_position' => 21,
                'rewrite' => array( 'slug' => 'team', 'with_front' => true ),
                'publicly_queryable' => false,
                'generator' => TLC_SLUG
            )
        );

		// Taxonomies
        $cpt->register_taxonomy(
            array(
                'taxonomy_name' => 'case-study_category',
                'slug' => 'case-study_category',
                'singular' => 'Case Study Category',
                'plural' => 'Case Study Categories'
            )
        );
    }

    /**
     * Team CPT
     */
    private function register_team_cpt() {

        // CPT
        $cpt = new FL1_CPT(
            array(
                'post_type_name' => 'team',
                'plural' => 'Team',
                'menu_name' => 'Team'
            ),
            array(
				'menu_position' => 21,
                'rewrite' => array( 'slug' => 'team', 'with_front' => true ),
                'publicly_queryable' => false,
                'generator' => TLC_SLUG
            )
        );

		$cpt->columns(array(
            'cb' => '<input type="checkbox" />',
	        'picture' => __('Picture'),
            'title' => __('Name'),
            'job_title' => __('Job Title'),
            'contact' => __('Contact'),
        ));

		$cpt->populate_column('picture', function($column, $post) {

            $post_id = $post->ID;
            $team = new TLC_Team($post_id);

            if(get_post_thumbnail_id($post_id)) {
				echo '<a href="'.get_admin_url().'post.php?post='.$post_id.'&action=edit"><img src="'.$team->image(200, 200)['url'].'"" style="width: 80px; border-radius: 8px;" /></a>';

			} else {
				echo __( '<div class="dashicons dashicons-format-image" style="font-size:48px; height:48px; color:#e0e0e0;"></div>' );

			}
		});

		$cpt->populate_column('job_title', function($column, $post) {
            $post_id = $post->ID;
            $team = new TLC_Team($post_id);
			$job_title = $team->job_title();

            echo $job_title ? $job_title : '--';
		});

		$cpt->populate_column('contact', function($column, $post) {
            $post_id = $post->ID;
            $team = new TLC_Team($post_id);
			$phone = $team->phone();
			$email = $team->email();

            $contact = array();

			if($phone) {
				$contact[] = '&bull; '.$phone;
			}

			if($email) {
				$contact[] = '&bull; '.$email;
			}

			echo join('<br />', $contact);
		});

    }

    /**
     * Testimonials CPT
     */
    private function register_testimonial_cpt() {

        // CPT
        $cpt = new FL1_CPT(
            array(
                'post_type_name' => 'testimonial',
                'plural' => 'Testimonials',
                'menu_name' => 'Testimonials'
            ),
            array(
                'menu_position' => 21,
                'rewrite' => array( 'slug' => 'testimonial', 'with_front' => true ),
                'publicly_queryable' => false,
                'generator' => TLC_SLUG
            )
        );

        // Taxonomies
        $cpt->register_taxonomy(
            array(
                'taxonomy_name' => 'testimonial_category',
                'slug' => 'testimonial_category',
                'singular' => 'Testimonial Category',
                'plural' => 'Testimonial Categories'
            )
        );

        $cpt->columns(array(
            'cb' => '<input type="checkbox" />',
            'rating' => __('Rating'),
            'title' => __('Name'),
            'quote' => __('Quote'),
            'testimonial_category' => __('Categories'),
        ));

        $cpt->populate_column('rating', function($column, $post) {

            $post_id = $post->ID;
            $testimonial = new TLC_Testimonial($post_id);
            
            $testimonial->rating_display();
        
        });

        $cpt->populate_column('quote', function($column, $post) {

            $post_id = $post->ID;
            $testimonial = new TLC_Testimonial($post_id);
            
            echo $testimonial->quote(30);
        
        });

    }

	/**
     * Product CPT
     */
    private function register_product_cpt() {

        // CPT
        $cpt = new FL1_CPT('product');

        $cpt->register_taxonomy(
            array(
                'taxonomy_name' => 'product_year',
                'slug' => 'product_year',
                'singular' => 'Year',
                'plural' => 'Years'
            )
        );

        $cpt->register_taxonomy(
            array(
                'taxonomy_name' => 'product_themes',
                'slug' => 'product_themes',
                'singular' => 'Themes',
                'plural' => 'Themes'
            )
        );

		$cpt->register_taxonomy(
            array(
                'taxonomy_name' => 'product_thinkific_cat',
                'slug' => 'product_thinkific_cat',
                'singular' => 'Thinkific Category',
                'plural' => 'Thinkific Categories'
            )
        );

		$cpt->columns(array(
            'cb' => '<input type="checkbox" />',
            'thumb' => __('<span class="wc-image tips">Image</span>'),
            'name' => __('Name'),
			'price' => __('Price'),
            'product_cats' => __('Categories'),
            'thinkific_cats' => __('Thinkific Categories'),
            'thinkific_course_id' => __('Thinkific Course ID'),
            'private' => __('Private'),
            'date' => __('Date'),
        ));

        $cpt->populate_column('thinkific_cats', function($column, $post) {

            $post_id = $post->ID;
            $_product = new TLC_Product($post_id);
			$thinkific_cats = $_product->thinkific_categories();
            
			if(!empty($thinkific_cats)) {
				$terms = array();
				foreach($thinkific_cats as $term_id) {
					$term = get_term($term_id);
					$terms[] = '<a href="'.get_admin_url().'edit.php?post_type=product&product_thinkific_cat='.$term->slug.'">'.$term->name.'</a>';
				}
				echo join(', ', $terms);
			} else {
				echo '--';
			}
        
        });

        $cpt->populate_column('thinkific_course_id', function($column, $post) {

            $post_id = $post->ID;
            $_product = new TLC_Product($post_id);
			$thinkific_cats = $_product->thinkific_categories();
            
            if($_product->is_thinkific_product()) {
				echo '<a href="'.TLC_Helpers::thinkific_base_url().'/manage/courses/'.$_product->thinkific_course_id().'" target="_blank">'.$_product->thinkific_course_id().'</a>';
			} else {
				echo '--';
			}
        
        });

        $cpt->populate_column('private', function($column, $post) {

            $post_id = $post->ID;
            $_product = new TLC_Product($post_id);
            
            if($_product->thinkific_is_private()) {
				echo '<i class="dashicons-before dashicons-yes-alt"></i>';
			} else {
				echo '--';
			}
        
        });

		$cpt->populate_column('product_cats', function($column, $post) {

            $post_id = $post->ID;
            $_product = new TLC_Product($post_id);
            $_product_cats = $_product->get_category_ids();

			if(!empty($_product_cats)) {
				$terms = array();
				foreach($_product_cats as $term_id) {
					$term = get_term($term_id);
					$terms[] = '<a href="'.get_admin_url().'edit.php?post_type=product&product_cat='.$term->slug.'">'.$term->name.'</a>';
				}
				echo join(', ', $terms);
			} else {
				echo '--';
			}
        
        });

    }

    /**
	 * Remove duplicate sub page
	 *
	 * @since 1.0
	 */
	public function remove_duplicate_subpage() {
        remove_submenu_page(TLC_SLUG, TLC_SLUG);

		foreach($this->post_types as $post_type) {
			if($post_type === 'product') continue;
			$post_type = str_replace('_', '-', $post_type);
            remove_menu_page('edit.php?post_type='.$post_type);
        }
    }

}