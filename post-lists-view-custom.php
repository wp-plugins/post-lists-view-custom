<?php
/*
Plugin Name: Post Lists View Custom
Description: You can customize the various lists screen.
Plugin URI: http://wordpress.org/extend/plugins/post-lists-view-custom/
Version: 1.5.3.1
Author: gqevu6bsiz
Author URI: http://gqevu6bsiz.chicappa.jp/?utm_source=use_plugin&utm_medium=list&utm_content=plvc&utm_campaign=1_5_3_1
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
		$AuthorUrl,
		$ltd,
		$ltd_p,
		$Record,
		$PageSlug,
		$SetPage,
		$ThumbnailSize,
		$Multiple,
		$UPFN,
		$Msg;


	function __construct() {
		$this->Ver = '1.5.3.1';
		$this->Name = 'Post Lists View Custom';
		$this->Dir = WP_PLUGIN_URL . '/' . dirname( plugin_basename( __FILE__ ) ) . '/';
		$this->AuthorUrl = 'http://gqevu6bsiz.chicappa.jp/';
		$this->ltd = 'plvc';
		$this->ltd_p = $this->ltd . '_plugin';
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
		$this->ThumbnailSize = 50;
		$this->Multiple = false;
		$this->UPFN = 'Y';
		$this->DonateKey = 'd77aec9bc89d445fd54b4c988d090f03';
		$this->Msg = '';

		$this->PluginSetup();
		$this->FilterStart();
	}
	
	// PluginSetup
	function PluginSetup() {
		// load text domain
		load_plugin_textdomain( $this->ltd , false , basename( dirname( __FILE__ ) ) . '/languages' );
		load_plugin_textdomain( $this->ltd_p , false , basename( dirname( __FILE__ ) ) . '/languages' );

		// plugin links
		add_filter( 'plugin_action_links' , array( $this , 'plugin_action_links' ) , 10 , 2 );

		// add menu
		add_action( 'admin_menu' , array( $this , 'admin_menu' ) );

		// setup database
		register_activation_hook( __FILE__ , array( $this , 'SetupRecord' ) );

		// get donation toggle
		add_action( 'wp_ajax_plvc_get_donation_toggle' , array( $this , 'wp_ajax_plvc_get_donation_toggle' ) );

		// set donation toggle
		add_action( 'wp_ajax_plvc_set_donation_toggle' , array( $this , 'wp_ajax_plvc_set_donation_toggle' ) );

		// setting check user role
		add_action( 'admin_notices' , array( $this , 'settingCheck' ) ) ;
	}

	// PluginSetup
	function plugin_action_links( $links , $file ) {
		if( plugin_basename(__FILE__) == $file ) {

			$mofile = $this->TransFileCk();
			if( $mofile == false ) {
				$translation_link = '<a href="' . $this->AuthorUrl . 'please-translation/?utm_source=use_plugin&utm_medium=side&utm_content=' . $this->ltd . '&utm_campaign=' . str_replace( '.' , '_' , $this->Ver ) . '" target="_blank">Please translation</a>'; 
				array_unshift( $links, $translation_link );
			}
			$support_link = '<a href="http://wordpress.org/support/plugin/post-lists-view-custom" target="_blank">' . __( 'Support Forums' ) . '</a>';
			array_unshift( $links, $support_link );
			array_unshift( $links, '<a href="' . admin_url( 'admin.php?page=' . $this->PageSlug ) . '">' . __('Settings') . '</a>' );

		}
		return $links;
	}

	// PluginSetup
	function admin_menu() {
		wp_enqueue_style( $this->ltd . '-table' , $this->Dir . dirname( plugin_basename( __FILE__ ) ) . '-table.css' , array() , $this->Ver );
		add_menu_page( 'Post Lists View Custom' , 'Post Lists View Custom' , 'administrator', $this->PageSlug , array( $this , 'setting_default') );
		add_submenu_page( $this->PageSlug , __( 'All Posts List Customize' , $this->ltd ) , __( 'All Posts' ) , 'administrator' , $this->Record["post"] , array( $this , 'setting_post' ) );
		add_submenu_page( $this->PageSlug , __( 'All Pages List Customize' , $this->ltd ) , __( 'All Pages' ) , 'administrator' , $this->Record["page"] , array( $this , 'setting_page' ) );
		add_submenu_page( $this->PageSlug , __( 'Media Library List Customize' , $this->ltd ) , __( 'Media Library' ) , 'administrator' , $this->Record["media"] , array( $this , 'setting_media' ) );
		add_submenu_page( $this->PageSlug , __( 'Comments List Customize' , $this->ltd ) , __( 'Comments' ) , 'administrator' , $this->Record["comments"] , array( $this , 'setting_comments' ) );
		add_submenu_page( $this->PageSlug , __( 'Available Widgets List Customize' , $this->ltd ) , __( 'Available Widgets' ) , 'administrator' , $this->Record["widgets"] , array( $this , 'setting_widgets' ) );
		add_submenu_page( $this->PageSlug , __( 'Menus show screen List Customize' , $this->ltd ) , __( 'Menus' ) , 'administrator' , $this->Record["menus"] , array( $this , 'setting_menus' ) );
		add_submenu_page( $this->PageSlug , __( 'Menus show advanced properties screen List Customize' , $this->ltd ) , __( 'Menus advanced properties' , $this->ltd ) , 'administrator' , $this->Record["menus_adv"] , array( $this , 'setting_menus_adv' ) );
		add_submenu_page( $this->PageSlug , __( 'Custom Posts Type' , $this->ltd ) , __( 'Custom Posts Type' , $this->ltd ) , 'administrator' , 'select_custom_posts_list_view_setting' , array( $this , 'select_custom_posts' ) );
		add_submenu_page( $this->PageSlug , __( 'Custom Posts Type List Customize' , $this->ltd ) , sprintf( '<div style="display: none;">$s</div>' , __( 'Custom Posts Type' , $this->ltd ) ) , 'administrator' , $this->Record["custom_posts"] , array( $this , 'setting_custom_posts' ) );
		add_submenu_page( $this->PageSlug , __( 'Setting Thumbnail size' , $this->ltd ) , __( 'Setting Thumbnail size' , $this->ltd ) , 'administrator' , $this->Record["thunmbnail"] , array( $this , 'setting_thumbnail' ) );
	}

	// PluginSetup
	function SetupRecord() {
		global $wpdb;

		$LvcNum = $wpdb->get_col( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE  '%_lists_view_custom%'" );

		if( !empty( $LvcNum ) ) {

			$Data = array();
			foreach( $LvcNum as $record_name) {
				$Data[$record_name] = get_option( $record_name );
			}
			
			if( isset( $Data["thumbnail_size_lists_view_custom"] ) ) {

				$Update = array();
				$Update = array( "UPFN" => $this->UPFN );
				$Update["width"] = intval( $Data["thumbnail_size_lists_view_custom"]["width"] );
				update_option( $this->Record["thunmbnail"] , $Update );
				unset( $Data["thumbnail_size_lists_view_custom"] );
				delete_option( "thumbnail_size_lists_view_custom" );
			
			}

			$RecordArr = array();
			$RecordArr["post"] = "post_lists_view_custom";
			$RecordArr["page"] = "page_lists_view_custom";
			$RecordArr["media"] = "media_lists_view_custom";
			$RecordArr["comments"] = "comment_lists_view_custom";
			$RecordArr["menus"] = "navi_lists_view_custom";
			$RecordArr["menus_adv"] = "navi_advance_lists_view_custom";

			foreach( $RecordArr as $record => $old_record ) {
				if( isset( $Data[$old_record] ) ) {

					$Update = array();
					$Update = $this->DataConvert( $Data[$old_record] );
					$Update["UPFN"] = $this->UPFN;
					update_option( $this->Record[$record] , $Update );
					delete_option( $old_record );
					unset( $Data[$old_record] );
	
				}
			}

			if( !empty( $Data ) ) {
				$Update = array();
				$Update = array( "UPFN" => $this->UPFN );
				foreach( $Data as $custom_post_record => $val ) {
					$CustomPostName = str_replace( '_lists_view_custom' , '' , $custom_post_record );
					$Update[$CustomPostName] = $this->DataConvert( $val );
					delete_option( $custom_post_record );
				}
				update_option( $this->Record["custom_posts"] , $Update );
			}

		}

		$Data = $this->get_data( "user_role" );
		if( empty( $Data["UPFN"] ) ) {

			$UserRoles = $this->get_user_role();
			$Update = array();
			$Update["UPFN"] = $this->UPFN;
			
			foreach( $UserRoles as $user_role => $role_name ) {
				$Update[$user_role] = 1;
			}
			update_option( $this->Record["user_role"] , $Update );

		}

	}

	// PluginSetup
	function DataConvert( $Data ) {
		$NewData = array();
		
		foreach( $Data as $id => $column ) {
			if( !empty( $column["use"] ) ) {
				$NewData["use"][$id] = array( "name" => $column["name"] );
			} else {
				$NewData["not_use"][$id] = array( "name" => $column["name"] );
			}
		}
		
		return $NewData;
	}



	// Translation File Check
	function TransFileCk() {
		$file = false;
		$moFile = WP_PLUGIN_DIR . '/' . dirname( plugin_basename( __FILE__ ) ) . '/languages/plvc-' . get_locale() . '.mo';
		if( file_exists( $moFile ) ) {
			$file = true;
		}
		return $file;
	}





	// SettingPage
	function setting_default() {
		add_filter( 'admin_footer_text' , array( $this , 'layout_footer' ) );
		
		$this->DisplayDonation();
		include_once 'inc/setting_default.php';
	}

	// SettingPage
	function setting_post() {
		$this->SetPage = 'post';
		
		add_filter( 'admin_footer_text' , array( $this , 'layout_footer' ) );
		$this->DisplayDonation();
		include_once 'inc/setting_lists_post.php';
	}

	// SettingPage
	function setting_page() {
		$this->SetPage = 'page';
		
		add_filter( 'admin_footer_text' , array( $this , 'layout_footer' ) );
		$this->DisplayDonation();
		include_once 'inc/setting_lists_post.php';
	}

	// SettingPage
	function setting_media() {
		$this->SetPage = 'media';
		
		add_filter( 'admin_footer_text' , array( $this , 'layout_footer' ) );
		$this->DisplayDonation();
		include_once 'inc/setting_lists.php';
	}

	// SettingPage
	function setting_comments() {
		$this->SetPage = 'comments';
		
		add_filter( 'admin_footer_text' , array( $this , 'layout_footer' ) );
		$this->DisplayDonation();
		include_once 'inc/setting_lists.php';
	}

	// SettingPage
	function setting_widgets() {
		$this->SetPage = 'widgets';
		
		add_filter( 'admin_footer_text' , array( $this , 'layout_footer' ) );
		$this->DisplayDonation();
		include_once 'inc/setting_lists.php';
	}

	// SettingPage
	function setting_menus() {
		$this->SetPage = 'menus';
		
		add_filter( 'admin_footer_text' , array( $this , 'layout_footer' ) );
		$this->DisplayDonation();
		include_once 'inc/setting_lists.php';
	}

	// SettingPage
	function setting_menus_adv() {
		$this->SetPage = 'menus_adv';
		
		add_filter( 'admin_footer_text' , array( $this , 'layout_footer' ) );
		$this->DisplayDonation();
		include_once 'inc/setting_lists.php';
	}

	// SettingPage
	function select_custom_posts() {
		add_filter( 'admin_footer_text' , array( $this , 'layout_footer' ) );
		$this->DisplayDonation();
		include_once 'inc/select_custom.php';
	}

	// SettingPage
	function setting_custom_posts() {
		$this->SetPage = 'custom_posts';

		$PostSlug = '';
		if( !empty( $_GET["setname"] ) && !empty( $_GET["name"] ) ) {
			$PostSlug = strip_tags( $_GET["name"] );
		}

		add_filter( 'admin_footer_text' , array( $this , 'layout_footer' ) );
		$this->DisplayDonation();

		if( !empty( $PostSlug ) ) {
			include_once 'inc/setting_lists_post.php';
		} else {
			echo sprintf( '<p>%s</p>' , __( 'No custom post type found.' , $this->ltd ) );
			echo sprintf( '<p><a href="%2$s">%1$s</a></p>' , __( 'Please select a Custom Posts type from here.' , $this->ltd ) , self_admin_url( 'admin.php?page=select_custom_posts_list_view_setting' ) );
		}

	}

	// SettingPage
	function setting_thumbnail() {
		$this->SetPage = 'thunmbnail';
		
		add_filter( 'admin_footer_text' , array( $this , 'layout_footer' ) );
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
	function get_data_columns( $record ) {
		$Data = array();
		
		if( !empty( $record ) ) {

			$GetData = get_option( $this->Record[$record] );

			if( $record == 'post' ) {
				$Columns = $this->get_post_columns();
			} elseif( $record == 'page' ) {
				$Columns = $this->get_page_columns();
			} elseif( $record == 'media' ) {
				$Columns = $this->get_media_columns();
			} elseif( $record == 'comments' ) {
				$Columns = $this->get_comments_columns();
			} elseif( $record == 'widgets' ) {
				$Columns = $this->get_widgets_columns();
			} elseif( $record == 'menus' ) {
				$Columns = $this->get_menus_columns();
			} elseif( $record == 'menus_adv' ) {
				$Columns = $this->get_menus_adv_columns();
			} elseif( $record == 'custom_posts' ) {
				$Columns = $this->get_custom_posts_columns();
				if( !empty( $GetData[strip_tags( $_GET["name"] )] ) ) {
					$GetData = $GetData[strip_tags( $_GET["name"] )];
				} else {
					$GetData = array();
				}
			}

			if( empty( $GetData ) ) {

				$Data = $Columns;

			} else {

				if( !empty( $GetData["use"] ) ) {
					$NewColumns = array();
					foreach( $GetData["use"] as $column_id => $column_val ) {
						$NewColumns[$column_id] = array( "use" => 1 , "name" => $column_val["name"] );
						unset( $Columns[$column_id] );
					}
					if( !empty( $GetData["not_use"] ) ) {
						foreach( $GetData["not_use"] as $column_id => $column_val ) {
							$NewColumns[$column_id] = array( "not_use" => 1 , "name" => $column_val["name"] );
							unset( $Columns[$column_id] );
						}
					}
					foreach( $Columns as $column_id => $column_val ) {
						$NewColumns[$column_id] = array( "not_use" => 1 , "name" => $column_val["name"] );
						unset( $Columns[$column_id]["use"] );
					}
					unset( $Columns );
					$Columns = $NewColumns;
				} else {
					foreach( $Columns as $column_id => $column_val ) {
						if( !empty( $column_val["use"] ) ) {
							$Columns[$column_id]["not_use"] = 1;
							unset( $Columns[$column_id]["use"] );
						}
					}
				}

				$Data = $Columns;

			}

		}
		
		return $Data;
	}

	// GetData
	function get_data_thumbnail() {
		$Data = $this->get_data( "thunmbnail" );
		
		if( !empty( $Data ) ) {
			$Thumbnail["width"] = intval( $Data["width"] );
		} else {
			$Thumbnail["width"] = intval( $this->ThumbnailSize );
		}
		
		return $Thumbnail;
	}




	// Settingcheck
	function settingCheck() {
		global $current_screen;

		$Data = $this->get_data( 'user_role' );

		if( !empty( $Data["UPFN"] ) ) {
			unset( $Data["UPFN"] );
		}

		if( empty( $Data ) ) {

			if( $current_screen->parent_base == $this->PageSlug && $current_screen->id != 'toplevel_page_post_lists_view_custom' && $current_screen->id != 'post-lists-view-custom_page_plvc_add_multiple' ) {
				echo '<div class="error"><p><strong>' . sprintf( __( 'Authority to apply the setting is not selected. <a href="%s">From here</a>, please select the permissions you want to set.' , $this->ltd ) , self_admin_url( 'admin.php?page=' . $this->PageSlug ) ) . '</strong></p></div>';
			}

		}
	}

	// SetList
	function get_user_role() {
		$editable_roles = get_editable_roles();
		foreach ( $editable_roles as $role => $details ) {
			$UserRole[$role] = translate_user_role( $details['name'] );
		}

		return $UserRole;
	}

	// SetList
	function get_custom_fields_columns( $Columns ) {
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
				if( strpos( $val , $name ) !== false ) {
					unset( $All_custom_columns[$key] );
				}
			}
		}

		foreach($All_custom_columns as $name) {
			$Columns[$name] = array( "not_use" => 1 , "name" => $name );
		}
		unset($All_custom_columns);

		return $Columns;
	}

	// SetList
	function get_plugin_fields_columns( $post_type , $Columns ) {

		$RegistColumns = $this->get_data( 'regist_columns' );

		if( !empty( $RegistColumns[$post_type] ) ) {
			
			$CurrentColumns = $RegistColumns[$post_type];

			foreach( $CurrentColumns as $column_name => $column_label ) {
				if( empty( $Columns[$column_name] ) ) {
					$Columns[$column_name] = array( "use" => 1 , "name" => $column_label );
				}
			}
		}

		return $Columns;
	}

	// SetList
	function get_columns_label( $column ) {

		global $wp_version;

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

			if ( version_compare( $wp_version, "3.5", '>=' ) ) {
				$Label = _x( 'Uploaded to' , 'column name' );
			} else {
				$Label = _x( 'Attached to' , 'column name' );
			}

		} elseif( $column_name == 'image_alt' ) {

			if ( version_compare( $wp_version, "3.5", '>=' ) ) {
				$Label = __( 'Alternative Text' );
			} else {
				$Label = __( 'Alternate Text' );
			}

		} elseif( $column_name == 'date' ) {

			if( $post_type == 'media' ) {
				$Label = _x( 'Date' , 'column name' );
			} else {
				$Label = __( 'Date' );
			}

		} elseif( $column_name == 'add-custom-links' ) {

			if ( version_compare( $wp_version, "3.5.1", '>' ) ) {
				$Label = __( 'Links' );
			} else {
				$Label = __( 'Custom Links' );
			}

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

	// SetList
	function get_post_columns() {
		// Default colum
		$Columns_Def = array(
			"title" , "author" , "categories" , "tags" , "comments" , "date" ,
			"slug" , "excerpt" , "id"
		);
		
		// Theme Support colum
		$ThemeSupports = array( 'post-thumbnails' , 'post-formats' );
		foreach( $ThemeSupports as $name ) {
			$Support = current_theme_supports( $name );
			if(!empty($Support)) {
				$Columns_Def[] = $name;
			}
		}
		unset($ThemeSupports);

		// set label
		$SetColumns = array();
		foreach( $Columns_Def as $id ) {
			$SetColumns[$id] = $this->get_columns_label( array( "post" , $id ) );
		}
		unset($Columns_Def);

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
		$Columns_Def = array(
			"title" , "author" , "comments" , "date" ,
			"slug" , "excerpt" , "id"
		);

		// Theme Support colum
		$ThemeSupports = array( 'post-thumbnails' );
		foreach( $ThemeSupports as $name ) {
			$Support = current_theme_supports( $name );
			if(!empty($Support)) {
				$Columns_Def[] = $name;
			}
		}
		unset($ThemeSupports);

		// set label
		$SetColumns = array();
		foreach( $Columns_Def as $id ) {
			$SetColumns[$id] = $this->get_columns_label( array( "page" , $id ) );
		}
		unset($Columns_Def);

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
		$Columns_Def = array(
			"icon" , "title" , "author" , "parent" , "comments" , "date" ,
			"media_title" , "image_alt" , "post_excerpt" , "post_content" , "id"
		);

		// set label
		$SetColumns = array();
		foreach( $Columns_Def as $id ) {
			$SetColumns[$id] = $this->get_columns_label( array( "media" , $id ) );
		}
		unset($Columns_Def);

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
		$Columns_Def = array(
			"author" , "comment" , "response" , 
			"newcomment_author" , "newcomment_author_email" , "newcomment_author_url" , "id"
		);

		// set label
		$SetColumns = array();
		foreach( $Columns_Def as $id ) {
			$SetColumns[$id] = $this->get_columns_label( array( "comments" , $id ) );
		}
		unset($Columns_Def);

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
		foreach($Columns_Def as $column => $name) {
			$Columns[$column] = array( "use" => 1 , "name" => $name );
		}
		unset($Columns_Def);

		return $Columns;

	}

	// SetList
	function get_menus_columns() {
		global $wp_version;

		// Default colum
		$Columns_Def = array(
			"nav-menu-theme-locations" , "add-custom-links" , "add-page" ,
			"add-category" , "add-post_format" , "add-post" , "add-post_tag" , 
		);

		if ( version_compare( $wp_version, "3.5.1", '>' ) ) {
			if( in_array( "nav-menu-theme-locations" , $Columns_Def ) ) {
				$index = array_search( "nav-menu-theme-locations" , $Columns_Def );
				unset( $Columns_Def[$index] );
			}
		}

		// set label
		$SetColumns = array();
		foreach( $Columns_Def as $id ) {
			$SetColumns[$id] = $this->get_columns_label( array( "menus" , $id ) );
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
				$Columns['add-'.$CustomPost->name] = array( "use" => 1 , "name" => esc_html( $CustomPost->labels->name ) );
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
			$SetColumns[$id] = $this->get_columns_label( array( "menus_adv" , $id ) );
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
		$Columns_Def = array(
			"title" , "author" , "categories" , "tags" , "comments" , "date" ,
			"slug" , "excerpt" , "id"
		);
		
		// Theme Support colum
		$ThemeSupports = array( 'post-thumbnails' , 'post-formats' );
		foreach( $ThemeSupports as $name ) {
			$Support = current_theme_supports( $name );
			if(!empty($Support)) {
				$Columns_Def[] = $name;
			}
		}
		unset($ThemeSupports);

		// set label
		$SetColumns = array();
		foreach( $Columns_Def as $id ) {
			$SetColumns[$id] = $this->get_columns_label( array( "post" , $id ) );
		}
		unset($Columns_Def);

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
	function get_lists( $type , $Data , $PostType ) {
		$Contents = '';
		foreach($Data as $key => $val) {
			if( !empty( $val[$type] ) ) {
				$Contents .= '<div id="'.$key.'" class="widget">';

				$Contents .= '<div class="widget-top">';
				$Contents .= '<div class="widget-title">';

				$Contents .= '<h4>' . stripslashes( $val["name"] ) . '</h4>';

				$Contents .= '</div>';
				$Contents .= '</div>';

				$Contents .= '<div class="widget-inside">';
				$Contents .= "\n\n";
				$Contents .= '<input type="hidden" name="' . $type . '[' . $key . '][name]" value="' . esc_html( stripslashes( $val["name"] ) ) . '" />';
				
				$Contents .= "\n\n";
				$Contents .= '</div>';

				$Contents .= '</div>';
			}
		}

		return $Contents;
	}

	// SetList
	function get_apply_roles() {

		$apply_user_roles = $this->get_data( 'user_role' );
		unset( $apply_user_roles["UPFN"] );
		
		$Contents =  __( 'Apply user roles' , $this->ltd ) . ' : ';
		
		if( !empty( $apply_user_roles ) ) {
			$UserRoles = $this->get_user_role();
			foreach( $apply_user_roles as $role => $v ) {
				$Contents .= '[ ' . $UserRoles[$role] . ' ]';
			}
		} else {
				$Contents .= __( 'None' );
		}

		$Contents = apply_filters( 'plvc_get_apply_roles' , $Contents );

		return $Contents;

	}

	// SetList
	function get_user_role_group() {
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
	function post_columns_default_load( $columns ) {
		
		global $typenow;
		
		$UserRole = $this->get_user_role_group();
		
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
	function wp_ajax_plvc_get_donation_toggle() {
		echo get_option( $this->ltd . '_donate_width' );
		die();
	}

	// SetList
	function wp_ajax_plvc_set_donation_toggle() {
		update_option( $this->ltd . '_donate_width' , strip_tags( $_POST["f"] ) );
		die();
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

	// Update Reset
	function update_reset( $record ) {
		$Update = $this->update_validate();
		if( !empty( $Update ) ) {
			$this->delete_record( $this->Record[$record] );
		}
	}

	// Update Reset
	function delete_record( $Record ) {
		$Record = apply_filters( 'plvc_pre_delete' , $Record );
		delete_option( $Record );
		$this->Msg .= '<div class="updated"><p><strong>' . __('Settings saved.') . '</strong></p></div>';
	}

	// DataUpdate
	function update_record( $Record , $Data ) {
		$Record = apply_filters( 'plvc_pre_update' , $Record );
		update_option( $Record , $Data );
		$this->Msg .= '<div class="updated"><p><strong>' . __('Settings saved.') . '</strong></p></div>';
	}

	// Update Reset
	function update_custom_posts_reset( $record ) {
		$Update = $this->update_validate();
		if( !empty( $Update ) ) {
			$Record_fil = apply_filters( 'plvc_pre_delete' , $this->Record['custom_posts'] );
			$GetData = get_option( $Record_fil );
			unset( $GetData[$record] );

			$this->update_record( $this->Record["custom_posts"] , $GetData );
		}
	}

	// DataUpdate
	function DonatingCheck() {
		$Update = $this->update_validate();

		if( !empty( $Update ) ) {
			if( !empty( $_POST["donate_key"] ) ) {
				$SubmitKey = md5( strip_tags( $_POST["donate_key"] ) );
				if( $this->DonateKey == $SubmitKey ) {
					update_option( $this->Record["donate"] , $SubmitKey );
					$this->Msg .= '<div class="updated"><p><strong>' . __( 'Thank you for your donation.' , $this->ltd_p ) . '</strong></p></div>';
				}
			}
		}

	}

	// DataUpdate
	function update_userrole() {
		$Update = $this->update_validate();
		if( !empty( $Update ) ) {

			if( !empty( $_POST["data"]["user_role"] ) ) {
				foreach($_POST["data"]["user_role"] as $key => $val) {
					$tmpK = strip_tags( $key );
					$tmpV = strip_tags ( $val );
					$Update[$tmpK] = $tmpV;
				}
			}

			$this->update_record( $this->Record["user_role"] , $Update );
		}
	}

	// DataUpdate
	function update_data( $record ) {
		$Update = $this->update_validate();
		if( !empty( $Update ) ) {

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
			
			$this->update_record( $this->Record[$record] , $Update );
		}
	}

	// DataUpdate
	function update_thunmbnail( $record ) {
		$Update = $this->update_validate();
		if( !empty( $Update ) ) {

			if( !empty( $_POST["width"] ) ) {
				$Update["width"] = intval( $_POST["width"] );
			}
			
			$this->update_record( $this->Record[$record] , $Update );
		}
	}

	// DataUpdate
	function update_custom_posts_data( $record ) {
		$Update = $this->update_validate();
		if( !empty( $Update ) ) {

			$Record_fil = apply_filters( 'plvc_pre_delete' , $this->Record['custom_posts'] );
			$GetData = get_option( $Record_fil );
			unset( $GetData[$record] );

			$Modes = array( "use" , "not_use" );
			foreach($Modes as $mode) {
				$GetData[$record][$mode] = array();
				if( !empty( $_POST[$mode] ) ) {
					$Columns = $_POST[$mode];
					foreach( $Columns as $column_id => $column_name ) {
						$tmpK = strip_tags( $column_id );
						$tmpV = stripslashes( $column_name["name"] );
						$GetData[$record][$mode][$tmpK]["name"] = $tmpV;
					}
				}
			}
			
			$this->update_record( $this->Record['custom_posts'] , $GetData );
		}
	}




	// FilterStart
	function FilterStart() {
		if ( is_admin() ) {
			// default columns load.
			add_action( 'load-edit.php' , array( $this , 'post_columns_default_load_action' ) );
			
			// Filter Set
			add_action( 'admin_head' , array( $this , 'columns_init' ) );
		}
	}

	// FilterStart
	function post_columns_default_load_action() {
		global $typenow;
		add_filter( "manage_edit-" . $typenow . "_columns" , array( $this , 'post_columns_default_load' ) , 10000 );
	}

	// FilterStart
	function columns_init() {

		$SettingRole = $this->get_data( 'user_role' );
		$SettingRole = apply_filters( 'plvc_pre_setting_roles' , $SettingRole );
		
		if( !empty( $SettingRole ) ) {
			unset($SettingRole["UPFN"]);
			
			$UserRole = $this->get_user_role_group();
		
			if( !is_network_admin() && !empty( $UserRole) ) {
				if( array_key_exists( $UserRole , $SettingRole ) ) {

						global $current_screen;
	
						$Data = array();
						if( $current_screen->base == 'edit' ) {
							if( $current_screen->post_type == 'post' or $current_screen->post_type == 'page' ) {
								$Data = $this->get_data( $current_screen->post_type );
							} else {
								$Custom = $this->get_data( "custom_posts" );
								if( !empty( $Custom[$current_screen->post_type] ) ) {
									$Data = $Custom[$current_screen->post_type];
								}
							}
							
							$hook_header = array( "manage_edit-" . $current_screen->post_type . "_columns" , "PostsColumnHeader" );
							$hook_body = array( "manage_" . $current_screen->post_type . "_posts_custom_column" , "PostsColumnBody" );
	
							if( !empty( $Data ) && !empty( $hook_header ) && !empty( $hook_body ) ) {
								add_filter( $hook_header[0] , array( $this , $hook_header[1] ) , 10001 );
								add_action( $hook_body[0] , array( $this , $hook_body[1] ) , 10 , 2 );
							}
						}
	
						if( $current_screen->base == $current_screen->id && $current_screen->id == 'upload' ) {
							$Data = $this->get_data( "media" );
							
							$hook_header = array( "manage_media_columns" , "MediaColumnHeader" );
							$hook_body = array( "manage_media_custom_column" , "MediaColumnBody" );
	
							if( !empty( $Data ) && !empty( $hook_header ) && !empty( $hook_body ) ) {
								add_filter( $hook_header[0] , array( $this , $hook_header[1] ) , 10001 );
								add_action( $hook_body[0] , array( $this , $hook_body[1] ) , 10 , 2 );
							}
						}
						
						if( $current_screen->base == $current_screen->id && $current_screen->id == 'edit-comments' ) {
							$Data = $this->get_data( "comments" );
							
							$hook_header = array( "manage_edit-comments_columns" , "CommentsColumnHeader" );
							$hook_body = array( "manage_comments_custom_column" , "CommentsColumnBody" );
	
							if( !empty( $Data ) && !empty( $hook_header ) && !empty( $hook_body ) ) {
								add_filter( $hook_header[0] , array( $this , $hook_header[1] ) , 10001 );
								add_action( $hook_body[0] , array( $this , $hook_body[1] ) , 10 , 2 );
							}
						}
						
						if( $current_screen->base == $current_screen->id && $current_screen->id == 'widgets' ) {
							$Data = $this->get_data( "widgets" );
							
							if( !empty( $Data ) ) {
								add_filter( 'widgets_admin_page' , array( $this , 'WidgetsColumnBody' ) );
							}
						}
	
						if( $current_screen->base == $current_screen->id && $current_screen->id == 'nav-menus' ) {
							$Data = $this->get_data( "menus" );
							
							if( !empty( $Data ) ) {
								add_filter( "manage_nav-menus_columns" , array( $this , "MenusMetaBox" ) );
							}
	
							$Data = $this->get_data( "menus_adv" );
							
							$hook_header = array( "manage_nav-menus_columns" , "MenusAdvColumnHeader" );
							$hook_body = array( "manage_nav-menus_columns" , "MenusAdvColumnBody" );
	
							if( !empty( $Data ) && !empty( $hook_header ) && !empty( $hook_body ) ) {
								add_filter( $hook_header[0] , array( $this , $hook_header[1] ) );
								add_action( $hook_body[0] , array( $this , $hook_body[1] ) );
							}
						}
					
				}
			}

		}

	}

	// FilterStart
	function PostsColumnHeader( $columns ) {
		global $current_screen;
		
		if( $current_screen->post_type == 'post' or $current_screen->post_type == 'page' ) {
			$Data = $this->get_data( $current_screen->post_type );
		} else {
			$Custom = $this->get_data( "custom_posts" );
			$Data = $Custom[$current_screen->post_type];
		}

		$FilterColumn = array( "cb" => $columns["cb"] );
		if( !empty( $Data["use"] ) ) {
			foreach( $Data["use"] as $id => $name ) {
				$FilterColumn[$id] = stripslashes( $name["name"] );
			}
		}

		wp_enqueue_style( $this->ltd . '-table' , $this->Dir . dirname( plugin_basename( __FILE__ ) ) . '-table.css' , array() , $this->Ver );

		return $FilterColumn;
	}

	// FilterStart
	function PostsColumnBody( $column_name , $post_id ) {
		$None = '';

		$Thumbnail_setting = $this->get_data_thumbnail();

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
				echo '<a href="media.php?attachment_id=' . $thumbnail_id . '&action=edit"><img src="' . $thumbnail[0] . '" width="' . $Thumbnail_setting["width"] . '" /></a>';
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
										echo '<a href="media.php?attachment_id=' . $post_meta[0] . '&action=edit"><img src="' . $CustomThumbnail[0] . '" width="' . $Thumbnail_setting["width"] . '" /></a>';
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
								echo '<a href="media.php?attachment_id=' . $post_meta[0] . '&action=edit"><img src="' . $CustomThumbnail[0] . '" width="' . $Thumbnail_setting["width"] . '" /></a>';
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
		$Data = $this->get_data( 'media' );

		$FilterColumn = array( "cb" => $columns["cb"] );
		if( !empty( $Data["use"] ) ) {
			foreach( $Data["use"] as $id => $name ) {
				$FilterColumn[$id] = esc_html( $name["name"] );
			}
		}

		wp_enqueue_style( $this->ltd . '-table' , $this->Dir . dirname( plugin_basename( __FILE__ ) ) . '-table.css' , array() , $this->Ver );

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
		$Data = $this->get_data( 'comments' );

		$FilterColumn = array( "cb" => $columns["cb"] );
		if( !empty( $Data["use"] ) ) {
			foreach( $Data["use"] as $id => $name ) {
				$FilterColumn[$id] = esc_html( $name["name"] );
			}
		}

		wp_enqueue_style( $this->ltd . '-table' , $this->Dir . dirname( plugin_basename( __FILE__ ) ) . '-table.css' , array() , $this->Ver );

		return $FilterColumn;
	}

	// FilterStart
	function CommentsColumnBody( $column_name , $comment_id ) {
		$None = '';
		$comments = get_comments( array( 'ID' => $comment_id ) );
		
		if( !empty( $comments[0] ) ) {
			$comment = $comments[0];

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
		
		$Data = $this->get_data( "widgets" );
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
		$Data = $this->get_data( 'menus' );

		if( !empty( $Data["not_use"] ) ) {
			foreach( $Data["not_use"] as $id => $name ) {
				remove_meta_box( $id , 'nav-menus' , 'side' );
			}
		}

		return $columns;
	}

	// FilterStart
	function MenusAdvColumnHeader( $columns ) {

		$Data = $this->get_data( 'menus_adv' );

		$FilterColumn = array();
		if( !empty( $Data["use"] ) ) {
			foreach( $Data["use"] as $id => $name ) {
				$FilterColumn[$id] = esc_html( $name["name"] );
			}
		}
		
		if( !empty( $FilterColumn ) ) {
			$FilterColumn["cb"] = $columns["cb"];
			$FilterColumn["_title"] = $columns["_title"];
		}

		return $FilterColumn;
	}

	// FilterStart
	function MenusAdvColumnBody( $columns ) {
		$Data = $this->get_data( 'menus_adv' );
		$user = wp_get_current_user();

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

echo
'
<script>
jQuery(document).ready(function($) {
	$("' . $hide_set . '").hide();
	$("' . $hide_field . '").hide();
});
</script>
';

		return $columns;
	}

	// FilterStart
	function layout_footer( $text ) {
		$text = '<img src="http://www.gravatar.com/avatar/7e05137c5a859aa987a809190b979ed4?s=18" width="18" /> Plugin developer : <a href="'. $this->AuthorUrl . '?utm_source=use_plugin&utm_medium=footer&utm_content=' . $this->ltd . '&utm_campaign=' . str_replace( '.' , '_' , $this->Ver ) . '" target="_blank">gqevu6bsiz</a>';
		return $text;
	}

	// FilterStart
	function DisplayDonation() {
		$donation = get_option( $this->ltd . '_donated' );
		if( $this->DonateKey != $donation ) {
			$this->Msg .= '<div class="updated" style="background: #E5FFE2; border-color: #7BE762;"><p><strong>' . __( 'To donate if you feel that it is useful, please.' , $this->ltd_p ) . '</strong></p></div>';
		}
	}


}

$Plvc = new Post_Lists_View_Custom();
