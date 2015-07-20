<?php

if ( !class_exists( 'Plvc_Data' ) ) :

class Plvc_Data
{

	function __construct() {
		
		if( is_admin() )
			add_action( 'wp_loaded' , array( $this , 'init' ) , 20 );

	}

	function init() {
		
		global $Plvc;
		
		if( !$Plvc->Current['ajax'] ) {
			add_action( 'admin_init' , array( $this , 'dataUpdate' ) );
		}
	}

	private function get_record( $record ) {
		
		global $Plvc;
		
		$Data = array();
		
		if( $Plvc->Current['multisite'] ) {
			
			$GetData = get_option( $record );

		} else {

			$GetData = get_option( $record );

		}
		
		if( !empty( $GetData ) )
			$Data = $GetData;
		
		return $Data;

	}

	private function get_filt_record( $record , $filter = false ) {
		
		global $Plvc;
		
		$Data = $this->get_record( $record );
		
		if( $filter ) {
			
			$Data = apply_filters( 'plvc_pre_get_filt_data' , $Data , $record );

		} else {
			
			$Data = apply_filters( 'plvc_pre_get_data' , $Data , $record );

		}
		
		if( empty( $Data ) )
			$Data = array();

		return $Data;

	}

	function get_data_user_role() {
		
		global $Plvc;
		
		$Data = $this->get_record( $Plvc->Plugin['record']['user_role'] , false );
		
		return $Data;

	}

	function get_data_post( $filter = false ) {
		
		global $Plvc;
		
		$Data = $this->get_filt_record( $Plvc->Plugin['record']['post'] , $filter );
		
		return $Data;

	}

	function get_data_page( $filter = false ) {
		
		global $Plvc;
		
		$Data = $this->get_filt_record( $Plvc->Plugin['record']['page'] , $filter );
		
		return $Data;

	}

	function get_data_media( $filter = false ) {
		
		global $Plvc;
		
		$Data = $this->get_filt_record( $Plvc->Plugin['record']['media'] , $filter );
		
		return $Data;

	}

	function get_data_comments( $filter = false ) {
		
		global $Plvc;
		
		$Data = $this->get_filt_record( $Plvc->Plugin['record']['comments'] , $filter );
		
		return $Data;

	}

	function get_data_widgets( $filter = false ) {
		
		global $Plvc;
		
		$Data = $this->get_filt_record( $Plvc->Plugin['record']['widgets'] , $filter );
		
		return $Data;

	}

	function get_data_menus( $filter = false ) {
		
		global $Plvc;
		
		$Data = $this->get_filt_record( $Plvc->Plugin['record']['menus'] , $filter );
		
		return $Data;

	}

	function get_data_menus_adv( $filter = false ) {
		
		global $Plvc;
		
		$Data = $this->get_filt_record( $Plvc->Plugin['record']['menus_adv'] , $filter );
		
		return $Data;

	}

	function get_data_custom_post( $post_name = false ,  $filter = false  ) {
		
		global $Plvc;
		
		$Data = array();
		$CustomPosts = $this->get_filt_record( $Plvc->Plugin['record']['custom_posts'] , $filter );
		
		if( !empty( $post_name ) && !empty( $CustomPosts[$post_name] ) )
			$Data = $CustomPosts[$post_name];

		return $Data;

	}

	function get_data_users( $filter = false ) {
		
		global $Plvc;
		
		$Data = $this->get_filt_record( $Plvc->Plugin['record']['users'] , $filter );
		
		return $Data;

	}

	function get_registed_columns( $post_name = false ) {
		
		global $Plvc;

		$registed_columns = array();
		$GetData = $this->get_record( $Plvc->Plugin['record']['regist_columns'] );
		
		if( !empty( $GetData ) ) {
			
			if( !empty( $post_name ) ) {
				
				if( !empty( $GetData[$post_name] ) )
					$registed_columns = $GetData[$post_name];
				
			} else {
				
				$registed_columns = $GetData;
				
			}
			
		}
		
		return $registed_columns;
		
	}

	function get_registed_sortable_columns( $post_name = false ) {
		
		global $Plvc;
		
		$registed_sortable_columns = array();
		$GetData = $this->get_record( $Plvc->Plugin['record']['regist_sortable_columns'] );

		if( !empty( $GetData ) ) {
			
			if( !empty( $post_name ) ) {
				
				if( !empty( $GetData[$post_name] ) )
					$registed_sortable_columns = $GetData[$post_name];
				
			} else {
				
				$registed_sortable_columns = $post_name;
				
			}
			
		}
		
		return $registed_sortable_columns;
		
	}

	function get_data_others() {
		
		global $Plvc;
		
		$Data = $this->get_record( $Plvc->Plugin['record']['other'] );
		
		return $Data;

	}





