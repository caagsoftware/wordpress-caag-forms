<?php
/*
 * Register Caag Form Shortcode
 * @param Array
 * return string | html
 */
require_once CAAG_FORMS_ROOT_FILE;

add_shortcode('caag_form','caag_form');
function caag_form($atts = [])
{
	caag_forms_scripts();
	$post = get_caag_form_by_meta($atts['id'])[0];
	if(isset(get_post_meta($post->post_id, CAAG_FORMS_LINK)[0])) {
		$link   = get_post_meta( $post->post_id, CAAG_FORMS_LINK )[0];
		$output = '';
		$output .= '<div> 
                <iframe id="caag_iframe" src="' . $link . '">
                </iframe>
            </div>';
		return $output;
	}
}

/*
 * Load Plugin Js Files
 * return void
 */
function caag_forms_scripts()
{
	wp_register_script('caag-iframe-resize', plugins_url(CAAG_PLUGIN_FOLDER.'/js/iframeResizer.min.js'), array( 'jquery' ), false, true);
	wp_register_script('caag-iframe-resize', plugins_url(CAAG_PLUGIN_FOLDER.'/js/iframeResizer.contentWindow.min.js'), array( 'jquery' ), false, true);
	wp_register_script('caag-iframe-init', plugins_url(CAAG_PLUGIN_FOLDER.'/js/caagResize.js'), array( 'jquery' ), '0.1', true);
	wp_enqueue_script('caag-iframe-resize');
	wp_enqueue_script('caag-iframe-resize');
	wp_enqueue_script('caag-iframe-init');
}
add_action('wp_enqueue_script','caag_forms_scripts');