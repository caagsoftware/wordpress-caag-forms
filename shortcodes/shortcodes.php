<?php
/*
 * Register Caag Form Shortcode
 * @param Array
 * return string | html
 */
add_shortcode('caag_form','caag_form');
function caag_form($atts = [])
{
	caag_forms_scripts();
	$post = get_caag_form_by_meta($atts['id'])[0];
	if(isset(get_post_meta($post->post_id, CAAG_FORMS_LINK)[0])) {
		$link   = get_post_meta( $post->post_id, CAAG_FORMS_LINK )[0];
		$output = '';
		$output .= '<div>
                <iframe style="width: 100%; height: 500px;" src="' . $link . '">
                </iframe>
            </div>';

		return $output;
	}
}