	function dataUpdate() {
		
		global $Plvc;
		
		$RecordField = false;

		if( !empty( $_POST ) && !empty( $Plvc->ClassManager->is_manager ) && !empty( $_POST[$Plvc->Plugin['form']['field']] ) && $_POST[$Plvc->Plugin['form']['field']] == $Plvc->Plugin['UPFN']  ) {
			
			if( !empty( $_POST['record_field'] ) ) {
				
				$RecordField = strip_tags( $_POST['record_field'] );
				
				if( !empty( $_POST[$Plvc->Plugin['nonces']['field']] ) && check_admin_referer( $Plvc->Plugin['nonces']['value'] , $Plvc->Plugin['nonces']['field'] ) ) {
					
					if( !empty( $_POST['reset'] ) ) {
						
						if( !empty( $_POST['list_type'] ) && $_POST['list_type'] == 'custom_posts' ) {
							
							$this->remove_custom_posts();
	
						} else {
							
							$this->remove_record( $RecordField );
	
						}
	
						
					} elseif( $RecordField == $Plvc->Plugin['record']['user_role'] ) {
						
						$this->update_user_role();
						
					} elseif( $RecordField == $Plvc->Plugin['record']['post'] ) {
						
						$this->update_post();
						
					} elseif( $RecordField == $Plvc->Plugin['record']['page'] ) {
						
						$this->update_page();
						
					} elseif( $RecordField == $Plvc->Plugin['record']['media'] ) {
						
						$this->update_media();
						
					} elseif( $RecordField == $Plvc->Plugin['record']['comments'] ) {
						
						$this->update_comments();
						
					} elseif( $RecordField == $Plvc->Plugin['record']['widgets'] ) {
						
						$this->update_widgets();
						
					} elseif( $RecordField == $Plvc->Plugin['record']['menus'] ) {
						
						$this->update_menus();
						
					} elseif( $RecordField == $Plvc->Plugin['record']['menus_adv'] ) {
						
						$this->update_menu_advance();
						
					} elseif( $RecordField == $Plvc->Plugin['record']['custom_posts'] ) {
						
						$this->update_custom_post();
						
					} elseif( $RecordField == $Plvc->Plugin['record']['users'] ) {
						
						$this->update_users();
						
					} elseif( $RecordField == $Plvc->Plugin['record']['other'] ) {
						
						$this->update_other();
						
					}

				}

			}

		}

	}

	private function update_format() {

		global $Plvc;

		$Update = array( 'UPFN' => 1 );

		return $Update;

	}

	private function update_format_list( $PostData ) {
		
		global $Plvc;

		$use_data = array();

		if( !empty( $PostData['use'] ) )
			$use_data = $PostData['use'];
			
		$Update = $this->update_format();
		$Update['use'] = array();
		
		if( !empty( $use_data ) ) {
			
			foreach( $use_data as $menu_id => $menu_settings ) {
				
				$menu_id = strip_tags( $menu_id );
				$menu_name = stripslashes( $menu_settings['name'] );
				$Update['use'][$menu_id]['name'] = $menu_name;
				
				if( !empty( $menu_settings['sort'] ) ) {

					$Update['use'][$menu_id]['sort'] = true;
					
				} else {

					$Update['use'][$menu_id]['sort'] = false;
					
				}
				
			}

		}

		return $Update;

	}

	private function update_format_menu( $PostData ) {
		
		global $Plvc;

		$not_use_data = array();

		if( !empty( $PostData['not_use'] ) )
			$not_use_data = $PostData['not_use'];
		
		$Update = $this->update_format();
		$Update['not_use'] = array();
		
		if( !empty( $not_use_data ) ) {
			
			foreach( $not_use_data as $menu_id => $menu_settings ) {
				
				$menu_id = strip_tags( $menu_id );
				$menu_name = stripslashes( $menu_settings['name'] );
				$Update['not_use'][$menu_id]['name'] = $menu_name;
				
			}

		}

		return $Update;

	}

	private function update_user_role() {
		
		global $Plvc;

		if( empty( $_POST['data'] ) )
			return false;

		$PostData = $_POST['data'];

		$Update = $this->update_format();

		if( !empty( $PostData['user_role'] ) ) {

			foreach( $PostData['user_role'] as $user_role => $v ) {

				$user_role = strip_tags( $user_role );
				$Update[$user_role] = 1;

			}

		}

		if( $Plvc->Current['multisite'] && $Plvc->Current['network_admin'] ) {
			
			//update_option( $Plvc->Plugin['record']['user_role'] , $Update );
			
		} else {

			update_option( $Plvc->Plugin['record']['user_role'] , $Update );

		}

		wp_redirect( esc_url_raw( add_query_arg( $Plvc->Plugin['msg_notice'] , 'update' ) ) );
		exit;

	}

