<?php

if ( !class_exists( 'Plvc_Data' ) ) :

class Plvc_Data
{

	function __construct() {
		
		if( is_admin() )
			add_action( 'plugins_loaded' , array( $this , 'init' ) , 20 );

	}

	function init() {
		
		global $Plvc;
		
		if( !$Plvc->Current['ajax'] ) {
			add_action( 'admin_init' , array( $this , 'dataUpdate' ) );
		}
	}

	function get_record( $record ) {
		
		global $Plvc;
		
		$Data = array();
		$GetData = get_option( $record );
		$GetData = apply_filters( 'plvc_pre_get_data' , $GetData , $record );
		
		if( !empty( $GetData ) )
			$Data = $GetData;
		
		return $Data;

	}

	function get_filt_record( $record ) {
		
		global $Plvc;
		
		$Data = array();
		$GetData = get_option( $record );
		$GetData = apply_filters( 'plvc_pre_get_filt_data' , $GetData , $record );
		
		if( !empty( $GetData ) )
			$Data = $GetData;
		
		return $Data;

	}

	function get_data_user_role() {
		
		global $Plvc;
		
		$Data = $this->get_record( $Plvc->Plugin['record']['user_role'] );
		
		return $Data;

	}

	function get_registed_columns( $post_type ) {
		
		global $Plvc;

		$registed_columns = array();
		$GetData = $this->get_record( $Plvc->Plugin['record']['regist_columns'] );
		if( !empty( $GetData ) && !empty( $GetData[$post_type] ) )
			$registed_columns = $GetData[$post_type];
		
		return $registed_columns;
		
	}

	function get_data_others() {
		
		global $Plvc;
		
		$Data = $this->get_record( $Plvc->Plugin['record']['other'] );
		
		return $Data;

	}

	function get_donate_key( $record ) {
		
		global $Plvc;

		$donateKey = get_option( $record );
		
		return $donateKey;

	}

	function get_donate_width() {
		
		global $Plvc;
		
		$width = false;
		$GetData = get_option( $Plvc->ClassInfo->DonateOptionRecord );

		if( !empty( $GetData ) ) {
			$width = true;
		}

		return $width;

	}





