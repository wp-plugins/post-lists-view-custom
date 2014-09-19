<?php

if ( !class_exists( 'Plvc_Config' ) ) :

class Plvc_Config
{

	function __construct() {
		
		add_action( 'plugins_loaded' , array( $this , 'setup_config' ) );
		add_action( 'plugins_loaded' , array( $this , 'setup_record' ) );
		add_action( 'init' , array( $this , 'setup_site_env' ) );
		add_action( 'init' , array( $this , 'setup_current_env' ) );
		add_action( 'init' , array( $this , 'setup_current_user' ) );
		add_action( 'init' , array( $this , 'setup_links' ) );
		add_action( 'init' , array( $this , 'setup_third_party' ) );
		
	}

	function setup_config() {
		
		global $Plvc;
		
		$Plvc->Plugin['plugin_slug']      = 'post-lists-view-custom';
		$Plvc->Plugin['dir']              = trailingslashit( dirname( dirname( __FILE__ ) ) );
		$Plvc->Plugin['name']             = 'Post Lists View Custom';
		$Plvc->Plugin['page_slug']        = str_replace( '-' , '_' , $Plvc->Plugin['plugin_slug'] );
		$Plvc->Plugin['url']              = plugin_dir_url( dirname( __FILE__ ) );
		$Plvc->Plugin['ltd']              = 'plvc';
		$Plvc->Plugin['nonces']           = array( 'field' => $Plvc->Plugin['ltd'] . '_field' , 'value' => $Plvc->Plugin['ltd'] . '_value' );
		$Plvc->Plugin['form']             = array( 'field' => $Plvc->Plugin['ltd'] . '_settings' );
		$Plvc->Plugin['UPFN']             = 'Y';
		$Plvc->Plugin['msg_notice']       = $Plvc->Plugin['ltd'] . '_msg';
		$Plvc->Plugin['default_role']     = array( 'child' => 'manage_options' , 'network' => 'manage_network' );
		
		$Plvc->Plugin['dir_admin_assets'] = $Plvc->Plugin['url'] . trailingslashit( 'admin' ) . trailingslashit( 'assets' );

	}

	function setup_record() {
		
		global $Plvc;
		
		$Plvc->Plugin['record']['user_role']    = $Plvc->Plugin['ltd'] . '_user_role';
		$Plvc->Plugin['record']['post']         = $Plvc->Plugin['ltd'] . '_post';
		$Plvc->Plugin['record']['page']         = $Plvc->Plugin['ltd'] . '_page';
		$Plvc->Plugin['record']['media']        = $Plvc->Plugin['ltd'] . '_media';
		$Plvc->Plugin['record']['comments']     = $Plvc->Plugin['ltd'] . '_comments';
		$Plvc->Plugin['record']['widgets']      = $Plvc->Plugin['ltd'] . '_widgets';
		$Plvc->Plugin['record']['menus']        = $Plvc->Plugin['ltd'] . '_menus';
		$Plvc->Plugin['record']['menus_adv']    = $Plvc->Plugin['ltd'] . '_menus_adv';
		$Plvc->Plugin['record']['custom_posts'] = $Plvc->Plugin['ltd'] . '_custom_posts';
		$Plvc->Plugin['record']['other']        = $Plvc->Plugin['ltd'] . '_other';
		$Plvc->Plugin['record']['regist_columns'] = $Plvc->Plugin['ltd'] . '_regist_columns';
		$Plvc->Plugin['record']['regist_sortable_columns'] = $Plvc->Plugin['ltd'] . '_regist_sortable_columns';

	}
	
	function setup_site_env() {
		
		global $Plvc;

		$Plvc->Current['multisite'] = is_multisite();
		$Plvc->Current['blog_id'] = get_current_blog_id();

		$Plvc->Current['main_blog'] = false;

		if( $Plvc->Current['blog_id'] == 1 ) {

			$Plvc->Current['main_blog'] = true;

		}
		
	}

	function setup_current_env() {
		
		global $Plvc;
		
		$Plvc->Current['admin']         = is_admin();
		$Plvc->Current['network_admin'] = is_network_admin();
		$Plvc->Current['ajax']          = false;

		if( defined( 'DOING_AJAX' ) )
			$Plvc->Current['ajax'] = true;
			
		$Plvc->Current['schema'] = is_ssl() ? 'https://' : 'http://';

	}
	
