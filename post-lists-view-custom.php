<?php
/*
Plugin Name: Post Lists View Custom
Description: Allow to customizing for the list screen.
Plugin URI: http://wordpress.org/extend/plugins/post-lists-view-custom/
Version: 1.5.7
Author: gqevu6bsiz
Author URI: http://gqevu6bsiz.chicappa.jp/?utm_source=use_plugin&utm_medium=list&utm_content=plvc&utm_campaign=1_5_7
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





class Post_Lists_View_Custom
{

	var $Ver,
		$Name,
		$Dir,
		$Url,
		$AuthorUrl,
		$ltd,
		$ltd_p,
		$Record,
		$PageSlug,
		$PluginSlug,
		$SetPage,
		$ThumbnailSize,
		$CustomFields,
		$Nonces,
		$Schema,
		$PageTitle,
		$UPFN,
		$Msg,
		$MsgQ;


	function __construct() {
		$this->Ver = '1.5.7';
		$this->Name = 'Post Lists View Custom';
		$this->Dir = plugin_dir_path( __FILE__ );
		$this->Url = plugin_dir_url( __FILE__ );
		$this->AuthorUrl = 'http://gqevu6bsiz.chicappa.jp/';
		$this->ltd = 'plvc';
		$this->Record = array(
			"user_role" => $this->ltd . '_user_role',
			"post" => $this->ltd . '_post',
			"page" => $this->ltd . '_page',
			"media" => $this->ltd . '_media',
			"comments" => $this->ltd . '_comments',
			"widgets" => $this->ltd . '_widgets',
			"menus" => $this->ltd . '_menus',
			"menus_adv" => $this->ltd . '_menus_adv',
			"custom_posts" => $this->ltd . '_custom_posts',
			"thunmbnail" => $this->ltd . '_thumbnail',
			"regist_columns" => $this->ltd . '_regist_columns',
			"donate" => $this->ltd . '_donated',
			"donate_width" => $this->ltd . '_donate_width',
		);

		$this->PageSlug = 'post_lists_view_custom';
		$this->PluginSlug = dirname( plugin_basename( __FILE__ ) );
		$this->ThumbnailSize = 50;
		$this->CustomFields = array();
		$this->Nonces = array( "field" => $this->ltd . '_field' , "value" => $this->ltd . '_value' );
		$this->Schema = is_ssl() ? 'https://' : 'http://';
		$this->PageTitle = $this->Name;
		$this->UPFN = 'Y';
		$this->DonateKey = 'd77aec9bc89d445fd54b4c988d090f03';
		$this->Msg = '';
		$this->MsgQ = $this->ltd . '_msg';

		$this->PluginSetup();
		$this->FilterStart();
	}
	
	// PluginSetup
	function PluginSetup() {
		// load text domain
		load_plugin_textdomain( $this->ltd , false , $this->PluginSlug . '/languages' );

		// plugin links
		add_filter( 'plugin_action_links' , array( $this , 'plugin_action_links' ) , 10 , 2 );

		// add menu
		add_action( 'admin_menu' , array( $this , 'admin_menu' ) , 2 );

		// data update
		add_action( 'admin_init' , array( $this , 'dataUpdate') );

		// get donation toggle
		add_action( 'wp_ajax_plvc_get_donation_toggle' , array( $this , 'wp_ajax_plvc_get_donation_toggle' ) );

		// set donation toggle
		add_action( 'wp_ajax_plvc_set_donation_toggle' , array( $this , 'wp_ajax_plvc_set_donation_toggle' ) );

		// setting check user role
		add_action( 'admin_notices' , array( $this , 'settingCheck' ) ) ;

		// default columns load.
		add_action( 'load-edit.php' , array( $this , 'post_columns_default_load_action' ) );

		// get all custom_fields
		add_action( 'admin_init' , array( $this , 'get_all_custom_fields') );
	}

	// PluginSetup
	function plugin_action_links( $links , $file ) {
		if( plugin_basename(__FILE__) == $file ) {
			$support_link = '<a href="http://wordpress.org/support/plugin/' . $this->PluginSlug . '" target="_blank">' . __( 'Support Forums' ) . '</a>';
			array_unshift( $links, $support_link );
			array_unshift( $links, '<a href="' . admin_url( 'admin.php?page=' . $this->PageSlug ) . '">' . __('Settings') . '</a>' );

		}
		return $links;
	}

	// PluginSetup
	function admin_menu() {
		add_menu_page( 'Post Lists View Custom' , 'Post Lists View Custom' , 'administrator', $this->PageSlug , array( $this , 'setting_default') );
		add_submenu_page( $this->PageSlug , __( 'Posts' ) . __( 'List View' ) . __( 'Customize' ) , __( 'All Posts' ) , 'administrator' , $this->Record["post"] , array( $this , 'setting_post' ) );
		add_submenu_page( $this->PageSlug , __( 'Pages') . __( 'List View' ) . __( 'Customize' ) , __( 'All Pages' ) , 'administrator' , $this->Record["page"] , array( $this , 'setting_page' ) );
		add_submenu_page( $this->PageSlug , __( 'Media Library' ) . __( 'Customize' ) , __( 'Media Library' ) , 'administrator' , $this->Record["media"] , array( $this , 'setting_media' ) );
		add_submenu_page( $this->PageSlug , __( 'Comments' ) . __( 'Customize' ) , __( 'Comments' ) , 'administrator' , $this->Record["comments"] , array( $this , 'setting_comments' ) );
		add_submenu_page( $this->PageSlug , __( 'Available Widgets' ) . __( 'Customize' ) , __( 'Available Widgets' ) , 'administrator' , $this->Record["widgets"] , array( $this , 'setting_widgets' ) );
		add_submenu_page( $this->PageSlug , __( 'Menus' ) . ' '. __( 'show screen' , $this->ltd ) . ' '. __( 'Customize' ) , __( 'Menus' ) , 'administrator' , $this->Record["menus"] , array( $this , 'setting_menus' ) );
		add_submenu_page( $this->PageSlug , __( 'Menus' ) . ' '. __( 'show advanced properties screen' , $this->ltd ) . ' '. __( 'Customize' ) , __( 'Menus' ) . ' ' . __( 'advanced properties' , $this->ltd ) , 'administrator' , $this->Record["menus_adv"] , array( $this , 'setting_menus_adv' ) );
		add_submenu_page( $this->PageSlug , __( 'Custom Post Type' , $this->ltd ) , __( 'Custom Post Type' , $this->ltd ) , 'administrator' , 'select_custom_posts_list_view_setting' , array( $this , 'select_custom_posts' ) );
		add_submenu_page( $this->PageSlug , __( 'Custom Post Type' , $this->ltd )  . __( 'Customize' ) , sprintf( '<div style="display: none;">$s</div>' , __( 'Custom Post Type' , $this->ltd ) ) , 'administrator' , $this->Record["custom_posts"] , array( $this , 'setting_custom_posts' ) );
		add_submenu_page( $this->PageSlug , __( 'Thumbnail size' ) , __( 'Thumbnail size' ) , 'administrator' , $this->Record["thunmbnail"] , array( $this , 'setting_thumbnail' ) );
	}





	// SettingPage
	function setting_default() {
		add_filter( 'admin_footer_text' , array( $this , 'layout_footer' ) );
		$this->display_msg();
		$this->DisplayDonation();
		include_once 'inc/setting_default.php';
	}

	// SettingPage
	function setting_post() {
		$this->SetPage = 'post';
		$this->PageTitle = sprintf( __( '%2$s for %3$s %1$s' , $this->ltd ) , __( 'Customize' ) , __( 'List View' ) , __( 'Posts' ) ) ;
		
		add_filter( 'admin_footer_text' , array( $this , 'layout_footer' ) );
		$this->display_msg();
		$this->DisplayDonation();
		include_once 'inc/setting_lists_post.php';
	}

	// SettingPage
	function setting_page() {
		$this->SetPage = 'page';
		$this->PageTitle = sprintf( __( '%2$s for %3$s %1$s' , $this->ltd ) , __( 'Customize' ) , __( 'List View' ) , __( 'Pages' ) ) ;
		
		add_filter( 'admin_footer_text' , array( $this , 'layout_footer' ) );
		$this->display_msg();
		$this->DisplayDonation();
		include_once 'inc/setting_lists_post.php';
	}

	// SettingPage
	function setting_media() {
		$this->SetPage = 'media';
		$this->PageTitle = sprintf( __( '%2$s for %3$s %1$s' , $this->ltd ) , __( 'Customize' ) , __( 'List View' ) , __( 'Media Library' ) ) ;
		
		add_filter( 'admin_footer_text' , array( $this , 'layout_footer' ) );
		$this->display_msg();
		$this->DisplayDonation();
		include_once 'inc/setting_lists.php';
	}

	// SettingPage
	function setting_comments() {
		$this->SetPage = 'comments';
		$this->PageTitle = sprintf( __( '%2$s for %3$s %1$s' , $this->ltd ) , __( 'Customize' ) , __( 'List View' ) , __( 'Comments' ) ) ;
		
		add_filter( 'admin_footer_text' , array( $this , 'layout_footer' ) );
		$this->display_msg();
		$this->DisplayDonation();
		include_once 'inc/setting_lists.php';
	}

	// SettingPage
	function setting_widgets() {
		$this->SetPage = 'widgets';
		$this->PageTitle = sprintf( __( '%2$s for %3$s %1$s' , $this->ltd ) , __( 'Customize' ) , __( 'List View' ) , __( 'Available Widgets' ) ) ;
		
		add_filter( 'admin_footer_text' , array( $this , 'layout_footer' ) );
		$this->display_msg();
		$this->DisplayDonation();
		include_once 'inc/setting_menus.php';
	}

	// SettingPage
	function setting_menus() {
		$this->SetPage = 'menus';
		$this->PageTitle = sprintf( __( '%2$s for %3$s %1$s' , $this->ltd ) , __( 'Customize' ) , __( 'List View' ) , __( 'Menus' ) . ' ' . __( 'show screen' , $this->ltd ) ) . ' ' ;
		
		add_filter( 'admin_footer_text' , array( $this , 'layout_footer' ) );
		$this->display_msg();
		$this->DisplayDonation();
		include_once 'inc/setting_menus.php';
	}

	// SettingPage
	function setting_menus_adv() {
		$this->SetPage = 'menus_adv';
		$this->PageTitle = __( 'Menus of advanced feature adapted to the screen' , $this->ltd );
		
		add_filter( 'admin_footer_text' , array( $this , 'layout_footer' ) );
		$this->display_msg();
		$this->DisplayDonation();
		include_once 'inc/setting_menus.php';
	}

	// SettingPage
	function select_custom_posts() {
		add_filter( 'admin_footer_text' , array( $this , 'layout_footer' ) );
		$this->display_msg();
		$this->DisplayDonation();
		include_once 'inc/select_custom.php';
	}

	// SettingPage
	function setting_custom_posts() {
		$this->SetPage = 'custom_posts';

		add_filter( 'admin_footer_text' , array( $this , 'layout_footer' ) );
		$this->display_msg();
		$this->DisplayDonation();

		$PostSlug = '';
		if( !empty( $_GET["setname"] ) && !empty( $_GET["name"] ) ) {
			$PostSlug = strip_tags( $_GET["name"] );
		}

		if( !empty( $PostSlug ) ) {
			$PostType = get_post_type_object( $PostSlug );
			$this->PageTitle = sprintf( __( '%2$s for %3$s %1$s' , $this->ltd ) , __( 'Customize' ) , __( 'List View' ) , __( 'Custom Post Type' , $this->ltd ) . ' ( ' . esc_html( $PostType->labels->name ) . ' )' ) ;
			include_once 'inc/setting_lists_custom_post.php';
		} else {
			echo sprintf( '<p>%s</p>' , __( 'No custom post type found.' , $this->ltd ) );
			echo sprintf( '<p><a href="%2$s">%1$s</a></p>' , __( 'Please select Custom Posts type from here.' , $this->ltd ) , admin_url( 'admin.php?page=select_custom_posts_list_view_setting' ) );
		}
	}

	// SettingPage
	function setting_thumbnail() {
		$this->SetPage = 'thunmbnail';
		$this->PageTitle = sprintf( __( '%2$s for %3$s %1$s' , $this->ltd ) , __( 'Customize' ) , __( 'List View' ) , __( 'Thumbnail size' ) ) ;
		
		add_filter( 'admin_footer_text' , array( $this , 'layout_footer' ) );
		$this->display_msg();
		$this->DisplayDonation();
		include_once 'inc/setting_thumbnail_size.php';
	}





	// GetData
	function get_data( $record ) {
		$GetData = get_option( $this->Record[$record] );
		$GetData = apply_filters( 'plvc_pre_get_data' , $GetData , $record );

		$Data = array();
		if( !empty( $GetData ) ) {
			$Data = $GetData;
		}

		return $Data;
	}

	// GetData
	function get_filt_data( $record ) {
		$GetData = get_option( $this->Record[$record] );
		$GetData = apply_filters( 'plvc_pre_get_filt_data' , $GetData , $record );

		$Data = array();
		if( !empty( $GetData ) ) {
			$Data = $GetData;
		}

		return $Data;
	}




	// Settingcheck
	function settingCheck() {
		global $current_screen;

		$Data = $this->get_data( 'user_role' );

		if( !empty( $Data["UPFN"] ) ) {
			unset( $Data["UPFN"] );
		}

		if( empty( $Data ) ) {
			if( $current_screen->parent_base == $this->PageSlug && $current_screen->id != 'toplevel_page_' . $this->PageSlug ) {
				echo '<div class="error"><p><strong>' . sprintf( __( 'Authority to apply the setting is not selected. <a href="%s">From here</a>, please select the permissions you want to set.' , $this->ltd ) , admin_url( 'admin.php?page=' . $this->PageSlug ) ) . '</strong></p></div>';
			}
		}
	}




	// SetList
	function get_user_role() {
		$editable_roles = get_editable_roles();
		foreach ( $editable_roles as $role => $details ) {
			$editable_roles[$role]["label"] = translate_user_role( $details['name'] );
		}

		return $editable_roles;
	}

	// SetList
	function get_apply_roles() {

		$apply_user_roles = $this->get_data( 'user_role' );
		unset( $apply_user_roles["UPFN"] );
		
		$Contents =  __( 'Apply user roles' , $this->ltd ) . ' : ';
		
		if( !empty( $apply_user_roles ) ) {
			$UserRoles = $this->get_user_role();
			foreach( $apply_user_roles as $role => $v ) {
				$Contents .= '[ ' . $UserRoles[$role]["label"] . ' ]';
			}
		} else {
			$Contents .= __( 'None' );
		}

		$Contents = apply_filters( 'plvc_get_apply_roles' , $Contents );

		return $Contents;

	}

	// SetList
	function post_columns_default_load( $columns ) {
		global $typenow;
		
		$UserRole = $this->current_user_role_group();
		
		$NowColumns = array();
		if( $UserRole == 'administrator' && !empty( $columns ) ) {

			$RegistColumns = $this->get_data( 'regist_columns' );
			$NowColumns = $columns;
			unset( $NowColumns["cb"] );

			$RegistColumns[$typenow] = $NowColumns;
			
			update_option( $this->Record["regist_columns"] , $RegistColumns );
		}
		
		return $columns;
	}

	// SetList
	function get_all_custom_fields() {
		global $wpdb;

		// All Post Custom Field meta
		$All_custom_columns = $wpdb->get_col( "SELECT meta_key FROM $wpdb->postmeta GROUP BY meta_key HAVING meta_key NOT LIKE '\_%' ORDER BY meta_key" );
		if(!empty($All_custom_columns)) {
			natcasesort($All_custom_columns);
		}

		// Unset colum name
		$Unset = array( 'allorany' , 'hide_on_screen' );
		foreach($Unset as $name) {
			if( array_search( $name , $All_custom_columns ) !== false ) {
				unset( $All_custom_columns[array_search( $name , $All_custom_columns )] );
			}
		}
		
		// Unset colum match name
		$MatchUnset = array( 'field_' );
		foreach( $MatchUnset as $name ) {
			foreach( $All_custom_columns as $key => $val ) {
				preg_match( '/^' . $name . '/' , $val , $match );
				if( !empty( $match ) ) {
					unset( $All_custom_columns[$key] );
				}
			}
		}
		$this->CustomFields = $All_custom_columns;
	}

	// SetList
	function get_custom_fields_columns( $Columns ) {
		foreach( $this->CustomFields as $name ) {
			$Columns[$name] = array( "not_use" => 1 , "name" => $name , "group" => "custom_fields" );
		}
		unset($All_custom_columns);

		return $Columns;
	}

	// SetList
	function check_column_type( $post_type , $column_id ) {
		$check = false;
		
		if( $post_type == 'post' or $post_type == 'page' ) {
			$Default_columns = $this->get_default_columns_type( $post_type );
		} else {
			$Default_columns = $this->get_default_columns_type( 'custom_posts' );
		}

		if( !in_array( $column_id, $Default_columns ) ) {

			$RegistColumns = $this->get_data( 'regist_columns' );
			$all_custom_fields = $this->CustomFields;

			if( in_array( $column_id, $all_custom_fields ) ) {

				$check = 'custom_fields';

			} elseif( !empty( $RegistColumns[$post_type] ) ) {

				$CurrentColumns = $RegistColumns[$post_type];
				foreach( $Default_columns as $column_name ) {
					unset( $CurrentColumns[$column_name] );
				}
				if( array_key_exists( $column_id , $CurrentColumns ) ) {
					$check = 'plugin';
				}

			}

		}

		return $check;

	}

	// SetList
	function get_plugin_fields_columns( $post_type , $Columns ) {
		$RegistColumns = $this->get_data( 'regist_columns' );

		if( !empty( $RegistColumns[$post_type] ) ) {
			
			$CurrentColumns = $RegistColumns[$post_type];

			foreach( $CurrentColumns as $column_name => $column_label ) {
				if( empty( $Columns[$column_name] ) ) {
					$Columns[$column_name] = array( "use" => 1 , "name" => $column_label , "group" => "plugin" );
				}
			}
		}

		return $Columns;
	}

	// SetList
	function replace_columns_label( $column ) {
		$post_type = strip_tags( $column[0] );
		$column_name = strip_tags( $column[1] );
		$Label = '';
		
		if( $column_name == 'title' ) {

			if( $post_type == 'media' ) {
				$Label = _x( 'File' , 'column name' );
			} else {
				$Label = __( 'Title' );
			}

		} elseif( $column_name == 'author' ) { $Label = __( 'Author' );
		} elseif( $column_name == 'categories' ) { $Label = __( 'Categories' );
		} elseif( $column_name == 'tags' ) { $Label = __( 'Tags' );
		} elseif( $column_name == 'comments' or $column_name == 'comment' ) { $Label = __( 'Comments' );
		} elseif( $column_name == 'slug' ) { $Label = __( 'Slug' );
		} elseif( $column_name == 'excerpt' ) { $Label = __('Excerpt');
		} elseif( $column_name == 'id' ) { $Label = __( 'ID' );

		} elseif( $column_name == 'post-thumbnails' ) { $Label = __( 'Featured Image' );
		} elseif( $column_name == 'post-formats' ) { $Label = __( 'Format' );

		} elseif( $column_name == 'media_title' ) { $Label = __( 'Title' );

		} elseif( $column_name == 'post_excerpt' ) { $Label = __('Caption');
		} elseif( $column_name == 'post_content' ) { $Label = __('Details');
		} elseif( $column_name == 'icon' ) { $Label = __( 'Image' );
		} elseif( $column_name == 'response' ) { $Label = _x( 'In Response To' , 'column name' ) ;
		} elseif( $column_name == 'newcomment_author' ) { $Label = __( 'Name' );
		} elseif( $column_name == 'newcomment_author_email' ) { $Label = __( 'E-mail' );
		} elseif( $column_name == 'newcomment_author_url' ) { $Label = __( 'URL' );

		} elseif( $column_name == 'nav-menu-theme-locations' ) { $Label = __( 'Theme Locations' );
		} elseif( $column_name == 'add-page' ) { $Label = __( 'Pages' );
		} elseif( $column_name == 'add-category' ) { $Label = __( 'Categories' );
		} elseif( $column_name == 'add-post_format' ) { $Label = __('Format');
		} elseif( $column_name == 'add-post' ) { $Label = __( 'Posts' );
		} elseif( $column_name == 'add-post_tag' ) { $Label = __( 'Tags' );

		} elseif( $column_name == 'link-target' ) { $Label = __('Link Target');
		} elseif( $column_name == 'css-classes' ) { $Label = __( 'CSS Classes' );
		} elseif( $column_name == 'xfn' ) { $Label = __( 'Link Relationship (XFN)' );
		} elseif( $column_name == 'description' ) { $Label = __( 'Description' );

		} elseif( $column_name == 'parent' ) {

			$Label = _x( 'Uploaded to' , 'column name' );

		} elseif( $column_name == 'image_alt' ) {

			$Label = __( 'Alternative Text' );

		} elseif( $column_name == 'date' ) {

			if( $post_type == 'media' ) {
				$Label = _x( 'Date' , 'column name' );
			} else {
				$Label = __( 'Date' );
			}

		} elseif( $column_name == 'add-custom-links' ) {

			$Label = __( 'Links' );

		} else {
			
			if( $post_type == 'menus' ) {

				$custom_post_type_name = str_replace( 'add-' , '' , $column_name );
				
				if( $custom_post_type_name ) {
	
					// menus
					$args = array( 'public' => true, '_builtin' => false );
					$PostTypes = get_post_types( $args , 'objects' );
					
					if( !empty( $PostTypes ) && is_array( $PostTypes ) ) {
	
						$Label = $column_name;
	
						foreach( $PostTypes as $name => $Type ) {
							if( $name == $custom_post_type_name ) {
								$Label = esc_html( $Type->labels->name );
								break;
							}
						}
	
					}

				}


			} else {

				$Label = $column_name;

			}
			
		}

		return $Label;

	}

	// GetData
	function get_list_columns( $type ) {
		$Columns = array();
		
		if( !empty( $type ) ) {
			if( $type == 'post' ) {
				$Columns = $this->get_post_columns();
			} elseif( $type == 'page' ) {
				$Columns = $this->get_page_columns();
			} elseif( $type == 'media' ) {
				$Columns = $this->get_media_columns();
			} elseif( $type == 'comments' ) {
				$Columns = $this->get_comments_columns();
			} elseif( $type == 'widgets' ) {
				$Columns = $this->get_widgets_columns();
			} elseif( $type == 'menus' ) {
				$Columns = $this->get_menus_columns();
			} elseif( $type == 'menus_adv' ) {
				$Columns = $this->get_menus_adv_columns();
			} elseif( $type == 'custom_posts' ) {
				$Columns = $this->get_custom_posts_columns();
			}
		}
		
		return $Columns;
	}

	// SetList
	function get_default_columns_type( $post_type ) {
		$Default_columns = array();
		
		if( !empty( $post_type ) ) {

			if( $post_type == 'post' or $post_type == 'custom_posts' ) {

				$Default_columns = array(
					"title" , "author" , "categories" , "tags" , "comments" , "date" , "slug" , "excerpt" , "id"
				);
				
				// Theme Support colum
				$ThemeSupports = array( 'post-thumbnails' , 'post-formats' );
				foreach( $ThemeSupports as $name ) {
					$Support = current_theme_supports( $name );
					if(!empty($Support)) {
						$Default_columns[] = $name;
					}
				}
				unset( $ThemeSupports );

			} elseif( $post_type == 'page' ) {

				$Default_columns = array(
					"title" , "author" , "comments" , "date" , "slug" , "excerpt" , "id"
				);
				
				// Theme Support colum
				$ThemeSupports = array( 'post-thumbnails' , 'post-formats' );
				foreach( $ThemeSupports as $name ) {
					$Support = current_theme_supports( $name );
					if(!empty($Support)) {
						$Default_columns[] = $name;
					}
				}
				unset( $ThemeSupports );

			} elseif( $post_type == 'media' ) {

				$Default_columns = array(
					"icon" , "title" , "author" , "parent" , "comments" , "date" , "media_title" , "image_alt" , "post_excerpt" , "post_content" , "id"
				);
				
			} elseif( $post_type == 'comments' ) {

				$Default_columns = array(
					"author" , "comment" , "response" , "newcomment_author" , "newcomment_author_email" , "newcomment_author_url" , "id"
				);
				
			}

		}

		return $Default_columns;
	}

	// SetList
	function get_post_columns() {
		// Default colum
		$Default_columns = $this->get_default_columns_type( 'post' );

		// set label
		$SetColumns = array();
		foreach( $Default_columns as $id ) {
			$SetColumns[$id] = $this->replace_columns_label( array( "page" , $id ) );
		}
		unset($Default_columns);

		// Default View Setting
		$Columns = array();
		foreach($SetColumns as $column => $name) {
			if( $column == 'title' or $column == 'author' or $column == 'categories' or $column == 'tags' or $column == 'comments' or $column == 'date' ) {
				$Columns[$column] = array( "use" => 1 , "name" => $name );
			} else {
				$Columns[$column] = array( "not_use" => 1 , "name" => $name );
			}
		}
		unset($SetColumns);

		// Custom fields Marge
		$Columns = $this->get_custom_fields_columns( $Columns );

		// Plugin fiedls Marge
		$Columns = $this->get_plugin_fields_columns( "post" , $Columns );

		return $Columns;
	}

	// SetList
	function get_page_columns() {
		// Default colum
		$Default_columns = $this->get_default_columns_type( 'page' );

		// set label
		$SetColumns = array();
		foreach( $Default_columns as $id ) {
			$SetColumns[$id] = $this->replace_columns_label( array( "page" , $id ) );
		}
		unset($Default_columns);

		// Default View Setting
		$Columns = array();
		foreach($SetColumns as $column => $name) {
			if( $column == 'title' or $column == 'author' or $column == 'comments' or $column == 'date' ) {
				$Columns[$column] = array( "use" => 1 , "name" => $name );
			} else {
				$Columns[$column] = array( "not_use" => 1 , "name" => $name );
			}
		}
		unset($SetColumns);

		// Custom fields Marge
		$Columns = $this->get_custom_fields_columns( $Columns );

		// Plugin fiedls Marge
		$Columns = $this->get_plugin_fields_columns( "page" , $Columns );

		return $Columns;

	}

	// SetList
	function get_media_columns() {
		// Default colum
		$Default_columns = $this->get_default_columns_type( 'media' );

		// set label
		$SetColumns = array();
		foreach( $Default_columns as $id ) {
			$SetColumns[$id] = $this->replace_columns_label( array( "media" , $id ) );
		}
		unset($Default_columns);

		// Default View Setting
		$Columns = array();
		foreach($SetColumns as $column => $name) {
			if( $column == 'icon' or $column == 'title' or $column == 'author' or $column == 'parent' or $column == 'comments' or $column == 'date' ) {
				$Columns[$column] = array( "use" => 1 , "name" => $name );
			} else {
				$Columns[$column] = array( "not_use" => 1 , "name" => $name );
			}
		}
		unset($SetColumns);

		return $Columns;

	}

	// SetList
	function get_comments_columns() {
		// Default colum
		$Default_columns = $this->get_default_columns_type( 'comments' );

		// set label
		$SetColumns = array();
		foreach( $Default_columns as $id ) {
			$SetColumns[$id] = $this->replace_columns_label( array( "comments" , $id ) );
		}
		unset($Default_columns);

		// Default View Setting
		$Columns = array();
		foreach($SetColumns as $column => $name) {
			if( $column == 'author' or $column == 'comment' or $column == 'response' ) {
				$Columns[$column] = array( "use" => 1 , "name" => $name );
			} else {
				$Columns[$column] = array( "not_use" => 1 , "name" => $name );
			}
		}
		unset($SetColumns);

		return $Columns;

	}

	// SetList
	function get_widgets_columns() {

		global $wp_registered_widgets;

		// Default Available
		$Columns_Def = array();
		foreach( $wp_registered_widgets as $widget_id => $widget ) {
			$Columns_Def[$widget_id] = $widget["name"];
		}
		
		// Default View Setting
		$Columns = array();
		$Default_columns = array( 'pages-1' , 'calendar-1' , 'archives-2' , 'meta-2' , 'search-2' , 'text-1' , 'categories-2' , 'recent-posts-2' , 'recent-comments-2' , 'rss-1' , 'tag_cloud-1' , 'nav_menu-1' );
		foreach($Columns_Def as $column => $name) {
			$group = false;
			if( !in_array( $column  , $Default_columns ) ) {
				$group = "plugin";
			}
			$Columns[$column] = array( "use" => 1 , "name" => $name , "group" => $group );
		}
		unset($Columns_Def);

		return $Columns;

	}

	// SetList
	function get_menus_columns() {
		// Default colum
		$Columns_Def = array(
			"nav-menu-theme-locations" , "add-custom-links" , "add-page" ,
			"add-category" , "add-post_format" , "add-post" , "add-post_tag" , 
		);

		if( in_array( "nav-menu-theme-locations" , $Columns_Def ) ) {
			$index = array_search( "nav-menu-theme-locations" , $Columns_Def );
			unset( $Columns_Def[$index] );
		}

		// set label
		$SetColumns = array();
		foreach( $Columns_Def as $id ) {
			$SetColumns[$id] = $this->replace_columns_label( array( "menus" , $id ) );
		}
		unset($Columns_Def);

		// Default View Setting
		$Columns = array();
		foreach($SetColumns as $column => $name) {
			if( $column == 'nav-menu-theme-locations' or $column == 'add-custom-links' or $column == 'add-page' or $column == 'add-category' or $column == 'add-post_format' ) {
				$Columns[$column] = array( "use" => 1 , "name" => $name );
			} else {
				$Columns[$column] = array( "not_use" => 1 , "name" => $name );
			}
		}
		unset($SetColumns);

		// Custom Post type suppoted nav-menu
		$CustomPosts = get_post_types( array( 'show_in_nav_menus' => true ), 'object' );
		unset( $CustomPosts["post"] );
		unset( $CustomPosts["page"] );
		
		if( !empty( $CustomPosts ) ) {
			foreach ( $CustomPosts as $CustomPost ) {
				$Columns['add-'.$CustomPost->name] = array( "use" => 1 , "name" => esc_html( $CustomPost->labels->name ) , "group" => "custom_post" );
			}
		}

		// Custom Taxonomies
		$CustomTaxonomies = get_taxonomies( array( 'show_in_nav_menus' => true ), 'object' );
		unset( $CustomTaxonomies["category"] );
		unset( $CustomTaxonomies["post_tag"] );
		unset( $CustomTaxonomies["post_format"] );
		if( !empty( $CustomTaxonomies ) ) {
			foreach ( $CustomTaxonomies as $CustomTaxonomy ) {
				$Columns['add-'.$CustomTaxonomy->name] = array( "use" => 1 , "name" => esc_html( $CustomTaxonomy->labels->name ) , "group" => "custom_taxonomy" );
			}
		}

		return $Columns;

	}

	// SetList
	function get_menus_adv_columns() {

		// Default colum
		$Columns_Def = array(
			"link-target" , "css-classes" , "xfn" , "description"
		);

		// set label
		$SetColumns = array();
		foreach( $Columns_Def as $id ) {
			$SetColumns[$id] = $this->replace_columns_label( array( "menus_adv" , $id ) );
		}
		unset($Columns_Def);


		// Default View Setting
		$Columns = array();
		foreach($SetColumns as $column => $name) {
			if( $column == 'nav-menu-theme-locations' or $column == 'add-custom-links' or $column == 'add-page' or $column == 'add-category' or $column == 'add-post_format' ) {
				$Columns[$column] = array( "use" => 1 , "name" => $name );
			} else {
				$Columns[$column] = array( "not_use" => 1 , "name" => $name );
			}
		}
		unset($SetColumns);

		return $Columns;

	}

	// SetList
	function get_custom_posts_columns() {
		// Default colum
		$Default_columns = $this->get_default_columns_type( 'custom_posts' );

		// set label
		$SetColumns = array();
		foreach( $Default_columns as $id ) {
			$SetColumns[$id] = $this->replace_columns_label( array( "post" , $id ) );
		}
		unset($Default_columns);

		// Default View Setting
		$Columns = array();
		foreach($SetColumns as $column => $name) {
			if( $column == 'title' or $column == 'author' or $column == 'comments' or $column == 'date' ) {
				$Columns[$column] = array( "use" => 1 , "name" => $name );
			} else {
				$Columns[$column] = array( "not_use" => 1 , "name" => $name );
			}
		}
		unset($SetColumns);

		// Custom fields Marge
		$Columns = $this->get_custom_fields_columns( $Columns );

		// Plugin fiedls Marge
		$post_type = "post";
		if( !empty( $_GET["setname"] ) && !empty( $_GET["name"] ) && $_GET["setname"] == 'custom_posts' ) {
			$post_type = strip_tags( $_GET["name"] );
		}
		$Columns = $this->get_plugin_fields_columns( $post_type , $Columns );

		return $Columns;

	}

	// SetList
	function setting_list_widget( $type , $column_id , $column , $post_type ) {
		if( !empty( $type ) ) {
			$class = 'widget';
			
			$group = '';

			if( !empty( $column["group"] ) ) {

				$group = strip_tags( $column["group"] );

			} else {

				$column_type = $this->check_column_type( $post_type , $column_id );
				if( !empty( $column_type ) ) {
					$group = $column_type;
				}

			}

			$class .= ' ' . $group;
				
?>
			<div id="<?php echo $column_id; ?>" class="<?php echo $class; ?>">
				<div class="widget-top">
						<div class="widget-title-action">
							<a class="widget-action hide-if-no-js" href="#available-widgets"></a>
						</div>
					<div class="widget-title">
						<h4>
							<?php echo stripslashes( $column["name"] ); ?>
							<span class="in-widget-title">: <?php echo strip_tags( $column_id ); ?></span>
						</h4>
					</div>
				</div>
				<div class="widget-inside">
					<div class="widget-content">
						<p><?php _e( 'Column' ); ?>: <?php echo strip_tags( $column_id ); ?></p>
						<p>
							<label>
								<?php _e( 'Name' ); ?>:<br />
								<input type="text" name="<?php echo $type; ?>[<?php echo $column_id; ?>][name]" value="<?php echo esc_html( stripslashes( $column["name"] ) ); ?>" class="large-text" />
							</label>
						</p>
					</div>
					<input type="hidden" name="<?php echo $type; ?>[<?php echo $column_id; ?>][id]" value="<?php echo strip_tags( $column_id ); ?>" />
					<input type="hidden" name="<?php echo $type; ?>[<?php echo $column_id; ?>][group]" value="<?php echo $group; ?>" />
				</div>
			</div>
<?php 
		}
	}

	// SetList
	function setting_list_menu( $Data , $column_id , $column , $post_type ) {
		if( !empty( $column_id ) ) {
			$class = '';
			if( !empty( $column["group"] ) ) {
				$class .= ' ' . $column["group"];
			}
?>
			<tr id="<?php echo $column_id; ?>" class="<?php echo $class; ?>">
				<th><?php echo stripslashes( $column["name"] ); ?></th>
				<td><label><input type="checkbox" name="not_use[<?php echo $column_id; ?>][name]" value="<?php echo esc_html( stripslashes( $column["name"] ) ); ?>" <?php if( !empty( $Data["not_use"][$column_id] ) ) checked( $column["name"] , $Data["not_use"][$column_id]["name"] ); ?> /> <?php _e ( 'Hide' ); ?></label></td>
			</tr>
<?php 
		}
	}

	// SetList
	function current_user_role_group() {
		$UserRole = '';
		$User = wp_get_current_user();
		if( !empty( $User->roles ) ) {
			foreach( $User->roles as $role ) {
				$UserRole = $role;
				break;
			}
		}
		return $UserRole;
	}

	// SetList
	function wp_ajax_plvc_get_donation_toggle() {
		echo get_option( $this->ltd . '_donate_width' );
		die();
	}

	// SetList
	function wp_ajax_plvc_set_donation_toggle() {
		update_option( $this->ltd . '_donate_width' , strip_tags( $_POST["f"] ) );
		die();
	}

	// SetList
	function post_columns_default_load_action() {
		global $typenow;
		add_filter( "manage_edit-" . $typenow . "_columns" , array( $this , 'post_columns_default_load' ) , 10000 );
		//add_filter( "manage_edit-" . $typenow . "_sortable_columns" , array( $this , 'post_sortable_columns_default_load' ) , 10000 );
	}





	// DataUpdate
	function dataUpdate() {

		$RecordField = false;
		
		if( !empty( $_POST[$this->Nonces["field"]] ) ) {
			if( !empty( $_POST["record_field"] ) ) {
				$RecordField = strip_tags( $_POST["record_field"] );
			}

			if( !empty( $RecordField ) && !empty( $_POST["update"] ) ) {
				if( $RecordField == 'user_role' ) {
					$this->update_userrole();
				} elseif( $RecordField == 'post' ) {
					$this->update_post();
				} elseif( $RecordField == 'page' ) {
					$this->update_page();
				} elseif( $RecordField == 'media' ) {
					$this->update_media();
				} elseif( $RecordField == 'comments' ) {
					$this->update_comments();
				} elseif( $RecordField == 'widgets' ) {
					$this->update_widgets();
				} elseif( $RecordField == 'menus' ) {
					$this->update_menus();
				} elseif( $RecordField == 'menus_adv' ) {
					$this->update_menus_adv();
				} elseif( $RecordField == 'thunmbnail' ) {
					$this->update_thunmbnail();
				} elseif( $RecordField == 'custom_posts' ) {
					$this->update_custom_post();
				}
			}
			if( !empty( $RecordField ) && $RecordField == 'custom_posts' && !empty( $_POST["reset"] ) ) {
				$this->update_reset_custom_post();
			} elseif( !empty( $RecordField ) && !empty( $_POST["reset"] ) ) {
				$this->update_reset( $RecordField );
			}

			if( !empty( $RecordField ) && $RecordField == 'donate' && !empty( $_POST["donate_key"] ) ) {
				$this->DonatingCheck();
			}
		}

	}

	// DataUpdate
	function update_validate() {
		$Update = array();

		if( !empty( $_POST[$this->UPFN] ) ) {
			$UPFN = strip_tags( $_POST[$this->UPFN] );
			if( $UPFN == $this->UPFN ) {
				$Update["UPFN"] = strip_tags( $_POST[$this->UPFN] );
			}
		}

		return $Update;
	}

	// DataUpdate
	function DonatingCheck() {
		$Update = $this->update_validate();

		if( !empty( $Update ) ) {
			if( !empty( $_POST["donate_key"] ) ) {
				$SubmitKey = md5( strip_tags( $_POST["donate_key"] ) );
				if( $this->DonateKey == $SubmitKey ) {
					update_option( $this->Record["donate"] , $SubmitKey );
					wp_redirect( add_query_arg( $this->MsgQ , 'donated' ) );
					exit;
				}
			}
		}

	}

	// Update Reset
	function update_reset( $record ) {
		$Update = $this->update_validate();
		if( !empty( $Update ) && check_admin_referer( $this->Nonces["value"] , $this->Nonces["field"] ) ) {
			$record = apply_filters( 'plvc_pre_delete' , $this->Record[$record] );
			delete_option( $record );
			wp_redirect( add_query_arg( $this->MsgQ , 'delete' , stripslashes( $_POST["_wp_http_referer"] ) ) );
			exit;
		}
	}

	// DataUpdate
	function update_userrole() {
		$Update = $this->update_validate();
		if( !empty( $Update ) && check_admin_referer( $this->Nonces["value"] , $this->Nonces["field"] ) ) {

			if( !empty( $_POST["data"]["user_role"] ) ) {
				foreach($_POST["data"]["user_role"] as $key => $val) {
					$tmpK = strip_tags( $key );
					$tmpV = strip_tags ( $val );
					$Update[$tmpK] = $tmpV;
				}
			}

			update_option( $this->Record["user_role"] , $Update );
			wp_redirect( add_query_arg( $this->MsgQ , 'update' , stripslashes( $_POST["_wp_http_referer"] ) ) );
			exit;
		}
	}

	// DataUpdate
	function update_data_format( $Update ) {
		$Modes = array( "use" , "not_use" );
		foreach($Modes as $mode) {
			$Update[$mode] = array();
			if( !empty( $_POST[$mode] ) ) {
				$Columns = $_POST[$mode];
				foreach( $Columns as $column_id => $column_name ) {
					$tmpK = strip_tags( $column_id );
					$tmpV = stripslashes( $column_name["name"] );
					$Update[$mode][$tmpK]["name"] = $tmpV;
				}
			}
		}

		return $Update;
	}

	// DataUpdate
	function update_post() {
		$Update = $this->update_validate();
		if( !empty( $Update ) && check_admin_referer( $this->Nonces["value"] , $this->Nonces["field"] ) ) {

			$Update = $this->update_data_format( $Update );
			$Record = apply_filters( 'plvc_pre_update' , $this->Record["post"] );
			
			update_option( $Record , $Update );
			wp_redirect( add_query_arg( $this->MsgQ , 'update' , stripslashes( $_POST["_wp_http_referer"] ) ) );
			exit;
		}
	}

	// DataUpdate
	function update_page() {
		$Update = $this->update_validate();
		if( !empty( $Update ) && check_admin_referer( $this->Nonces["value"] , $this->Nonces["field"] ) ) {

			$Update = $this->update_data_format( $Update );
			$Record = apply_filters( 'plvc_pre_update' , $this->Record["page"] );

			update_option( $Record , $Update );
			wp_redirect( add_query_arg( $this->MsgQ , 'update' , stripslashes( $_POST["_wp_http_referer"] ) ) );
			exit;
		}
	}

	// DataUpdate
	function update_media() {
		$Update = $this->update_validate();
		if( !empty( $Update ) && check_admin_referer( $this->Nonces["value"] , $this->Nonces["field"] ) ) {

			$Update = $this->update_data_format( $Update );
			$Record = apply_filters( 'plvc_pre_update' , $this->Record["media"] );

			update_option( $Record , $Update );
			wp_redirect( add_query_arg( $this->MsgQ , 'update' , stripslashes( $_POST["_wp_http_referer"] ) ) );
			exit;
		}
	}

	// DataUpdate
	function update_comments() {
		$Update = $this->update_validate();
		if( !empty( $Update ) && check_admin_referer( $this->Nonces["value"] , $this->Nonces["field"] ) ) {

			$Update = $this->update_data_format( $Update );
			$Record = apply_filters( 'plvc_pre_update' , $this->Record["comments"] );

			update_option( $Record , $Update );
			wp_redirect( add_query_arg( $this->MsgQ , 'update' , stripslashes( $_POST["_wp_http_referer"] ) ) );
			exit;
		}
	}

	// DataUpdate
	function update_widgets() {
		$Update = $this->update_validate();
		if( !empty( $Update ) && check_admin_referer( $this->Nonces["value"] , $this->Nonces["field"] ) ) {
			
			$Update = $this->update_data_format( $Update );
			$Record = apply_filters( 'plvc_pre_update' , $this->Record["widgets"] );

			update_option( $Record , $Update );
			wp_redirect( add_query_arg( $this->MsgQ , 'update' , stripslashes( $_POST["_wp_http_referer"] ) ) );
			exit;
		}
	}

	// DataUpdate
	function update_menus() {
		$Update = $this->update_validate();
		if( !empty( $Update ) && check_admin_referer( $this->Nonces["value"] , $this->Nonces["field"] ) ) {
			
			$Update = $this->update_data_format( $Update );
			$Record = apply_filters( 'plvc_pre_update' , $this->Record["menus"] );

			update_option( $Record , $Update );
			wp_redirect( add_query_arg( $this->MsgQ , 'update' , stripslashes( $_POST["_wp_http_referer"] ) ) );
			exit;
		}
	}

	// DataUpdate
	function update_menus_adv() {
		$Update = $this->update_validate();
		if( !empty( $Update ) && check_admin_referer( $this->Nonces["value"] , $this->Nonces["field"] ) ) {
			
			$Update = $this->update_data_format( $Update );
			$Record = apply_filters( 'plvc_pre_update' , $this->Record["menus_adv"] );

			update_option( $Record , $Update );
			wp_redirect( add_query_arg( $this->MsgQ , 'update' , stripslashes( $_POST["_wp_http_referer"] ) ) );
			exit;
		}
	}

	// DataUpdate
	function update_thunmbnail() {
		$Update = $this->update_validate();
		if( !empty( $Update ) && check_admin_referer( $this->Nonces["value"] , $this->Nonces["field"] ) ) {

			if( !empty( $_POST["width"] ) ) {
				$Update["width"] = intval( $_POST["width"] );
			}
			
			update_option( $this->Record["thunmbnail"] , $Update );
			wp_redirect( add_query_arg( $this->MsgQ , 'update' , stripslashes( $_POST["_wp_http_referer"] ) ) );
			exit;
		}
	}

	// DataUpdate
	function update_custom_post() {
		$Update = $this->update_validate();
		if( !empty( $Update ) && check_admin_referer( $this->Nonces["value"] , $this->Nonces["field"] ) ) {

			$GetData = $this->get_data( 'custom_posts' );

			$Update = $this->update_data_format( $Update );
			unset( $Update["UPFN"] );

			$GetData[strip_tags( $_POST["CustomSelect"] )] = $Update;
			$Record = apply_filters( 'plvc_pre_update' , $this->Record["custom_posts"] );

			update_option( $Record , $GetData );
			wp_redirect( add_query_arg( $this->MsgQ , 'update' , stripslashes( $_POST["_wp_http_referer"] ) ) );
			exit;
			
		}
	}

	// Update Reset
	function update_reset_custom_post() {
		$Update = $this->update_validate();
		if( !empty( $Update ) && check_admin_referer( $this->Nonces["value"] , $this->Nonces["field"] ) ) {

			$GetData = $this->get_data( 'custom_posts' );
			unset( $GetData[strip_tags( $_POST["CustomSelect"] )] );
			unset( $GetData["use"] );
			unset( $GetData["not_use"] );
			$Record = apply_filters( 'plvc_pre_update' , $this->Record["custom_posts"] );

			update_option( $Record , $GetData );
			wp_redirect( add_query_arg( $this->MsgQ , 'update' , stripslashes( $_POST["_wp_http_referer"] ) ) );
			exit;
		}
	}





	// FilterStart
	function FilterStart() {
		if ( is_admin() ) {
			// reset css
			add_action( 'admin_footer' , array( $this , 'include_css' ) );
			
			// Filter Set
			add_action( 'admin_init' , array( $this , 'columns_init' ) );
		}
	}

	// FilterStart
	function include_css() {
		global $current_screen;
		
		$screen_ids = array( 'upload' , 'edit-comments' );
		if( $current_screen->base == 'edit' or in_array( $current_screen->id , $screen_ids ) ) {
			wp_enqueue_style( $this->PageSlug . '-table' , $this->Url . $this->PluginSlug . '-table.css' , array() , $this->Ver );
		}
	}

	// FilterStart
	function columns_init() {

		$SettingRole = $this->get_data( 'user_role' );
		$SettingRole = apply_filters( 'plvc_pre_setting_roles' , $SettingRole );
		
		if( !empty( $SettingRole ) ) {
			unset($SettingRole["UPFN"]);
			
			$UserRole = $this->current_user_role_group();
		
			if( !is_network_admin() && !empty( $UserRole) ) {
				if( array_key_exists( $UserRole , $SettingRole ) ) {

					global $pagenow, $typenow;

					$Req = array( "req_file" => $pagenow , "typenow" => $typenow );
					if ( $pagenow == 'edit.php' && empty( $typenow ) ) {
						$Req["typenow"] = 'post';
					} elseif ( $pagenow == 'admin-ajax.php' && !empty( $_REQUEST["post_type"] ) ) {
						$Req["req_file"] = 'edit.php';
						$Req["typenow"] = $_REQUEST["post_type"];
					}
					
					$Data = array();

					if( $Req["req_file"] == 'edit.php' ) {

						if( $Req["typenow"] == 'post' or $Req["typenow"] == 'page' ) {
							$Data = $this->get_filt_data( $Req["typenow"] );
						} else {
							$Custom = $this->get_filt_data( "custom_posts" );
							if( !empty( $Custom[$Req["typenow"]] ) ) {
								$Data = $Custom[$Req["typenow"]];
							}
						}
								
						$hook_header = array( "manage_edit-" . $Req["typenow"] . "_columns" , "PostsColumnHeader" );
						$hook_body = array( "manage_" . $Req["typenow"] . "_posts_custom_column" , "PostsColumnBody" );
		
						if( !empty( $Data ) && !empty( $hook_header ) && !empty( $hook_body ) ) {
							add_filter( $hook_header[0] , array( $this , $hook_header[1] ) , 10001 );
							add_action( $hook_body[0] , array( $this , $hook_body[1] ) , 10 , 2 );
						}

					} if( $Req["req_file"] == 'upload.php' ) {

						$Data = $this->get_filt_data( "media" );
							
						$hook_header = array( "manage_media_columns" , "MediaColumnHeader" );
						$hook_body = array( "manage_media_custom_column" , "MediaColumnBody" );
	
						if( !empty( $Data ) && !empty( $hook_header ) && !empty( $hook_body ) ) {
							add_filter( $hook_header[0] , array( $this , $hook_header[1] ) , 10001 );
							add_action( $hook_body[0] , array( $this , $hook_body[1] ) , 10 , 2 );
						}

					} if( $Req["req_file"] == 'edit-comments.php' ) {

						$Data = $this->get_filt_data( "comments" );
						
						$hook_header = array( "manage_edit-comments_columns" , "CommentsColumnHeader" );
						$hook_body = array( "manage_comments_custom_column" , "CommentsColumnBody" );
	
						if( !empty( $Data ) && !empty( $hook_header ) && !empty( $hook_body ) ) {
							add_filter( $hook_header[0] , array( $this , $hook_header[1] ) , 10001 );
							add_action( $hook_body[0] , array( $this , $hook_body[1] ) , 10 , 2 );
						}

					} if( $Req["req_file"] == 'widgets.php' ) {

						$Data = $this->get_filt_data( "widgets" );
						
						if( !empty( $Data ) ) {
							add_filter( 'widgets_admin_page' , array( $this , 'WidgetsColumnBody' ) );
						}

					} if( $Req["req_file"] == 'nav-menus.php' ) {

						$Data = $this->get_filt_data( "menus" );
						
						if( !empty( $Data ) ) {
							add_filter( "admin_head-nav-menus.php" , array( $this , "MenusMetaBox" ) );
						}
	
						$Data = $this->get_filt_data( "menus_adv" );
				
						$hook_header = array( "manage_nav-menus_columns" , "MenusAdvColumnHeader" );
						$hook_body = array( "admin_head-nav-menus.php" , "MenusAdvColumnBody" );
	
						if( !empty( $Data ) && !empty( $hook_header ) && !empty( $hook_body ) ) {
							add_filter( $hook_header[0] , array( $this , $hook_header[1] ) , 11 );
							add_action( $hook_body[0] , array( $this , $hook_body[1] ) );
						}

					}

				}
			}

		}

	}

	// FilterStart
	function PostsColumnHeader( $columns ) {
		global $pagenow, $typenow;
		
		$Req = array( "req_file" => $pagenow , "typenow" => $typenow );
		if ( $pagenow == 'edit.php' && empty( $typenow ) ) {
			$Req["typenow"] = 'post';
		} elseif ( $pagenow == 'admin-ajax.php' ) {
			$Req["req_file"] = 'edit.php';
			$Req["typenow"] = $_REQUEST["post_type"];
		}

		if( $Req["typenow"] == 'post' or $Req["typenow"] == 'page' ) {
			$Data = $this->get_filt_data( $Req["typenow"] );
			$current_columns = $this->get_list_columns( $Req["typenow"] );
		} else {
			$Custom = $this->get_filt_data( "custom_posts" );
			$Data = $Custom[$Req["typenow"]];
			$current_columns = $this->get_list_columns( "custom_posts" );
		}
		$FilterColumn = array( "cb" => $columns["cb"] );
		
		if( !empty( $Data["use"] ) ) {
			foreach( $Data["use"] as $id => $name ) {
				$FilterColumn[$id] = stripslashes( $name["name"] );
			}
		}

		return $FilterColumn;
	}

	// FilterStart
	function PostsColumnBody( $column_name , $post_id ) {
		$None = '';

		$GetDataThumbnail = get_option( $this->Record["thunmbnail"] );
		if( !empty( $GetDataThumbnail ) ) {
			$Thumbnail_setting = intval( $GetDataThumbnail["width"] );
		} else {
			$Thumbnail_setting = intval( $this->ThumbnailSize );
		}

		if($column_name == 'post-formats') {
			// post-formats
			echo get_post_format_string( get_post_format( $post_id ) );
		} else if($column_name == 'id') {
			// post ID
			echo $post_id;
		} else if($column_name == 'slug') {
			// slug
			echo urldecode( get_page_uri( $post_id ) );
		} else if($column_name == 'excerpt') {
			// excerpt
			$excerpt = get_post( $post_id );
			if( !empty( $excerpt->post_excerpt ) ) {
				echo mb_substr( strip_tags( $excerpt->post_excerpt ) , 0 , 20 ) . '.';
			} else {
				echo $None;
			}
		} else if($column_name == 'post-thumbnails') {
			// thumbnail
			if( has_post_thumbnail( $post_id ) ) {
				$thumbnail_id = get_post_thumbnail_id( $post_id );
				$thumbnail = wp_get_attachment_image_src( $thumbnail_id , 'post-thumbnail', true );
				echo '<a href="media.php?attachment_id=' . $thumbnail_id . '&action=edit"><img src="' . $thumbnail[0] . '" width="' . $Thumbnail_setting . '" /></a>';
			} else {
				echo $None;
			}
		} else {

			// custom fields
			$post_meta = get_post_meta( $post_id , $column_name , false );
			if( !empty( $post_meta[0] ) ) {
				if( is_array( $post_meta[0] ) ) {
					// checkbox multiselect
					echo '<ul>';
					foreach($post_meta[0] as $val) {
						if( is_array( $val ) ) {
							foreach($val as $v) {
								echo '<li>' . $v . '</li>';
							}
						} else {
							echo '<li>' . $val . '</li>';
						}
					}
					echo '</ul>';
				} else {

					// custom-field-template active flag
					$cftd_flg = false;
					foreach ((array) get_option('active_plugins') as $plugin) {
						if ( preg_match( '/custom-field-template/i' , $plugin ) ) {
							$cftd_flg = true;
							break;
						}
					}
					if( $cftd_flg == true) {
						// custom-field-template custom fileds
						$cftd = get_option( 'custom_field_template_data' );
						$cftd_contents = '';
						$cftd_field_file = array();
						foreach($cftd["custom_fields"] as $key => $cftdct) {
							if( !empty( $cftdct["content"] ) ) {
								$cftd_contents = explode( "\n" , stripcslashes( $cftdct["content"] ) );
								for($i=0; $i<count($cftd_contents); $i++) {
									if( strpos( $cftd_contents[$i] , 'file' ) ) {
										for($ct=1;$ct<3;$ct++) {
											if( !empty( $cftd_contents[$i-$ct] ) ) {
												if ( preg_match( "/\[(.+)\]/" , $cftd_contents[$i-$ct] , $match ) ) {
													$cftd_field_file[] = $match[1];
												}
											}
										}
									}
								}
							}
						}
						if( !empty( $cftd_field_file ) ) {
							if( in_array( $column_name , $cftd_field_file ) ) {
								$post = get_post( $post_meta[0] );
								if( !empty($post) && intval( $post_meta[0] ) && $post->post_type == 'attachment' ) {
									$CustomThumbnail = wp_get_attachment_image_src( $post_meta[0] , 'post-thumbnail', true );
									if(!empty($CustomThumbnail)) {
										echo '<a href="media.php?attachment_id=' . $post_meta[0] . '&action=edit"><img src="' . $CustomThumbnail[0] . '" width="' . $Thumbnail_setting . '" /></a>';
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
						// more plugin custom fileds
						$post = get_post( $post_meta[0] );
						if( !empty($post) && intval($post_meta[0]) && $post->post_type == 'attachment' && $post_id == $post->post_parent ) {
							$CustomThumbnail = wp_get_attachment_image_src( $post_meta[0], 'post-thumbnail', true );
							if( !empty($CustomThumbnail ) ) {
								echo '<a href="media.php?attachment_id=' . $post_meta[0] . '&action=edit"><img src="' . $CustomThumbnail[0] . '" width="' . $Thumbnail_setting . '" /></a>';
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

	// FilterStart
	function MediaColumnHeader( $columns ) {
		$Data = $this->get_filt_data( 'media' );

		$FilterColumn = array( "cb" => $columns["cb"] );
		if( !empty( $Data["use"] ) ) {
			foreach( $Data["use"] as $id => $name ) {
				$FilterColumn[$id] = esc_html( $name["name"] );
			}
		}

		return $FilterColumn;
	}

	// FilterStart
	function MediaColumnBody( $column_name , $post_id ) {
		$None = '';
		$posts = get_posts( array( 'numberposts' => 1 , 'include' => $post_id , 'post_type' => 'attachment' ) );

		if( !empty( $posts[0] ) ) {
			$attachment = $posts[0];

			if($column_name == 'id') {
				// post ID
				echo $post_id;
			} else if($column_name == 'image_alt') {
				// alternate
				$image_alt = get_post_meta( $post_id , '_wp_attachment_image_alt' , true );
				echo wp_strip_all_tags( stripslashes( $image_alt ) );
			} else if($column_name == 'media_title') {
				// media title
				echo _draft_or_post_title( $post_id );
			} else if($column_name == 'post_excerpt') {
				// Caption
				echo $attachment->post_excerpt;
			} else if($column_name == 'post_content') {
				// Description
				echo $attachment->post_content;
			} else {
				echo $None;
			}

		} else {
			echo $None;
		}

	}

	// FilterStart
	function CommentsColumnHeader( $columns ) {
		$Data = $this->get_filt_data( 'comments' );

		$FilterColumn = array( "cb" => $columns["cb"] );
		if( !empty( $Data["use"] ) ) {
			foreach( $Data["use"] as $id => $name ) {
				$FilterColumn[$id] = esc_html( $name["name"] );
			}
		}

		return $FilterColumn;
	}

	// FilterStart
	function CommentsColumnBody( $column_name , $comment_id ) {
		$None = '';
		$comment = get_comment( $comment_id );
		
		if( !empty( $comment ) ) {

			if($column_name == 'id') {
				// post ID
				echo $comment_id;
			} else if($column_name == 'newcomment_author') {
				// author
				echo $comment->comment_author;
			} else if($column_name == 'newcomment_author_email') {
				// email
				echo $comment->comment_author_email;
			} else if($column_name == 'newcomment_author_url') {
				// url
				echo $comment->comment_author_url;
			} else {
				echo $None;
			}

		} else {
			echo $None;
		}

	}

	// FilterStart
	function WidgetsColumnBody() {
		global $wp_registered_widgets;
		
		$Data = $this->get_filt_data( "widgets" );
		if( !empty( $Data["not_use"] ) ) {
			foreach( $wp_registered_widgets as $widget_id => $widget ) {
				if( array_key_exists( $widget_id , $Data["not_use"] ) !== false ) {
					unset( $wp_registered_widgets[$widget_id] );
				}
			}
		}

	}

	// FilterStart
	function MenusMetaBox( $columns ) {
		$Data = $this->get_filt_data( 'menus' );

		if( !empty( $Data["not_use"] ) ) {
			foreach( $Data["not_use"] as $id => $name ) {
				remove_meta_box( $id , 'nav-menus' , 'side' );
			}
		}

		return $columns;
	}

	// FilterStart
	function MenusAdvColumnHeader( $columns ) {
		$Data = $this->get_filt_data( 'menus_adv' );

		if( !empty( $Data["not_use"] ) ) {
			foreach( $Data["not_use"] as $id => $name ) {
				unset( $columns[$id] );
			}
		}

		if( count( $columns ) == 2 ) {
			$columns = array();
		}

		return $columns;
	}

	// FilterStart
	function MenusAdvColumnBody() {
		$Data = $this->get_filt_data( 'menus_adv' );

		$FilterColumn = array();
		if( !empty( $Data["not_use"] ) ) {
			foreach( $Data["not_use"] as $id => $name ) {
				$FilterColumn[$id] = esc_html( $name["name"] );
			}
		}
		
		$hide_set = '';
		$hide_field = '';
		foreach( $FilterColumn as $id => $name ) {
			$hide_set .= '.metabox-prefs label[for=' . $id . '-hide], ';
			$hide_field .= '.menu-item-settings p.field-' . $id . ', ';
		}
		$hide_set = rtrim( $hide_set , ', ' );
		$hide_field = rtrim( $hide_field , ', ' );

		if( !empty( $hide_field ) ) {
			echo '<style>' . $hide_field . ' { display: none; }</style>';
		}
	}

	// FilterStart
	function display_msg() {
		if( !empty( $_GET[$this->MsgQ] ) ) {
			$msg = strip_tags(  $_GET[$this->MsgQ] );
			if( $msg == 'update' or $msg == 'delete' ) {
				$this->Msg .= '<div class="updated"><p><strong>' . __( 'Settings saved.' ) . '</strong></p></div>';
			} elseif( $msg == 'donated' ) {
				$this->Msg .= '<div class="updated"><p><strong>' . __( 'Thank you for your donation.' , $this->ltd ) . '</strong></p></div>';
			}
		}
	}

	// FilterStart
	function layout_footer( $text ) {
		$text = '<img src="' . $this->Schema . 'www.gravatar.com/avatar/7e05137c5a859aa987a809190b979ed4?s=18" width="18" /> Plugin developer : <a href="'. $this->AuthorUrl . '?utm_source=use_plugin&utm_medium=footer&utm_content=' . $this->ltd . '&utm_campaign=' . str_replace( '.' , '_' , $this->Ver ) . '" target="_blank">gqevu6bsiz</a>';
		return $text;
	}

	// FilterStart
	function DisplayDonation() {
		$donation = get_option( $this->ltd . '_donated' );
		if( $this->DonateKey != $donation ) {
			$this->Msg .= '<div class="updated"><p><strong>' . __( 'To donate if you feel that it is useful, please.' , $this->ltd ) . '</strong></p></div>';
		}
	}


}

$Plvc = new Post_Lists_View_Custom();
