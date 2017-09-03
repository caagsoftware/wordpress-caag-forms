<?php


/*
 * Add all MetaBoxes
 * @return void
 */
add_action('add_meta_boxes','caag_meta_boxes');
function caag_meta_boxes()
{
	add_meta_box(
		'caag_link_box',
		'Caag System Link',
		'caag_link_box_html',
		CAAG_CUSTOM_POST_TYPE,
		'normal'
	);
}


/*
 * Html Template for Form Link Metabox
 */
function caag_link_box_html()
{

	if(isset(get_post_meta(get_post()->ID, CAAG_FORMS_LINK)[0])){
		$link = get_post_meta(get_post()->ID, CAAG_FORMS_LINK)[0];
	}else{
		$link = '';
	}
	?>
	<div id="titlediv">
		<div id="titlewrap">
			<label class="screen-reader-text" id="title-prompt-text" for="title">Enter Link Here</label>
			<input type="text" name="<?php echo CAAG_FORMS_LINK; ?>" size="30" value="<?php echo $link; ?>" id="title" spellcheck="true" autocomplete="off" placeholder="Enter Link">
		</div>
	<?php
}


/*
 * Saving Post Meta Data
 */
function caag_form_save_post( $post_id )
{
	if ( get_post_type($post_id) != CAAG_CUSTOM_POST_TYPE ) return;
	if ( isset( $_POST[CAAG_FORMS_LINK] ) ) {
		update_post_meta( $post_id, CAAG_FORMS_LINK, $_POST[CAAG_FORMS_LINK] );
		update_post_meta( $post_id, CAAG_FORMS_ID, get_caag_forms_count() );
		update_post_meta( $post_id, CAAG_FORMS_SHORTCODE, '[caag_form id='.get_caag_forms_count().']' );
	}
}
add_action( 'save_post', 'caag_form_save_post');


/*
 * Add Meta Data columns to Post Table: Link
 * Only Header and Footer
 */
add_filter('manage_posts_columns', 'add_meta_column_link');
function add_meta_column_link($defaults) {
	$defaults[CAAG_FORMS_LINK] = 'Link';
	return $defaults;
}

/*
 * Displaying Actual Meta Data Values: Link
 */
add_action( 'manage_posts_custom_column' , 'fill_meta_column_link', 10, 2 );
function fill_meta_column_link($column_name, $post_id) {
	if ($column_name == CAAG_FORMS_LINK) {
		if(isset(get_post_meta($post_id, CAAG_FORMS_LINK)[0])){
			echo get_post_meta($post_id, CAAG_FORMS_LINK)[0];
		}else{
			echo '';
		}
	}
}

/*
 * Add Meta Data columns to Post Table: Shortcode
 * Only Header and Footer
 */
add_filter('manage_posts_columns', 'add_meta_column_shortcode');
function add_meta_column_shortcode($defaults) {
	$defaults[CAAG_FORMS_SHORTCODE] = 'Shortcode';
	return $defaults;
}

/*
 * Displaying Actual Meta Data Values: Shortcode
 */
add_action( 'manage_posts_custom_column' , 'fill_meta_column_shortcode', 10, 2 );
function fill_meta_column_shortcode($column_name, $post_id) {
	if ($column_name == CAAG_FORMS_SHORTCODE) {
		if(isset(get_post_meta($post_id, CAAG_FORMS_SHORTCODE)[0])){
			echo get_post_meta($post_id, CAAG_FORMS_SHORTCODE)[0];
		}else{
			echo '';
		}
	}
}

function on_all_status_transitions( $new_status, $old_status, $post ) {
	if ($old_status == 'pending' or $old_status = 'draft') {
		if($new_status == 'publish'){
			update_post_meta( $post->ID, CAAG_FORMS_SHORTCODE, '[caag_form id='.get_caag_forms_count().']' );
		}
	}
	if($new_status == 'pending' or $new_status = 'draft'){
		if($old_status == 'publish'){
			update_post_meta( $post->ID, CAAG_FORMS_SHORTCODE, '' );
		}
	}
}
add_action('transition_post_status','on_all_status_transitions', 10, 3 );