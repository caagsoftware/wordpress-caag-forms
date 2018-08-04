<?php
/*
 * Register Caag Form Shortcode
 * @param Array
 * return string | html
 */

function caag_form_shortcode($atts = [])
{
    caag_forms_scripts();
	$post = get_caag_form_by_meta($atts['id'])[0];
	if(isset(get_post_meta($post->post_id, CAAG_FORMS_LINK)[0])) {
		$link   = get_post_meta( $post->post_id, CAAG_FORMS_LINK )[0];
		$output = '';
		$output .= '<div id="caag-form">
						<iframe id="caag-iframe" src="' . $link . '">
	                    </iframe>
                    </div>';
		return $output;
	}
}
add_shortcode('caag_form','caag_form_shortcode');