	function setup_current_user() {
		
		global $Plvc;
		
		$Plvc->Current['user_login'] = is_user_logged_in();
		$Plvc->Current['user_role']  = false;

		$User = wp_get_current_user();
		
		if( !empty( $User->roles ) ) {

			foreach( $User->roles as $role ) {

				$Plvc->Current['user_role'] = $role;
				break;

			}

		}

		$Plvc->Current['superadmin'] = false;

		if( $Plvc->Current['multisite'] )
			$Plvc->Current['superadmin'] = is_super_admin();

	}

	function setup_links() {
		
		global $Plvc;
		
		$Plvc->Plugin['links']['author'] = 'http://gqevu6bsiz.chicappa.jp/';
		$Plvc->Plugin['links']['forum'] = 'http://wordpress.org/support/plugin/' . $Plvc->Plugin['plugin_slug'];
		$Plvc->Plugin['links']['review'] = 'http://wordpress.org/support/view/plugin-reviews/' . $Plvc->Plugin['plugin_slug'];
		$Plvc->Plugin['links']['profile'] = 'http://profiles.wordpress.org/gqevu6bsiz';
		
		if( $Plvc->Current['multisite'] ) {

			$Plvc->Plugin['links']['setting'] = admin_url( 'admin.php?page=' . $Plvc->Plugin['page_slug'] );

		} else {

			$Plvc->Plugin['links']['setting'] = admin_url( 'admin.php?page=' . $Plvc->Plugin['page_slug'] );

		}
		
	}

	function setup_third_party() {
		
		global $Plvc;

		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		
		$check_plugins = array( 'mp6' => 'mp6/mp6.php' , 'afc' => 'advanced-custom-fields/acf.php' );
		
		if( !empty( $check_plugins ) ) {
			foreach( $check_plugins as $name => $base_name ) {
				if( is_plugin_active( $base_name ) )
					$Plvc->ThirdParty[$name] = true;
			}
		}
		
	}

	function get_all_user_roles() {

		global $Plvc;
		global $wp_roles;

		$UserRole = array();
		$all_user_roles = $wp_roles->roles;

		foreach ( $all_user_roles as $role => $user ) {

			$user['label'] = translate_user_role( $user['name'] );
			$UserRole[$role] = $user;

		}
		
		if( !empty( $Plvc->Current['multisite'] ) && !empty( $Plvc->Current['network_admin'] ) && !empty( $Plvc->Current['superadmin'] ) ) {
			
			$add_caps = array( 'manage_network' , 'manage_network_users' , 'manage_network_themes' , 'manage_network_plugins' , 'manage_network_options' );

			foreach( $add_caps as $cap ) {

				$UserRole[$Plvc->Current['user_role']]['capabilities'][$cap] = 1;

			}
			
		}

		return $UserRole;

	}
	
	function get_default_thumbnail_size() {
		return 80;
	}
	
	function get_all_custom_fields() {
		
		global $wpdb;
		
		$custom_fields = array();
		$all_custom_fields = $wpdb->get_col( "SELECT meta_key FROM $wpdb->postmeta GROUP BY meta_key HAVING meta_key NOT LIKE '\_%' ORDER BY meta_key" );

		if( !empty( $all_custom_fields ) ) {

			natcasesort( $all_custom_fields );
			
			$remove_fields = array( 'allorany' , 'hide_on_screen' );
			foreach( $remove_fields as $field_name ) {
				
				$searched = array_search( $field_name , $all_custom_fields );
				if( $searched !== false ) {
					
					unset( $all_custom_fields[$searched] );
					
				}

			}
			
			if( !empty( $all_custom_fields ) ) {
				
				$remove_fields = array( 'field_' );
				foreach( $remove_fields as $field_name ) {
					
					foreach( $all_custom_fields as $key => $val ) {
						
						preg_match( '/^' . $field_name . '/' , $val , $match );
						if( !empty( $match ) )
							unset( $all_custom_fields[$key] );
						
					}
					
				}
				
			}
			
			if( !empty( $all_custom_fields ) )
				$custom_fields = $all_custom_fields;

		}
		
		return $custom_fields;

	}
	
	function get_all_custom_posts( $in_nav = false ) {
		
		$post_types = array();
		$custom_posts = get_post_types( array( 'public' => true, '_builtin' => false ) , 'objects' );
		
		if( !empty( $custom_posts ) ) {
			
			foreach( $custom_posts as $post_name => $post_type_obj ) {
				
				if( !empty( $in_nav ) ) {
					
					if( !empty( $post_type_obj->show_in_nav_menus ) )
						$post_types[$post_name] = array( 'name' => $post_type_obj->labels->name );
					
				} else {
					
					$post_types[$post_name] = array( 'name' => $post_type_obj->labels->name );
					
				}
				
			}
			
		}
		
		return $post_types;

	}
	
