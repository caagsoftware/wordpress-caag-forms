<?php

function get_caag_forms()
{
	$args = array(
		'numberposts'   =>  -1,
		'post_type'     =>  CAAG_CUSTOM_POST_TYPE
	);
	return get_posts($args);
}
add_action('get_caag_forms','get_caag_forms');

function get_caag_form($id)
{
	return get_post($id);
}
add_action('get_caag_form','get_caag_form');

function get_caag_forms_count()
{
	return wp_count_posts(CAAG_CUSTOM_POST_TYPE)->publish;
}
add_action('get_caag_form_count','get_caag_form_count');

/*
 *
 */
function get_caag_form_by_meta($meta_id)
{
	global $wpdb;
	$meta = $wpdb->get_results('SELECT post_id FROM '.$wpdb->prefix.'postmeta WHERE meta_value = '.$meta_id.' and meta_key = "'.CAAG_FORMS_ID.'"');
	return $meta;
}
add_action('get_caag_form_by_meta','get_caag_form_by_meta');