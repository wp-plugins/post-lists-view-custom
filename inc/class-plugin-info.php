<?php

if ( !class_exists( 'Plvc_Plugin_Info' ) ) :

class Plvc_Plugin_Info
{

	var $nonces = array();

	private $DonateKey = 'd77aec9bc89d445fd54b4c988d090f03';
	private $DonateRecord = '';
	private $DonateOptionRecord = '';

	function __construct() {
		
		add_action( 'wp_loaded' , array( $this , 'setup' ) , 20 );
		
	}

	function setup() {
		
		global $Plvc;
		
		$this->DonateRecord = $Plvc->Plugin['ltd'] . '_donated';
		$this->DonateOptionRecord = $Plvc->Plugin['ltd'] . '_donate_width';
		$this->nonces = array( 'field' => $Plvc->Plugin['nonces']['field'] . '_donate' , 'value' => $Plvc->Plugin['nonces']['value'] . '_donate' );
		
		if( $Plvc->Current['admin'] && $Plvc->ClassManager->is_manager ) {

			if( !$Plvc->Current['ajax'] ) {

				if( $Plvc->Current['multisite'] ) {

					add_action( 'network_admin_notices' , array( $this , 'donate_notice' ) );

				} else {

					add_action( 'admin_notices' , array( $this , 'donate_notice' ) );

				}

				add_action( 'admin_init' , array( $this , 'dataUpdate' ) );

			} else {

				add_action( 'wp_ajax_' . $Plvc->Plugin['ltd'] . '_donation_toggle' , array( $this , 'ajax_donation_toggle' ) );

			}

			add_action( 'admin_print_scripts' , array( $this , 'admin_print_scripts' ) );

		}

	}
	
	function admin_print_scripts() {
		
		global $Plvc;
		
		if( $Plvc->ClassManager->is_settings_page() ) {
			
			$translation = array( $this->nonces['field'] => wp_create_nonce( $this->nonces['value'] ) );
			wp_localize_script( $Plvc->Plugin['page_slug'] , $Plvc->Plugin['ltd'] . '_donate' , $translation );

		}

	}

	function ajax_donation_toggle() {
		
		if( isset( $_POST['f'] ) ) {

			$is_donated = $this->is_donated();
			
			if( !empty( $is_donated ) ) {

				$this->update_donate_toggle( intval( $_POST['f'] ) );

			}

		}
		
		die();
		
	}

	function is_donated() {
		
		$donated = false;
		$donateKey = $this->get_donate_key( $this->DonateRecord );

		if( !empty( $donateKey ) && $donateKey == $this->DonateKey ) {
			$donated = true;
		}

		return $donated;

	}

	function donate_notice() {
		
		global $Plvc;
		
		$setting_page = $Plvc->ClassManager->is_settings_page();
		
		if( !empty( $setting_page ) ) {
			
			if( !empty( $_GET ) && !empty( $_GET[$Plvc->Plugin['msg_notice']] ) && $_GET[$Plvc->Plugin['msg_notice']] == 'donated' ) {

				printf( '<div class="updated"><p><strong>%s</strong></p></div>' , __( 'Thank you for your donation.' , $Plvc->Plugin['ltd'] ) );

			} else {

				$is_donated = $this->is_donated();
	
				if( empty( $is_donated ) )
					printf( '<div class="updated"><p><strong><a href="%1$s" target="_blank">%2$s</a></strong></p></div>' , $this->author_url( array( 'donate' => 1 , 'tp' => 'use_plugin' , 'lc' => 'footer' ) ) , __( 'Please consider making a donation.' , $Plvc->Plugin['ltd'] ) );
					
			}

		}

	}
	
	function version_checked() {

		global $Plvc;

		$readme = file_get_contents( $Plvc->Plugin['dir'] . 'readme.txt' );
		$items = explode( "\n" , $readme );
		$version_checked = '';
		foreach( $items as $key => $line ) {
			if( strpos( $line , 'Requires at least: ' ) !== false ) {
				$version_checked .= str_replace( 'Requires at least: ' , '' ,  $line );
				$version_checked .= ' - ';
			} elseif( strpos( $line , 'Tested up to: ' ) !== false ) {
				$version_checked .= str_replace( 'Tested up to: ' , '' ,  $line );
				break;
			}
		}
		
		return $version_checked;
		
	}

