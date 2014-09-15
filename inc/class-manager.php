<?php

if ( !class_exists( 'Plvc_Manager' ) ) :

class Plvc_Manager
{

	var $is_manager = false;
	var $page_title = '';
	var $list_type = '';
	var $list_name = '';
	var $menu_type = '';
	
	function __construct() {
		
		if( is_admin() ) {

			add_action( 'init' , array( $this , 'set_manager' ) , 20 );
			add_action( 'init' , array( $this , 'init' ) , 20 );
			
		}

	}

	function get_manager_user_role() {

		global $Plvc;

		$cap = false;

		$cap = $Plvc->Plugin['default_role']['child'];
		
		$other_data = $Plvc->ClassData->get_data_others();
		if( !empty( $other_data['capability'] ) )
			$cap = strip_tags( $other_data['capability'] );
			
		return $cap;

	}

	function set_manager() {
		
		$cap = $this->get_manager_user_role();
		if( current_user_can( $cap ) )
			$this->is_manager = true;
		
	}

	function init() {
		
		global $Plvc;

		if( $Plvc->Current['admin'] && $this->is_manager && !$Plvc->Current['ajax'] ) {
			
			$base_plugin = trailingslashit( $Plvc->Plugin['plugin_slug'] ) . $Plvc->Plugin['plugin_slug'] . '.php';
			
			add_filter( 'plugin_action_links_' . $base_plugin , array( $this , 'plugin_action_links' ) );
			add_action( 'admin_menu' , array( $this , 'admin_menu' ) );
			add_action( 'admin_notices' , array( $this , 'update_notice' ) );
				
			add_action( 'admin_print_scripts' , array( $this , 'admin_print_scripts' ) );
		
		}

		if( $Plvc->Current['admin'] && $this->is_manager && $Plvc->Current['ajax'] ) {
			
			add_action( 'wp_ajax_' . $Plvc->Plugin['ltd'] . '_add_list' , array( $this , 'ajax_add_list' ) );
			
		}
		
	}

	function plugin_action_links( $links ) {

		global $Plvc;
		
		$link_setting = sprintf( '<a href="%1$s">%2$s</a>' , $Plvc->Plugin['links']['setting'] , __( 'Settings' ) );
		$link_support = sprintf( '<a href="%1$s" target="_blank">%2$s</a>' , $Plvc->Plugin['links']['forum'] , __( 'Support Forums' ) );

		array_unshift( $links , $link_setting, $link_support );

		return $links;

	}

	function admin_menu() {
		
		global $Plvc;

		$cap = $this->get_manager_user_role();
		$main_slug = $Plvc->Plugin['page_slug'];

		add_menu_page( $Plvc->Plugin['name'] , $Plvc->Plugin['name'] , $cap , $main_slug , array( $this , 'views') );
		add_submenu_page( $main_slug , __( 'Posts' ) . __( 'List View' ) . __( 'Customize' ) , __( 'All Posts' ) , $cap , $Plvc->Plugin['record']['post'] , array( $this , 'views' ) );
		add_submenu_page( $main_slug , __( 'Pages' ) . __( 'List View' ) . __( 'Customize' ) , __( 'All Pages' ) , $cap , $Plvc->Plugin['record']['page'] , array( $this , 'views' ) );
		add_submenu_page( $main_slug , __( 'Media Library' ) . __( 'Customize' ) , __( 'Media Library' ) , $cap , $Plvc->Plugin['record']['media'] , array( $this , 'views' ) );
		add_submenu_page( $main_slug , __( 'Comments' ) . __( 'Customize' ) , __( 'Comments' ) , $cap , $Plvc->Plugin['record']['comments'] , array( $this , 'views' ) );
		add_submenu_page( $main_slug , __( 'Available Widgets' ) . __( 'Customize' ) , __( 'Available Widgets' ) , $cap , $Plvc->Plugin['record']['widgets'] , array( $this , 'views' ) );
		add_submenu_page( $main_slug , __( 'Menus' ) . ' '. __( 'show screen' , $Plvc->Plugin['ltd'] ) . ' '. __( 'Customize' ) , __( 'Menus' ) , $cap , $Plvc->Plugin['record']['menus'] , array( $this , 'views' ) );
		add_submenu_page( $main_slug , __( 'Menus' ) . ' '. __( 'show advanced properties screen' , $Plvc->Plugin['ltd'] ) . ' '. __( 'Customize' ) , __( 'Menus' ) . ' ' . __( 'advanced properties' , $Plvc->Plugin['ltd'] ) , $cap , $Plvc->Plugin['record']['menus_adv'] , array( $this , 'views' ) );
		add_submenu_page( $main_slug , __( 'Custom Post Type' , $Plvc->Plugin['ltd'] ) , __( 'Custom Post Type' , $Plvc->Plugin['ltd'] ) , $cap , $Plvc->Plugin['record']['custom_posts'] , array( $this , 'views' ) );
		add_submenu_page( $main_slug , __( 'Other Settings' , $Plvc->Plugin['ltd'] ) , __( 'Other Settings' , $Plvc->Plugin['ltd'] ) , $cap , $Plvc->Plugin['record']['other'] , array( $this , 'views' ) );

	}