	private function update_post() {
		
		global $Plvc;

		if( empty( $_POST['use'] ) )
			return false;

		$Update = $this->update_format_list( $_POST );

		$record = apply_filters( 'plvc_pre_update' , $Plvc->Plugin['record']['post'] );

		if( $Plvc->Current['multisite'] && $Plvc->Current['network_admin'] ) {
			
			//update_option( $record , $Update );
			
		} else {

			update_option( $record , $Update );

		}

		wp_redirect( esc_url_raw( add_query_arg( $Plvc->Plugin['msg_notice'] , 'update' ) ) );
		exit;

	}

	private function update_page() {
		
		global $Plvc;

		if( empty( $_POST['use'] ) )
			return false;

		$Update = $this->update_format_list( $_POST );

		$record = apply_filters( 'plvc_pre_update' , $Plvc->Plugin['record']['page'] );

		if( $Plvc->Current['multisite'] && $Plvc->Current['network_admin'] ) {
			
			//update_option( $record , $Update );
			
		} else {

			update_option( $record , $Update );

		}

		wp_redirect( esc_url_raw( add_query_arg( $Plvc->Plugin['msg_notice'] , 'update' ) ) );
		exit;

	}

	private function update_media() {
		
		global $Plvc;

		if( empty( $_POST['use'] ) )
			return false;

		$Update = $this->update_format_list( $_POST );

		$record = apply_filters( 'plvc_pre_update' , $Plvc->Plugin['record']['media'] );

		if( $Plvc->Current['multisite'] && $Plvc->Current['network_admin'] ) {
			
			//update_option( $record , $Update );
			
		} else {

			update_option( $record , $Update );

		}

		wp_redirect( esc_url_raw( add_query_arg( $Plvc->Plugin['msg_notice'] , 'update' ) ) );
		exit;

	}

	private function update_comments() {
		
		global $Plvc;

		if( empty( $_POST['use'] ) )
			return false;

		$Update = $this->update_format_list( $_POST );

		$record = apply_filters( 'plvc_pre_update' , $Plvc->Plugin['record']['comments'] );

		if( $Plvc->Current['multisite'] && $Plvc->Current['network_admin'] ) {
			
			//update_option( $record , $Update );
			
		} else {

			update_option( $record , $Update );

		}

		wp_redirect( esc_url_raw( add_query_arg( $Plvc->Plugin['msg_notice'] , 'update' ) ) );
		exit;

	}

	private function update_widgets() {
		
		global $Plvc;

		if( empty( $_POST['not_use'] ) )
			return false;

		$Update = $this->update_format_menu( $_POST );

		$record = apply_filters( 'plvc_pre_update' , $Plvc->Plugin['record']['widgets'] );

		if( $Plvc->Current['multisite'] && $Plvc->Current['network_admin'] ) {
			
			//update_option( $record , $Update );
			
		} else {

			update_option( $record , $Update );

		}

		wp_redirect( esc_url_raw( add_query_arg( $Plvc->Plugin['msg_notice'] , 'update' ) ) );
		exit;

	}

	private function update_menus() {
		
		global $Plvc;

		if( empty( $_POST['not_use'] ) )
			return false;

		$Update = $this->update_format_menu( $_POST );

		$record = apply_filters( 'plvc_pre_update' , $Plvc->Plugin['record']['menus'] );

		if( $Plvc->Current['multisite'] && $Plvc->Current['network_admin'] ) {
			
			//update_option( $record , $Update );
			
		} else {

			update_option( $record , $Update );

		}

		wp_redirect( esc_url_raw( add_query_arg( $Plvc->Plugin['msg_notice'] , 'update' ) ) );
		exit;

	}

	private function update_menu_advance() {
		
		global $Plvc;

		if( empty( $_POST['not_use'] ) )
			return false;

		$Update = $this->update_format_menu( $_POST );

		$record = apply_filters( 'plvc_pre_update' , $Plvc->Plugin['record']['menus_adv'] );

		if( $Plvc->Current['multisite'] && $Plvc->Current['network_admin'] ) {
			
			//update_option( $record , $Update );
			
		} else {

			update_option( $record , $Update );

		}

		wp_redirect( esc_url_raw( add_query_arg( $Plvc->Plugin['msg_notice'] , 'update' ) ) );
		exit;

	}

