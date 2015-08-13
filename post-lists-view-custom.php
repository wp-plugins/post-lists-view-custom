<?php
/*
Plugin Name: Post Lists View Custom
Description: Allow to customizing for the list screen.
Plugin URI: http://wordpress.org/extend/plugins/post-lists-view-custom/
Version: 1.7.4
Author: gqevu6bsiz
Author URI: http://gqevu6bsiz.chicappa.jp/?utm_source=use_plugin&utm_medium=list&utm_content=plvc&utm_campaign=1_7_4
Text Domain: plvc
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



if ( !class_exists( 'Plvc' ) ) :

class Post_Lists_View_Custom
{

	var	$Ver = '1.7.4';

	var $Plugin = array();
	var $Current = array();
	var $ThirdParty = array();

	var $ClassConfig;
	var $ClassData;
	var $ClassManager;
	var $ClassInfo;

	function __construct() {

		$inc_path = plugin_dir_path( __FILE__ );

		include_once $inc_path . 'inc/class-config.php';
		include_once $inc_path . 'inc/class-data.php';
		include_once $inc_path . 'inc/class-manager.php';
		include_once $inc_path . 'inc/class-plugin-info.php';

		$this->ClassConfig = new Plvc_Config();
		$this->ClassData = new Plvc_Data();
		$this->ClassManager = new Plvc_Manager();
		$this->ClassInfo = new Plvc_Plugin_Info();

		add_action( 'plugins_loaded' , array( $this , 'init' ) , 100 );

	}

	function init() {
		
		load_plugin_textdomain( $this->Plugin['ltd'] , false , $this->Plugin['plugin_slug'] . '/languages' );

		add_action( 'load-edit.php' , array( $this , 'registed_columns_load_action' ) );
		add_action( 'load-edit-comments.php' , array( $this , 'registed_columns_load_action' ) );
		add_action( 'load-upload.php' , array( $this , 'registed_columns_load_action' ) );
		add_action( 'load-users.php' , array( $this , 'registed_columns_load_action' ) );
		
		add_action( 'init' , array( $this , $this->Plugin['ltd'] . '_init' ) , 20 );
		
		add_action( 'wp_loaded' , array( $this , 'FilterStart' ) );

	}
	
	function plvc_init() {
		
		do_action( $this->Plugin['ltd'] . '_init' );
		
	}

	function registed_columns_load_action() {

		global $typenow, $pagenow;
		
		$column_type = false;
		$sortable_type = false;
		
		if( $pagenow == 'edit-comments.php' ) {

			$column_type = 'edit-comments';
			$sortable_type = 'edit-comments';

		} elseif( $pagenow == 'upload.php' ) {

			$column_type = 'media';
			$sortable_type = 'upload';

		} elseif( $pagenow == 'users.php' ) {

			$column_type = 'users';
			$sortable_type = 'users';

		} elseif( $pagenow == 'edit.php' ) {

			$column_type = 'edit-' . $typenow;
			$sortable_type = 'edit-' . $typenow;
			
		}
		
		if( !empty( $column_type ) ) {
		
			add_filter( 'manage_' . $column_type . '_columns' , array( $this , 'registed_columns_loading' ) , 10000 );
			add_filter( 'manage_' . $sortable_type . '_sortable_columns' , array( $this , 'registed_sortable_loading' ) , 10000 );
		
		}
		
	}

	// SetList
	function registed_columns_loading( $columns ) {
		
		global $typenow, $pagenow;
		
		$default_columns = $columns;

		if( $this->ClassManager->is_manager ) {
			
			$column_type = false;

			if( $pagenow == 'edit-comments.php' ) {

				$column_type = 'comments';

			} elseif( $pagenow == 'upload.php' ) {

				$column_type = 'media';

			} elseif( $pagenow == 'edit.php' ) {

				$column_type = $typenow;

			} elseif( $pagenow == 'users.php' ) {

				$column_type = 'users';

			}
			
			if( !empty( $column_type ) && !empty( $columns['cb'] ) ) {

				$check_column = $columns['cb'];
				unset( $columns['cb'] );
				
				$this->ClassData->update_registed_columns( $column_type , $columns );

			}
			
		}

		return $default_columns;
		
	}

	// SetList
	function registed_sortable_loading( $columns ) {

		global $typenow, $pagenow;
		
		$default_columns = $columns;
		
		if( $this->ClassManager->is_manager ) {
			
			$column_type = false;

			if( $pagenow == 'edit-comments.php' ) {

				$column_type = 'comments';

			} elseif( $pagenow == 'upload.php' ) {

				$column_type = 'media';

			} elseif( $pagenow == 'edit.php' ) {

				$column_type = $typenow;

			} elseif( $pagenow == 'users.php' ) {

				$column_type = 'users';

			}
			
			if( !empty( $column_type ) ) {

				$this->ClassData->update_registed_sortable_columns( $column_type , $columns );

			}
			
		}		
		
		return $default_columns;

	}

	// SetList
	function setting_list_post( $post_type , $post_name , $use_type , $column_id , $column_setting ) {

		$class = '';
		$group = '';
		$column_name = stripslashes( $column_setting['name'] );

		if( !empty( $column_setting['group'] ) ) {

			$group = strip_tags( $column_setting['group'] );
			$class = $group;

		}
		
		printf( '<th id="column-%1$s" class="%2$s">' , $column_id , $class );
			
		echo '<div class="show-field">';
		printf( '<div class="column-title">%s</div>' , $column_name );
		printf( '<a class="column-toggle" href="javascript:void(0);">&nbsp;</a>' );
		echo '</div>';

		echo '<div class="edit-field">';
		printf( '<input type="text" name="use[%2$s][name]" class="large-text input-column-name" value="%1$s" />' , esc_html( $column_name ) , $column_id );
		
		if( !empty( $column_setting['orderby'] ) )
			printf( '<label class="sort_label"><input type="checkbox" name="use[%2$s][sort]" value="1" %3$s /> %1$s</label>' , __( 'Sort' , $this->Plugin['ltd'] ) , $column_id , checked( $column_setting['sort'] , 1 , false ) );
		
		if( !empty( $group ) ) {
				
			if( $group == 'plugin' ) {
					
				printf( '<p class="description">%s</p>' , __( 'Plugin' ) . ' / ' . __( 'Current Theme' ) );
					
			} elseif( $group == 'custom_field' ) {
					
				printf( '<p class="description">%s</p>' , __( 'Custom Fields' ) );
					
			}

		}
			
		if( $column_id == 'icon' )
			printf( '<p class="description">%s</p>' , __( 'Thumbnail' ) );

		printf( '<div class="remove-action"><a href="javascript:void(0);" title="column-id-%2$s">%1$s</a></div>' , __( 'Remove' ) , $column_id );

		echo '</div>';
			
		echo '</th>';

	}

	// SetList
	function setting_list_menus( $menu_type , $use_type , $menu_id , $menu_setting ) {

		$menu_name = stripslashes( $menu_setting['name'] );

		if( empty( $menu_name ) )
			$menu_name = '&nbsp;';

		$group = '';
		if( !empty( $menu_setting['group'] ) )
			$group = strip_tags( $menu_setting['group'] );

		printf( '<th>%s</th>' , $menu_name );
				
		echo '<td class="column-check">';
				
		$field_name = sprintf( 'not_use[%s][name]' , $menu_id );
		printf( '<label><input type="checkbox" name="%1$s" value="%2$s" %3$s /> %4$s</label>' , $field_name , esc_html( $menu_name ) , checked( $use_type , 'not_use' , false ) , __( 'Hide' ) );

		echo '</td>';
				
		echo '<td class="column-group">';
				
		if( !empty( $group ) ) {
				
			if( $group == 'plugin' ) {
					
				printf( '<span class="description">%s</span>' , __( 'Plugin' ) . ' / ' . __( 'Current Theme' ) );
					
			} elseif( $group == 'custom_post' ) {
					
				printf( '<span class="description">%s</span>' , __( 'Custom' ) . __( 'Posts' ) );
					
			} elseif( $group == 'custom_taxonomy' ) {
					
				printf( '<span class="description">%s</span>' , __( 'Custom' ) . __( 'Taxonomy' , $this->Plugin['ltd'] ) );
					
			}

		}

		echo '</td>';
				
	}

	// SetList
	function is_list_page() {
		
		global $pagenow;
		
		$check = false;
		
		if( in_array( $pagenow , array( 'edit.php' , 'upload.php' , 'edit-comments.php' , 'users.php' ) ) )
			$check = true;
		
		if( !$check && $this->Current['ajax'] ) {

			if( !empty( $_REQUEST['action'] ) ) {

				if( $_REQUEST['action'] == 'inline-save' && !empty( $_REQUEST['screen'] ) ) {
					
					if( strpos( $_REQUEST['screen'] , 'edit-' ) !== false )
						$check = true;
	
				} elseif( $_REQUEST['action'] == 'edit-comment' or $_REQUEST['action'] == 'replyto-comment' ) {
					
					$check = true;
					
				}
				
				
			}
			
		}
			
		return $check;
		
	}
	
	// SetList
	function is_menu_page() {
		
		global $pagenow;
		
		$check = false;
		
		if( in_array( $pagenow , array( 'widgets.php' , 'nav-menus.php' ) ) )
			$check = true;
			
		return $check;

	}

	// SetList
	function is_apply_user() {
		
		$check = false;

		$setting_roles = $this->ClassData->get_data_user_role();
		$setting_roles = apply_filters( 'plvc_pre_setting_roles' , $setting_roles );
		
		if( !empty( $setting_roles['UPFN'] ) )
			unset( $setting_roles['UPFN'] );

		if( !empty( $setting_roles ) && !empty( $this->Current['user_role'] ) && array_key_exists( $this->Current['user_role'] , $setting_roles ) )
			$check = true;

		return $check;
		
	}




	// FilterStart
	function FilterStart() {

		if( !$this->Current['network_admin'] && $this->Current['admin'] ) {
			
			if( !$this->Current['ajax'] ) {
				
				add_action( 'admin_print_scripts' , array( $this , 'admin_print_scripts' ) );
				add_action( 'admin_init' , array( $this , 'menus_init' ) , 100 );
				
			}
			
			add_action( 'admin_init' , array( $this , 'columns_init' ) , 100 );

		}

	}

	// FilterStart
	function admin_print_scripts() {
		
		global $pagenow;
		
		if( $this->is_list_page() && $this->is_apply_user() ) {

			$Data = $this->ClassData->get_data_others();

			if( empty( $Data['cell_auto'] ) )
				wp_enqueue_style( $this->Plugin['ltd'] , $this->Plugin['url'] . 'admin/assets/list-table.css', array() , $this->Ver );

			if( !empty( $pagenow ) && $pagenow == 'edit.php' ) {
				
				$thumbnail_size = $this->ClassConfig->get_default_thumbnail_size();
				
				$GetOtherData = $this->ClassData->get_data_others();
				if( !empty( $GetOtherData ) && !empty( $GetOtherData['thumbnail'] ) && !empty( $GetOtherData['thumbnail']['width'] ) )
					$thumbnail_size = intval( $GetOtherData['thumbnail']['width'] );

				printf( '<style>.wp-list-table thead tr th.column-post-thumbnails { width: %spx; }</style>' , $thumbnail_size );
				
			}
		}
		
	}

	// FilterStart
	function columns_init() {

		global $pagenow;
		
		if( $this->is_apply_user() && $this->is_list_page() ) {
			
			if( $pagenow == 'edit.php' ) {

				$this->load_edit();

			} elseif( $pagenow == 'upload.php' ) {
				
				$this->load_upload();
			
			} elseif( $pagenow == 'edit-comments.php' ) {
				
				$this->load_edit_comments();
			
			} elseif( $pagenow == 'users.php' ) {
				
				$this->load_edit_users();
			
			} elseif( $pagenow == 'admin-ajax.php' && !empty( $_REQUEST['action'] ) ) {
				
				if( $_REQUEST['action'] == 'inline-save' && !empty( $_REQUEST['screen'] ) && strpos( $_REQUEST['screen'] , 'edit-' ) !== false ) {

					$this->load_edit();

				} elseif( $_REQUEST['action'] == 'edit-comment' or $_REQUEST['action'] == 'replyto-comment' ) {

					$this->load_edit_comments();
					
				}
				
			}
			
		}

	}
	
	// FilterStart
	function sortable_request( $request ) {
		
		if( !empty( $request['orderby'] ) ) {
			
			if( $request['orderby'] == 'post-thumbnails' ) {
				
				$request['meta_key'] = '_thumbnail_id';
				$request['orderby'] = 'meta_value';
				
			} elseif( $request['orderby'] == 'image_alt' ) {
				
				$request['meta_key'] = '_wp_attachment_image_alt';
				$request['orderby'] = 'meta_value';

			} else {
				
				$list_type = $request['post_type'];

				if( $list_type == 'attachment' )
					$list_type = 'media';

				$registed_columns = $this->ClassConfig->get_registed_columns( $list_type );
				
				$orderby_id = $request['orderby'];
				
				if( !empty( $registed_columns ) ) {
				
					foreach( $registed_columns as $use_type => $columns ) {
						
						if( !empty( $columns[$orderby_id] ) && !empty( $columns[$orderby_id]['group'] ) && $columns[$orderby_id]['group'] == 'custom_field' ) {
							
							$request['meta_key'] = $orderby_id;
							$request['orderby'] = 'meta_value';
							
							break;
	
						}
						
					}
					
				}
				
			}
			
		}
		
		return $request;

	}
	
	// FilterStart
	function sortable_posts_orderby( $orderby_statement ) {
		
		global $wpdb;
		global $wp_query;
		
		$orderby = $wp_query->get( 'orderby' );
		
		if( !empty( $orderby ) ) {

			$order = $wp_query->get( 'order' );

			if( strstr( $orderby_statement, 'post_date' ) ) {
				
				if( $orderby == 'post_excerpt' ) {
		
					$orderby_statement = sprintf( '%1$s.%2$s %3$s' , $wpdb->posts , $orderby , $order );
					
				}

			}
			
		}
		
		return $orderby_statement;

	}

	// FilterStart
	function menus_init() {

		global $pagenow;
		
		if( $this->is_apply_user() && $this->is_menu_page() ) {
			
			if( $pagenow == 'widgets.php' ) {

				$this->load_widgets();

			} elseif( $pagenow == 'nav-menus.php' ) {

				$this->load_nav_menus();

			}
			
		}

	}

	// FilterStart
	function load_edit() {
		
		global $typenow;
		global $pagenow;
		
		if( $this->is_list_page() && $this->is_apply_user() ) {
			
			$Data = array();
			
			$list_type = $typenow;
			
			if( empty( $list_type ) ) {
				
				if( $this->Current['ajax'] ) {

					$list_type = strip_tags( $_REQUEST['post_type'] );

				} else {
					
					$list_type = 'post';
					
				}
			}
			
			if( $list_type == 'post' ) {
				
				$Data = $this->ClassData->get_data_post( true );
				
			} elseif( $list_type == 'page' ) {
				
				$Data = $this->ClassData->get_data_page( true );
				
			} else {
				
				$Data = $this->ClassData->get_data_custom_post( $list_type , true );
				
			}
			
			if( !empty( $Data ) ) {

				add_filter( 'manage_edit-' . $list_type . '_columns' , array( $this , 'posts_columns_header' ) , 10001 );
				add_action( 'manage_' . $list_type . '_posts_custom_column' , array( $this , 'posts_columns_body' ) , 10 , 2 );
				add_filter( 'manage_edit-' . $list_type . '_sortable_columns', array( $this , 'posts_sortable_columns' ) , 10001 );
				add_filter( 'request' , array( $this , 'sortable_request' ) );
				add_filter( 'posts_orderby',  array( $this , 'sortable_posts_orderby' ) );

			}

		}

	}

	// FilterStart
	function load_upload() {
		
		if( $this->is_list_page() && $this->is_apply_user() ) {
			
			$Data = $this->ClassData->get_data_media( true );

			if( !empty( $Data ) ) {
				
				add_filter( 'manage_media_columns' , array( $this , 'media_columns_header' ) , 10001 );
				add_action( 'manage_media_custom_column' , array( $this , 'media_columns_body' ) , 10 , 2 );
				add_filter( 'manage_upload_sortable_columns', array( $this , 'media_sortable_columns' ) , 10001 );
				add_filter( 'request' , array( $this , 'sortable_request' ) );
				add_filter( 'posts_orderby',  array( $this , 'sortable_posts_orderby' ) );

			}

		}

	}

	// FilterStart
	function load_edit_comments() {
		
		if( $this->is_list_page() && $this->is_apply_user() ) {
			
			$Data = $this->ClassData->get_data_comments( true );

			if( !empty( $Data ) ) {

				add_filter( 'manage_edit-comments_columns' , array( $this , 'comments_columns_header' ) , 10001 );
				add_action( 'manage_comments_custom_column' , array( $this , 'comments_columns_body' ) , 10 , 2 );
				add_filter( 'manage_edit-comments_sortable_columns', array( $this , 'comments_sortable_columns' ) , 10001 );

			}

		}

	}

	// FilterStart
	function load_edit_users() {
		
		if( $this->is_list_page() && $this->is_apply_user() ) {
			
			$Data = $this->ClassData->get_data_users( true );

			if( !empty( $Data ) ) {

				add_filter( 'manage_users_columns' , array( $this , 'users_columns_header' ) , 10001 );
				add_filter( 'manage_users_custom_column' , array( $this , 'users_columns_body' ) , 10 , 3 );
				add_filter( 'manage_users_sortable_columns', array( $this , 'users_sortable_columns' ) , 10001 );

			}

		}

	}

	// FilterStart
	function load_widgets() {
		
		if( $this->is_menu_page() && $this->is_apply_user() ) {
			
			$Data = $this->ClassData->get_data_widgets( true );

			if( !empty( $Data ) ) {

				add_filter( 'widgets_admin_page' , array( $this , 'widgets_menu' ) );

			}

		}

	}

	// FilterStart
	function load_nav_menus() {
		
		if( $this->is_menu_page() && $this->is_apply_user() ) {
			
			$Data = $this->ClassData->get_data_menus( true );

			if( !empty( $Data ) ) {

				add_filter( 'admin_head-nav-menus.php' , array( $this , 'nav_menu_metabox' ) );

			}

			$Data = $this->ClassData->get_data_menus_adv( true );

			if( !empty( $Data ) ) {

				add_filter( 'manage_nav-menus_columns' , array( $this , 'menu_columns_header' ) , 11 );
				add_action( 'admin_head-nav-menus.php' , array( $this , 'menu_advance_style' ) );

			}

		}

	}

	// FilterStart
	function posts_columns_header( $columns ) {
		
		global $typenow;
		
		$Data = array();

		$list_type = $typenow;
			
		if( empty( $list_type ) ) {
				
			if( $this->Current['ajax'] ) {

				$list_type = strip_tags( $_REQUEST['post_type'] );

			} else {
					
				$list_type = 'post';
					
			}

		}

		if( $list_type == 'post' ) {
				
			$Data = $this->ClassData->get_data_post( true );
				
		} elseif( $list_type == 'page' ) {
				
			$Data = $this->ClassData->get_data_page( true );
				
		} else {
			
			$Data = $this->ClassData->get_data_custom_post( $list_type , true );
				
		}
		
		$default_columns = $this->ClassConfig->get_registed_columns( $list_type );
		$FilterColumn = array( 'cb' => $columns['cb'] );
		
		if( !empty( $Data['use'] ) && !empty( $default_columns ) ) {

			foreach( $Data['use'] as $column_id => $column ) {

				if( array_key_exists( $column_id , $default_columns['use'] ) or array_key_exists( $column_id , $default_columns['not_use'] ) )
				$FilterColumn[$column_id] = $column['name'];
				
			}

		} else {
			
			$FilterColumn = $columns;
			
		}

		return $FilterColumn;

	}

	// FilterStart
	function posts_columns_body( $column_name , $post_id ) {
		
		$content = '';
		$thumbnail_size = $this->ClassConfig->get_default_thumbnail_size();
		
		$GetOtherData = $this->ClassData->get_data_others();
		if( !empty( $GetOtherData ) && !empty( $GetOtherData['thumbnail'] ) && !empty( $GetOtherData['thumbnail']['width'] ) )
			$thumbnail_size = intval( $GetOtherData['thumbnail']['width'] );
			
		if( $column_name == 'id' ) {
				
			$content = $post_id;
				
		} elseif( $column_name == 'slug' ) {
			
			$post = get_post( $post_id );
			$content = sanitize_title( $post->post_name );

		} elseif( $column_name == 'post-formats' ) {
				
			$content = get_post_format_string( get_post_format( $post_id ) );

		} elseif( $column_name == 'excerpt' ) {
				
			$post = get_post( $post_id );

			if( !empty( $post->post_excerpt ) ) {

				if( function_exists( 'mb_substr' ) ) {

					$content = mb_substr( strip_tags( $post->post_excerpt ) , 0 , 20 ) . '.';

				} else {

					$content = substr( strip_tags( $post->post_excerpt ) , 0 , 20 ) . '.';

				}

			}

		} elseif( $column_name == 'post-thumbnails' ) {
				
			if( has_post_thumbnail( $post_id ) ) {
					
				$thumbnail_id = get_post_thumbnail_id( $post_id );
				$thumbnail = wp_get_attachment_image_src( $thumbnail_id , 'post-thumbnail', true );
				$content = sprintf( '<a href="media.php?attachment_id=%1$s&action=edit"><img src="%2$s" width="%3$s" /></a>' , $thumbnail_id , $thumbnail[0] , $thumbnail_size );
				
			}
	
		} elseif( $column_name == 'only_title' ) {
			
			$post = get_post( $post_id );
			
			$current_level = 0;
			
			if( !empty( $post->post_parent ) ) {
				
				$find_post_id = (int) $post->post_parent;
				
				while ( $find_post_id > 0 ) {
					
					$parent_post = get_post( $find_post_id );
					
					if ( is_null( $parent_post ) ) {

						break;

					}

					$current_level++;
					$find_post_id = (int) $parent_post->post_parent;

				}
				
			}
			
			$pad = str_repeat( '&#8212; ' , $current_level );
			
			$content = '<strong>';
			
			$format = get_post_format( $post->ID );

			if ( $format ) {

				$label = get_post_format_string( $format );
	
				$content .= sprintf( '<a href="%1$s" class="post-state-format post-format-icon post-format-%2$s" title="%3$s">%3$s:</a>' , esc_url( add_query_arg( array( 'post_format' => $format, 'post_type' => $post->post_type ), 'edit.php' ) ) , $format , $label );
			}

			$can_edit_post = current_user_can( 'edit_post', $post->ID );
			$title = _draft_or_post_title( $post->ID );

			if ( $can_edit_post && $post->post_status != 'trash' ) {

				$edit_link = get_edit_post_link( $post->ID );
				$content .= sprintf( '<a class="row-title" href="%1$s" title="%2$s">%3$s</a>' , $edit_link , esc_attr( sprintf( __( 'Edit &#8220;%s&#8221;' ), $title ) ) , $pad . $title );

			} else {

				$content .= $pad . $title;

			}
			
			$post_states = array();

			if ( !empty( $post->post_password ) ) {
				
				$post_states[] = __( 'Password protected' );

			} elseif( 'private' == $post->post_status ) {
				
				$post_states[] = __( 'Private' );

			} elseif( 'draft' == $post->post_status ) {
				
				$post_states[] = __( 'Draft' );

			} elseif( 'pending' == $post->post_status ) {
				
				$post_states[] =  _x( 'Pending' , 'post state' );

			} elseif( is_sticky( $post->ID ) ) {
				
				$post_states[] = __( 'Sticky' );

			}
			
			if( 'future' == $post->post_status ) {
				
				$post_states[] = __( 'Scheduled' );

			}
			
			if( get_option( 'page_on_front' ) == $post->ID ) {
				
				$post_states[] = __( 'Front Page' );

			}
			
			if( get_option( 'page_for_posts' ) == $post->ID ) {
				
				$post_states[] = __( 'Posts Page' );

			}
			
			if( !empty( $post_states ) ) {
				
				$state_count = count( $post_states );
				$i = 0;
				
				$content .= ' - ';

				foreach( $post_states as $state ) {
					
					$i++;
					( $i == $state_count ) ? $sep = '' : $sep = ', ';
					$content .= sprintf( '<span class="post-state">%s</span>' , $state . $sep );
					
				}
				
			}

			$content .= '</strong>';
			
		} else {
				
			$post_meta = get_post_meta( $post_id , $column_name , false );
			if( !empty( $post_meta[0] ) ) {
					
				if( is_array( $post_meta[0] ) ) {
					
					$content .= '<ul>';
					foreach( $post_meta[0] as $val ) {
						
						if( is_array( $val ) ) {
								
							foreach( $val as $v ) {
								$content .= sprintf( '<li>%s</li>' , $v );
							}
								
						} else {
								
							$content .= sprintf( '<li>%s</li>' , $val );
								
						}
							
					}
					$content .= '</ul>';
						
				} else {
					
					$post = get_post( $post_meta[0] );
					if( !empty($post) && intval( $post_meta[0] ) && $post->post_type == 'attachment' ) {
							
						$custom_thumbnail = wp_get_attachment_image_src( $post_meta[0], 'post-thumbnail' , true );
						if( !empty( $custom_thumbnail ) ) {
								
							$content = sprintf( '<a href="media.php?attachment_id=%1$s&action=edit"><img src="%2$s" width="%3$s" /></a>' , $post_meta[0] , $custom_thumbnail[0] , $thumbnail_size );

						} else {
								
							$content = $post_meta[0];

						}

					} else {
							
						$content = $post_meta[0];
							
					}

				}
					
			}
				
		}

		echo $content;

	}

	// FilterStart
	function posts_sortable_columns( $sortables ) {
		
		global $typenow;
		
		$list_type = $typenow;

		$Data = array();

		if( $list_type == 'post' ) {
			
			$Data = $this->ClassData->get_data_post( true );
				
		} elseif( $list_type == 'page' ) {
				
			$Data = $this->ClassData->get_data_page( true );
				
		} else {
			
			$Data = $this->ClassData->get_data_custom_post( $list_type , true );
				
		}
		
		if( !empty( $Data['use'] ) ) {

			$registed_columns = $this->ClassConfig->get_registed_columns( $list_type );
			
			if( !empty( $registed_columns ) ) {
			
				foreach( $Data['use'] as $column_id => $column ) {
					
					if( isset( $column['sort'] ) ) {
						
						if( $column['sort'] ) {
							
							if( empty( $sortables[$column_id] ) ) {
								
								if( !empty( $registed_columns['use'][$column_id]['orderby'] ) ) {
	
									$sortables[$column_id] = $registed_columns['use'][$column_id]['orderby'];
									
								} elseif( !empty( $registed_columns['not_use'][$column_id]['orderby'] ) ) {
	
									$sortables[$column_id] = $registed_columns['not_use'][$column_id]['orderby'];
									
								} else {
	
									$sortables[$column_id] = $column_id;
									
								}
								
							}
							
						} else {
							
							if( !empty( $sortables[$column_id] ) )
								unset( $sortables[$column_id] );
	
						}
						
					}
					
				}
				
			}

		}
		
		return $sortables;

	}

	// FilterStart
	function media_columns_header( $columns ) {
		
		$Data = $this->ClassData->get_data_media( true );
		$FilterColumn = array( 'cb' => $columns['cb'] );
		$default_columns = $this->ClassConfig->get_registed_columns( 'media' );

		if( !empty( $Data['use'] ) && !empty( $default_columns ) ) {

			foreach( $Data['use'] as $column_id => $column ) {

				if( array_key_exists( $column_id , $default_columns['use'] ) or array_key_exists( $column_id , $default_columns['not_use'] ) )
				$FilterColumn[$column_id] = $column['name'];
				
			}

		} else {
			
			$FilterColumn = $columns;
			
		}

		return $FilterColumn;
		
	}

	// FilterStart
	function media_columns_body( $column_name , $post_id ) {
		
		$content = '';
		$posts = get_posts( array( 'numberposts' => 1 , 'include' => $post_id , 'post_type' => 'attachment' ) );
		
		if( !empty( $posts[0] ) ) {
			
			$attachment = $posts[0];
			
			if( $column_name == 'id' ) {
				
				$content = $post_id;
				
			} elseif( $column_name == 'image_alt' ) {
				
				$image_alt = get_post_meta( $post_id , '_wp_attachment_image_alt' , true );
				$content = wp_strip_all_tags( stripslashes( $image_alt ) );

			} elseif( $column_name == 'media_title' ) {
				
				$content = _draft_or_post_title( $post_id );

			} elseif( $column_name == 'post_excerpt' ) {
				
				$content = $attachment->post_excerpt;

			} elseif( $column_name == 'post_content' ) {
				
				$content = $attachment->post_content;

			}

		}

		echo $content;
		
	}

	// FilterStart
	function media_sortable_columns( $sortables ) {
		
		$Data = $this->ClassData->get_data_media( true );
		
		if( !empty( $Data['use'] ) ) {
			
			$registed_columns = $this->ClassConfig->get_registed_columns( 'media' );
			
			if( !empty( $registed_columns ) ) {
			
				foreach( $Data['use'] as $column_id => $column ) {
					
					if( isset( $column['sort'] ) ) {
						
						if( $column['sort'] ) {
							
							if( empty( $sortables[$column_id] ) ) {
								
								if( !empty( $registed_columns['use'][$column_id]['orderby'] ) ) {
	
									$sortables[$column_id] = $registed_columns['use'][$column_id]['orderby'];
									
								} elseif( !empty( $registed_columns['not_use'][$column_id]['orderby'] ) ) {
	
									$sortables[$column_id] = $registed_columns['not_use'][$column_id]['orderby'];
									
								} else {
	
									$sortables[$column_id] = $column_id;
									
								}
								
							}
							
						} else {
							
							if( !empty( $sortables[$column_id] ) )
								unset( $sortables[$column_id] );
	
						}
						
					}
					
				}
				
			}

		}
		
		return $sortables;

	}

	// FilterStart
	function comments_columns_header( $columns ) {
		
		$Data = $this->ClassData->get_data_comments( true );
		$FilterColumn = array( 'cb' => $columns['cb'] );
		$default_columns = $this->ClassConfig->get_registed_columns( 'comments' );

		if( !empty( $Data['use'] ) && !empty( $default_columns ) ) {

			foreach( $Data['use'] as $column_id => $column ) {

				if( array_key_exists( $column_id , $default_columns['use'] ) or array_key_exists( $column_id , $default_columns['not_use'] ) )
				$FilterColumn[$column_id] = $column['name'];
				
			}

		} else {
			
			$FilterColumn = $columns;
			
		}
		
		return $FilterColumn;
		
	}

	// FilterStart
	function comments_columns_body( $column_name , $comment_id ) {
		
		$content = '';
		$comment = get_comment( $comment_id );
		
		if( !empty( $comment ) ) {
			
			if( $column_name == 'id' ) {
				
				$content = $comment_id;
				
			} elseif( $column_name == 'newcomment_author' ) {
				
				$content = $comment->comment_author;

			} elseif( $column_name == 'newcomment_author_email' ) {
				
				$content = $comment->comment_author_email;

			} elseif( $column_name == 'newcomment_author_url' ) {
				
				$content = $comment->comment_author_url;

			}

		}

		echo $content;
		
	}

	// FilterStart
	function comments_sortable_columns( $sortables ) {
		
		$Data = $this->ClassData->get_data_comments( true );
		
		if( !empty( $Data['use'] ) ) {

			$registed_columns = $this->ClassConfig->get_registed_columns( 'comments' );
			
			if( !empty( $registed_columns ) ) {
			
				foreach( $Data['use'] as $column_id => $column ) {
					
					if( isset( $column['sort'] ) ) {
						
						if( $column['sort'] ) {
							
							if( empty( $sortables[$column_id] ) ) {
								
								if( !empty( $registed_columns['use'][$column_id]['orderby'] ) ) {
	
									$sortables[$column_id] = $registed_columns['use'][$column_id]['orderby'];
									
								} elseif( !empty( $registed_columns['not_use'][$column_id]['orderby'] ) ) {
	
									$sortables[$column_id] = $registed_columns['not_use'][$column_id]['orderby'];
									
								} else {
	
									$sortables[$column_id] = $column_id;
									
								}
								
							}
							
						} else {
							
							if( !empty( $sortables[$column_id] ) )
								unset( $sortables[$column_id] );
	
						}
						
					}
					
				}
				
			}

		}

		return $sortables;

	}

	// FilterStart
	function users_columns_header( $columns ) {
		
		$Data = $this->ClassData->get_data_users( true );
		$FilterColumn = array( 'cb' => $columns['cb'] );
		$default_columns = $this->ClassConfig->get_registed_columns( 'users' );

		if( !empty( $Data['use'] ) && !empty( $default_columns ) ) {

			foreach( $Data['use'] as $column_id => $column ) {

				if( array_key_exists( $column_id , $default_columns['use'] ) or array_key_exists( $column_id , $default_columns['not_use'] ) )
				$FilterColumn[$column_id] = $column['name'];
				
			}

		} else {
			
			$FilterColumn = $columns;
			
		}
		
		return $FilterColumn;
		
	}

	// FilterStart
	function users_columns_body( $false , $column_name , $user_id ) {
		
		$content = '';
		$user = get_userdata( $user_id );
		
		if( !empty( $user ) ) {
			
			if( $column_name == 'id' ) {
				
				$content = $user_id;
				
			}

		}

		return $content;
		
	}

	// FilterStart
	function users_sortable_columns( $sortables ) {
		
		$Data = $this->ClassData->get_data_users( true );
		
		if( !empty( $Data['use'] ) ) {

			$registed_columns = $this->ClassConfig->get_registed_columns( 'users' );
			
			if( !empty( $registed_columns ) ) {
			
				foreach( $Data['use'] as $column_id => $column ) {
					
					if( isset( $column['sort'] ) ) {
						
						if( $column['sort'] ) {
							
							if( empty( $sortables[$column_id] ) ) {
								
								if( !empty( $registed_columns['use'][$column_id]['orderby'] ) ) {
	
									$sortables[$column_id] = $registed_columns['use'][$column_id]['orderby'];
									
								} elseif( !empty( $registed_columns['not_use'][$column_id]['orderby'] ) ) {
	
									$sortables[$column_id] = $registed_columns['not_use'][$column_id]['orderby'];
									
								} else {
	
									$sortables[$column_id] = $column_id;
									
								}
								
							}
							
						} else {
							
							if( !empty( $sortables[$column_id] ) )
								unset( $sortables[$column_id] );
	
						}
						
					}
					
				}
				
			}

		}

		return $sortables;

	}

	// FilterStart
	function widgets_menu() {

		global $wp_registered_widgets;

		$Data = $this->ClassData->get_data_widgets( true );
		
		if( !empty( $Data['not_use'] ) && !empty( $wp_registered_widgets ) ) {

			$widgets_pattern_ids = array();

			foreach( $Data['not_use'] as $widget_id => $widget_setting ) {

				preg_match( '/(.*)-[0-9]/' , $widget_id , $match );
				
				if( !empty( $match[1] ) )
					$widgets_pattern_ids[] = $match[1];
				
			}
			
			foreach( $wp_registered_widgets as $core_widget_id => $widget ) {
				
				foreach( $widgets_pattern_ids as $widget_id ) {
	
					preg_match( '/' . $widget_id . '-[0-9]/' , $core_widget_id , $match );
					
					if( empty( $match[0] ) )
						continue;

					unset( $wp_registered_widgets[$core_widget_id] );
					break;
					
				}

			}

		}

	}

	// FilterStart
	function nav_menu_metabox( $columns ) {

		$Data = $this->ClassData->get_data_menus( true );
		
		if( !empty( $Data['not_use'] ) ) {

			foreach( $Data['not_use'] as $menu_id => $menu_setting ) {

				remove_meta_box( $menu_id , 'nav-menus' , 'side' );
				
			}

		}

		return $columns;

	}

	// FilterStart
	function menu_columns_header( $columns ) {

		$Data = $this->ClassData->get_data_menus_adv( true );
		
		if( !empty( $Data['not_use'] ) ) {
			
			foreach( $Data['not_use'] as $menu_id => $menu_name ) {

				if( !empty( $columns[$menu_id] ) )
					unset( $columns[$menu_id] );
				
			}

			if( count( $columns ) == 2 ) {
				$columns = array();
			}

		}

		return $columns;

	}

	// FilterStart
	function menu_advance_style() {

		$Data = $this->ClassData->get_data_menus_adv( true );
		
		$hide_set = '';
		$hide_field = '';

		foreach( $Data['not_use'] as $menu_id => $menu_name ) {
			
			$hide_set .= sprintf( '.metabox-prefs label[for=%s-hide], ' , $menu_id );
			$hide_field .= sprintf( '.menu-item-settings p.field-%s, ' , $menu_id );
			
		}

		$hide_set = rtrim( $hide_set , ', ' );
		$hide_field = rtrim( $hide_field , ', ' );

		if( !empty( $hide_field ) ) {

			printf( '<style>%s { display: none; }</style>' , $hide_field );
			printf( '<style>%s { display: none; }</style>' , $hide_set );

		}
		
	}

}

$GLOBALS['Plvc'] = new Post_Lists_View_Custom();

endif;
