<?php

/*
 * Retrieves all caag_forms posts
 * @return Array / WP_POST
 */
function get_caag_forms()
{
	$args = array(
		'numberposts'   =>  -1,
		'post_type'     =>  CAAG_CUSTOM_POST_TYPE
	);
	return get_posts($args);
}
add_action('get_caag_forms','get_caag_forms');


/*
 * Retrieves caag_form by Id
 * @param int id
 * @return Array / WP_POST
 */
function get_caag_form($id)
{
	return get_post($id);
}
add_action('get_caag_form','get_caag_form');

/*
 * Retrieves a caag_forms count
 *
 * @return stdClass
 */
function get_caag_forms_count()
{
	return wp_count_posts(CAAG_CUSTOM_POST_TYPE)->publish;
}
add_action('get_caag_form_count','get_caag_form_count');

/*
 *Retrieves post id for a meta Id
 * @param int | meta Id
 * @return Array
 */
function get_caag_form_by_meta($meta_id)
{
	global $wpdb;
	$meta = $wpdb->get_results('SELECT post_id FROM '.$wpdb->prefix.'postmeta WHERE meta_value = '.$meta_id.' and meta_key = "'.CAAG_FORMS_ID.'"');
	return $meta;
}
add_action('get_caag_form_by_meta','get_caag_form_by_meta');