	function is_settings_page() {
		
		global $plugin_page;
		global $Plvc;
		
		$is_settings_page = false;
		$setting_pages = array(
			$Plvc->Plugin['page_slug'],
			$Plvc->Plugin['record']['post'],
			$Plvc->Plugin['record']['page'],
			$Plvc->Plugin['record']['media'],
			$Plvc->Plugin['record']['comments'],
			$Plvc->Plugin['record']['widgets'],
			$Plvc->Plugin['record']['menus'],
			$Plvc->Plugin['record']['menus_adv'],
			$Plvc->Plugin['record']['custom_posts'],
			$Plvc->Plugin['record']['other']
		);
		
		if( in_array( $plugin_page , $setting_pages ) )
			$is_settings_page = true;
			
		return $is_settings_page;
		
	}

	function admin_print_scripts() {
		
		global $wp_version;
		global $Plvc;
		
		if( $this->is_settings_page() ) {
			
			$ReadedJs = array( 'jquery' , 'jquery-ui-sortable' );
			wp_enqueue_script( $Plvc->Plugin['page_slug'] ,  $Plvc->Plugin['url'] . $Plvc->Plugin['ltd'] . '.js', $ReadedJs , $Plvc->Ver );
			
			wp_enqueue_style( $Plvc->Plugin['page_slug'] , $Plvc->Plugin['url'] . $Plvc->Plugin['ltd'] . '.css', array() , $Plvc->Ver );
			if( version_compare( $wp_version , '3.8' , '<' ) )
				wp_enqueue_style( $Plvc->Plugin['page_slug'] . '-37' , $Plvc->Plugin['url'] . $Plvc->Plugin['ltd'] . '-3.7.css', array() , $Plvc->Ver );

		}
		
	}

	function views() {

		global $Plvc;
		global $plugin_page;

		if( $this->is_settings_page() ) {
			
			$this->page_title = $Plvc->Plugin['name'];

			$manage_page_path = $Plvc->Plugin['dir'] . trailingslashit( 'inc' );
			
			if( $plugin_page == $Plvc->Plugin['page_slug'] ) {
				
				include_once $manage_page_path . 'setting.php';
				
			} elseif( $plugin_page == $Plvc->Plugin['record']['post'] ) {
				
				$this->page_title = sprintf( __( '%2$s for %3$s %1$s' , $Plvc->Plugin['ltd'] ) , __( 'Customize' ) , __( 'List View' ) , __( 'Posts' ) );
				$this->list_type = 'post';
				$this->list_name = 'post';
				include_once $manage_page_path . 'setting_post.php';
				
			} elseif( $plugin_page == $Plvc->Plugin['record']['page'] ) {
				
				$this->page_title = sprintf( __( '%2$s for %3$s %1$s' , $Plvc->Plugin['ltd'] ) , __( 'Customize' ) , __( 'List View' ) , __( 'Pages' ) );
				$this->list_type = 'page';
				$this->list_name = 'page';
				include_once $manage_page_path . 'setting_post.php';
				
			} elseif( $plugin_page == $Plvc->Plugin['record']['custom_posts'] ) {
				
				$this->list_type = 'custom_posts';

				if( !empty( $_GET['custom_post_type'] ) )
					$this->list_name = strip_tags(  $_GET['custom_post_type'] );
					
				$custom_posts_types = $Plvc->ClassConfig->get_all_custom_posts();

				if( !isset( $_GET['custom_post_type'] ) ) {
					
					$this->page_title = sprintf( __( '%2$s for %3$s %1$s' , $Plvc->Plugin['ltd'] ) , __( 'Customize' ) , __( 'List View' ) , __( 'Custom Post Type' , $Plvc->Plugin['ltd'] ) );
					include_once $manage_page_path . 'setting_select_post.php';
					
				} elseif( !empty( $this->list_name ) && !empty( $custom_posts_types[$this->list_name] ) ) {
					
					$this->page_title = sprintf( __( '%2$s for %3$s %1$s' , $Plvc->Plugin['ltd'] ) , __( 'Customize' ) , __( 'List View' ) , esc_html( $custom_posts_types[$this->list_name]['name'] ) );
					include_once $manage_page_path . 'setting_post.php';
					
				}

			} elseif( $plugin_page == $Plvc->Plugin['record']['media'] ) {
				
				$this->page_title = sprintf( __( '%2$s for %3$s %1$s' , $Plvc->Plugin['ltd'] ) , __( 'Customize' ) , __( 'List View' ) , __( 'Media Library' ) );
				$this->list_type = 'media';
				$this->list_name = 'media';
				include_once $manage_page_path . 'setting_post.php';
				
			} elseif( $plugin_page == $Plvc->Plugin['record']['comments'] ) {
				
				$this->page_title = sprintf( __( '%2$s for %3$s %1$s' , $Plvc->Plugin['ltd'] ) , __( 'Customize' ) , __( 'List View' ) , __( 'Comments' ) );
				$this->list_type = 'comments';
				$this->list_name = 'comments';
				include_once $manage_page_path . 'setting_post.php';
				
			} elseif( $plugin_page == $Plvc->Plugin['record']['widgets'] ) {
				
				$this->page_title = sprintf( __( '%2$s for %3$s %1$s' , $Plvc->Plugin['ltd'] ) , __( 'Customize' ) , __( 'List View' ) , __( 'Available Widgets' ) );
				$this->menu_type = 'widgets';
				include_once $manage_page_path . 'setting_menu.php';
				
			} elseif( $plugin_page == $Plvc->Plugin['record']['menus'] ) {
				
				$this->page_title = sprintf( __( '%2$s for %3$s %1$s' , $Plvc->Plugin['ltd'] ) , __( 'Customize' ) , __( 'List View' ) , __( 'Menus' ) . ' ' . __( 'show screen' , $Plvc->Plugin['ltd'] ) );
				$this->menu_type = 'menus';
				include_once $manage_page_path . 'setting_menu.php';
				
			} elseif( $plugin_page == $Plvc->Plugin['record']['menus_adv'] ) {
				
				$this->page_title =__( 'Menus of advanced feature adapted to the screen' , $Plvc->Plugin['ltd'] );
				$this->menu_type = 'menus_adv';

				include_once $manage_page_path . 'setting_menu.php';
				
			} elseif( $plugin_page == $Plvc->Plugin['record']['other'] ) {
				
				$this->page_title =__( 'Other Settings' , $Plvc->Plugin['ltd'] );

				include_once $manage_page_path . 'setting_other.php';
				
			}
			
			add_filter( 'admin_footer_text' , array( $Plvc->ClassInfo , 'admin_footer_text' ) );
			
		}
		
	}
	