	function get_all_custom_taxonomies( $in_nav = false ) {
		
		$taxonomies = array();
		$custom_taxonomies = get_taxonomies( array( 'public' => true, '_builtin' => false ), 'object' );

		if( !empty( $custom_taxonomies ) ) {
			
			foreach( $custom_taxonomies as $tax_name => $tax_obj ) {

				if( !empty( $in_nav ) ) {
					
					if( !empty( $tax_obj->show_in_nav_menus ) )
						$taxonomies[$tax_name] = array( 'name' => $tax_obj->labels->name );
					
				} else {
					
					$taxonomies[$tax_name] = array( 'name' => $tax_obj->labels->name );
					
				}

			}

		}
		
		return $taxonomies;
		
	}
	
	function get_core_posts_columns() {
		
		$columns = array(
			'title'      => array( 'sort' => true  , 'orderby' => 'title' , 'label' => __( 'Title' ) ),
			'author'     => array( 'sort' => false , 'orderby' => 'author' , 'label' => __( 'Author' ) ),
			'categories' => array( 'sort' => false , 'orderby' => '' , 'label' => __( 'Categories' ) ),
			'tags'       => array( 'sort' => false , 'orderby' => '' , 'label' => __( 'Tags' ) ),
			'comments'   => array( 'sort' => true  , 'orderby' => 'comment_count' , 'label' => '<span class="vers"><div title="' . esc_attr__( 'Comments' ) . '" class="comment-grey-bubble"></div></span>' ),
			'date'       => array( 'sort' => false , 'orderby' => 'date' , 'label' => __( 'Date' ) ),
			'slug'       => array( 'sort' => false , 'orderby' => 'name' , 'label' => __( 'Slug' ) ),
			'excerpt'    => array( 'sort' => false , 'orderby' => 'post_excerpt' , 'label' => __( 'Excerpt' ) ),
			'id'         => array( 'sort' => false , 'orderby' => 'ID' , 'label' => __( 'ID' ) ),
		);
		
		if( current_theme_supports( 'post-thumbnails' ) )
			$columns['post-thumbnails'] = array( 'sort' => false , 'orderby' => 'post-thumbnails' , 'label' => __( 'Featured Image' ) );

		if( current_theme_supports( 'post-formats' ) )
			$columns['post-formats'] = array( 'sort' => false , 'orderby' => '' , 'label' => _x( 'Format' , 'post format' ) );
		
		return $columns;

	}
	
	function get_core_media_columns() {
		
		$columns = array(
			'icon'         => array( 'sort' => false , 'orderby' => '' , 'label' => '' ),
			'title'        => array( 'sort' => true , 'orderby' => 'title' , 'label' => _x( 'File' , 'column name' ) ),
			'author'       => array( 'sort' => true , 'orderby' => 'author' , 'label' => __( 'Author' ) ),
			'parent'       => array( 'sort' => true , 'orderby' => 'parent' , 'label' => _x( 'Uploaded to' , 'column name' ) ),
			'comments'     => array( 'sort' => true , 'orderby' => 'comment_count' , 'label' => '<span class="vers"><div title="' . esc_attr__( 'Comments' ) . '" class="comment-grey-bubble"></div></span>' ),
			'date'         => array( 'sort' => true , 'orderby' => 'date' , 'label' => _x( 'Date' , 'column name' ) ),
			'media_title'  => array( 'sort' => false , 'orderby' => 'title' , 'label' => __( 'Title' ) ),
			'image_alt'    => array( 'sort' => false , 'orderby' => 'image_alt' , 'label' => __( 'Alternative Text' ) ),
			'post_excerpt' => array( 'sort' => false , 'orderby' => 'post_excerpt' , 'label' => __( 'Caption' ) ),
			'post_content' => array( 'sort' => false , 'orderby' => '' , 'label' => __( 'Details' ) ),
			'id'           => array( 'sort' => false , 'orderby' => 'ID' , 'label' => __( 'ID' ) ),
		);
		
		return $columns;

	}
	