	function author_url( $args ) {
		
		$url = 'http://gqevu6bsiz.chicappa.jp/';
		
		if( !empty( $args['translate'] ) ) {
			$url .= 'please-translation/';
		} elseif( !empty( $args['donate'] ) ) {
			$url .= 'please-donation/';
		} elseif( !empty( $args['contact'] ) ) {
			$url .= 'contact-us/';
		} elseif( !empty( $args['add-on'] ) ) {
			$url .= 'post-lists-view-custom-for-multiple-setups-add-on/';
		}
		
		$url .= $this->get_utm_link( $args );

		return $url;

	}

	function get_utm_link( $args ) {
		
		global $Plvc;

		$url = '?utm_source=' . $args['tp'];
		$url .= '&utm_medium=' . $args['lc'];
		$url .= '&utm_content=' . $Plvc->Plugin['ltd'];
		$url .= '&utm_campaign=' . str_replace( '.' , '_' , $Plvc->Ver );

		return $url;

	}

	private function is_donate_key_check( $key ) {
		
		$check = false;
		$key = md5( strip_tags( $key ) );
		if( $this->DonateKey == $key )
			$check = $key;

		return $check;

	}

	function get_width_class() {
		
		global $Plvc;

		$class = $Plvc->Plugin['ltd'];
		
		if( $this->is_donated() ) {

			$width_option = $this->get_donate_width();

			if( !empty( $width_option ) )
				$class .= ' full-width';

		}
		
		return $class;

	}
	
	function get_gravatar_src( $size = 40 ) {
		
		global $Plvc;

		$img_src = $Plvc->Current['schema'] . 'www.gravatar.com/avatar/7e05137c5a859aa987a809190b979ed4?s=' . $size;

		return $img_src;

	}

	function admin_footer_text() {
		
		$author_url = $this->author_url( array( 'tp' => 'use_plugin' , 'lc' => 'footer' ) );
		$text = sprintf( '<a href="%1$s" target="_blank"><img src="%2$s" width="18" /></a>' ,  $author_url , $this->get_gravatar_src( '18' ) );
		$text .= sprintf( 'Plugin developer : <a href="%s" target="_blank">gqevu6bsiz</a>' , $author_url );

		return $text;
		
	}

	private function get_donate_key( $record ) {
		
		global $Plvc;

		if( $Plvc->Current['multisite'] ) {

			$donateKey = get_site_option( $record );

		} else {

			$donateKey = get_option( $record );

		}
		
		return $donateKey;

	}

	private function get_donate_width() {
		
		global $Plvc;
		
		$width = false;

		if( $Plvc->Current['multisite'] ) {

			$GetData = get_site_option( $this->DonateOptionRecord );

		} else {

			$GetData = get_option( $this->DonateOptionRecord );

		}

		if( !empty( $GetData ) )
			$width = true;

		return $width;

	}
	
	function dataUpdate() {
		
		global $Plvc;
		
		$RecordField = false;
		
		if( !empty( $_POST ) && !empty( $Plvc->ClassManager->is_manager ) && !empty( $_POST[$Plvc->Plugin['form']['field']] ) && $_POST[$Plvc->Plugin['form']['field']] == $Plvc->Plugin['UPFN'] ) {

			if( !empty( $_POST[$this->nonces['field']] ) && check_admin_referer( $this->nonces['value'] , $this->nonces['field'] ) ) {
					
				$this->update_donate();
					
			}

		}

	}
	
	private function update_donate() {
		
		global $Plvc;

		$is_donate_check = false;
		$submit_key = false;

		if( !empty( $_POST['donate_key'] ) ) {

			$is_donate_check = $this->is_donate_key_check( $_POST['donate_key'] );

			if( !empty( $is_donate_check ) ) {

				if( !empty( $Plvc->Current['multisite'] ) ) {
							
					update_site_option( $this->DonateRecord , $is_donate_check );
							
				} else {
				
					update_option( $this->DonateRecord , $is_donate_check );
		
				}

				wp_redirect( add_query_arg( $Plvc->Plugin['msg_notice'] , 'donated' ) );

			}

		}

	}

	private function update_donate_toggle( $Data ) {
		
		global $Plvc;

		if( $Plvc->ClassManager->is_manager && check_ajax_referer( $this->nonces['value'] , $this->nonces['field'] ) ) {

			if( $Plvc->Current['multisite'] ) {
						
				update_site_option( $this->DonateOptionRecord , $Data );
						
			} else {
			
				update_option( $this->DonateOptionRecord , $Data );

			}
			
		}

	}

}

endif;
