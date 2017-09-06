<?php


/*
* Hook Function to Create Setting Submenu
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
/*
 * Html for Setting Screen
 * @return void
 */
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
						<td> <input type="text" name="<?php echo CAAG_FORMS_TENANT_TOKEN; ?>" size="70" value="<?php echo $settings[CAAG_FORMS_TENANT_TOKEN]; ?>" id="title" spellcheck="true" autocomplete="off"></td>
					</tr>
					<tr>
						<th><label class="wp-heading-inline" id="title-prompt-text" for="title">User Token</label></th>
						<td><input type="text" name="<?php echo CAAG_FORMS_USER_TOKEN; ?>" size="70" value="<?php echo $settings[CAAG_FORMS_USER_TOKEN]; ?>" id="title" spellcheck="true" autocomplete="off"></td>
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
