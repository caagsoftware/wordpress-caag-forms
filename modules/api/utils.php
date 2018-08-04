<?php

/*
 * Retrieves all caag_forms posts
 * @return Array / WP_POST
 */
function caag_forms_get_all_forms_posts()
{
	$args = array(
		'post_per_page'     =>  -1,
		'post_type'         =>  CAAG_CUSTOM_POST_TYPE
	);
	$query = new WP_Query( $args );
	return $query->posts;
}

/*
 * Retrieves caag_form by Id
 * @param int id
 * @return Array / WP_POST
 */
function caag_forms_get_form_by_post_id($id)
{
	return get_post($id);
}


/*
 * Retrieves a caag_forms count
 *
 * @return stdClass
 */
function get_caag_forms_count()
{
	return wp_count_posts(CAAG_FORMS_CUSTOM_POST_TYPE)->publish;
}

/*
 *Retrieves post id for a meta Id
 * @param int | meta Id
 * @return Array
 */
function caag_forms_get_form_by_caag_id($caag_id)
{
    $args = array(
        'post_type' =>  CAAG_FORMS_CUSTOM_POST_TYPE,
        'meta_query'    =>  array(
            array(
                'key'       =>  CAAG_FORMS_CAAG_ID,
                'value'     =>  $caag_id,
                'compare'   =>  '='
            )
        )
    );
    $query = new WP_Query( $args );
    return $query->posts[0];
}

/*
 * Save Plugin Settings
 * return void
 */
function save_caag_forms_settings($settings)
{
	update_option(CAAG_FORMS_TENANT_TOKEN,$settings[CAAG_FORMS_TENANT_TOKEN]);
	update_option(CAAG_FORMS_USER_TOKEN,$settings[CAAG_FORMS_USER_TOKEN]);
    update_option(CAAG_FORMS_USER_API_BASE_URL,$settings[CAAG_FORMS_USER_API_BASE_URL]);
}


/*
 * Retrieves the Tenant Token from Settings
 * @return string
 */
function caag_form_get_tenant_token()
{
	return get_option(CAAG_FORMS_TENANT_TOKEN, '');
}

/*
 * Retrieves the User Token from Settings
 * @return string
 */
function caag_forms_get_user_token()
{
	return get_option(CAAG_FORMS_USER_TOKEN);
}


/*
 * Checks if the Caag Forms Exists
 * @param int | caag id
 * @return boolean
 */
function caag_forms_form_exists($caag_id)
{
	return !empty(caag_forms_get_form_by_caag_id($caag_id));
}

/*
 * Option Introduce by the User
 * 
 */
function caag_forms_get_caag_user_settings()
{
	$settings = array(
		CAAG_FORMS_USER_TOKEN    => get_option(CAAG_FORMS_USER_TOKEN),
		CAAG_FORMS_TENANT_TOKEN  => get_option(CAAG_FORMS_TENANT_TOKEN)
	);
	return $settings;
}