	function get_action_link() {
		
		global $Plvc;
		
		$url = remove_query_arg( array( $Plvc->Plugin['msg_notice'] , 'donated' ) );
		
		return $url;

	}
	
	function update_notice() {
		
		global $Plvc;

		if( $this->is_settings_page() ) {
			
			if( !empty( $_GET ) && !empty( $_GET[$Plvc->Plugin['msg_notice']] ) ) {
				
				$update_nag = $_GET[$Plvc->Plugin['msg_notice']];
				
				if( $update_nag == 'update' or $update_nag == 'delete' ) {

					printf( '<div class="updated"><p><strong>%s</strong></p></div>' , __( 'Settings saved.' ) );

				}
				
			}
			
		}
		
	}

	function get_apply_roles_html() {
		
		global $Plvc;
		
		$apply_user_roles = $Plvc->ClassData->get_data_user_role();
		if( !empty( $apply_user_roles['UPFN'] ) )
			unset( $apply_user_roles['UPFN'] );

		$Contents =  __( 'Apply user roles: ' , $Plvc->Plugin['ltd'] );

		if( !empty( $apply_user_roles ) ) {
			
			$all_user_roles = $Plvc->ClassConfig->get_all_user_roles();

			foreach( $apply_user_roles as $user_role => $v ) {
				
				if( !empty( $all_user_roles[$user_role] ) && !empty( $all_user_roles[$user_role]['label'] ) )
					$Contents .= sprintf( '[ %s ]' , $all_user_roles[$user_role]['label'] );
				
			}

		} else {
			
			$text = __( 'Authority to apply the setting is not selected. <a href="%s">From here</a>, please select the permissions you want to set.' , $Plvc->Plugin['ltd'] );
			$text = sprintf( $text , admin_url( 'admin.php?page=' . $Plvc->Plugin['page_slug'] ) );
			$Contents .= sprintf( '<span style="color: #c00">%s</span>' , $text );

		}
		
		$Contents = apply_filters( 'plvc_get_apply_roles' , $Contents );

		return $Contents;
		
	}
	
