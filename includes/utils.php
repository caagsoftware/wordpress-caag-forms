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

/*
 * Save Plugin Settings
 */
function save_caag_forms_settings($settings)
{
	update_option(CAAG_FORMS_TENANT_TOKEN,$settings[CAAG_FORMS_TENANT_TOKEN]);
	update_option(CAAG_FORMS_USER_TOKEN,$settings[CAAG_FORMS_USER_TOKEN]);
	//wp_redirect('option.php');
	//exit;
}
add_action('save_caag_forms_settings','save_caag_forms_settings');
function check_setting_save($settings)
{
	$tenant = get_option(CAAG_FORMS_TENANT_TOKEN);
	$user = get_option(CAAG_FORMS_USER_TOKEN);
	if($tenant == $settings[CAAG_FORMS_TENANT_TOKEN] and $user == $settings[CAAG_FORMS_USER_TOKEN]){
		return true;
	}else{
		return false;
	}
}
add_action('check_setting_save','check_setting_save');