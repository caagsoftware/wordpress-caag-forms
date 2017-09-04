<?php

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
	<div class="wrap">
		<div id="titlewrap">
			<label class="screen-reader-text" id="title-prompt-text" for="title">Enter title here</label>
			<input type="text" name="post_title" size="30" value="<?php echo $settings[CAAG_FORMS_TENANT_TOKEN]; ?>" id="title" spellcheck="true" autocomplete="off">
		</div>
		<div id="titlewrap">
			<label class="screen-reader-text" id="title-prompt-text" for="title">Enter title here</label>
			<input type="text" name="post_title" size="30" value="Registro 2" id="title" spellcheck="true" autocomplete="off">
		</div>
		<input type="submit" name="publish" id="publish" class="button button-primary button-large" value="Publish">
	</div>
	<?php

}
add_action('caag_form_menu_setting_html','caag_form_menu_setting_html');

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
