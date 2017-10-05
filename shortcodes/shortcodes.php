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
	caag_forms_styles();
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

/*
 * Load Plugin Js Files
 * return void
 */
function caag_forms_scripts()
{
	wp_register_script('caag-iframe-resize', plugins_url(CAAG_PLUGIN_FOLDER.'/js/iframeResizer.min.js'), array( 'jquery' ));
	wp_register_script('caag-iframe-resize', plugins_url(CAAG_PLUGIN_FOLDER.'/js/iframeResizer.contentWindow.min.js'));
	wp_register_script('caag-iframe-init', plugins_url(CAAG_PLUGIN_FOLDER.'/js/caagResize.js'), array( 'jquery' ));
	wp_enqueue_script('caag-iframe-resize');
	wp_enqueue_script('caag-iframe-resize');
	wp_enqueue_script('caag-iframe-init');
}
add_action('wp_enqueue_script','caag_forms_scripts');

/*
 * Register Styles
 */
function caag_forms_styles()
{
	wp_register_style('caag-iframe-style',plugins_url(CAAG_PLUGIN_FOLDER.'/css/caag.css'),array());
	wp_enqueue_style('caag-iframe-style');
}
add_action('caag_forms_styles','caag_forms_styles');