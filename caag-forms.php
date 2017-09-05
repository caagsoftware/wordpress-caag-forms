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
define('CAAG_FORMS_ROOT', __FILE__);
define('CAAG_CUSTOM_POST_TYPE','caag_form');
define('CAAG_FORMS_SLUG','caag-forms');

/*
 * Special Plugin Attributtes
 */
define('CAAG_FORMS_LINK','caag_form_link');
define('CAAG_FORMS_SHORTCODE','caag_shortcode');
define('CAAG_FORMS_TENANT_TOKEN','caag_tenant_token');
define('CAAG_FORMS_USER_TOKEN','caag_user_token');
define('CAAG_FORMS_NONCE', plugin_basename(__FILE__));
define('CAAG_FORMS_CAAG_ID','caag_id');
define('CAAG_FORMS_CATEGORY','caag_form_category');
define('CAAG_FORMS_TITLE','caag_form_title');

require_once 'includes/setup.php';
require_once 'includes/utils.php';
require_once 'includes/metaboxes.php';
require_once 'shortcodes/shortcodes.php';
include 'includes/options.php';
require_once 'includes/HttpClient.php';
/*
 * Install Plugin
 */
function caag_forms_install()
{
	if(!post_type_exists(CAAG_CUSTOM_POST_TYPE) and is_admin()){
		register_caag_forms_custom_post_type();
		add_caag_forms_setting_options();
		save_caag_forms_init();
	}

}
register_activation_hook(__FILE__,'caag_forms_install');


function caag_forms_deactivate()
{
	//Nothing To Do
}
register_deactivation_hook(__FILE__,'caag_forms_deactivate');

/*
 * Load JS
 */
function caag_forms_scripts()
{
	wp_enqueue_scripts('iframe-resize',CAAG_FORMS_ROOT.'/js/iframeResizer.min.js', false);
	wp_enqueue_scripts('iframe-resize-ie8',CAAG_FORMS_ROOT.'/js/iframe-ie8.polyfils.min.js', false);
	wp_enqueue_scripts('iframe-resize-windos',CAAG_FORMS_ROOT.'/js/iframeResizer.contentWindow.min', false);
	wp_enqueue_scripts('iframe-init',CAAG_FORMS_ROOT.'/js/iframe.js',false);
}
add_action('caag_forms_scripts','caag_forms_scripts');


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
				<div id="titlewrap">
					<label class="wp-heading-inline" id="title" for="title">Tenant Token</label>
					<input type="text" name="<?php echo CAAG_FORMS_TENANT_TOKEN; ?>" size="30" value="<?php echo $settings[CAAG_FORMS_TENANT_TOKEN]; ?>" id="title" spellcheck="true" autocomplete="off">
				</div>
				<div id="titlewrap">
					<label class="wp-heading-inline" id="title-prompt-text" for="title">User Token</label>
					<input type="text" name="<?php echo CAAG_FORMS_USER_TOKEN; ?>" size="30" value="<?php echo $settings[CAAG_FORMS_USER_TOKEN]; ?>" id="title" spellcheck="true" autocomplete="off">
				</div>
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