	private function update_custom_post() {
		
		global $Plvc;
		
		if( empty( $_POST['list_name'] ) )
			return false;
			
		$GetData = $this->get_filt_record( $Plvc->Plugin['record']['custom_posts'] );
		
		$Update = $this->update_format_list( $_POST );

		$custom_post_name = strip_tags( $_POST['list_name'] );
		$GetData[$custom_post_name] = $Update;

		$record = apply_filters( 'plvc_pre_update' , $Plvc->Plugin['record']['custom_posts'] );
		
		if( $Plvc->Current['multisite'] && $Plvc->Current['network_admin'] ) {
			
			//update_option( $record , $GetData );
			
		} else {

			update_option( $record , $GetData );

		}
		
		wp_redirect( esc_url_raw( add_query_arg( $Plvc->Plugin['msg_notice'] , 'update' ) ) );
		exit;

	}

	private function update_users() {
		
		global $Plvc;

		if( empty( $_POST['use'] ) )
			return false;

		$Update = $this->update_format_list( $_POST );

		$record = apply_filters( 'plvc_pre_update' , $Plvc->Plugin['record']['users'] );

		if( $Plvc->Current['multisite'] && $Plvc->Current['network_admin'] ) {
			
			//update_option( $record , $Update );
			
		} else {

			update_option( $record , $Update );

		}

		wp_redirect( esc_url_raw( add_query_arg( $Plvc->Plugin['msg_notice'] , 'update' ) ) );
		exit;

	}

	private function remove_record( $record ) {
		
		global $Plvc;
		
		$record = apply_filters( 'plvc_pre_delete' , $record );

		if( $Plvc->Current['multisite'] && $Plvc->Current['network_admin'] ) {
			
			//delete_option( $record );
			
		} else {

			delete_option( $record );

		}

		wp_redirect( esc_url_raw( add_query_arg( $Plvc->Plugin['msg_notice'] , 'delete' ) ) );
		exit;

	}
	
	private function remove_custom_posts() {
		
		global $Plvc;
		
		if( empty( $_POST['list_name'] ) )
			return false;

		$GetData = $this->get_filt_record( $Plvc->Plugin['record']['custom_posts'] );

		$custom_post_name = strip_tags( $_POST['list_name'] );
		unset( $GetData[$custom_post_name] );

		$record = apply_filters( 'plvc_pre_update' , $Plvc->Plugin['record']['custom_posts'] );

		if( $Plvc->Current['multisite'] && $Plvc->Current['network_admin'] ) {
			
			//update_option( $record , $GetData );
			
		} else {

			update_option( $record , $GetData );

		}
		
		wp_redirect( esc_url_raw( add_query_arg( $Plvc->Plugin['msg_notice'] , 'update' ) ) );
		exit;

	}

	private function update_other() {
		
		global $Plvc;

		if( empty( $_POST['data'] ) )
			return false;

		$PostData = $_POST['data'];
		
		if( empty( $PostData['other'] ) )
			return false;
		
		$OtherData = $PostData['other'];

		if( empty( $OtherData ) )
			return false;
			
		$Update = $this->update_format();

		if( !empty( $OtherData['capability'] ) )
			$Update['capability'] = strip_tags( $OtherData['capability'] );

		if( !empty( $OtherData['cell_auto'] ) )
			$Update['cell_auto'] = intval( $OtherData['cell_auto'] );

		if( !empty( $OtherData['thumbnail']['width'] ) )
			$Update['thumbnail']['width'] = intval( $OtherData['thumbnail']['width'] );

		if( $Plvc->Current['multisite'] && $Plvc->Current['network_admin'] ) {
			
			//update_option( $Plvc->Plugin['record']['other'] , $Update );
			
		} else {

			update_option( $Plvc->Plugin['record']['other'] , $Update );

		}
		
		wp_redirect( esc_url_raw( add_query_arg( $Plvc->Plugin['msg_notice'] , 'update' ) ) );
		exit;
		
	}
	
	function update_registed_columns( $column_type , $columns ) {
		
		global $Plvc;

		if( empty( $column_type ) or empty( $columns ) )
			return false;
			
		$column_type = strip_tags( $column_type );

		$Data = $this->get_registed_columns();
		
		$Data[$column_type] = $columns;

		if( $Plvc->Current['multisite'] && $Plvc->Current['network_admin'] ) {
			
			//update_option( $Plvc->Plugin['record']['regist_columns'] , $Data );
			
		} else {

			update_option( $Plvc->Plugin['record']['regist_columns'] , $Data );

		}

	}

	function update_registed_sortable_columns( $column_type , $columns ) {
		
		global $Plvc;

		if( empty( $column_type ) or empty( $columns ) )
			return false;
			
		$column_type = strip_tags( $column_type );

		$Data = $this->get_registed_sortable_columns();
		
		$Data[$column_type] = $columns;

		if( $Plvc->Current['multisite'] && $Plvc->Current['network_admin'] ) {
			
			//update_option( $Plvc->Plugin['record']['regist_columns'] , $Data );
			
		} else {

			update_option( $Plvc->Plugin['record']['regist_sortable_columns'] , $Data );

		}

	}
	
}

endif;
