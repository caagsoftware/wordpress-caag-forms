<?php





/*
 * Add all MetaBoxes
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
	$meta = get_post_meta(get_post()->ID);
	print_r($meta);
	if(isset($meta[CAAG_FORMS_LINK])){
		$link = $meta[CAAG_FORMS_LINK];
		//var_dump($link);
	}else{
		$link='';
	}
	?>
	<div id="titlediv">
		<div id="titlewrap">
			<label class="screen-reader-text" id="title-prompt-text" for="title">Enter Link Here</label>
			<input type="text" name="<?php echo CAAG_FORMS_LINK; ?>" size="30" value="<?php echo $link; ?>" id="title" spellcheck="true" autocomplete="off">
		</div>
	<?php
}

function caag_form_save_post( $post_id )
{
	if ( get_post_type($post_id) != CAAG_CUSTOM_POST_TYPE ) return;
	if ( isset( $_POST[CAAG_FORMS_LINK] ) ) {
		update_post_meta( $post_id, CAAG_FORMS_LINK, $_POST[CAAG_FORMS_LINK] );
		update_post_meta( $post_id, CAAG_FORMS_ID, get_caag_forms_count() );
		update_post_meta( $post_id, CAAG_FORMS_SHORTCODE, '[caag_form id='.strval(get_caag_forms_count()).']' );
	}
}
add_action( 'save_post', 'caag_form_save_post');

