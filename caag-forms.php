<?php
/**
 * @package caag-forms
 * @version 1.0
 */
/*
Plugin Name: Caag Forms
Description: Use this plugin to easily add Caag Software Forms
Author: Miguel Faggioni
Version: 1.0
Author URI: https://www.caagsoftware.com/
*/

/*
 * Global Attributtes
 */
define('CAAG_FORMS_VERSION','0.1');
define('CAAG_FORMS_ROOT', __DIR__);
define('CAAG_FORMS_ROOT_FILE', __FILE__);
define('CAAG_CUSTOM_POST_TYPE','caag_form');
define('CAAG_FORMS_SLUG','caag-forms');
define('CAAG_PLUGIN_FOLDER','caag-forms');
define('CAAG_API_ROUTE','https://api.caagcrm.com/api/sheets?limit=1000&filters=[{"type":"boolean","column":"allowed_for_public_view","value":"1"}]');
/*
 * Special Plugin Attributtes
 */
define('CAAG_FORMS_TENANT_TOKEN','caag_tenant_token');
define('CAAG_FORMS_USER_TOKEN','caag_user_token');
define('CAAG_FORMS_NONCE', plugin_basename(__FILE__));
/*
 * MetaKeys
 */
define('CAAG_FORMS_CAAG_ID','caag_id');
define('CAAG_FORMS_CATEGORY','caag_form_category');
define('CAAG_FORMS_TITLE','caag_form_title');
define('CAAG_FORMS_LINK','caag_form_link');
define('CAAG_FORMS_SHORTCODE','caag_shortcode');
/*
 * Columns Names in Index Table
 */
define('CAAG_FORMS_ID_COLUMN_NAME','Identifier');
define('CAAG_FORMS_TITLE_COLUMN_NAME','Title');
define('CAAG_FORMS_CATEGORY_COLUMN_NAME','Category');
define('CAAG_FORMS_LINK_COLUMN_NAME','Link');
define('CAAG_FORMS_SHORTCODE_COLUMN_NAME','Shortcode');

/*
 * Require Plugin Files
 */
require_once 'includes/setup.php';
require_once 'includes/utils.php';
require_once 'includes/metaboxes.php';
require_once 'includes/settings.php';
require_once 'includes/HttpClient.php';
require_once 'shortcodes/shortcodes.php';

/*
 * Install Plugin
 */
function caag_forms_install()
{
	if(!post_type_exists(CAAG_CUSTOM_POST_TYPE) and is_admin()){
		register_caag_forms_custom_post_type();
		add_caag_forms_setting_options();
	}

}
register_activation_hook(__FILE__,'caag_forms_install');

/*
 * Deactivation Function
 */
function caag_forms_deactivate()
{
	//Nothing To Do
}
register_deactivation_hook(__FILE__,'caag_forms_deactivate');