	function get_core_comments_columns() {
		
		$columns = array(
			'author'                  => array( 'sort' => true , 'orderby' => 'comment_author' , 'label' => __( 'Author' ) ),
			'comment'                 => array( 'sort' => true , 'orderby' => '' , 'label' => __( 'Comments' ) ),
			'response'                => array( 'sort' => true , 'orderby' => 'comment_post_ID' , 'label' => _x( 'In Response To' , 'column name' ) ),
			'newcomment_author'       => array( 'sort' => false , 'orderby' => 'comment_author' , 'label' => __( 'Name' ) ),
			'newcomment_author_email' => array( 'sort' => false , 'orderby' => 'comment_author_email' , 'label' => __( 'E-mail' ) ),
			'newcomment_author_url'   => array( 'sort' => false , 'orderby' => 'comment_author_url' , 'label' => __( 'URL' ) ),
			'id'                      => array( 'sort' => false , 'orderby' => 'comment_ID' , 'label' => __( 'ID' ) ),
		);
		
		return $columns;

	}
	
	function get_core_widgets() {

		$widgets = array(
			'pages-1' => __( 'Pages' ),
			'calendar-1' => __( 'Calendar' ),
			'archives-2' => __( 'Archives' ),
			'meta-2' => __( 'Meta' ),
			'search-1' => __( 'Search' ),
			'text-1' => __( 'Text' ),
			'categories-2' => __( 'Categories' ),
			'recent-posts-2' => __( 'Recent Posts' ),
			'recent-comments-2' => __( 'Recent Comments' ),
			'rss-1' => __( 'RSS' ),
			'tag_cloud-1' => __( 'Tag Cloud' ),
			'nav_menu-1' => __( 'Custom Menu' ),
		);		

		return $widgets;

	}

	function get_core_menu_items() {
		
		$menu_items = array(
			'add-custom-links' => __( 'Links' ),
			'add-page' => __( 'Pages' ),
			'add-category' => __( 'Categories' ),
			'add-post_format' => _x( 'Format' , 'post format' ),
			'add-post' => __( 'Posts' ),
			'add-post_tag' => __( 'Tags' ),
		);
		
		return $menu_items;

	}

	function get_core_menu_advance() {
		
		$menu_advance = array(
			'link-target' => __( 'Link Target' ),
			'css-classes' => __( 'CSS Classes' ),
			'xfn' => __( 'Link Relationship (XFN)' ),
			'description' => __( 'Description' ),
		);

		return $menu_advance;

	}
	
	function get_registed_columns( $list_type ) {
		
		global $Plvc;
		
		$columns = array();
		$registed_columns = $Plvc->ClassData->get_registed_columns( $list_type );
		$registed_sortable_columns = $Plvc->ClassData->get_registed_sortable_columns( $list_type );
		
		if( empty( $registed_columns ) )
			return false;

		if( !empty( $registed_columns['cb'] ) )
			unset( $registed_columns['cb'] );
			
		if( !empty( $registed_columns ) ) {
			
			if( !empty( $registed_columns ) ) {
				
				foreach( $registed_columns as $column_id => $column_name ) {
					
					$orderby = false;
					$sort = false;

					if( !empty( $registed_sortable_columns[$column_id] ) ) {
						
						$sort = true;

						if( is_array( $registed_sortable_columns[$column_id] ) ) {
							
							$orderby = $column_id;

						} else {
							
							$orderby = $registed_sortable_columns[$column_id];
							
						}

					}
					$columns['use'][$column_id] = array( 'name' => $column_name , 'group' => 'plugin' , 'sort' => $sort , 'orderby' => $orderby );
	
				}
				
			}

			if( $list_type == 'media' ) {

				$core_columns = $this->get_core_media_columns();

			} elseif( $list_type == 'comments' ) {

				$core_columns = $this->get_core_comments_columns();

			} else {

				$core_columns = $this->get_core_posts_columns();

				if( $list_type == 'page' ) {

					unset( $core_columns['categories'] );
					unset( $core_columns['tags'] );

				}

			}
			
			foreach( $core_columns as $column_id => $column ) {
				
				if( array_key_exists( $column_id , $registed_columns ) ) {

					if( !empty( $columns['use'][$column_id] ) ) {
						
						$columns['use'][$column_id]['group'] = false;
						$columns['use'][$column_id]['sort'] = $column['sort'];
						$columns['use'][$column_id]['orderby'] = $column['orderby'];

					} else {

						$columns['use'][$column_id] = array( 'name' => $column['label'] , 'group' => '' , 'sort' => $column['sort'] , 'orderby' => $column['orderby'] );

					}
					unset( $registed_columns[$column_id] );

				} else {

					$columns['not_use'][$column_id] = array( 'name' => $column['label'] , 'group' => '' , 'sort' => $column['sort'] , 'orderby' => $column['orderby'] );
					unset( $registed_columns[$column_id] );

				}
				
			}
			
			$custom_fields = $this->get_all_custom_fields();
	
			if( !empty( $custom_fields ) && !in_array( $list_type , array( 'media' , 'comments' ) ) ) {
						
				foreach( $custom_fields as $field_name ) {
					
					$columns['not_use'][$field_name] = array( 'name' => $field_name , 'group' => 'custom_field' , 'sort' => true , 'orderby' => $field_name );
						
				}
						
			}

		}
		
		if( !empty( $columns ) ) {

			if( !empty( $registed_sortable_columns ) ) {
				
				foreach( $registed_sortable_columns as $sortable_id => $sortable ) {
					
					foreach( $columns as $use_type => $column ) {
						
						foreach( $column as $column_id => $column_setting ) {
							
							if( $column_id == $sortable_id ) {
								
								$columns[$use_type][$column_id]['sort'] = true;
								
							}
							
						}
						
					}
					
				}
				
			}
		
			foreach( $columns as $use_type => $column ) {

				foreach( $column as $column_id => $column_setting ) {

					$columns[$use_type][$column_id]['default_name'] = $column_setting['name'];

				}

			}

		}
		
		return $columns;

	}

