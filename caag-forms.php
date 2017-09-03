<?php
/**
 * @package caag-forms
 * @version 0.1
 */
/*
Plugin Name: Caag Forms
Description: Use this plugin to easily add Caag Software Forms
Author: Miguel Faggioni
Version: 0.1
Author URI: https://www.caagsoftware.com/
*/

define('CAAG_FORMS_VERSION','0.1');
define('CAAG_FORMS_ROOT', __FILE__);
define('CAAG_CUSTOM_POST_TYPE','caag_form');
define('CAAG_FORMS_SLUG','caag-forms');

/*
 * Special Attributtes
 */
define('CAAG_FORMS_LINK','caag_form_link');
define('CAAG_FORMS_ID','caag_form_id');
define('CAAG_FORMS_SHORTCODE','caag_shortcode');

require_once 'includes/setup.php';
require_once 'includes/utils.php';
require_once 'includes/metaboxes.php';
require_once 'shortcodes/shortcodes.php';
/*
 * Install Plugin
 */
function caag_forms_install()
{
	if(!post_type_exists(CAAG_CUSTOM_POST_TYPE) and is_admin()){
		register_caag_forms_custom_post_type();
	}

}
register_activation_hook(__FILE__,'caag_forms_install');


function caag_forms_deactivate()
{
	//Nothing To Do
}
register_deactivation_hook(__FILE__,'caag_forms_deactivate');


