<?php

/*
 * Add Meta Data columns to Post Table: Link
 * Only Header and Footer
 */
add_filter('manage_'.CAAG_FORMS_CUSTOM_POST_TYPE.'_posts_columns', 'caag_forms_add_meta_columns');
function caag_forms_add_meta_columns($columns)
{
    return array(
            CAAG_FORMS_ID_COLUMN_NAME               =>  CAAG_FORMS_ID_COLUMN_NAME,
            CAAG_FORMS_TITLE_COLUMN_NAME            =>  CAAG_FORMS_TITLE_COLUMN_NAME,
            CAAG_FORMS_CATEGORY_COLUMN_NAME         =>  CAAG_FORMS_CATEGORY_COLUMN_NAME,
            CAAG_FORMS_LINK_COLUMN_NAME             =>  CAAG_FORMS_LINK_COLUMN_NAME,
            CAAG_FORMS_SHORTCODE_COLUMN_NAME        =>  CAAG_FORMS_SHORTCODE_COLUMN_NAME
    );
}

/*
 * Displaying Actual Meta Data Values
 * return @void
 */
add_action( 'manage_posts_custom_column' , 'caag_forms_fill_meta_column_link', 10, 2 );
function caag_forms_fill_meta_column_link($column_name, $post_id) {
	if ($column_name == CAAG_FORMS_ID_COLUMN_NAME) {
		if(!empty(get_post_meta($post_id, CAAG_FORMS_CAAG_ID, true))){
			echo get_post_meta($post_id, CAAG_FORMS_CAAG_ID, true);
		}else{
			echo '';
		}
	}
	if ($column_name == CAAG_FORMS_TITLE_COLUMN_NAME) {
		$post = get_post($post_id);
		echo $post->post_title;
	}
	if ($column_name == CAAG_FORMS_CATEGORY_COLUMN_NAME) {
		if(!empty(get_post_meta($post_id, CAAG_FORMS_CATEGORY, true))){
			echo get_post_meta($post_id, CAAG_FORMS_CATEGORY, true);
		}else{
			echo '';
		}
	}
	if ($column_name == CAAG_FORMS_LINK_COLUMN_NAME) {
		if(!empty(get_post_meta($post_id, CAAG_FORMS_LINK, true))){
			echo get_post_meta($post_id, CAAG_FORMS_LINK, true);
		}else{
			echo '';
		}
	}
	if ($column_name == CAAG_FORMS_SHORTCODE_COLUMN_NAME) {
		if(!empty(get_post_meta($post_id, CAAG_FORMS_SHORTCODE, true))){
			echo get_post_meta($post_id, CAAG_FORMS_SHORTCODE, true);
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


/*
 * Make Id and Title Table Header sortable
 * @param array
 * @return array
 */
add_filter( 'manage_edit-'.CAAG_FORMS_CUSTOM_POST_TYPE.'_sortable_columns', 'caag_forms_all_sortable_columns' );
function caag_forms_all_sortable_columns( $columns )
{
	$columns[CAAG_FORMS_ID_COLUMN_NAME] = 'Identifier';
	$columns[CAAG_FORMS_TITLE_COLUMN_NAME] = 'Title';
	return $columns;
}