	function get_registed_widgets() {
		
		global $wp_registered_widgets;
		
		$menus = array();
		
		if( !empty( $wp_registered_widgets ) ) {
			
			$core_widgets = $this->get_core_widgets();
			
			$core_widgets_pattern_ids = array();
			foreach( $core_widgets as $widget_id => $widget_name ) {
				
				preg_match( '/(.*)-[0-9]/' , $widget_id , $match );
				if( !empty( $match[1] ) )
					$core_widgets_pattern_ids[] = $match[1];
				
			}
			
			$done = array();

			foreach( $wp_registered_widgets as $widget_id => $widget ) {
				
				if ( in_array( $widget['callback'], $done, true ) )
					continue;

				$done[] = $widget['callback'];

				$menus['use'][$widget_id] = array( 'name' => $widget['name'] , 'group' => '' );

				$core_pattern_check = false;
				foreach( $core_widgets_pattern_ids as $core_pattern_id ) {
					
					if( preg_match( '/^' . $core_pattern_id . '-[0-9]/' , $widget_id ) ) {
						$core_pattern_check = true;
						break;
					}
					
				}
				
				if( !$core_pattern_check )
					$menus['use'][$widget_id]['group'] = 'plugin';

			}
			
		}
		
		return $menus;

	}
	
	function get_registed_menu_items() {
		
		$menus = array();
		$core_menu_items = $this->get_core_menu_items();

		foreach( $core_menu_items as $menu_id => $menu_name ) {
			
			$menus['use'][$menu_id] = array( 'name' => $menu_name , 'group' => '' );

		}
		
		$custom_posts = $this->get_all_custom_posts( true );

		if( !empty( $custom_posts ) ) {
			
			foreach( $custom_posts as $post_name => $custom_post ) {
				
				$menu_id = 'add-' . $post_name;
				$menus['use'][$menu_id] = array( 'name' => $custom_post['name'] , 'group' => 'custom_post' );
				
			}
			
		}

		$custom_taxonomies = $this->get_all_custom_taxonomies( true );

		if( !empty( $custom_taxonomies ) ) {
			
			foreach( $custom_taxonomies as $tax_name => $taxonomy ) {
				
				$menu_id = 'add-' . $tax_name;
				$menus['use'][$menu_id] = array( 'name' => $taxonomy['name'] , 'group' => 'custom_taxonomy' );
				
			}
			
		}
		
		return $menus;
		
	}

	function get_registed_menu_advance() {
		
		$menus = array();
		$core_menu_items = $this->get_core_menu_advance();

		foreach( $core_menu_items as $menu_id => $menu_name ) {
			
			$menus['use'][$menu_id] = array( 'name' => $menu_name , 'group' => '' );

		}
		
		return $menus;
		
	}

	function get_list_table_cell_auto() {
		
		global $Plvc;

		$types = array(
			__( 'Automatic' , $Plvc->Plugin['ltd'] ),
			__( 'Not automatic' , $Plvc->Plugin['ltd'] )
		);
		
		return $types;
		
	}

}

endif;