	function dataUpdate() {
		
		global $Plvc;
		
		$RecordField = false;
		
		if( !empty( $_POST ) && !empty( $_POST[$Plvc->Plugin['ltd'] . '_settings'] ) && $_POST[$Plvc->Plugin['ltd'] . '_settings'] == $Plvc->Plugin['UPFN'] && !empty( $_POST[$Plvc->Plugin['nonces']['field']] ) && !empty( $_POST['record_field'] ) ) {

			$RecordField = strip_tags( $_POST['record_field'] );
			$can_capability = $Plvc->ClassManager->get_manager_user_role();
			
			if( check_admin_referer( $Plvc->Plugin['nonces']['value'] , $Plvc->Plugin['nonces']['field'] ) && current_user_can( $can_capability ) ) {
				
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
					
				} elseif( $RecordField == $Plvc->Plugin['record']['other'] ) {
					
					$this->update_other();
					
				} elseif( $RecordField == 'donate' ) {
					
					$this->update_donate();

				}
				
			}

		}

	}

	function update_format() {

		global $Plvc;

		$Update = array( 'UPFN' => 1 );

		return $Update;

	}

	function update_format_list( $PostData ) {
		
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
				
			}

		}

		return $Update;

	}

	function update_format_menu( $PostData ) {
		
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

	function update_user_role() {
		
		global $Plvc;

		$Update = $this->update_format();
		$PostData = array();
		if( !empty( $_POST['data'] ) )
			$PostData = $_POST['data'];

		if( !empty( $PostData['user_role'] ) ) {

			foreach( $PostData['user_role'] as $user_role => $v ) {

				$user_role = strip_tags( $user_role );
				$Update[$user_role] = 1;

			}

		}

		update_option( $Plvc->Plugin['record']['user_role'] , $Update );
		wp_redirect( add_query_arg( $Plvc->Plugin['msg_notice'] , 'update' ) );
		exit;

	}

	function update_post() {
		
		global $Plvc;

		$Update = $this->update_format_list( $_POST );
		

		$record = apply_filters( 'plvc_pre_update' , $Plvc->Plugin['record']['post'] );
		update_option( $record , $Update );
		wp_redirect( add_query_arg( $Plvc->Plugin['msg_notice'] , 'update' ) );
		exit;

	}

	function update_page() {
		
		global $Plvc;

		$Update = $this->update_format_list( $_POST );

		$record = apply_filters( 'plvc_pre_update' , $Plvc->Plugin['record']['page'] );
		update_option( $record , $Update );
		wp_redirect( add_query_arg( $Plvc->Plugin['msg_notice'] , 'update' ) );
		exit;

	}

	function update_media() {
		
		global $Plvc;

		$Update = $this->update_format_list( $_POST );

		$record = apply_filters( 'plvc_pre_update' , $Plvc->Plugin['record']['media'] );
		update_option( $record , $Update );
		wp_redirect( add_query_arg( $Plvc->Plugin['msg_notice'] , 'update' ) );
		exit;

	}

	function update_comments() {
		
		global $Plvc;

		$Update = $this->update_format_list( $_POST );

		$record = apply_filters( 'plvc_pre_update' , $Plvc->Plugin['record']['comments'] );
		update_option( $record , $Update );
		wp_redirect( add_query_arg( $Plvc->Plugin['msg_notice'] , 'update' ) );
		exit;

	}

	function update_widgets() {
		
		global $Plvc;

		$Update = $this->update_format_menu( $_POST );

		$record = apply_filters( 'plvc_pre_update' , $Plvc->Plugin['record']['widgets'] );
		update_option( $record , $Update );
		wp_redirect( add_query_arg( $Plvc->Plugin['msg_notice'] , 'update' ) );
		exit;

	}

	function update_menus() {
		
		global $Plvc;

		$Update = $this->update_format_menu( $_POST );

		$record = apply_filters( 'plvc_pre_update' , $Plvc->Plugin['record']['menus'] );
		update_option( $record , $Update );
		wp_redirect( add_query_arg( $Plvc->Plugin['msg_notice'] , 'update' ) );
		exit;

	}

	function update_menu_advance() {
		
		global $Plvc;

		$Update = $this->update_format_menu( $_POST );

		$record = apply_filters( 'plvc_pre_update' , $Plvc->Plugin['record']['menus_adv'] );
		update_option( $record , $Update );
		wp_redirect( add_query_arg( $Plvc->Plugin['msg_notice'] , 'update' ) );
		exit;

	}

	function update_custom_post() {
		
		global $Plvc;
		
		$GetData = $this->get_record( $Plvc->Plugin['record']['custom_posts'] );
		$Update = $this->update_format_list( $_POST );

		$custom_post_name = strip_tags( $_POST['list_name'] );
		$GetData[$custom_post_name] = $Update;
		
		$record = apply_filters( 'plvc_pre_update' , $Plvc->Plugin['record']['custom_posts'] );
		update_option( $record , $GetData );
		wp_redirect( add_query_arg( $Plvc->Plugin['msg_notice'] , 'update' ) );
		exit;

	}

	function remove_record( $record ) {
		
		global $Plvc;
		
		$record = apply_filters( 'plvc_pre_delete' , $record );
		delete_option( $record );
		wp_redirect( add_query_arg( $Plvc->Plugin['msg_notice'] , 'delete' ) );
		exit;

	}
	
	function remove_custom_posts() {
		
		global $Plvc;
		
		$GetData = $this->get_record( $Plvc->Plugin['record']['custom_posts'] );

		$custom_post_name = strip_tags( $_POST['list_name'] );
		unset( $GetData[$custom_post_name] );

		$record = apply_filters( 'plvc_pre_update' , $Plvc->Plugin['record']['custom_posts'] );
		update_option( $record , $GetData );
		wp_redirect( add_query_arg( $Plvc->Plugin['msg_notice'] , 'update' ) );
		exit;

	}

	function update_other() {
		
		global $Plvc;

		$Update = $this->update_format();
		$PostData = array();
		if( !empty( $_POST['data']['other'] ) )
			$OtherData = $_POST['data']['other'];

		if( !empty( $OtherData ) ) {

			if( !empty( $OtherData['capability'] ) )
				$Update['capability'] = strip_tags( $OtherData['capability'] );

			if( !empty( $OtherData['cell_auto'] ) )
				$Update['cell_auto'] = intval( $OtherData['cell_auto'] );

			if( !empty( $OtherData['thumbnail']['width'] ) )
				$Update['thumbnail']['width'] = intval( $OtherData['thumbnail']['width'] );

			update_option( $Plvc->Plugin['record']['other'] , $Update );
			wp_redirect( add_query_arg( $Plvc->Plugin['msg_notice'] , 'update' ) );
			exit;

		}
		
	}
	
	function update_donate() {
		
		global $Plvc;

		$is_donate_check = false;
		$submit_key = false;

		if( !empty( $_POST['donate_key'] ) ) {

			$is_donate_check = $Plvc->ClassInfo->is_donate_key_check( $_POST['donate_key'] );

			if( !empty( $is_donate_check ) ) {

				update_option( $Plvc->ClassInfo->DonateRecord , $is_donate_check );
				wp_redirect( add_query_arg( $Plvc->Plugin['msg_notice'] , 'donated' ) );
				exit;

			}
		}
		
	}
	
	function update_donate_toggle( $Data ) {
		
		global $Plvc;

		update_option( $Plvc->ClassInfo->DonateOptionRecord , $Data );

	}

}

endif;
