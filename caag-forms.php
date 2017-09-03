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
define('CAAG_FORMS_ROOT',__DIR__);
define('CAAG_CUSTOM_POST_TYPE','caag_form');
define('CAAG_FORMS_SLUG','caag-forms');

require_once 'includes/setup.php';

function caag_forms_install()
{
	register_caag_forms_custom_post_type();
}
register_activation_hook(__FILE__,'caag_forms_install');

