<?php
/*
 * Register Caag Form Shortcode
 * @param Array
 * return string | html
 */
add_shortcode('caag_form','caag_form');
function caag_form($atts = [])
{
	$post = get_caag_form_by_meta($atts['id'])[0];
	if(isset(get_post_meta($post->post_id, CAAG_FORMS_LINK)[0])) {
		$link   = get_post_meta( $post->post_id, CAAG_FORMS_LINK )[0];
		$output = '';
		$output .= '<div>
                <iframe src="' . $link . '">
                </iframe>
            </div>';

		return $output;
	}
}

