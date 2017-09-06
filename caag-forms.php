<?php
/**
 * @package caag-forms
 * @version 0.2
 */
/*
Plugin Name: Caag Forms
Description: Use this plugin to easily add Caag Software Forms
Author: Miguel Faggioni
Version: 0.2
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


require_once 'includes/setup.php';
require_once 'includes/utils.php';
require_once 'includes/metaboxes.php';
require_once 'includes/options.php';
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
		caag_forms_scripts_registration();
	}

}
register_activation_hook(__FILE__,'caag_forms_install');


function caag_forms_deactivate()
{
	//Nothing To Do
}
register_deactivation_hook(__FILE__,'caag_forms_deactivate');

/*
 * Registering All Js Files
 */
function caag_forms_scripts_registration()
{
	
}
add_action('caag_forms_scripts_registration','caag_forms_scripts_registration');


/*
 *
 *
 *OPTION FILE CODE
 *
 *
 *
 */

/*
* Hooking Function to Create Setting Submenu
*/

add_action('admin_menu', 'caag_forms_setting_menu');
function caag_forms_setting_menu()
{
	add_options_page(
		'Caag Forms Settings',
		'Caag Forms',
		'manage_options',
		'caag',
		'caag_form_menu_setting_html'
	);
}

function caag_form_menu_setting_html()
{
	$settings = get_caag_user_settings();
	?>
	<?php if(isset($success)): ?>
	<div class="message updated"><p><?php echo $success; ?></p>
	</div>
<?php endif; ?>
	<div class="wrap">
		<div id="wrap">
			<h1>Caag Software Authentication Access</h1>
			<form action="" method="post">
				<table class="form-table">
					<tbody>
						<tr>
							<th><label class="wp-heading-inline" id="title" for="title">Tenant Token</label></th>
							<td> <input type="text" name="<?php echo CAAG_FORMS_TENANT_TOKEN; ?>" size="30" value="<?php echo $settings[CAAG_FORMS_TENANT_TOKEN]; ?>" id="title" spellcheck="true" autocomplete="off"></td>
						</tr>
						<tr>
							<th><label class="wp-heading-inline" id="title-prompt-text" for="title">User Token</label></th>
							<td><input type="text" name="<?php echo CAAG_FORMS_USER_TOKEN; ?>" size="30" value="<?php echo $settings[CAAG_FORMS_USER_TOKEN]; ?>" id="title" spellcheck="true" autocomplete="off"></td>
						</tr>
					</tbody>
				</table>
				<?php wp_nonce_field( CAAG_FORMS_NONCE, 'caag_nonce' ); ?>
				<input type="submit" name="publish" id="publish" class="button button-primary button-large" value="Save">
			</form>
		</div>
	</div>
	<?php

	if(!empty($_POST) and wp_verify_nonce($_POST['caag_nonce'], CAAG_FORMS_NONCE)){
		save_caag_forms_settings($_POST);
		if(check_setting_save($_POST)){
			$success = __('Settings were successfully saved!');
		}else{
			$error = __('It was an Error Proccessing the Information. Please Try Again!!!');
		};
	}
	?>
	<?php if(isset($success)): ?>
	<div class="message updated"><p><?php echo $success; ?></p>
	</div>
	<script>
		document.getElementById("wrap").remove();
	</script>
<?php endif; ?>

	<?php if(isset($error)): ?>
		<div class="message updated"><p><?php echo $error; ?></p>
		</div>
	<?php endif; ?>
	<?php

}

function add_caag_forms_setting_options()
{
	add_option(CAAG_FORMS_TENANT_TOKEN,'');
	add_option(CAAG_FORMS_USER_TOKEN,'');
}
add_action('add_caag_forms_setting_options','add_caag_forms_setting_options');

function get_caag_user_settings()
{
	$settings = array(
		CAAG_FORMS_USER_TOKEN    => get_option(CAAG_FORMS_USER_TOKEN),
		CAAG_FORMS_TENANT_TOKEN  => get_option(CAAG_FORMS_TENANT_TOKEN)
	);
	return $settings;
}
add_action('get_caag_user_settings','get_caag_user_settings');