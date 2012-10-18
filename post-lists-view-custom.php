<?php
/*
Plugin Name: Post Lists View Custom
Description: Customize the list of the post. view thumbnails and custom fields.
Plugin URI: http://gqevu6bsiz.chicappa.jp
Version: 1.0.2
Author: gqevu6bsiz
Author URI: http://gqevu6bsiz.chicappa.jp/author/admin/
Text Domain: post_lists_view_custom
Domain Path: /languages
*/

/*  Copyright 2012 gqevu6bsiz (email : gqevu6bsiz@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

load_plugin_textdomain('post_lists_view_custom', false, basename(dirname(__FILE__)).'/languages');

define ('POST_LISTS_VIEW_CUSTOM_VER', '1.0.2');
define ('POST_LISTS_VIEW_CUSTOM_PLUGIN_NAME', 'Post Lists View Costom');
define ('POST_LISTS_VIEW_CUSTOM_MANAGE_URL', admin_url('options-general.php').'?page=post_lists_view_custom');
define ('POST_LISTS_VIEW_CUSTOM_RECORD_NAME', 'post_lists_view_custom');
define ('POST_LISTS_VIEW_CUSTOM_PLUGIN_DIR', WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)).'/');
define ('POST_LISTS_VIEW_CUSTOM_ThumnailSize', 50);
?>
<?php
function post_lists_view_custom_add_menu() {
	// add menu
	add_options_page(__('Post Lists View Custom\'s Setting', 'post_lists_view_custom'), __(POST_LISTS_VIEW_CUSTOM_PLUGIN_NAME, 'post_lists_view_custom') , 'administrator', 'post_lists_view_custom', 'post_lists_view_custom_setting');

	// plugin links
	add_filter('plugin_action_links', 'post_lists_view_custom_plugin_setting', 10, 2);
}



// plugin setup
function post_lists_view_custom_plugin_setting($links, $file) {
	if(plugin_basename(__FILE__) == $file) {
		$settings_link = '<a href="'.POST_LISTS_VIEW_CUSTOM_MANAGE_URL.'">'.__('Settings').'</a>'; 
		array_unshift( $links, $settings_link );
	}
	return $links;
}
add_action('admin_menu', 'post_lists_view_custom_add_menu');



// setting
function post_lists_view_custom_setting() {
	$UPFN = 'sett';
	$Msg = '';

	if(!empty($_POST[$UPFN])) {

		// update
		if($_POST[$UPFN] == 'Y') {
			unset($_POST[$UPFN]);

			$Modes = array("use", "not_use");
			$Update = array();
			foreach($Modes as $mode) {
				if(!empty($_POST[$mode])) {
					foreach ($_POST[$mode] as $key => $val) {
						$Update[strip_tags($key)]["name"] = strip_tags($val["name"]);
						$Update[strip_tags($key)]["option_name"] = strip_tags($val["option_name"]);
						$Update[strip_tags($key)][$mode] = 1;
					}
				}
			}

			update_option(POST_LISTS_VIEW_CUSTOM_RECORD_NAME, $Update);
			$Msg = '<div class="updated"><p><strong>'.__('Settings saved.').'</strong></p></div>';
		}

	}

	// get data
	$Data = post_lists_view_custom_get(get_option(POST_LISTS_VIEW_CUSTOM_RECORD_NAME));

	// include js css
	$ReadedJs = array('jquery', 'jquery-ui-sortable');
	wp_enqueue_script('post-lists-view-custom', POST_LISTS_VIEW_CUSTOM_PLUGIN_DIR.dirname(plugin_basename(__FILE__)).'.js', $ReadedJs, POST_LISTS_VIEW_CUSTOM_VER);
	wp_enqueue_style('post-lists-view-custom', POST_LISTS_VIEW_CUSTOM_PLUGIN_DIR.dirname(plugin_basename(__FILE__)).'.css', array(), POST_LISTS_VIEW_CUSTOM_VER);
?>
<div class="wrap">
	<div class="icon32" id="icon-options-general"></div>
	<h2><?php _e('Post Lists View Custom\'s Setting', 'post_lists_view_custom'); ?></h2>
	<?php echo $Msg; ?>
	<p>&nbsp;</p>

	<form id="post_lists_view_custom_form" method="post" action="">
		<input type="hidden" name="<?php echo $UPFN; ?>" value="Y">
		<?php wp_nonce_field(); ?>

		<table cellspacing="0" class="widefat fixed">
			<thead>
				<tr>
					<th><?php _e('Show'); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<span class="description"><?php _e('Please side by side by dragging.','post_lists_view_custom'); ?></span>
						<div id="use" class="widget-list">
							<?php if(!empty($Data)): ?>
								<?php post_lists_view_custom_lists_create('use', $Data); ?>
							<?php endif; ?>
						</div>
						<div class="clear"></div>
					</td>
				</tr>
			</tbody>
		</table>

		<p>&nbsp;</p>

		<table cellspacing="0" class="widefat fixed">
			<thead>
				<tr>
					<th><?php _e('Hide'); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<div id="not_use" class="widget-list">
							<?php if(!empty($Data)): ?>
								<?php post_lists_view_custom_lists_create('not_use', $Data); ?>
							<?php endif; ?>
						</div>
						<div class="clear"></div>
					</td>
				</tr>
			</tbody>
		</table>
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save'); ?>" />
		</p>
		<p class="submit reset">
			<span class="description"><?php _e('Would initialize?', 'post_lists_view_custom'); ?></span>
			<input type="button" class="button-secondary" value="<?php _e('Reset'); ?>" />
		</p>

	</form>
</div>
<?php
}



// lists create
function post_lists_view_custom_lists_create($mode, $Data) {

	$Contents = '';
	foreach($Data as $key => $val) {
		if(!empty($val[$mode])) {
			$Contents .= '<div id="'.$val["option_name"].'" class="widget">';
			
			$Contents .= '<div class="widget-top">';
			$Contents .= '<div class="widget-title">';
			$Contents .= '<h4>'.$val["name"].'</h4>';
			$Contents .= '</div>';
			$Contents .= '</div>';
			
			$Contents .= '<div class="widget-inside">';
			$Contents .= '<input type="hidden" name="'.$mode.'['.$key.'][name]" value="'.$val["name"].'" />';
			$Contents .= '<input type="hidden" name="'.$mode.'['.$key.'][option_name]" value="'.$val["option_name"].'" />';
			$Contents .= '</div>';
			
			$Contents .= '</div>';
		}
	}

	echo $Contents;

}



// post list get datas
function post_lists_view_custom_get($Data = array()) {

	global $wpdb;

	// Default columns
	$posts_columns = array();
	$posts_columns['title'] = _x( 'Title', 'column name' );
	$posts_columns['author'] = __( 'Author' );
	$posts_columns['categories'] = __( 'Categories' );
	$posts_columns['tags'] = __( 'Tags' );
	$posts_columns['comments'] = esc_attr__( 'Comments' );
	$posts_columns['date'] = __( 'Date' );

	// theme supports
	$ThemeSupports = array('post-thumbnails' => __('Featured Image'), 'post-formats' => _x( 'Format', 'post format' ));
	foreach($ThemeSupports as $Name => $TransName) {
		$Support = current_theme_supports( $Name );
		if(!empty($Support)) {
			$posts_columns[$Name] = $TransName;
		}
	}

	// post type
	$posts_columns['excerpt'] = __('Excerpt');
	$posts_columns['slug'] = __( 'Slug' );

	// All custom field key
	$Acfk = $wpdb->get_col( "SELECT meta_key FROM $wpdb->postmeta GROUP BY meta_key HAVING meta_key NOT LIKE '\_%' AND meta_key NOT LIKE 'field\_%' ORDER BY meta_key ");
	if(!empty($Acfk)) {
		natcasesort($Acfk);
	}


	// Default Data
	$Defaulst = array();
	foreach($posts_columns as $column => $name) {
		$Defaulst[$column] = array("use" => 1, "not_use" => 0, "option_name" => $column, "name" => $name);
	}
	foreach($Acfk as $name) {
		$Defaulst[$name] = array("use" => 0, "not_use" => 1, "option_name" => $name, "name" => $name);
	}

	// Data Marge
	$NewData = array();
	if(!empty($Data) and is_array($Data)) {
		foreach($Data as $name => $val) {
			if(!empty($Defaulst[$name])) {
				$NewData[$name] = $val;
				unset($Defaulst[$name]);
			}
		}
		if(!empty($Defaulst) and is_array($Defaulst)) {
			foreach($Defaulst as $name => $val) {
				$NewData[$name] = $val;
			}
		}
	} else {
		$NewData = $Defaulst;
	}
	
	// checkbox
	if(!empty($NewData["cb"])) {
		unset($NewData["cb"]);
	}

	return $NewData;

}



// column filter
function post_lists_view_custom_filter($columns) {
	
	$Data = get_option(POST_LISTS_VIEW_CUSTOM_RECORD_NAME);
	$FilterColumn = array();
	if(!empty($Data) and is_array($Data)) {

		// checkbox
		$FilterColumn["cb"] = $columns["cb"];

		foreach($Data as $name => $val) {
			if(!empty($val["use"])) {
				$FilterColumn[$name] = $val["name"];
			}
		}

	} else {
		$FilterColumn = $columns;
	}
	
	wp_enqueue_style('post-lists-view-custom', POST_LISTS_VIEW_CUSTOM_PLUGIN_DIR.dirname(plugin_basename(__FILE__)).'.css', array(), POST_LISTS_VIEW_CUSTOM_VER);
	
	return $FilterColumn;
}
add_filter('manage_posts_columns', 'post_lists_view_custom_filter', 101);



// column data
function post_lists_view_custom_manage($column_name, $post_id) {

	$None = '&nbsp; - &nbsp;';

	if($column_name == 'post-formats') {
		// post-formats
		echo get_post_format_string(get_post_format($post_id));
	} else if($column_name == 'slug') {
		// slug
		echo urldecode(get_page_uri($post_id));
	} else if($column_name == 'excerpt') {
		// excerpt
		$excerpt = get_post($post_id);
		if(!empty($excerpt->post_excerpt)) {
			echo mb_substr(strip_tags($excerpt->post_excerpt), 0, 20).'.';
		} else {
			echo $None;
		}
	} else if($column_name == 'post-thumbnails') {
		// thumbnail
		if( has_post_thumbnail( $post_id ) ) {
			$thumbnail_id = get_post_thumbnail_id($post_id);
			$thumbnail = get_the_post_thumbnail( $post_id, array(POST_LISTS_VIEW_CUSTOM_ThumnailSize, "") );
			echo '<a href="media.php?attachment_id='.$thumbnail_id.'&action=edit">'.$thumbnail.'</a>';
		} else {
			echo $None;
		}
	} else {
		// custom fields
		$post_meta = get_post_meta( $post_id , $column_name , false );
		
		if(!empty($post_meta[0])) {
			if(is_array($post_meta[0])) {
				// checkbox multiselect
				echo '<ul>';
				foreach($post_meta[0] as $val) {
					if(is_array($val)) {
						foreach($val as $v) {
							echo '<li>'.$v.'</li>';
						}
					} else {
						echo '<li>'.$val.'</li>';
					}
				}
				echo '</ul>';
			} else {
				// custom-field-template active flag
				$is_active = false;
				foreach ((array) get_option('active_plugins') as $plugin) {
					if (preg_match('/custom-field-template/i', $plugin)) {
						$is_active = true;
						break;
					}
				}
				if(!empty($is_active)) {
					$cftd = get_option('custom_field_template_data');
					if(!empty($cftd)) {
						$cttd_contents = '';
						$cttd_field_file = array();
						foreach($cftd["custom_fields"] as $key => $cftdct) {
							if(!empty($cftdct["content"])) {
								$cttd_contents = explode("\n", stripcslashes($cftdct["content"]));
								for($i=0; $i<count($cttd_contents); $i++) {
									if(strpos($cttd_contents[$i], 'file')) {
										for($ct=1;$ct<3;$ct++) {
											if(!empty($cttd_contents[$i-$ct])) {
												if (preg_match("/\[(.+)\]/", $cttd_contents[$i-$ct], $match)) {
													$cttd_field_file[] = $match[1];
												}
											}
										}
									}
								}
							}
						}

						if(!empty($cttd_field_file)) {
							if(in_array($column_name, $cttd_field_file)) {
								$post = get_post($post_meta[0]);
								if(!empty($post) && intval($post_meta[0]) && $post->post_type == 'attachment') {
									$CustomThumbnail = wp_get_attachment_image_src( $post_meta[0], 'post-thumbnail', true );
									if(!empty($CustomThumbnail)) {
										echo '<a href="media.php?attachment_id='.$post_meta[0].'&action=edit"><img src="'.$CustomThumbnail[0].'" width="'.POST_LISTS_VIEW_CUSTOM_ThumnailSize.'" /></a>';
									} else {
										echo $post_meta[0];
									}
								} else {
									echo $post_meta[0];
								}
							} else {
								echo $post_meta[0];
							}
						} else {
							echo $post_meta[0];
						}
					} else {
						echo $post_meta[0];
					}
				} else {
					$post = get_post($post_meta[0]);
					if(!empty($post) && intval($post_meta[0]) && $post->post_type == 'attachment' && $post_id == $post->post_parent) {
						$CustomThumbnail = wp_get_attachment_image_src( $post_meta[0], 'post-thumbnail', true );
						if(!empty($CustomThumbnail)) {
							echo '<a href="media.php?attachment_id='.$post_meta[0].'&action=edit"><img src="'.$CustomThumbnail[0].'" width="'.POST_LISTS_VIEW_CUSTOM_ThumnailSize.'" /></a>';
						} else {
							echo $post_meta[0];
						}
					} else {
						echo $post_meta[0];
					}
				}
				
			}
		} else {
			echo $None;
		}

	}

}
add_action('manage_posts_custom_column', 'post_lists_view_custom_manage', 8, 2);
?>