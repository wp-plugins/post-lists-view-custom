<?php
/*
Plugin Name: Post Lists View Custom
Description: Customize the list of the post and page, and custom post type.
Plugin URI: http://gqevu6bsiz.chicappa.jp
Version: 1.3.2
Author: gqevu6bsiz
Author URI: http://gqevu6bsiz.chicappa.jp/author/admin/
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





class Plvc
{

	var $Ver,
		$Name,
		$Dir,
		$RecordBaseName,
		$RecordSelectCustom,
		$Slug,
		$ThumbnailSize,
		$SetPage,
		$UPFN,
		$Msg;


	function __construct() {
		$this->Ver = '1.3.2';
		$this->Name = 'Post Lists View Custom';
		$this->Dir = WP_PLUGIN_URL . '/' . dirname( plugin_basename( __FILE__ ) ) . '/';
		$this->Slug = 'post_lists_view_custom';
		$this->RecordBaseName = '_lists_view_custom';
		$this->RecordSelectCustom = $this->Slug . '_select_custom';
		$this->ThumbnailSize = 50;
		$this->SetPage = 'post';
		$this->UPFN = 'Y';
		$this->Msg = '';

		$this->PluginSetup();
		add_action( 'admin_head' , array( $this , 'FilterStart' ) );
	}

	// PluginSetup
	function PluginSetup() {
		// load text domain
		load_plugin_textdomain( 'plvc' , false , basename( dirname( __FILE__ ) ) . '/languages' );

		// plugin links
		add_filter( 'plugin_action_links' , array( $this , 'plugin_action_links' ) , 10 , 2 );

		// add menu
		add_action( 'admin_menu' , array( $this , 'admin_menu' ) );

		$this->Msg = '<div class="updated"><p><strong>' . __( 'Please donation' , 'plvc' ) . '</strong></p><p>' . __( 'Please donate for better development.' , 'plvc' ) . '</p><p>&gt;&gt; <a href="http://gqevu6bsiz.chicappa.jp/please-donation/" target="_blank">' . __( 'Donation' , 'plvc' ) . '</a></p></div>';
		$this->Msg .= '<div><p><strong>' . __( 'Please donation' , 'plvc' ) . '</strong></p><p>' . __( 'Please donate for better development.' , 'plvc' ) . '</p><p>&gt;&gt; <a href="http://gqevu6bsiz.chicappa.jp/please-donation/" target="_blank">' . __( 'Donation' , 'plvc' ) . '</a></p></div>';

	}

	// PluginSetup
	function plugin_action_links( $links , $file ) {
		if( plugin_basename(__FILE__) == $file ) {

			$mofile = $this->TransFileCk();
			if( $mofile == false ) {
				$translation_link = '<a href="http://gqevu6bsiz.chicappa.jp/please-translation/" target="_blank">Please translation</a>'; 
				array_unshift( $links, $translation_link );
			}
			$donation_link = '<a href="http://gqevu6bsiz.chicappa.jp/please-donation/" target="_blank">' . __( 'Donation' , 'plvc' ) . '</a>';
			array_unshift( $links, $donation_link );
			array_unshift( $links, '<a href="' . admin_url( 'admin.php?page=' . $this->Slug ) . '">' . __('Settings') . '</a>' );

		}
		return $links;
	}

	// PluginSetup
	function admin_menu() {
		add_menu_page( __( 'Post Lists View Customize' , 'plvc' ) , __( 'Post Lists View Customize' , 'plvc' ) , 'administrator', $this->Slug , array( $this , 'setting_post') );
		add_submenu_page( $this->Slug , __( 'Page Lists View Customize' , 'plvc' ) , __( 'Page Lists View Customize' , 'plvc' ) , 'administrator' , 'page' . $this->RecordBaseName , array( $this , 'setting_page' ) );
		add_submenu_page( $this->Slug , __( 'Media Lists View Customize' , 'plvc' ) , __( 'Media Lists View Customize' , 'plvc' ) , 'administrator' , 'media' . $this->RecordBaseName , array( $this , 'setting_media' ) );
		add_submenu_page( $this->Slug , __( 'Navi Lists View Customize' , 'plvc' ) , __( 'Menu Lists View Customize' , 'plvc' ) , 'administrator' , 'navi' . $this->RecordBaseName , array( $this , 'setting_navi' ) );
		add_submenu_page( $this->Slug , __( 'Navi Advance View Customize' , 'plvc' ) , __( 'Menu Advance View Customize' , 'plvc' ) , 'administrator' , 'navi_advance' . $this->RecordBaseName , array( $this , 'setting_navi_advance' ) );
		add_submenu_page( $this->Slug , __( 'Custom Post Type Lists View Customize' , 'plvc' ) , __( 'Custom Post Type Lists View Customize' , 'plvc' ) , 'administrator' , 'custom_post' . $this->RecordBaseName , array( $this , 'setting_custom' ) );
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
	function setting_post() {
		include_once 'inc/setting_lists.php';
	}

	// SettingPage
	function setting_page() {
		$this->SetPage = 'page';
		include_once 'inc/setting_lists.php';
	}

	// SettingPage
	function setting_media() {
		$this->SetPage = 'media';
		include_once 'inc/setting_media.php';
	}

	// SettingPage
	function setting_navi() {
		$this->SetPage = 'navi';
		include_once 'inc/setting_navi.php';
	}

	// SettingPage
	function setting_navi_advance() {
		$this->SetPage = 'navi_advance';
		include_once 'inc/setting_navi_advance.php';
	}

	// SettingPage
	function setting_custom() {
		if( !empty( $_POST["reset"] ) ) {
			$this->update_reset();
		} elseif( !empty( $_POST[$this->UPFN] ) ) {
			$this->update();
		} 
		if( empty( $_POST["CustomSelect"] ) ) {
			$this->SetPage = 'custom';
			include_once 'inc/select_custom.php';
		} else {
			$this->SetPage = strip_tags( $_POST["CustomSelect"] );
			include_once 'inc/setting_lists.php';
		}
	}


	// Data get
	function get_data( $type ) {
		global $wpdb;

		// Default colum
		$Columns_Def = array(
			"title" => __( 'Title' ) , "date" => __( 'Date' ) , "author" => __( 'Author' ) , "comments" => __( 'Comments' ) , "slug" => __( 'Slug' ) ,
			"categories" => __( 'Categories' ) , "tags" => __( 'Tags' ) , "excerpt" => __('Excerpt') , "id" => 'ID'
		);

		// Theme Support colum
		$ThemeSupports = array( 'post-thumbnails' => __('Featured Image') , 'post-formats' => __( 'Format' ));
		foreach( $ThemeSupports as $Name => $TransName ) {
			$Support = current_theme_supports( $Name );
			if(!empty($Support)) {
				$Columns_Def[$Name] = $TransName;
			}
		}
		unset($ThemeSupports);

		// All Post Custom Field meta
		$Acfk = $wpdb->get_col( "SELECT meta_key FROM $wpdb->postmeta GROUP BY meta_key HAVING meta_key NOT LIKE '\_%' ORDER BY meta_key" );
		if(!empty($Acfk)) {
			natcasesort($Acfk);
		}

		// Default View Setting
		$Columns = array();
		foreach($Columns_Def as $column => $name) {
			if( $column == 'title' or $column == 'author' or $column == 'date' or $column == 'comments' or $column == 'tags' or $column == 'categories' ) {
				$Columns[$column] = array( "use" => 1 , "name" => $name );
			} else {
				$Columns[$column] = array( "not_use" => 1 , "name" => $name );
			}
		}
		unset($Columns_Def);
		foreach($Acfk as $name) {
			$Columns[$name] = array( "not_use" => 1 , "name" => $name );
		}
		unset($Acfk);

		// Data Marge
		$NewData = array();
		$Data = get_option( $type . $this->RecordBaseName );
		if(!empty($Data) and is_array($Data)) {
			foreach($Data as $name => $val) {
				if(!empty($Columns[$name])) {
					$NewData[$name] = $val;
					unset($Columns[$name]);
				}
			}
			if(!empty($Columns) and is_array($Columns)) {
				foreach($Columns as $name => $val) {
					$NewData[$name] = $val;
				}
			}
		} else {
			$NewData = $Columns;
		}

		// checkbox
		if(!empty($NewData["cb"])) {
			unset($NewData["cb"]);
		}

		// unset colum name
		$Unset = array( 'allorany' , 'hide_on_screen' );
		foreach($Unset as $name) {
			unset( $NewData[$name] );
		}
		$MatchUnset = array( 'field_' );
		foreach($MatchUnset as $name) {
			foreach($NewData as $key => $val) {
				if( strpos( $key , $name ) !== false ) {
					unset( $NewData[$key] );
				}
			}
		}

		return $NewData;
	}


	// Data get
	function get_data_media( $type ) {
		// Default colum
		$Columns_Def = array(
			"icon" => __( 'Image' ) , "title" => _x( 'File' , 'column name' ) , "author" => __( 'Author' ) , "parent" => __( 'Attached to' , 'plvc' ) , "comments" => __( 'Comments' ) , "date" => __( 'Date' ) ,
			"media_title" => __( 'Title' ) , "image_alt" => __( 'Alt Text' , 'plvc' ) , "post_excerpt" => __('Caption') , "post_content" => __('Details') , "id" => 'ID'
		);

		// Default View Setting
		$Columns = array();
		foreach($Columns_Def as $column => $name) {
			if( $column == 'title' or $column == 'author' or $column == 'parent' or $column == 'comments' or $column == 'date' ) {
				$Columns[$column] = array( "use" => 1 , "name" => $name );
			} else {
				$Columns[$column] = array( "not_use" => 1 , "name" => $name );
			}
		}
		unset($Columns_Def);

		// Data Marge
		$NewData = array();
		$Data = get_option( $type . $this->RecordBaseName );
		if(!empty($Data) and is_array($Data)) {
			foreach($Data as $name => $val) {
				if(!empty($Columns[$name])) {
					$NewData[$name] = $val;
					unset($Columns[$name]);
				}
			}
			if(!empty($Columns) and is_array($Columns)) {
				foreach($Columns as $name => $val) {
					$NewData[$name] = $val;
				}
			}
		} else {
			$NewData = $Columns;
		}

		// checkbox
		if(!empty($NewData["cb"])) {
			unset($NewData["cb"]);
		}

		return $NewData;
	}

	// Data get
	function get_data_navi( $type ) {
		// Default colum
		$Columns_Def = array(
			"nav-menu-theme-locations" => __( 'Theme Locations' ) , "add-custom-links" => __( 'Custom Links' ), "add-post" => __( 'Posts' ) , "add-page" => __( 'Pages' ) ,
			"add-category" => __( 'Categories' ) , "add-post_tag" => __( 'Tags' ) , "add-post_format" => __('Format')
		);

		// Default View Setting
		$Columns = array();
		foreach($Columns_Def as $column => $name) {
			if( $column == 'nav-menu-theme-locations' or $column == 'add-custom-links' or $column == 'add-page' or $column == 'add-category' ) {
				$Columns[$column] = array( "use" => 1 , "name" => $name );
			} else {
				$Columns[$column] = array( "not_use" => 1 , "name" => $name );
			}
		}
		unset($Columns_Def);

		// Data Marge
		$NewData = array();
		$Data = get_option( $type . $this->RecordBaseName );
		if(!empty($Data) and is_array($Data)) {
			foreach($Data as $name => $val) {
				if(!empty($Columns[$name])) {
					$NewData[$name] = $val;
					unset($Columns[$name]);
				}
			}
			if(!empty($Columns) and is_array($Columns)) {
				foreach($Columns as $name => $val) {
					$NewData[$name] = $val;
				}
			}
		} else {
			$NewData = $Columns;
		}

		return $NewData;
	}

	// Data get
	function get_data_navi_advance( $type ) {
		// Default colum
		$Columns_Def = array(
			"link-target" => __( 'Link Target' ) , "css-classes" => __( 'CSS Classes' ) , "xfn" => __( 'Link Relationship (XFN)' ) , "description" => __( 'Description' )
		);

		// Default View Setting
		$Columns = array();
		foreach($Columns_Def as $column => $name) {
			if( $column == 'no_use_column' ) {
				$Columns[$column] = array( "use" => 1 , "name" => $name );
			} else {
				$Columns[$column] = array( "not_use" => 1 , "name" => $name );
			}
		}
		unset($Columns_Def);

		// Data Marge
		$NewData = array();
		$Data = get_option( $type . $this->RecordBaseName );
		if(!empty($Data) and is_array($Data)) {
			foreach($Data as $name => $val) {
				if(!empty($Columns[$name])) {
					$NewData[$name] = $val;
					unset($Columns[$name]);
				}
			}
			if(!empty($Columns) and is_array($Columns)) {
				foreach($Columns as $name => $val) {
					$NewData[$name] = $val;
				}
			}
		} else {
			$NewData = $Columns;
		}

		return $NewData;
	}

	// Setting Item
	function get_lists( $type , $Data ) {
		$Contents = '';
		foreach($Data as $key => $val) {
			if(!empty($val[$type])) {
				$Contents .= '<div id="'.$key.'" class="widget">';

				$Contents .= '<div class="widget-top">';
				$Contents .= '<div class="widget-title">';
				$Contents .= '<h4>'.$val["name"].'</h4>';
				$Contents .= '</div>';
				$Contents .= '</div>';

				$Contents .= '<div class="widget-inside">';
				$Contents .= '<input type="hidden" name="'.$type.'['.$key.'][name]" value="'.$val["name"].'" />';
				$Contents .= '</div>';

				$Contents .= '</div>';
			}
		}

		return $Contents;
	}

	// Update Setting
	function update() {
		$UPFN = strip_tags( $_POST[$this->UPFN] );
		if( $UPFN == 'Y' ) {
			unset( $_POST[$this->UPFN] );

			$Modes = array( "use" , "not_use" );
			$Update = array();
			foreach($Modes as $mode) {
				if(!empty( $_POST[$mode] )) {
					foreach ($_POST[$mode] as $key => $val) {
						$Update[strip_tags( $key )]["name"] = strip_tags( $val["name"] );
						$Update[strip_tags( $key )][$mode] = 1;
					}
				}
			}

			if(!empty( $Update )) {
				$Record = strip_tags( $_POST["SetPage"] ) . $this->RecordBaseName;
				update_option( $Record , $Update );
				$this->Msg = '<div class="updated"><p><strong>' . __('Settings saved.') . '</strong></p></div>';
			}
		}
	}

	// Update Reset
	function update_reset() {
		$Record = strip_tags( $_POST["SetPage"] ) . $this->RecordBaseName;
		delete_option( $Record );
		$this->Msg = '<div class="updated"><p><strong>' . __('Settings saved.') . '</strong></p></div>';
	}


	// FilterStart
	function FilterStart() {
		global $wpdb;

		$QueryPostType = get_query_var( 'post_type' );
		$LvcNum = $wpdb->get_col( "SELECT option_name FROM $wpdb->options WHERE option_name LIKE  '%$this->RecordBaseName%'" );
		
		if( !empty( $LvcNum ) && is_array( $LvcNum ) ) {
			foreach($LvcNum as $name) {
				$Type[] = str_replace( $this->RecordBaseName , "" , $name);
			}
		}
		if( !empty( $Type ) && is_array( $Type ) ) {
			if( in_array( $QueryPostType , $Type ) == true ) {
				$Data = get_option( $QueryPostType . $this->RecordBaseName );
				if( !empty( $Data ) && is_array( $Data ) ) {
					$FilterName = 'manage_edit-' . $QueryPostType . '_columns';
					add_filter( $FilterName , array( $this , 'ColumnHeader' ) , 101);
					$ActionName = 'manage_' . $QueryPostType . '_posts_custom_column';
					add_action( $ActionName , array( $this , 'ColumnBody' ) , 10 , 2 );
				}
			}
			if ( in_array( 'media' , $Type ) == true ) {
				$Data = get_option( 'media' . $this->RecordBaseName );
				if( !empty( $Data ) && is_array( $Data ) ) {
					$FilterName = 'manage_media_columns';
					add_filter( $FilterName , array( $this , 'ColumnHeaderMedia' ) , 101);
					$ActionName = 'manage_media_custom_column';
					add_action( $ActionName , array( $this , 'ColumnBodyMedia' ) , 10 , 2 );
				}
			}
			if ( in_array( 'navi' , $Type ) == true ) {
				$Data = get_option( 'navi' . $this->RecordBaseName );
				if( !empty( $Data ) && is_array( $Data ) ) {
					$FilterName = 'manage_nav-menus_columns';
					add_filter( $FilterName , array( $this , 'ColumnNavi' ));
					
				}
			}
			if ( in_array( 'navi_advance' , $Type ) == true ) {
				$Data = get_option( 'navi_advance' . $this->RecordBaseName );
				if( !empty( $Data ) && is_array( $Data ) ) {
					$FilterName = 'manage_nav-menus_columns';
					add_filter( $FilterName , array( $this , 'ColumnNaviAdvanceHeader' ));
					$ActionName = 'manage_nav-menus_columns';
					add_action( $ActionName , array( $this , 'ColumnNaviAdvanceBody' ) );
				}
			}
		}
	}
	
	// FilterStart
	function ColumnHeader( $columns ) {
		$QueryPostType = get_query_var( 'post_type' );
		$Data = get_option( $QueryPostType . $this->RecordBaseName );

		$FilterColumn = array( "cb" => $columns["cb"] );
		foreach($Data as $name => $val) {
			if( !empty( $val["use"] ) ) {
				$FilterColumn[$name] = $val["name"];
			}
		}

		wp_enqueue_style( $this->Slug , $this->Dir . dirname( plugin_basename( __FILE__ ) ) . '.css' , array() , $this->Ver );

		return $FilterColumn;
	}

	// FilterStart
	function ColumnBody( $column_name , $post_id) {
		$None = '  -  ';

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
				$thumbnail = get_the_post_thumbnail( $post_id , array( $this->ThumbnailSize , "" ) );
				echo '<a href="media.php?attachment_id=' . $thumbnail_id . '&action=edit">' . $thumbnail . '</a>';
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
									$CustomThumbnail = wp_get_attachment_image_src( $post_meta[0], 'post-thumbnail', true );
									if(!empty($CustomThumbnail)) {
										echo '<a href="media.php?attachment_id=' . $post_meta[0] . '&action=edit"><img src="' . $CustomThumbnail[0] . '" width="'.$this->ThumbnailSize . '" /></a>';
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
								echo '<a href="media.php?attachment_id=' . $post_meta[0] . '&action=edit"><img src="' . $CustomThumbnail[0] . '" width="' . $this->ThumbnailSize . '" /></a>';
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
	function ColumnHeaderMedia( $columns ) {
		$Data = get_option( 'media' . $this->RecordBaseName );

		$FilterColumn = array( "cb" => $columns["cb"] );
		foreach($Data as $name => $val) {
			if( !empty( $val["use"] ) ) {
				$FilterColumn[$name] = $val["name"];
			}
		}

		wp_enqueue_style( $this->Slug , $this->Dir . dirname( plugin_basename( __FILE__ ) ) . '.css' , array() , $this->Ver );

		return $FilterColumn;
	}

	// FilterStart
	function ColumnBodyMedia( $column_name , $post_id) {
		$None = '  -  ';
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
	function ColumnNavi( $columns ) {
		$Data = get_option( 'navi' . $this->RecordBaseName );

		foreach( $Data as $metabox_name => $val ) {
			if( !empty( $val["not_use"] ) ) {
				remove_meta_box( $metabox_name , 'nav-menus' , 'side' );
			}
		}

		return $columns;
	}

	// FilterStart
	function ColumnNaviAdvanceHeader( $columns ) {
		$Data = get_option( 'navi_advance' . $this->RecordBaseName );

		$FilterColumn = array();
		foreach($Data as $name => $val) {
			if( !empty( $val["use"] ) ) {
				$FilterColumn[$name] = $val["name"];
			}
		}
		
		if( !empty( $FilterColumn ) ) {
			$FilterColumn["_title"] = $columns["_title"];
			$FilterColumn["cb"] = $columns["cb"];
		}

		return $FilterColumn;
	}

	// FilterStart
	function ColumnNaviAdvanceBody( $columns ) {
		$Data = get_option( 'navi_advance' . $this->RecordBaseName );
		$user = wp_get_current_user();

		$FilterColumn = array();
		foreach($Data as $name => $val) {
			if( !empty( $val["not_use"] ) ) {
				$FilterColumn[] = $name;
			}
		}
		
		$hide_set = '';
		$hide_field = '';
		foreach( $FilterColumn as $name ) {
			$hide_set .= '.metabox-prefs  label[for=' . $name . '-hide], ';
			$hide_field .= '.menu-item-settings  p.field-' . $name . ', ';
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

}

$Plvc = new Plvc();
