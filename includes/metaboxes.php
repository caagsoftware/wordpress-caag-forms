<?php

require_once 'HttpClient.php';

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
		wp_redirect('edit.php?post_type=caag_form');
		exit;
	}
}
add_action( 'save_post', 'caag_form_save_post');

/*
 * Add Meta Data columns to Post Table: Link
 * Only Header and Footer
 */
add_filter('manage_'.CAAG_CUSTOM_POST_TYPE.'_posts_columns', 'add_meta_columns');
function add_meta_columns($defaults)
{
	$columns[CAAG_FORMS_CAAG_ID] = CAAG_FORMS_ID_COLUMN_NAME;
	$columns[CAAG_FORMS_TITLE] = CAAG_FORMS_TITLE_COLUMN_NAME;
	$columns[CAAG_FORMS_CATEGORY] = CAAG_FORMS_CATEGORY_COLUMN_NAME;
	$columns[CAAG_FORMS_LINK] = CAAG_FORMS_LINK_COLUMN_NAME;
	$columns[CAAG_FORMS_SHORTCODE] = CAAG_FORMS_SHORTCODE_COLUMN_NAME;
	return $columns;
}

/*
 * Displaying Actual Meta Data Values
 * return @void
 */
add_action( 'manage_posts_custom_column' , 'fill_meta_column_link', 10, 2 );
function fill_meta_column_link($column_name, $post_id) {
	if ($column_name == CAAG_FORMS_CAAG_ID) {
		if(isset(get_post_meta($post_id, CAAG_FORMS_CAAG_ID)[0])){
			echo get_post_meta($post_id, CAAG_FORMS_CAAG_ID)[0];
		}else{
			echo '';
		}
	}
	if ($column_name == CAAG_FORMS_TITLE) {
		$post = get_post($post_id);
		echo $post->post_title;
	}

	if ($column_name == CAAG_FORMS_CATEGORY) {
		if(isset(get_post_meta($post_id, CAAG_FORMS_CATEGORY)[0])){
			echo get_post_meta($post_id, CAAG_FORMS_CATEGORY)[0];
		}else{
			echo '';
		}
	}
	if ($column_name == CAAG_FORMS_LINK) {
		if(isset(get_post_meta($post_id, CAAG_FORMS_LINK)[0])){
			echo get_post_meta($post_id, CAAG_FORMS_LINK)[0];
		}else{
			echo '';
		}
	}
	if ($column_name == CAAG_FORMS_SHORTCODE) {
		if(isset(get_post_meta($post_id, CAAG_FORMS_SHORTCODE)[0])){
			echo get_post_meta($post_id, CAAG_FORMS_SHORTCODE)[0];
		}else{
			echo '';
		}
	}
}

/*
 * Update Caag Form Via API
 * @param WpQuery
 * @returns void
 */
function update_caag_forms($query)
{
	if(isset($query->query['post_type']) and  $query->query['post_type'] == CAAG_CUSTOM_POST_TYPE){
		$client = new HttpClient();
		$caag_forms = $client->get(CAAG_API_ROUTE);
		if(!is_null($caag_forms->data) and !isset($caag_forms->message) and !isset($caag_forms->status_code)){
			foreach ($caag_forms->data as $form){
				if(!caag_forms_exists($form->id)){
					$args = array(
						'post_title' => $form->label,
						'post_status' => 'publish',
						'post_type' => CAAG_CUSTOM_POST_TYPE
					);
					$post_id = wp_insert_post($args);
					update_post_meta($post_id, CAAG_FORMS_CAAG_ID, $form->id);
					update_post_meta($post_id, CAAG_FORMS_LINK, $form->public_permanent_link_url);
					update_post_meta($post_id, CAAG_FORMS_SHORTCODE, '[caag_form id="'.$form->id.'"]');
					if(is_null($form->sheet_category)){
						update_post_meta($post_id, CAAG_FORMS_CATEGORY, 'General');
					}else{
						update_post_meta($post_id, CAAG_FORMS_CATEGORY, $form->sheet_category);
					}
				}else{
					$postId = get_caag_form_by_meta($form->id);
					update_post_meta($postId, CAAG_FORMS_CAAG_ID, $form->id);
					update_post_meta($postId, CAAG_FORMS_LINK, $form->public_permanent_link_url);
					update_post_meta($postId, CAAG_FORMS_SHORTCODE, '[caag_form id="'.$form->id.'"]');
					if(is_null($form->sheet_category)){
						update_post_meta($postId, CAAG_FORMS_CATEGORY, 'General');
					}else{
						update_post_meta($postId, CAAG_FORMS_CATEGORY, $form->sheet_category);
					}
				}
			}
		}else{			
			$output = '<div class="notice notice-error">
							<p style="text-transform: Capitalize">Error: '.$caag_forms->message.'</p>
						</div>
						<div class="notice notice-error">
							<p>Please. Check Caag Authentication Settings</p>
						</div>	
						';
			echo $output;
		}
		}

}
add_action( 'pre_get_posts', 'update_caag_forms' );
add_action('update_caag_forms','update_caag_forms');

/*
 * Deletes Caag Forms Posts Metadata
 * @param int | post Id
 * @return void
 */
function delete_post_attachments($post_id) {
	global $post_type;
	if($post_type == CAAG_CUSTOM_POST_TYPE){
		delete_post_meta($post_id, CAAG_FORMS_CAAG_ID);
		delete_post_meta($post_id, CAAG_FORMS_CATEGORY);
		delete_post_meta($post_id, CAAG_FORMS_LINK);
		delete_post_meta($post_id, CAAG_FORMS_SHORTCODE);
	}
}
add_action('before_delete_post', 'delete_post_attachments');


/*
 * Make Id and Title Table Header sortable
 * @param array
 * @return array
 */
add_filter( 'manage_edit-'.CAAG_CUSTOM_POST_TYPE.'_sortable_columns', 'caag_forms_all_sortable_columns' );
function caag_forms_all_sortable_columns( $columns )
{
	$columns[CAAG_FORMS_CAAG_ID] = 'Identifier';
	$columns[CAAG_FORMS_TITLE] = 'Title';
	return $columns;
}

/*
 * Orders Columns By Id and Title in Admin Table
 * @param WpQuery
 * @return void
 */
add_action( 'pre_get_posts', 'caag_sort_by_column' );
function caag_sort_by_column( $query )
{
	if ( ! is_admin() )
		return;
	if($query->query['post_type'] == CAAG_CUSTOM_POST_TYPE){
		if($query->query['orderby'] == CAAG_FORMS_ID_COLUMN_NAME){
			$query->set('meta_key', CAAG_FORMS_CAAG_ID );
			$query->set('orderby', 'meta_value_num' );
		}elseif($query->query['orderby'] == CAAG_FORMS_TITLE_COLUMN_NAME){
			$query->set('orderby','title');
		}
	}
}