	function load_list() {

		global $wp_version;
		global $Plvc;

		$custom_posts_types = $Plvc->ClassConfig->get_all_custom_posts();
		$load_list = array( 'link' => self_admin_url( 'edit.php' ) , 'label' => __( 'Posts' ) );
		
		if( $this->list_type == 'page' ) {
			
			$load_list = array( 'link' => self_admin_url( 'edit.php?post_type=page' ) , 'label' => __( 'Pages' ) );
			
		} elseif( $this->list_type == 'media' ) {
			
			$load_list = array( 'link' => self_admin_url( 'upload.php?mode=list' ) , 'label' => __( 'Media Library' ) );

			if( version_compare( $wp_version , '4.0' , '<' ) )
				$load_list['link'] = self_admin_url( 'upload.php' );
			
			
		} elseif( $this->list_type == 'comments' ) {
			
			$load_list = array( 'link' => self_admin_url( 'edit-comments.php' ) , 'label' => __( 'Comments' ) );
			
		} elseif( $this->list_type == 'custom_posts' ) {
			
			$load_list = array( 'link' => self_admin_url( 'edit.php?post_type=' . $this->list_name ) , 'label' => $custom_posts_types[$this->list_name]['name'] );
			
		}
		
		return $load_list;
		
	}
	
	function ajax_add_list() {
		
		global $Plvc;

		if( empty( $_POST['column_id'] ) or empty( $_POST['list_name'] ) or empty( $_POST['list_type'] ) )
			return false;

		$column_id = strip_tags( $_POST['column_id'] );
		$list_type = strip_tags( $_POST['list_type'] );
		$list_name = strip_tags( $_POST['list_name'] );

		$Columns = $Plvc->ClassConfig->get_registed_columns( $list_name );
		
		$add_column = array();

		foreach( $Columns as $use_type => $column ) {

			if( array_key_exists( $column_id , $column ) )
				$add_column = $Columns[$use_type][$column_id];

		}

		$Plvc->setting_list_post( $_POST['list_type'] , $_POST['list_name'] , 'use' , $_POST['column_id'] , $add_column );

		die();
		
	}

	function get_data_menus( $menu_type ) {
		
		global $Plvc;
		
		$Menus = array();

		if( $menu_type == 'widgets' ) {
			
			$Menus = $Plvc->ClassConfig->get_registed_widgets();
			$Data = $Plvc->ClassData->get_data_widgets();

		} elseif( $menu_type == 'menus' ) {
			
			$Menus = $Plvc->ClassConfig->get_registed_menu_items();
			$Data = $Plvc->ClassData->get_data_menus();

		} elseif( $menu_type == 'menus_adv' ) {
			
			$Menus = $Plvc->ClassConfig->get_registed_menu_advance();
			$Data = $Plvc->ClassData->get_data_menus_adv();
			
		}
		
		if( !empty( $Data ) && !empty( $Data['not_use'] ) ) {
			
			foreach( $Data['not_use'] as $menu_id => $menu ) {
				
				if( !empty( $Menus['use'][$menu_id] ) ) {
					
					$Menus['not_use'][$menu_id] = $Menus['use'][$menu_id];
					unset( $Menus['use'][$menu_id] );

				}
				
			}
			
		}
		
		return $Menus;

	}

	function get_data_lists( $list_type ) {
		
		global $Plvc;
		
		$Columns = $Plvc->ClassConfig->get_registed_columns( $list_type );
		
		if( !empty( $Columns ) ) {
			
			if( $list_type == 'post' ) {
				
				$Data = $Plvc->ClassData->get_data_post();
			
			} elseif( $list_type == 'page' ) {
				
				$Data = $Plvc->ClassData->get_data_page();
			
			} elseif( $list_type == 'media' ) {
				
				$Data = $Plvc->ClassData->get_data_media();
			
			} elseif( $list_type == 'comments' ) {
				
				$Data = $Plvc->ClassData->get_data_comments();
			
			} else {

				$Data = $Plvc->ClassData->get_data_custom_post( $list_type );
				
			}
			
			if( !empty( $Data ) ) {

				foreach( $Columns['use'] as $column_id => $column ) {
						
					$Columns['not_use'][$column_id] = $column;
					unset( $Columns['use'][$column_id] );

				}
				
				if( !empty( $Data['use'] ) ) {
					
					foreach( $Data['use'] as $column_id => $column ) {
						
						if( !isset( $column['sort'] ) ) {
							
							$column['sort'] = $Columns['not_use'][$column_id]['sort'];
							
						}
						
						if( !empty( $Columns['not_use'][$column_id] ) ) {
							
							$Columns['use'][$column_id]['name'] = $column['name'];
							$Columns['use'][$column_id]['sort'] = $column['sort'];
							$Columns['use'][$column_id]['orderby'] = $Columns['not_use'][$column_id]['orderby'];
							$Columns['use'][$column_id]['group'] = $Columns['not_use'][$column_id]['group'];
							$Columns['use'][$column_id]['default_name'] = $Columns['not_use'][$column_id]['default_name'];
							unset( $Columns['not_use'][$column_id] );

						}
						
					}

				}
				
			}
			
		}
		
		return $Columns;

	}

}

endif;
