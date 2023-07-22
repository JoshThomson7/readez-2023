<?php
/**
 * FC Helpers
 *
 * Helper static methods for the FC module.
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class FC_Helpers {
    
    public static function flexible_content($fc_id = null) {

        $current_user_id = get_current_user_id();

        do_action('fl1_before_flexible_content');

        if(have_rows('fc_content_types', $fc_id)) {

            $row_count = 1;
            $wave = get_field('fc_wave_separator', $fc_id);
            $theme = get_field('theme_colour', $fc_id);
            $has_wave = $wave ? 'has-wave' : '';

            if(!$fc_id) {
                echo '<div class="flexible__content">';
            }

            while(have_rows('fc_content_types', $fc_id)) {
                the_row();

                // open section - see fc-functions.php
                $open = FC_Helpers::fc_field_section(get_row_layout(), 'open', $row_count);

                if(!$open['skip_open']) {
                    echo get_row_layout() === 'fc_global' ? '' : $open['html'];
                }

                if(file_exists(FC_PATH . 'layouts/'.get_row_layout().'.php')) {
                    require FC_PATH . 'layouts/'.get_row_layout().'.php';
                }

                // close section - see fc-functions.php
                $close = FC_Helpers::fc_field_section(get_row_layout(), 'close', $row_count);

                if(!$close['skip_close']) {
                    echo get_row_layout() === 'fc_global' ? '' : $close['html'];
                }

                $row_count++; 
            }

            if(!$fc_id) {
                echo '</div><!-- flexible__content -->';
            }
        }

        do_action('fl1_after_flexible_content');
    }

    public static function fc_field_section($row_layout, $open_close, $row_count) {

        $fc_classes = array('fc-layout', $row_layout);
        $layout_container_classes = array('fc-layout-container');

        // section heading
        $options = get_sub_field('fc_options');
        $option_heading_logo = $options['heading_logo'];
        $option_top_heading = $options['top_heading'];
        $option_heading = $options['heading'];
        $option_heading_center = $options['heading_center'];
        $option_dots_separator = $options['dots_separator'];
        $option_caption = $options['caption'];
        $option_tab = $options['tab_name'];

        // generate section ID
        $tab_id = '';
        if($option_tab) {
            $tab_id = ' id="'.strtolower(preg_replace("#[^A-Za-z0-9]#", "", $option_tab)).'_section"';
        }

        // Styles
        $style = array();
        $fc_styles = get_sub_field('fc_styles');

        // Padding
        $padding = $fc_styles['fc_padding'];
        $padding_style = 'padding:';
        $padding_style .= !empty($padding['fc_padding_top']) ? ' '.$padding['fc_padding_top'].'px' : ' 0';
        $padding_style .= !empty($padding['fc_padding_right']) ? ' '.(($padding['fc_padding_right']*100)/1200).'%' : ' 0';
        $padding_style .= !empty($padding['fc_padding_bottom']) ? ' '.$padding['fc_padding_bottom'].'px' : ' 0';
        $padding_style .= !empty($padding['fc_padding_left']) ? ' '.(($padding['fc_padding_left']*100)/1200).'%' : ' 0';

        $style[] = $padding_style;

        // Background class
        $ellipse_divider = $fc_styles['fc_ellipse_divider'];
        $ellipse_divider_classes = array('fc-layout-divider');

        $fc_classes = join(' ', $fc_classes);
        $layout_container_classes = join(' ', $layout_container_classes);

        // full width
        $full_width = $fc_styles['fc_full_width'] == true ? true : false;

        // open/close
        $html = '';
        if($open_close === 'open') {

            if($row_layout === 'fc_carousel_open') {

                $html .= '<div class="fc-layout-carousel '.$fc_classes.'">';

            } else {

                $html .= '<section'.$tab_id.' class="'.$fc_classes.'">';

                if($ellipse_divider) {

                    $offset_top = $fc_styles['fc_ellipse_divider_offset_top'] ? 'offset-'.$fc_styles['fc_ellipse_divider_offset_top'] : '';
                    $offset_bottom = $fc_styles['fc_ellipse_divider_offset_bottom'] ? 'offset-'.$fc_styles['fc_ellipse_divider_offset_bottom'] : '';

                    switch ($ellipse_divider) {
                        case 'bottom':
                            $html .='<div class="fc-layout-divider ellipse-bottom '.$offset_bottom.'"></div>';
                            break;
            
                        case 'top-bottom':
                            $html .='<div class="fc-layout-divider ellipse-top '.$offset_top.'"></div>';
                            $html .='<div class="fc-layout-divider ellipse-bottom '.$offset_bottom.'"></div>';
                            break;
                        
                        default: // top
                            $html .='<div class="fc-layout-divider ellipse-top '.$offset_top.'"></div>';
                            break;
                    }

                }
            

                $html .='<div class="'.$layout_container_classes.'" style="'.$padding_style.'">';

                // check if full with
                if(!$full_width) {
                    $html .='<div class="max__width">';
                }

                if($option_heading_logo || $option_top_heading || $option_heading || $option_caption) {
                    $centre_heading = '';
                    if($option_heading_center) {
                        $centre_heading = ' centred';
                    }

                    $section_heading_logo = '';
                    if($option_heading_logo) {
                        $heading_logo = array();
                        $image_url = wp_get_attachment_image_src($option_heading_logo, 'full');

                        // if extension is svg
                        if($image_url && pathinfo($image_url[0], PATHINFO_EXTENSION) === 'svg') {
                            $heading_logo['url'] = $image_url[0];
                        } else {
                            $heading_logo = vt_resize($option_heading_logo, '', 800, 330, false);
                        }
                        
                        $section_heading_logo = '<div class="fc-heading-logo"><img src="'.$heading_logo['url'].'" /></div>';
                    }

                    $section_top_heading = '';
                    if($option_top_heading) {
                        $section_top_heading = '<h5>'.$option_top_heading.'</h5>';
                    }

                    $section_heading = '';
                    if($option_heading) {
                        $section_heading = '<h2>'.$option_heading.'</h2>';
                    }

                    $section_caption = '';
                    if($option_caption) {
                        $section_caption = $option_caption;
                    }

                    $section_dots_separator = '';
                    if($option_dots_separator) {
                        $section_dots_separator = '<div class="fc-dots-separator"><span class="quaternary"></span><span class="tertiary"></span><span class="secondary"></span><span class="primary"></span></div>';
                    }

                    $html .= '<div class="fc-layout-heading'.$centre_heading.'">';
                    $html .= '<div class="fc-layout-heading-left">'.$section_heading_logo.$section_top_heading.$section_dots_separator.$section_heading.$section_caption.'</div>';
                    $html .= '<div class="fc-layout-heading-right"></div>';
                    $html .= '</div>';
                }
            }


        } elseif($open_close === 'close') {

            if($row_layout === 'fc_carousel_close') {

                $html .= '</div>';

            } else {

                // check if full with
                if(!$full_width) {
                    $html .= '</div><!-- max__width -->';
                    $html .='</div><!-- fc-layout-container -->';
                    $html .= '</section><!-- '.$row_layout.' -->';
                } else {
                    $html .= '</div><!-- fc-layout-container -->';
                    $html .= '</section><!-- '.$row_layout.' -->';
                }

            }

        }

        switch ($row_layout) {
            case 'fc_carousel_open':
            case 'fc_wrapper_open':
                $skip_close = true;
                $skip_open = false;
                break;

            case 'fc_carousel_close':
            case 'fc_wrapper_close':
                $skip_close = false;
                $skip_open = true;
                break;
            
            default:
                $skip_close = false;
                $skip_open = false;
                break;
        }

        return array(
            'html' => $html,
            'skip_open' => $skip_open,
            'skip_close' => $skip_close,
        );
    }

}