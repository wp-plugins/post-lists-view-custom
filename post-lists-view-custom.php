<?php
/*
Plugin Name: Post Lists View Custom
Description: Allow to customizing for the list screen.
Plugin URI: http://wordpress.org/extend/plugins/post-lists-view-custom/
Version: 1.5.9
Author: gqevu6bsiz
Author URI: http://gqevu6bsiz.chicappa.jp/?utm_source=use_plugin&utm_medium=list&utm_content=plvc&utm_campaign=1_5_9
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




if ( !class_exists('Post_Lists_View_Custom') ) :

class Post_Lists_View_Custom
{

	var $Ver,
		$Name,
		$Dir,
		$Url,
		$AuthorUrl,
		$ltd,
		$Record,
		$PageSlug,
		$PluginSlug,
		$SetPage,
		$SetName,
		$ThumbnailSize,
		$CustomFields,
		$Nonces,
		$Schema,
		$PageTitle,
		$UPFN,
		$Msg,
		$MsgQ;


	function __construct() {
		$this->Ver = '1.5.9';
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
			"other" => $this->ltd . '_other',
			"regist_columns" => $this->ltd . '_regist_columns',
			"regist_sortable_columns" => $this->ltd . '_regist_sortable_columns',
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

		// data upgrade for thumbnail size
		add_action( 'admin_init' , array( $this , 'dataUpgrade') );

		// donation toggle
		add_action( 'wp_ajax_plvc_donation_toggle' , array( $this , 'wp_ajax_plvc_donation_toggle' ) );

		// setting check user role
		add_action( 'admin_notices' , array( $this , 'settingCheck' ) ) ;

		// default columns load.
		add_action( 'load-edit.php' , array( $this , 'post_columns_default_load_action' ) );
		add_action( 'load-edit-comments.php' , array( $this , 'post_columns_default_load_action' ) );
		add_action( 'load-upload.php' , array( $this , 'post_columns_default_load_action' ) );

		// get all custom_fields
		add_action( 'admin_init' , array( $this , 'set_all_custom_fields') );
	}

	// PluginSetup
	function plugin_action_links( $links , $file ) {
		if( plugin_basename(__FILE__) == $file ) {
			$support_link = '<a href="http://wordpress.org/support/plugin/' . $this->PluginSlug . '" target="_blank">' . __( 'Support Forums' ) . '</a>';
			array_unshift( $links, $support_link );
			array_unshift( $links, '<a href="' . admin_url( 'admin.php?page=' . $this->PageSlug ) . '">' . __( 'Settings' ) . '</a>' );

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
		add_submenu_page( $this->PageSlug , __( 'Other Settings' , $this->ltd ) , __( 'Other Settings' , $this->ltd ) , 'administrator' , $this->Record["other"] , array( $this , 'setting_other' ) );
	}

	// PluginSetup
	function post_columns_default_load_action() {
		global $typenow, $pagenow;
		
		if( $pagenow == 'edit-comments.php' ) {
			add_filter( "manage_edit-comments_columns" , array( $this , 'list_columns_default_load' ) , 10000 );
			add_filter( "manage_edit-comments_sortable_columns" , array( $this , 'list_sortable_columns_default_load' ) , 10000 );
		} elseif( $pagenow == 'upload.php' ) {
			add_filter( "manage_media_columns" , array( $this , 'list_columns_default_load' ) , 10000 );
			add_filter( "manage_upload_sortable_columns" , array( $this , 'list_sortable_columns_default_load' ) , 10000 );
		} elseif( $pagenow == 'edit.php' ) {
			add_filter( "manage_edit-" . $typenow . "_columns" , array( $this , 'list_columns_default_load' ) , 10000 );
			add_filter( "manage_edit-" . $typenow . "_sortable_columns" , array( $this , 'list_sortable_columns_default_load' ) , 10000 );
		}
	}

	// PluginSetup
	function list_columns_default_load( $columns ) {
		global $typenow, $pagenow;
		
		$UserRole = $this->current_user_role_group();
		
		$NowColumns = array();
		if( $UserRole == 'administrator' && !empty( $columns ) ) {

			$RegistColumns = $this->get_data( 'regist_columns' );
			$NowColumns = $columns;
			unset( $NowColumns["cb"] );

			if( $pagenow == 'edit-comments.php' ) {
				$type = 'comments';
			} elseif( $pagenow == 'upload.php' ) {
				$type = 'media';
			} elseif( $pagenow == 'edit.php' ) {
				$type = $typenow;
			}
			$RegistColumns[$type] = $NowColumns;
			
			update_option( $this->Record["regist_columns"] , $RegistColumns );
		}
		
		return $columns;
	}

	// PluginSetup
	function list_sortable_columns_default_load( $columns ) {
		global $typenow, $pagenow;
		
		$UserRole = $this->current_user_role_group();
		
		$NowColumns = array();
		if( $UserRole == 'administrator' && !empty( $columns ) ) {
			
			$RegistColumns = $this->get_data( 'regist_sortable_columns' );
			$NowColumns = $columns;

			if( $pagenow == 'edit-comments.php' ) {
				$type = 'comments';
			} elseif( $pagenow == 'upload.php' ) {
				$type = 'media';
			} elseif( $pagenow == 'edit.php' ) {
				$type = $typenow;
			}

			$RegistColumns[$type] = $NowColumns;
			update_option( $this->Record["regist_sortable_columns"] , $RegistColumns );
		}
		
		return $columns;
	}

	// PluginSetup
	function set_all_custom_fields() {
		global $wpdb;

		// All Post Custom Field meta
		$All_custom_columns = $wpdb->get_col( "SELECT meta_key FROM $wpdb->postmeta GROUP BY meta_key HAVING meta_key NOT LIKE '\_%' ORDER BY meta_key" );
		if( !empty( $All_custom_columns ) ) {
			natcasesort($All_custom_columns);
		}

		// Unset colum name
		$Unset = array( 'allorany' , 'hide_on_screen' );
		foreach( $Unset as $name ) {
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

	// PluginSetup
	function get_all_list_types() {
		$Lists = array();

		$Lists["post"] = array( "name" => __( 'All Posts' ), "edit_link" => self_admin_url( '/edit.php' ) );
		$Lists["page"] = array( "name" => __( 'All Pages' ), "edit_link" => self_admin_url( '/edit.php?post_type=page' ) );

		$Lists["media"] = array( "name" => __( 'Media Library' ), "edit_link" => self_admin_url( '/upload.php' ) );
		$Lists["comments"] = array( "name" => __( 'Comments' ), "edit_link" => self_admin_url( '/edit-comments.php' ) );

		$Lists = array_merge( $Lists, $this->get_all_customposts() );
		
		return $Lists;
	}

	// PluginSetup
	function get_all_customposts() {
		$Lists = array();

		$PostTypes = get_post_types( array( 'public' => true, '_builtin' => false ) , 'objects' );
		if( !empty( $PostTypes ) && is_array( $PostTypes ) ) {
			foreach( $PostTypes as $post_type => $post_type_obj ) {
				$Lists[$post_type] = array( "name" => $post_type_obj->labels->name, "edit_link" => self_admin_url( '/edit.php?post_type=' . $post_type ) );
			}
		}
		
		return $Lists;
	}

	// PluginSetup
	function get_all_taxonomies() {
		$Taxs = array();

		$CustomTaxonomies = get_taxonomies( array( 'public' => true, '_builtin' => false ), 'object' );
		if( !empty( $CustomTaxonomies ) ) {
			foreach( $CustomTaxonomies as $CustomTaxonomy ) {
				$Taxs[$CustomTaxonomy->name] = array( "name" => $CustomTaxonomy->labels->name, "edit_link" => self_admin_url( '/edit-tags.php?taxonomy=' . $CustomTaxonomy->name ) );
			}
		}
		
		return $Taxs;
	}

	// PluginSetup
	function wp_ajax_plvc_donation_toggle() {
		if( !empty( $_POST["f"] ) ) {
			update_option( $this->ltd . '_donate_width' , strip_tags( $_POST["f"] ) );
		} else {
			get_option( $this->ltd . '_donate_width' );
		}
		die();
	}

	// PluginSetup
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

	// PluginSetup
	function dataUpgrade() {
		$GetData = get_option( $this->ltd . '_thumbnail' );
		if( !empty( $GetData ) && !empty( $GetData['width'] ) ) {
			$Data = array( 'UPFN' => 1 , 'thumbnail' => array( 'width' => $GetData['width'] ) );
			update_option( $this->Record["other"] , $Data );
			delete_option( $this->ltd . '_thumbnail' );
		}
	}




	// PluginSetup page
	function pageSetUp() {
		add_filter( 'admin_footer_text' , array( $this , 'layout_footer' ) );
		$this->display_msg();
		$this->DisplayDonation();
	}

	// SettingPage
	function setting_default() {
		$this->pageSetUp();
		include_once 'inc/setting_default.php';
	}

	// SettingPage
	function setting_post() {
		$this->SetPage = 'post';
		$this->PageTitle = sprintf( __( '%2$s for %3$s %1$s' , $this->ltd ) , __( 'Customize' ) , __( 'List View' ) , __( 'Posts' ) ) ;
		
		$this->pageSetUp();
		include_once 'inc/setting_lists.php';
	}

	// SettingPage
	function setting_page() {
		$this->SetPage = 'page';
		$this->PageTitle = sprintf( __( '%2$s for %3$s %1$s' , $this->ltd ) , __( 'Customize' ) , __( 'List View' ) , __( 'Pages' ) ) ;
		
		$this->pageSetUp();
		include_once 'inc/setting_lists.php';
	}

	// SettingPage
	function setting_media() {
		$this->SetPage = 'media';
		$this->PageTitle = sprintf( __( '%2$s for %3$s %1$s' , $this->ltd ) , __( 'Customize' ) , __( 'List View' ) , __( 'Media Library' ) ) ;
		
		$this->pageSetUp();
		include_once 'inc/setting_lists.php';
	}

	// SettingPage
	function setting_comments() {
		$this->SetPage = 'comments';
		$this->PageTitle = sprintf( __( '%2$s for %3$s %1$s' , $this->ltd ) , __( 'Customize' ) , __( 'List View' ) , __( 'Comments' ) ) ;
		
		$this->pageSetUp();
		include_once 'inc/setting_lists.php';
	}

	// SettingPage
	function setting_widgets() {
		$this->SetPage = 'widgets';
		$this->PageTitle = sprintf( __( '%2$s for %3$s %1$s' , $this->ltd ) , __( 'Customize' ) , __( 'List View' ) , __( 'Available Widgets' ) ) ;
		
		$this->pageSetUp();
		include_once 'inc/setting_menus.php';
	}

	// SettingPage
	function setting_menus() {
		$this->SetPage = 'menus';
		$this->PageTitle = sprintf( __( '%2$s for %3$s %1$s' , $this->ltd ) , __( 'Customize' ) , __( 'List View' ) , __( 'Menus' ) . ' ' . __( 'show screen' , $this->ltd ) ) . ' ' ;
		
		$this->pageSetUp();
		include_once 'inc/setting_menus.php';
	}

	// SettingPage
	function setting_menus_adv() {
		$this->SetPage = 'menus_adv';
		$this->PageTitle = __( 'Menus of advanced feature adapted to the screen' , $this->ltd );
		
		$this->pageSetUp();
		include_once 'inc/setting_menus.php';
	}

	// SettingPage
	function select_custom_posts() {
		$this->pageSetUp();
		include_once 'inc/select_custom.php';
	}

	// SettingPage
	function setting_custom_posts() {
		$this->SetPage = 'custom_posts';

		$this->pageSetUp();

		$PostSlug = '';
		if( !empty( $_GET["setname"] ) && !empty( $_GET["name"] ) ) {
			$PostSlug = strip_tags( $_GET["name"] );
		}

		if( !empty( $PostSlug ) ) {
			$this->SetName = $PostSlug;
			$PostTypes = $this->get_all_customposts();

			if( !empty( $PostTypes[$PostSlug] ) ) {
				$this->PageTitle = sprintf( __( '%2$s for %3$s %1$s' , $this->ltd ) , __( 'Customize' ) , __( 'List View' ) , __( 'Custom Post Type' , $this->ltd ) . ' ( ' . esc_html( $PostTypes[$PostSlug]["name"] ) . ' )' ) ;
			include_once 'inc/setting_lists.php';
			} else {
				echo sprintf( '<p>%s</p>' , __( 'No custom post type found.' , $this->ltd ) );
				echo sprintf( '<p><a href="%2$s">%1$s</a></p>' , __( 'Please select Custom Posts type from here.' , $this->ltd ) , admin_url( 'admin.php?page=select_custom_posts_list_view_setting' ) );
			}

		} else {
			echo sprintf( '<p>%s</p>' , __( 'No custom post type found.' , $this->ltd ) );
			echo sprintf( '<p><a href="%2$s">%1$s</a></p>' , __( 'Please select Custom Posts type from here.' , $this->ltd ) , admin_url( 'admin.php?page=select_custom_posts_list_view_setting' ) );
		}
	}

	// SettingPage
	function setting_other() {
		$this->SetPage = 'other';
		$this->PageTitle = __( 'Other Settings' , $this->ltd );
		
		$this->pageSetUp();
		include_once 'inc/setting_other.php';
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

	// GetData
	function get_regist_columns( $post_name ) {
		$Columns = array();

		$Regist_columns = $this->get_data( "regist_columns" );
		if( !empty( $Regist_columns[$post_name] ) ) {
			foreach( $Regist_columns[$post_name] as $column_id => $name ) {
				$Columns[$column_id] = array( "name" => $name , "group" => "" );
			}
		}
		
		return $Columns;
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
	function current_list_types() {
		$Current_lists = array();
		if( !empty( $this->SetPage ) ) {
			$all_lists = $this->get_all_list_types();
			
			$type = $this->SetPage;
			if( $this->SetPage == 'custom_posts' ) {
				$type = $this->SetName;
			}
			if( !empty( $all_lists[$type] ) ) {
				$Current_lists = $all_lists[$type];
			}
		}
		return $Current_lists;
	}

	// SetList
	function get_lists_data() {
		$Columns = array();

		if( !empty( $this->SetPage ) ) {
			
			$Columns = $this->get_lists_data_columns( $this->SetPage , $this->SetName );

			if( !empty( $Columns ) ) {
				
				$Data = $this->get_data( $this->SetPage );
				
				if( $this->SetPage == 'custom_posts' ) {
					if( !empty( $Data[$this->SetName] ) ) {
						$Data = $Data[$this->SetName];
					} else {
						$Data = array();
					}
				}

				if( !empty( $Data ) ) {
					
					$NewColumn = array();
					foreach( $Data["use"] as $column_id => $column ) {
						if( !empty( $Columns[$column_id] ) ) {
							unset( $Columns[$column_id]["not_use"] );
							$Columns[$column_id]["use"] = 1;
							$Columns[$column_id]["name"] = $column["name"];
							$NewColumn[$column_id] = $Columns[$column_id];
							
							unset( $Columns[$column_id] );
						}
					}

					if( !empty( $Columns ) ) {
						foreach( $Columns as $column_id => $column ) {
							unset( $Columns[$column_id]["use"] );
							$Columns[$column_id]["not_use"] = 1;
							$NewColumn[$column_id] = $Columns[$column_id];
						}
					}
					
					$Columns = $NewColumn;

				}

			}

		}

		return $Columns;
	}

	// SetList
	function get_menus_data() {
		$Columns = array();

		if( !empty( $this->SetPage ) ) {

			$Columns = $this->get_menus_data_columns( $this->SetPage , $this->SetName );

			if( !empty( $Columns ) ) {

				$Data = $this->get_data( $this->SetPage );
				if( !empty( $Data ) && !empty( $Data["not_use"] ) ) {
					
					foreach( $Data["not_use"] as $menu_id => $menu_set ) {
						if( !empty( $Columns[$menu_id]["use"] ) ) {
							unset( $Columns[$menu_id]["use"] );
							$Columns[$menu_id]["not_use"] = 1;
						}
					}

				}

			}
			
		}
		
		return $Columns;
	}

	// SetList
	function get_core_column( $post_type ) {
		$Columns = array();
		
		if( $post_type == 'post' or $post_type == 'page' or $post_type == 'custom_posts' ) {

			if( $post_type == 'post' or $post_type == 'custom_posts' ) {
				$Columns = array( "title" , "author" , "categories" , "tags" , "comments" , "date" , "slug" , "excerpt" , "id" );
			} elseif( $post_type == 'page' ) {
				$Columns = array( "title" , "author" , "comments" , "date" , "slug" , "excerpt" , "id" );
			}

			$ThemeSupports = array( 'post-thumbnails' , 'post-formats' );
			foreach( $ThemeSupports as $name ) {
				$Support = current_theme_supports( $name );
				if( !empty( $Support ) ) {
					$Columns[] = $name;
				}
			}
			unset( $ThemeSupports );
			
		} elseif( $post_type == 'media' ) {

			$Columns = array( "icon" , "title" , "author" , "parent" , "comments" , "date" , "media_title" , "image_alt" , "post_excerpt" , "post_content" , "id" );

		} elseif( $post_type == 'comments' ) {

			$Columns = array( "author" , "comment" , "response" , "newcomment_author" , "newcomment_author_email" , "newcomment_author_url" , "id" );

		} elseif( $post_type == 'widgets' ) {

			$Columns = array( 'pages-1' , 'calendar-1' , 'archives-2' , 'meta-2' , 'search-2' , 'text-1' , 'categories-2' , 'recent-posts-2' , 'recent-comments-2' , 'rss-1' , 'tag_cloud-1' , 'nav_menu-1' );

		} elseif( $post_type == 'menus' ) {

			$Columns = array( "add-custom-links" , "add-page" , "add-category" , "add-post_format" , "add-post" , "add-post_tag" );
			
		} elseif( $post_type == 'menus_adv' ) {

			$Columns = array( "link-target" , "css-classes" , "xfn" , "description" );
			
		}
		
		return $Columns;
	}

	// SetList
	function core_column_marge( $post_type , $post_name , $Columns ) {
		$Core_columns = $this->get_core_column( $post_type );
		
		if( !empty( $Core_columns ) ) {
			
			if( !empty( $Columns ) ) {
				foreach( $Columns as $column_id => $column ) {
					if( !in_array( $column_id, $Core_columns ) ) {
						if( empty( $Columns[$column_id]["group"] ) ) {
							$Columns[$column_id]["group"] = 'plugin';
						}
					}
				}
			}
			foreach( $Core_columns as $column_id ) {
				if( empty( $Columns[$column_id] ) ) {
					$Columns[$column_id] = array( "name" => "", "group" => "" );
				}
			}
		}
		
		return $Columns;
	}

	// SetList
	function custom_fields_column_marge( $Columns ) {
		if( !empty( $this->CustomFields ) ) {
			foreach( $this->CustomFields as $column_id ) {
				$Columns[$column_id] = array( 'name' => $column_id , 'group' => 'custom_fields' );
			}
		}

		return $Columns;
	}

	// SetList
	function get_regist_menus( $menu_type ) {
		$Columns = array();
		
		if( $menu_type == 'widgets' ) {
			
			global $wp_registered_widgets;

			if( !empty( $wp_registered_widgets ) ) {
				foreach( $wp_registered_widgets as $widget_id => $widget ) {
					$Columns[$widget_id] = array( "name" => $widget["name"] , "group" => "" );
				}
			}
			
		} elseif( $menu_type == 'menus' ) {
			
			$Core_columns = $this->get_core_column( $menu_type );
			foreach( $Core_columns as $menu_id ) {
				$Columns[$menu_id] = array( "name" => $menu_id , "group" => "" );
			}
			
			$Custom_posts = $this->get_all_customposts();
			if( !empty( $Custom_posts ) ) {
				foreach( $Custom_posts as $post_type => $cpt ) {
					$Columns['add-'.$post_type] = array( "name" => $cpt["name"] , "group" => "custom_post" );
				}
			}
			
			$Custom_taxs = $this->get_all_taxonomies();
			if( !empty( $Custom_taxs ) ) {
				foreach( $Custom_taxs as $tax_type => $ctx ) {
					$Columns['add-'.$tax_type] = array( "name" => $ctx["name"] , "group" => "custom_taxonomy" );
				}
			}
			
		} elseif( $menu_type == 'menus_adv' ) {
			
			$Core_columns = $this->get_core_column( $menu_type );
			foreach( $Core_columns as $menu_id ) {
				$Columns[$menu_id] = array( "name" => $menu_id , "group" => "" );
			}

		}
		
		return $Columns;
	}

	// SetList
	function get_lists_data_columns( $post_type , $post_name ) {
		$Columns = array();
		
		if( $post_type == 'custom_posts' ) {
			$Columns = $this->get_regist_columns( $post_name );
		} else {
			$Columns = $this->get_regist_columns( $post_type );
		}
		
		if( !empty( $Columns ) ) {
			$Columns = $this->core_column_marge( $post_type , $post_name , $Columns );
			$Columns = $this->replace_default_label( $post_type , $post_name , $Columns );
			if( $post_type == 'post' or $post_type == 'page' or $post_type == 'custom_posts' ) {
				$Columns = $this->custom_fields_column_marge( $Columns );
			}
			$Columns = $this->defaults_val_marge_columns( $post_type , $post_name , $Columns );
		}
		
		return $Columns;
	}

	// SetList
	function get_menus_data_columns( $menu_type ) {
		$Columns = array();

		$Columns = $this->get_regist_menus( $menu_type );
		
		if( !empty( $Columns ) ) {
			$Columns = $this->core_column_marge( $menu_type , "" , $Columns );
			$Columns = $this->replace_default_label( $menu_type , "" , $Columns );
			$Columns = $this->defaults_val_marge_menus( $menu_type , $Columns );
		}
		
		return $Columns;
	}

	// SetList
	function defaults_val_marge_columns( $post_type , $post_name , $Columns ) {
		if( $post_type == 'custom_posts' ) {
			$Use_columns = $this->get_regist_columns( $post_name );
		} else {
			$Use_columns = $this->get_regist_columns( $post_type );
		}
		
		if( !empty( $Use_columns ) ) {
			foreach( $Columns as $column_id => $column ) {
				if( !empty( $Use_columns[$column_id] ) ) {
					$Columns[$column_id]["use"] = 1;
				} else {
					$Columns[$column_id]["not_use"] = 1;
				}
			}
		}
		
		return $Columns;
	}

	// SetList
	function defaults_val_marge_menus( $menu_type , $Columns ) {
		$Use_columns = $this->get_regist_menus( $menu_type );
		if( !empty( $Use_columns ) ) {
			foreach( $Columns as $column_id => $column ) {
				if( !empty( $Use_columns[$column_id] ) ) {
					$Columns[$column_id]["use"] = 1;
				} else {
					$Columns[$column_id]["not_use"] = 1;
				}
			}
		}
		
		return $Columns;
	}

	// SetList
	function replace_default_label( $type, $post_name, $Columns ) {
		foreach( $Columns as $column_id => $column ) {
			
			$Label = "";

			if( empty( $column["group"] ) ) {
				
				if( $column_id == 'title' ) {
		
					if( $type == 'media' ) {
						$Label = _x( 'File' , 'column name' );
					} else {
						$Label = __( 'Title' );
					}

				} elseif( $column_id == 'author' ) { $Label = __( 'Author' );
				} elseif( $column_id == 'categories' ) { $Label = __( 'Categories' );
				} elseif( $column_id == 'tags' ) { $Label = __( 'Tags' );
				} elseif( $column_id == 'comments' or $column_id == 'comment' ) { $Label = __( 'Comments' );
				} elseif( $column_id == 'slug' ) { $Label = __( 'Slug' );
				} elseif( $column_id == 'excerpt' ) { $Label = __('Excerpt');
				} elseif( $column_id == 'id' ) { $Label = __( 'ID' );
		
				} elseif( $column_id == 'post-thumbnails' ) { $Label = __( 'Featured Image' );
				} elseif( $column_id == 'post-formats' ) { $Label = __( 'Format' );
		
				} elseif( $column_id == 'media_title' ) { $Label = __( 'Title' );
		
				} elseif( $column_id == 'post_excerpt' ) { $Label = __('Caption');
				} elseif( $column_id == 'post_content' ) { $Label = __('Details');
				} elseif( $column_id == 'icon' ) { $Label = __( 'Image' );
				} elseif( $column_id == 'response' ) { $Label = _x( 'In Response To' , 'column name' ) ;
				} elseif( $column_id == 'newcomment_author' ) { $Label = __( 'Name' );
				} elseif( $column_id == 'newcomment_author_email' ) { $Label = __( 'E-mail' );
				} elseif( $column_id == 'newcomment_author_url' ) { $Label = __( 'URL' );
		
				} elseif( $column_id == 'add-page' ) { $Label = __( 'Pages' );
				} elseif( $column_id == 'add-category' ) { $Label = __( 'Categories' );
				} elseif( $column_id == 'add-post_format' ) { $Label = __('Format');
				} elseif( $column_id == 'add-post' ) { $Label = __( 'Posts' );
				} elseif( $column_id == 'add-post_tag' ) { $Label = __( 'Tags' );
		
				} elseif( $column_id == 'link-target' ) { $Label = __('Link Target');
				} elseif( $column_id == 'css-classes' ) { $Label = __( 'CSS Classes' );
				} elseif( $column_id == 'xfn' ) { $Label = __( 'Link Relationship (XFN)' );
				} elseif( $column_id == 'description' ) { $Label = __( 'Description' );
		
				} elseif( $column_id == 'parent' ) {
		
					$Label = _x( 'Uploaded to' , 'column name' );
		
				} elseif( $column_id == 'image_alt' ) {
		
					$Label = __( 'Alternative Text' );
		
				} elseif( $column_id == 'date' ) {
		
					if( $type == 'media' ) {
						$Label = _x( 'Date' , 'column name' );
					} else {
						$Label = __( 'Date' );
					}
		
				} elseif( $column_id == 'add-custom-links' ) {
		
					$Label = __( 'Links' );
		
				}
				
			}
			
			if( !empty( $Label ) ) {
				$Columns[$column_id]["name"] = $Label;
			}
			
		}

		return $Columns;
	}

	// SetList
	function setting_list_widget( $type , $column_id , $column ) {

		if( !empty( $type ) && !empty( $column_id ) && !empty( $column[$type] ) ) {
			
			$class = 'widget';
			if( !empty( $column["group"] ) ) {
				$class .= ' ' . $column["group"];
			}
			$column_name = stripslashes( $column["name"] );
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
						<p><em><?php echo strip_tags( $column_id ); ?></em></p>
						<p>
							<label>
								<?php _e( 'Name' ); ?>:<br />
								<input type="text" name="<?php echo $type; ?>[<?php echo $column_id; ?>][name]" value="<?php echo esc_html( $column_name ); ?>" class="large-text" />
							</label>
						</p>
					</div>
					<input type="hidden" name="<?php echo $type; ?>[<?php echo $column_id; ?>][id]" value="<?php echo strip_tags( $column_id ); ?>" />
				</div>
			</div>
<?php 
		}
	}

	// SetList
	function setting_list_menu( $menu_id , $menu_set ) {
		
		if( !empty( $menu_id ) ) {
			$class = '';
			if( !empty( $menu_set["group"] ) ) {
				$class .= ' ' . $menu_set["group"];
			}
			$menu_name = stripslashes( $menu_set["name"] );
			$checked = false;
			if( !empty( $menu_set["not_use"] ) ) {
				$checked = true;
			}
			if( !empty( $menu_name ) ) :
?>
				<tr id="<?php echo $menu_id; ?>" class="<?php echo $class; ?>">
					<th>
						<?php echo $menu_name; ?>
					</th>
					<td>
						<label>
							<input type="checkbox" name="not_use[<?php echo $menu_id; ?>][name]" value="<?php echo esc_html( $menu_name ); ?>" <?php checked( $checked , 1 ); ?> />
							<?php _e ( 'Hide' ); ?>
						</label>
					</td>
				</tr>
<?php
			endif;
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
				} elseif( $RecordField == 'other' ) {
					$this->update_other();
				} elseif( $RecordField == 'custom_posts' ) {
					$this->update_custom_post();
				}
			}
			
			if( !empty( $_POST["reset"] ) ) {
				if( !empty( $RecordField ) && $RecordField == 'custom_posts' ) {
					$this->update_reset_custom_post();
				} elseif( !empty( $RecordField ) ) {
					$this->update_reset( $RecordField );
				}
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
	function update_data_format_menu( $Update ) {
		$Modes = array( "not_use" );
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
			
			$Update = $this->update_data_format_menu( $Update );
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
			
			$Update = $this->update_data_format_menu( $Update );
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
			
			$Update = $this->update_data_format_menu( $Update );
			$Record = apply_filters( 'plvc_pre_update' , $this->Record["menus_adv"] );

			update_option( $Record , $Update );
			wp_redirect( add_query_arg( $this->MsgQ , 'update' , stripslashes( $_POST["_wp_http_referer"] ) ) );
			exit;
		}
	}

	// DataUpdate
	function update_other() {
		$Update = $this->update_validate();
		if( !empty( $Update ) && check_admin_referer( $this->Nonces["value"] , $this->Nonces["field"] ) ) {

			if( isset( $_POST['data']['cell_auto'] ) ) {
				$Update['cell_auto'] = intval( $_POST['data']['cell_auto'] );
			}
			
			if( !empty( $_POST['data']['thumbnail']['width'] ) ) {
				$Update['thumbnail']['width'] = intval( $_POST['data']['thumbnail']['width'] );
			}
			
			update_option( $this->Record["other"] , $Update );
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

			$GetData[strip_tags( $_POST["SetName"] )] = $Update;
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
			unset( $GetData[strip_tags( $_POST["SetName"] )] );
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
		add_action( 'admin_init' , array( $this , 'plvc_start' ) );
	}

	// FilterStart
	function plvc_start() {
		$SettingRole = $this->get_data( 'user_role' );
		$SettingRole = apply_filters( 'plvc_pre_setting_roles' , $SettingRole );
			
		if( !empty( $SettingRole ) ) {
			unset($SettingRole["UPFN"]);

			$UserRole = $this->current_user_role_group();
			if( !is_network_admin() && !empty( $UserRole) ) {
				if( array_key_exists( $UserRole , $SettingRole ) ) {

					global $pagenow;
					// reset css
					add_action( 'admin_footer' , array( $this , 'include_css' ) );
					
					// Filter Set
					add_action( 'admin_init' , array( $this , 'columns_init' ) , 11 );

				}
			}
		}
	}

	// FilterStart
	function include_css() {
		global $current_screen;
		
		$GetOtherData = get_option( $this->Record["other"] );

		if( empty( $GetOtherData['cell_auto'] ) ) {

			$screen_ids = array( 'upload' , 'edit-comments' );
			if( $current_screen->base == 'edit' or in_array( $current_screen->id , $screen_ids ) ) {
				wp_enqueue_style( $this->PageSlug . '-table' , $this->Url . $this->PluginSlug . '-table.css' , array() , $this->Ver );
			}

		}
	}

	// FilterStart
	function columns_init() {
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
		} else {
			$Custom = $this->get_filt_data( "custom_posts" );
			if( !empty( $Custom[$Req["typenow"]] ) ) {
				$Data = $Custom[$Req["typenow"]];
			}
		}
		$FilterColumn = array( "cb" => $columns["cb"] );
		
		if( $Req["typenow"] == 'post' or $Req["typenow"] == 'page' ) {
			$get_lists_data_columns = $this->get_lists_data_columns( $Req["typenow"] , $Req["typenow"] );
		} else {
			$get_lists_data_columns = $this->get_lists_data_columns( 'custom_posts' , $Req["typenow"] );
		}
		
		if( !empty( $Data["use"] ) ) {
			foreach( $Data["use"] as $id => $name ) {
				if( !empty( $get_lists_data_columns[$id] ) ) {
					$FilterColumn[$id] = stripslashes( $name["name"] );
				}
			}
		}

		return $FilterColumn;
	}

	// FilterStart
	function PostsColumnBody( $column_name , $post_id ) {
		$None = '';

		$GetOtherData = get_option( $this->Record["other"] );
		if( !empty( $GetOtherData ) && !empty( $GetOtherData['thumbnail'] ) && !empty( $GetOtherData['thumbnail']['width'] ) ) {
			$Thumbnail_setting = intval( $GetOtherData['thumbnail']['width'] );
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

endif;
